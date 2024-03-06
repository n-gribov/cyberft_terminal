<?php
namespace addons\VTB;

use addons\edm\EdmModule;
use addons\edm\models\BaseVTBDocument\BaseVTBDocumentType;
use addons\edm\models\VTBCancellationRequest\VTBCancellationRequestType;
use addons\edm\models\VTBConfDocInquiry138I\VTBConfDocInquiry138IType;
use addons\edm\models\VTBContractChange\VTBContractChangeType;
use addons\edm\models\VTBContractReg\VTBContractRegType;
use addons\edm\models\VTBContractUnReg\VTBContractUnRegType;
use addons\edm\models\VTBCredReg\VTBCredRegType;
use addons\edm\models\VTBCurrBuy\VTBCurrBuyType;
use addons\edm\models\VTBCurrConversion\VTBCurrConversionType;
use addons\edm\models\VTBCurrDealInquiry181i\VTBCurrDealInquiry181iType;
use addons\edm\models\VTBCurrSell\VTBCurrSellType;
use addons\edm\models\VTBFreeClientDoc\VTBFreeClientDocType;
use addons\edm\models\VTBPayDocCur\VTBPayDocCurType;
use addons\edm\models\VTBPayDocRu\VTBPayDocRuType;
use addons\edm\models\VTBPrepareCancellationRequest\VTBPrepareCancellationRequestExt;
use addons\edm\models\VTBPrepareCancellationRequest\VTBPrepareCancellationRequestType;
use addons\edm\models\VTBPrepareCancellationResponse\VTBPrepareCancellationResponseType;
use addons\edm\models\VTBRegisterCur\VTBRegisterCurType;
use addons\edm\models\VTBRegisterRu\VTBRegisterRuType;
use addons\edm\models\VTBStatementQuery\VTBStatementQueryType;
use addons\edm\models\VTBStatementRu\VTBStatementRuType;
use addons\edm\models\VTBTransitAccPayDoc\VTBTransitAccPayDocType;
use addons\VTB\helpers\VTBModuleHelper;
use addons\VTB\models\soap\messages\WSGetStatement\GetStatementRequest;
use addons\VTB\models\soap\messages\WSPrepareDocForCancel\PrepareDocForCancelRequest;
use addons\VTB\models\soap\services\WSGetStatement;
use addons\VTB\models\soap\services\WSPrepareDocForCancel;
use addons\VTB\models\VTBBankBranch;
use addons\VTB\models\VTBCryptoproCert;
use addons\VTB\models\VTBCryptoproCertSearch;
use addons\VTB\models\VTBCustomer;
use addons\VTB\models\VTBDocumentImportRequest;
use common\base\BaseBlock;
use common\document\Document;
use common\helpers\DocumentHelper;
use common\helpers\vtb\VTBHelper;
use common\models\cyberxml\CyberXmlDocument;
use common\models\Terminal;
use common\models\vtbxml\documents\BSDocument;
use common\models\vtbxml\documents\StatementQuery;
use common\models\vtbxml\documents\StatementRu;
use common\models\vtbxml\service\SignInfo;
use common\modules\transport\helpers\DocumentTransportHelper;
use DateTime;
use Exception;
use Yii;

class VTBModule extends BaseBlock
{
    const EXT_USER_MODEL_CLASS = '\addons\VTB\models\VTBUserExt';
    const SERVICE_ID = 'VTB';
    const RESOURCE_IN = 'in';
    const RESOURCE_OUT = 'out';
    const RESOURCE_TEMP = 'temp';
    //const RESOURCE_IMPORT = 'in';
    const RESOURCE_IMPORT_ERROR = 'error';

    const SETTINGS_CODE = 'VTB:VTB';

    public function getCryptoProCertModel($getSearchModel = false)
    {
        return $getSearchModel
            ? new VTBCryptoproCertSearch()
            : new VTBCryptoproCert();
    }

    public function registerMessage(CyberXmlDocument $cyxDocument, $documentId)
	{
        switch ($cyxDocument->docType) {
            case VTBPayDocRuType::TYPE:
                return $this->registerVTBPayDocRuDocument($cyxDocument, $documentId);
            case VTBStatementQueryType::TYPE:
                return $this->registerVTBStatementQueryDocument($cyxDocument, $documentId);
            case VTBPayDocCurType::TYPE:
                return $this->registerVTBPayDocCurDocument($cyxDocument, $documentId);
            case VTBFreeClientDocType::TYPE:
            case VTBTransitAccPayDocType::TYPE:
            case VTBCurrBuyType::TYPE:
            case VTBCurrSellType::TYPE:
            case VTBCurrDealInquiry181iType::TYPE:
            case VTBConfDocInquiry138IType::TYPE:
            case VTBContractRegType::TYPE:
            case VTBCredRegType::TYPE:
            case VTBCurrConversionType::TYPE:
            case VTBCancellationRequestType::TYPE:
            case VTBContractChangeType::TYPE:
            case VTBContractUnRegType::TYPE:
                return $this->registerGenericVTBDocument($cyxDocument, $documentId);
            case VTBPrepareCancellationRequestType::TYPE:
                return $this->registerVTBPrepareCancellationRequest($cyxDocument, $documentId);
            default:
                Yii::error("Unsupported document type: {$cyxDocument->docType}");
                return false;
        }
	}

    private function registerGenericVTBDocument(CyberXmlDocument $cyxDocument, $documentId)
    {
        /** @var BaseVTBDocumentType $requestTypeModel */
        $requestTypeModel = $cyxDocument->getContent()->getTypeModel();

        $isAuthorizedSender = $this->authorizeSender($requestTypeModel->customerId, $cyxDocument->senderId);
        if (!$isAuthorizedSender) {
            return false;
        }

        $request = new VTBDocumentImportRequest([
            'documentId' => $documentId,
            'status' => VTBDocumentImportRequest::STATUS_PENDING
        ]);
        // Сохранить модель в БД и вернуть результат сохранения
        return $request->save();
    }

    private function registerVTBPayDocRuDocument(CyberXmlDocument $cyxDocument, $documentId)
    {
        $isRegistered = $this->registerGenericVTBDocument($cyxDocument, $documentId);

        if (!$isRegistered) {
            Yii::info("Failed to register document $documentId");
            VTBModuleHelper::sendPaymentStatusReport(
                Document::findOne($documentId),
                'RJCT',
                'RJCT',
                'Документ не принят',
                'При регистрации документа на терминале ВТБ произошла ошибка'
            );
        }

        return $isRegistered;
    }

    private function registerVTBPayDocCurDocument(CyberXmlDocument $cyxDocument, $documentId)
    {
        $isRegistered = $this->registerGenericVTBDocument($cyxDocument, $documentId);

        if (!$isRegistered) {
            Yii::info("Failed to register document $documentId");
            VTBModuleHelper::sendPaymentStatusReport(
                Document::findOne($documentId),
                'RJCT',
                'RJCT',
                'Документ не принят',
                'При регистрации документа на терминале ВТБ произошла ошибка'
            );
        }

        return $isRegistered;
    }

    private function registerVTBStatementQueryDocument(CyberXmlDocument $cyxDocument, $documentId)
    {
        /** @var VTBStatementQueryType $requestTypeModel */
        $requestTypeModel = $cyxDocument->getContent()->getTypeModel();

        $isAuthorizedSender = $this->authorizeSender($requestTypeModel->customerId, $cyxDocument->senderId);
        if (!$isAuthorizedSender) {
            return false;
        }

        $request = new VTBDocumentImportRequest([
            'documentId' => $documentId,
            'status'     => VTBDocumentImportRequest::STATUS_PENDING
        ]);
        // Сохранить модель в БД и вернуть результат сохранения
        return $request->save();
    }

    private function registerVTBPrepareCancellationRequest($cyxDocument, $documentId)
    {
        $this->log("Processing VTBPrepareCancellationRequest $documentId...");

        /** @var VTBPrepareCancellationRequestType $requestTypeModel */
        $requestTypeModel = $cyxDocument->getContent()->getTypeModel();

        $requestDocument = Document::findOne($documentId);
        if ($requestDocument === null) {
            throw new \Exception("Cannot find request document $documentId");
        }

        $isAuthorizedSender = $this->authorizeSender($requestTypeModel->vtbCustomerId, $cyxDocument->senderId);
        if (!$isAuthorizedSender) {
            $this->sendVTBPrepareCancellationResponse($requestDocument, false);
            return false;
        }

        $targetDocument = $this->findTargetDocumentByPrepareCancellationRequest($requestTypeModel);
        if ($targetDocument === null) {
            $this->log("Target document with remote UUID {$requestTypeModel->documentUuid} is not found");
            $this->sendVTBPrepareCancellationResponse($requestDocument, false);
            return false;
        }

        $importRequest = VTBDocumentImportRequest::find()
            ->where([
                'documentId' => $targetDocument->id,
                'status'     => [VTBDocumentImportRequest::STATUS_SENT, VTBDocumentImportRequest::STATUS_PROCESSED],
            ])->one();
        if ($importRequest === null) {
            $this->log("Import request for document {$targetDocument->id} is not found");
            $this->sendVTBPrepareCancellationResponse($requestDocument, false);
            return false;
        }

        $service = new WSPrepareDocForCancel();
        $request = (new PrepareDocForCancelRequest())
            ->setCustID($requestTypeModel->vtbCustomerId)
            ->setRecordID($importRequest->externalRequestId)
            ->setDocScheme($requestTypeModel->vtbDocumentType);

        $response = $service->prepareDocForCancel($request);
        if (!empty($response->getBSErrorCode())) {
            $this->log("Got error from WSPrepareDocForCancel for document {$targetDocument->id}: " . $response->getBSError() . ', ' . $response->getBSErrorCode());
            $this->sendVTBPrepareCancellationResponse($requestDocument, false);
            return true;
        }

        $this->sendVTBPrepareCancellationResponse(
            $requestDocument,
            true,
            $response->getCancelFields(),
            $importRequest->externalRequestId
        );
        return true;
    }

    private function findTargetDocumentByPrepareCancellationRequest(VTBPrepareCancellationRequestType $requestTypeModel)
    {
        $documents = Document::find()
            ->where(['uuidRemote' => $requestTypeModel->documentUuid])
            ->andWhere(['direction' => Document::DIRECTION_IN])
            ->andWhere(['not in', 'type', [VTBRegisterRuType::TYPE, VTBRegisterCurType::TYPE]])
            ->all();

        if (count($documents) === null) {
            return null;
        }
        if (count($documents) === 1) {
            return $documents[0];
        }

        $documentMatches = function (Document $document) use ($requestTypeModel) {
            if (!VTBHelper::isVTBDocument($document)) {
                return false;
            }
            if (empty($requestTypeModel->documentNumber) || empty($requestTypeModel->documentDate)) {
                return false;
            }
            $cyxDoc = CyberXmlDocument::read($document->getValidStoredFileId());
            $typeModel = $cyxDoc->getContent()->getTypeModel();
            /** @var BSDocument $vtbDocument */
            $vtbDocument = $typeModel->document;
            if ($vtbDocument->DOCUMENTNUMBER != $requestTypeModel->documentNumber) {
                return false;
            }
            if (empty($vtbDocument->DOCUMENTDATE)) {
                return false;
            }

            return $vtbDocument->DOCUMENTDATE->format('Y-m-d') === $requestTypeModel->documentDate;
        };

        $matchingDocuments = array_values(
            array_filter(
                $documents,
                $documentMatches
            )
        );

        return count($matchingDocuments) === 1 ? $matchingDocuments[0] : null;
    }

    private function sendVTBPrepareCancellationResponse(Document $requestDocument, $isAccepted, $vtbDocumentInfo = null, $vtbReferenceId = null)
    {
        $this->log('Sending VTBPrepareCancellationResponse...');

        $senderTerminal = Terminal::find()->where(['terminalId' => $requestDocument->receiver])->one();

        $status = $isAccepted ? VTBPrepareCancellationRequestExt::STATUS_PROCESSED : VTBPrepareCancellationRequestExt::STATUS_REJECTED;
        $typeModel = new VTBPrepareCancellationResponseType([
            'status'              => $status,
            'documentInfo'        => $vtbDocumentInfo,
            'requestDocumentUuid' => $requestDocument->uuidRemote,
            'vtbReferenceId'      => $vtbReferenceId,
        ]);

        // Создать контекст документа
        $context = DocumentHelper::createDocumentContext(
            $typeModel,
            [
                'type'               => $typeModel->getType(),
                'typeGroup'          => EdmModule::SERVICE_ID,
                'direction'          => Document::DIRECTION_OUT,
                'origin'             => Document::ORIGIN_WEB,
                'terminalId'         => $senderTerminal->id,
                'sender'             => $senderTerminal->terminalId,
                'receiver'           => $requestDocument->sender,
                'status'             => Document::STATUS_CREATING,
                'signaturesRequired' => 0,
            ]
        );

        if ($context === false) {
            throw new Exception('Failed to create document context');
        }
        // Создать стейт отправки документа
        DocumentTransportHelper::createSendingState($context['document']);
    }

    public function getStatementFromVTB(StatementQuery $statementQuery)
    {
        $bankBranch = VTBBankBranch::findOneByBranchId($statementQuery->KBOPID);
        if ($bankBranch === null) {
            $this->log("Bank branch {$statementQuery->KBOPID} is not found");
            return [null, null];
        }

        $service = new WSGetStatement();
        $request = (new GetStatementRequest())
            ->setCustID($statementQuery->CUSTID)
            ->setAccount($statementQuery->ACCOUNT)
            ->setBIC($bankBranch->bik)
            ->setStatementType(0)
            ->setStatementDate($statementQuery->DATEFROM->format('Y-m-d'));
        $response = $service->getStatement($request);

        if (!empty($response->getBSError())) {
            $this->log("Request WSGetStatement/GetStatement has failed, error: {$response->getBSErrorCode()}, {$response->getBSError()}");
            return [null, null];
        }
        return [
            StatementRu::fromXml($response->getStatementDoc()),
            !empty($response->getSignData()) ? SignInfo::fromXml($response->getSignData()) : null
        ];
    }

    /**
     * @param StatementRu $statement
     * @param SignInfo|null $signatureData
     * @param string $receiverTerminalId
     * @return bool
     * @throws \Exception
     */
    public function sendStatement($statement, $signatureData, $receiverTerminalId)
    {
        $responseTypeModel = new VTBStatementRuType([
            'document'        => $statement,
            'documentVersion' => 2,
            'signatureInfo'   => $signatureData,
        ]);

        $terminal = Yii::$app->exchange->defaultTerminal;

        $filename = implode(
            '_',
            [
                VTBStatementRuType::TYPE,
                $statement->ACCOUNT,
                $statement->DOCUMENTDATE->format('Y-m-d'),
                (new DateTime())->format('Y-m-d H-i-s')
            ]
        );

        // Создать контекст документа
        $context = DocumentHelper::createDocumentContext(
            $responseTypeModel,
            [
                'type'               => $responseTypeModel->getType(),
                'direction'          => Document::DIRECTION_OUT,
                'origin'             => Document::ORIGIN_SERVICE,
                'terminalId'         => $terminal->id,
                'sender'             => $terminal->terminalId,
                'receiver'           => $receiverTerminalId,
                'signaturesRequired' => 0,
            ],
            null,
            null,
            ['filename' => "$filename.xml"]
        );

        if (empty($context)) {
            $this->log('Failed to create response document');
            return false;
        }
        // Получить документ из контекста
        $document = $context['document'];
        // Отправить документ на обработку в транспортном уровне
        DocumentTransportHelper::processDocument($document, true);

        return true;
    }

    private function authorizeSender($vtbCustomerId, $senderTerminalId)
    {
        $customer = VTBCustomer::findOneByCustomerId($vtbCustomerId);
        if ($customer === null) {
            $this->log("VTBCustomer with id $vtbCustomerId is not found");
            return false;
        }
        if (empty($customer->terminalId)) {
            $this->log("Terminal id for VTBCustomer $vtbCustomerId is not set");
            return false;
        }
        if ($customer->terminalId != $senderTerminalId) {
            $this->log("Terminal $senderTerminalId is not allowed to send documents on behalf of VTB customer {$customer->customerId}");
            return false;
        }
        return true;
    }

    public function onDocumentStatusChange(Document $document)
    {
        return;
    }

    public function storeFileOut($path, $filename = '')
    {
        return $this->storeFile($path, self::RESOURCE_IN, $filename);
    }

    public function storeFileIn($path, $filename = '')
    {
        return $this->storeFile($path, self::RESOURCE_OUT, $filename);
    }

    public function storeDataOut($data, $filename = '')
    {
        return $this->storeData($data, self::RESOURCE_OUT, $filename);
    }

//    public function storeDataTemp($data, $filename = '')
//    {
//        $fileInfo = Yii::$app->registry->getTempResource(self::SERVICE_ID)->putData($data, $filename);
//
//        return $fileInfo['path'];
//    }

  	public function isSignatureRequired($origin, $terminalId = null)
	{
    	return false;
	}

    public function isAutoSignatureRequired($origin, $terminalId = null)
    {
        return false;
    }

    public function getDocument($id)
    {
        return null;
    }

    public function hasUserAccessSettings(): bool
    {
        return false;
    }

    public function getName(): string
    {
        return Yii::t('app', 'VTB');
    }
}

<?php

namespace addons\edm;

use addons\edm\jobs\VTBDocumentCryptoproSignJob;
use addons\edm\models\AccountBalance;
use addons\edm\models\BankLetter\BankLetterDocumentExt;
use addons\edm\models\BaseVTBDocument\BaseVTBDocumentType;
use addons\edm\models\CryptoproSigningRequest;
use addons\edm\models\CurrencyPaymentRegister\CurrencyPaymentRegisterDocumentExt;
use addons\edm\models\DictBank;
use addons\edm\models\DictCurrency;
use addons\edm\models\DictOrganization;
use addons\edm\models\DictVTBBankBranch;
use addons\edm\models\EdmPayerAccount;
use addons\edm\models\EdmSBBOLAccount;
use addons\edm\models\EdmSearch;
use addons\edm\models\ForeignCurrencyOperation\ForeignCurrencyOperationDocumentExt;
use addons\edm\models\Pain001Fcy\Pain001FcyType;
use addons\edm\models\Pain001Fx\Pain001FxType;
use addons\edm\models\Pain001Rls\Pain001RlsType;
use addons\edm\models\Pain001Rub\Pain001RubType;
use addons\edm\models\PaymentOrder\PaymentOrderType;
use addons\edm\models\PaymentRegister\PaymentRegisterDocumentExt;
use addons\edm\models\PaymentRegister\PaymentRegisterPaymentOrder;
use addons\edm\models\PaymentRegister\PaymentRegisterType;
use addons\edm\models\PaymentStatusReport\PaymentStatusReportType;
use addons\edm\models\RaiffeisenClientTerminalSettings\RaiffeisenClientTerminalSettingsType;
use addons\edm\models\RaiffeisenStatement\RaiffeisenStatementType;
use addons\edm\models\Sbbol2ClientTerminalSettings\Sbbol2ClientTerminalSettingsType;
use addons\edm\models\Sbbol2PayDocRu\Sbbol2PayDocRuType;
use addons\edm\models\Sbbol2Statement\Sbbol2StatementType;
use addons\edm\models\SBBOLClientTerminalSettings\SBBOLClientTerminalSettingsType;
use addons\edm\models\SBBOLPayDocRu\SBBOLPayDocRuType;
use addons\edm\models\SBBOLStatement\SBBOLStatementType;
use addons\edm\models\SBBOLStmtReq\SBBOLStmtReqType;
use addons\edm\models\Statement\StatementDocumentExt;
use addons\edm\models\Statement\StatementType;
use addons\edm\models\StatementRequest\StatementRequestType;
use addons\edm\models\VTBCancellationRequest\VTBCancellationRequestType;
use addons\edm\models\VTBClientTerminalSettings\VTBClientTerminalSettingsBankBranch;
use addons\edm\models\VTBClientTerminalSettings\VTBClientTerminalSettingsType;
use addons\edm\models\VTBConfDocInquiry138I\VTBConfDocInquiry138IType;
use addons\edm\models\VTBContractChange\VTBContractChangeType;
use addons\edm\models\VTBContractReg\VTBContractRegType;
use addons\edm\models\VTBContractUnReg\VTBContractUnRegType;
use addons\edm\models\VTBCredReg\VTBCredRegType;
use addons\edm\models\VTBCurrBuy\VTBCurrBuyType;
use addons\edm\models\VTBCurrConversion\VTBCurrConversionType;
use addons\edm\models\VTBCurrDealInquiry181i\VTBCurrDealInquiry181iType;
use addons\edm\models\VTBCurrSell\VTBCurrSellType;
use addons\edm\models\VTBFreeBankDoc\VTBFreeBankDocType;
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
use addons\finzip\models\FinZipDocumentExt;
use addons\finzip\models\FinZipType;
use addons\ISO20022\ISO20022Module;
use addons\ISO20022\models\Auth026Type;
use addons\ISO20022\models\Auth027Type;
use addons\ISO20022\models\Camt052Type;
use addons\ISO20022\models\Camt053Type;
use addons\ISO20022\models\Camt054Type;
use addons\ISO20022\models\ISO20022Type;
use addons\ISO20022\models\Pain002Type;
use common\base\BaseBlock;
use common\components\storage\StoredFile;
use common\components\TerminalId;
use common\components\xmlsec\xmlseclibs\XMLSeclibsHelper;
use common\document\Document;
use common\document\DocumentPermission;
use common\helpers\Countries;
use common\helpers\DocumentHelper;
use common\helpers\raiffeisen\RaiffeisenHelper;
use common\helpers\sbbol2\Sbbol2Helper;
use common\helpers\sbbol\SBBOLHelper;
use common\helpers\Uuid;
use common\helpers\vtb\VTBHelper;
use common\models\cyberxml\CyberXmlDocument;
use common\models\Terminal;
use common\models\TerminalRemoteId;
use common\models\vtbxml\documents\FreeBankDoc;
use common\modules\certManager\models\Cert;
use common\modules\transport\helpers\DocumentTransportHelper;
use common\settings\VTBIntegrationSettings;
use DOMDocument;
use DOMXPath;
use Exception;
use Psr\Log\LogLevel;
use Yii;
use yii\web\User;

/**
 * Edm module class
 *
 * @package addons
 * @subpackage edm
 */
class EdmModule extends BaseBlock
{
    const SETTINGS_CLASS       = 'addons\edm\models\Settings';
    const EXT_USER_MODEL_CLASS = '\addons\edm\models\EdmUserExt';
    const SERVICE_ID           = 'edm';
    const RESOURCE_OUT         = 'out';
    const RESOURCE_IN          = 'in';
    const RESOURCE_EXPORT      = 'export';
    const SETTINGS_CODE        = 'edm:Edm';

    /**
     * @param CyberXmlDocument $cyx
     * @param $documentId
     * @return boolean
     */
    public function registerMessage(CyberXmlDocument $cyx, $documentId)
    {
        if ($cyx->docType === PaymentRegisterType::TYPE || $cyx->docType === VTBRegisterRuType::TYPE) {
            $document  = Document::findOne($documentId);

            if (!$this->validateSignatures($cyx, $document) || !$this->createPaymentOrders($cyx, $documentId)) {
                return false;
            }
        }

        // Связываем paymentStatusReport с документом
        if (PaymentStatusReportType::TYPE == $cyx->docType) {
            $refDocId = $cyx->getContent()->getTypeModel()->refDocId;

            $document  = Document::findOne($documentId);

            if (!$document) {
                $this->log('Could not load document for document ID ' . $documentId);

                return false;
            }

            $document->uuidReference = $refDocId;
            $document->save();
        }

        // Входящие документы в адрес шлюза ВТБ передаем модулю VTB
        $vtbModuleTypes = [
            VTBPayDocRuType::TYPE,
            VTBStatementQueryType::TYPE,
            VTBFreeClientDocType::TYPE,
            VTBPayDocCurType::TYPE,
            VTBTransitAccPayDocType::TYPE,
            VTBCurrBuyType::TYPE,
            VTBCurrSellType::TYPE,
            VTBCurrDealInquiry181iType::TYPE,
            VTBConfDocInquiry138IType::TYPE,
            VTBContractRegType::TYPE,
            VTBCredRegType::TYPE,
            VTBCurrConversionType::TYPE,
            VTBPrepareCancellationRequestType::TYPE,
            VTBCancellationRequestType::TYPE,
            VTBContractChangeType::TYPE,
            VTBContractUnRegType::TYPE
        ];
        if (in_array($cyx->docType, $vtbModuleTypes)) {
            $vtbModule = Yii::$app->addon->getModule('VTB');

            return $vtbModule->registerMessage($cyx, $documentId);
        }

        $sbbolModuleTypes = [
            SBBOLStmtReqType::TYPE,
            SBBOLPayDocRuType::TYPE,
        ];
        if (in_array($cyx->docType, $sbbolModuleTypes)) {
            $sbbolModule = Yii::$app->addon->getModule('SBBOL');

            return $sbbolModule->registerMessage($cyx, $documentId);
        }

        if ($cyx->docType === Sbbol2PayDocRuType::TYPE) {

            $sbbol2Module = Yii::$app->addon->getModule('sbbol2');

            return $sbbol2Module->registerMessage($cyx, $documentId);
        }
        $sbbol2ModuleTypes = [
            StatementRequestType::TYPE,
        ];
        if (in_array($cyx->docType, $sbbol2ModuleTypes)) {
            $sbbol2Module = Yii::$app->addon->getModule('sbbol2');
            // Если нет сббол2 модуля, то может мы и не шлюз, чо уж там
            if ($sbbol2Module) {
                // понять, мы шлюз или нет
                if (Sbbol2Helper::isGatewayTerminal($cyx->receiverId)) {
                    return $sbbol2Module->registerMessage($cyx, $documentId);
                }
            }
        }

        $raiffeisenModuleTypes = [
            StatementRequestType::TYPE,
        ];
        if (in_array($cyx->docType, $raiffeisenModuleTypes)) {
            $raiffeisenModule = Yii::$app->addon->getModule('raiffeisen');
            if ($raiffeisenModule) {
                if (RaiffeisenHelper::isGatewayTerminal($cyx->receiverId)) {
                    return $raiffeisenModule->registerMessage($cyx, $documentId);
                }
            }
        }

        if ($cyx->docType === VTBClientTerminalSettingsType::TYPE) {
            return $this->registerVTBClientTerminalSettings($cyx, $documentId);
        } else if ($cyx->docType === VTBFreeBankDocType::TYPE) {
            return $this->registerVTBFreeBankDoc($cyx, $documentId);
        } else if ($cyx->docType === VTBPrepareCancellationResponseType::TYPE) {
            return $this->registerVTBPrepareCancellationResponse($cyx, $documentId);
        } else if ($cyx->docType === VTBRegisterRuType::TYPE) {
            return $this->registerVTBRegisterRu($documentId);
        } else if ($cyx->docType === VTBRegisterCurType::TYPE) {
            return $this->registerVTBRegisterCur($documentId);
        }

        if ($this->isStatement($cyx->docType)) {
            $this->updateAccountBalanceFromStatement($documentId);
        }

        // Пробуем найти экспорт-джоб для данного типа документа, если нашли, то запускаем.
        if (isset($this->_config->docTypes[$cyx->docType]['jobs']['export'])) {
            Yii::$app->resque->enqueue(
                $this->_config->docTypes[$cyx->docType]['jobs']['export'],
                ['documentId' => $documentId]
            );
        }

        if ($cyx->docType === SBBOLClientTerminalSettingsType::TYPE) {
            return $this->registerSBBOLClientTerminalSettings($cyx, $documentId);
        }

        if ($cyx->docType === Sbbol2ClientTerminalSettingsType::TYPE) {
            return $this->registerSbbol2ClientTerminalSettings($cyx, $documentId);
        }

        if ($cyx->docType === RaiffeisenClientTerminalSettingsType::TYPE) {
            return $this->registerRaiffeisenClientTerminalSettings($cyx, $documentId);
        }

        return true;
    }

    private function validateSignatures(CyberXmlDocument $cyx, Document $document)
    {
        $typeModel = $cyx->content->getTypeModel();

		$mySignVerifier = \Yii::$app->xmlsec;

        $dom = new DOMDocument();
        $dom->loadXML((string) $typeModel);

        $xpath = new DOMXPath($dom);
        $xpath->registerNamespace('doc', 'http://cyberft.ru/xsd/edm.02');
        $xpath->registerNamespace('data', 'http://cyberft.ru/xsd/cftdata.02');
        $xpath->registerNamespace('cftsign', 'http://www.w3.org/2000/09/xmldsig#');
        $query = '/' . XMLSeclibsHelper::nsNode('doc', 'PaymentRegister')
                    . '/' . XMLSeclibsHelper::nsNode('data', 'Signatures')
                    . '/' . XMLSeclibsHelper::nsNode('cftsign', 'Signature');

        $signatures = $xpath->query($query, $dom);
		//$myCertManager = Yii::$app->getModule('certManager');

        $address = $cyx->senderId;

        $result = true;
        $fingerprint = null;

        foreach($signatures as $signature) {
            if (($fingerprint = $mySignVerifier->getFingerprint($signature)) === false) {
                Yii::info('Unable to find fingerprint inside XMLDSIG signature');
                $result = false;

                break;
            }

      		if (!($terminalId = TerminalId::extract($address))) {
                Yii::info("Terminal identifier '$address' has wrong format");
                $result = false;

                break;
            }

            $condition = array_diff($terminalId->toArray(), ['' => null]); // выбиваем пустые условия

            $myCert = Cert::findOne(
                array_merge($condition, ['fingerprint' => $fingerprint])
            );

            if (!$myCert) {
                Yii::info('No certificate found: ' . $fingerprint);
                $result = false;
                Yii::$app->monitoring->log(
                   'cert:certificateNotFound', 'document', $document->id,
                    [
                        'logLevel' => LogLevel::ERROR,
                        'fingerprint' => $fingerprint,
                        'terminalId' => $document->terminalId
                    ]
                );

                break;
            }

            if ($myCert->status != Cert::STATUS_C10) {
                Yii::info('Cert is not active: ' . $fingerprint);
                $result = false;
                Yii::$app->monitoring->log(
                   'cert:invalidCertificate', 'document', $document->id,
                    [
                        'logLevel' => LogLevel::ERROR,
                        'certId' => $myCert->id,
                        'fingerprint' => $fingerprint,
                        'terminalId' => $document->terminalId
                    ]
                );

                break;
            }

//            if (($myCert = $myCertManager->getCertificateByAddress($cyx->senderId, $fingerprint)) === null) {
//                Yii::info('No certificate found: ' . $fingerprint);
//
//                return false;
//            }

            if (!$myCert->isActive) {
                Yii::info('Certificate expired: ' . $fingerprint);
                $result = false;
                Yii::$app->monitoring->log(
                   'cert:certificateExpired', 'document', $document->id,
                    [
                        'logLevel' => LogLevel::ERROR,
                        'certId' => $myCert->id,
                        'fingerprint' => $fingerprint,
                        'terminalId' => $document->terminalId
                    ]
                );

                break;
            }

//            if (!$mySignVerifier->verifySignature($signature, $myCert->body)) {
//                // Не продолжаем проверку, если произошла ошибка верификации
//                Yii::info('Verification failed');
//
//                return false;
//            }
        }

        if (!$result) {
            DocumentTransportHelper::statusReport($document, [
                'statusCode' => 'RJCT',
                'errorCode' => '9999',
                'errorDescription' => 'Terminal error: Certificate(s) are not valid'
            ]);
        }

        return $result;
    }

    private function createPaymentOrders(CyberXmlDocument $cyx, $documentId)
    {
        $typeModel = $cyx->getContent()->getTypeModel();
        $document  = Document::findOne($documentId);

        if (empty($typeModel) || empty($document)) {
            $this->log('Could not load typeModel or Document for document ID ' . $documentId);
        }

        $terminal = $document->direction == Document::DIRECTION_IN ? $cyx->receiverId : $cyx->senderId;
        $terminalId = Terminal::getIdByAddress($terminal);

        if ($typeModel->type == PaymentRegisterType::TYPE) {
            $paymentOrderList = $typeModel->getPaymentOrders();

            foreach ($paymentOrderList as $typeModel) {
                $paymentOrder = new PaymentRegisterPaymentOrder();
                $paymentOrder->loadFromTypeModel($typeModel);
                $paymentOrder->registerId = $documentId;
                $paymentOrder->terminalId = $terminalId;

                if (!$paymentOrder->save()) {
                    $this->log(
                        'Failed to save payment order from payment register: '
                        . print_r($paymentOrder->errors, true)
                    );
                }
            }
        } elseif ($typeModel->type == SBBOLPayDocRuType::TYPE) {
            $paymentOrderType = PaymentOrderType::createFromSBBOLPayDocRu($typeModel);
            $paymentOrderType->setDate(date('d.m.Y', strtotime($paymentOrderType->dateCreated)));

            $paymentOrder = new PaymentRegisterPaymentOrder();
            $paymentOrder->loadFromTypeModel($paymentOrderType);
            $paymentOrder->registerId = $documentId;
            $paymentOrder->terminalId = $terminalId;
            $paymentOrder->date = $paymentOrderType->dateCreated;

            if (!$paymentOrder->save()) {
                $this->log(
                    'Failed to save payment order from SBBOLPayDocRu: '
                    . print_r($paymentOrder->errors, true)
                );
            }
        } elseif ($typeModel->type === VTBRegisterRuType::TYPE) {
            foreach ($typeModel->paymentOrders as $vtbPayDocRuTypeModel) {
                $paymentOrderType = PaymentOrderType::createFromVTBPayDocRu($vtbPayDocRuTypeModel);
                $paymentOrderType->setDate(date('d.m.Y', strtotime($paymentOrderType->dateCreated)));

                $paymentOrder = new PaymentRegisterPaymentOrder();
                $paymentOrder->loadFromTypeModel($paymentOrderType);
                $paymentOrder->registerId = $documentId;
                $paymentOrder->terminalId = $terminalId;
                $paymentOrder->date = $paymentOrderType->dateCreated;

                if (!$paymentOrder->save()) {
                    $this->log(
                        'Failed to save payment order from VTBPayDocRu: '
                        . print_r($paymentOrder->errors, true)
                    );
                }
            }
        }

        return true;
    }

    /**
     * Save file into storage. Using out folder
     *
     * @param string $path Data to save
     * @param string $filename File name
     * @return StoredFile|NULL
     */
    public function storeFileOut($path, $filename = '')
    {
        return $this->storeFile($path, static::RESOURCE_OUT, $filename);
    }

    /**
     * Save data into storage. Using out folder
     *
     * @param string $data Data to save
     * @param string $filename File name
     * @return StoredFile|NULL
     */
    public function storeDataOut($data, $filename = '')
    {
        return $this->storeData($data, static::RESOURCE_OUT, $filename);
    }

    public function storeDataIn($data, $filename = '')
    {
        return $this->storeData($data, static::RESOURCE_IN, $filename);
    }
    /**
     * Save data into storage. Using export folder
     *
     * @param string $data Data to save
     * @param string $filename Filename
     * @return StoredFile|NULL
     */
    public function storeDataExport($data, $filename = '')
    {
        return $this->storeData($data, static::RESOURCE_EXPORT, $filename);
    }

    /**
     * Get document
     *
     * @param integer $id Document ID
     * @return Edm|NULL
     */
    public function getDocument($id)
    {
        return EdmSearch::findOne($id);
    }

    /**
     * Перечень сопровождаемых документов
     * @return array
     */
    public function getDocTypes()
    {
        // @todo позднее скрестить с текущей реализацией настроек
        return [
            PaymentOrderType::TYPE => [
                'label' => PaymentOrderType::LABEL,
                'module' => $this->id,
            ],
//			PaymentOrderForeignCurrency::TYPE => [
//				'label'  => PaymentOrderForeignCurrency::LABEL,
//				'module' => $this->id,
//			],
//			CollectionOrder::TYPE             => [
//				'label'  => CollectionOrder::LABEL,
//				'module' => $this->id,
//			],
//			ForeignCurrencyPurchaseRequest::TYPE => [
//				'label'  => ForeignCurrencyPurchaseRequest::LABEL,
//				'module' => $this->id,
//			],
//			ForeignCurrencySellRequest::TYPE     => [
//				'label'  => ForeignCurrencySellRequest::LABEL,
//				'module' => $this->id,
//			],
//			PaymentRequest::TYPE                 => [
//				'label'  => PaymentRequest::LABEL,
//				'module' => $this->id,
//			],
        ];
    }

    /**
     * Get document data views
     *
     * @param string $docType Document type
     * @return array Return list of views
     */
    public function getDataViews($docType)
    {
        if (!$docType || !isset($this->_config->docTypes[$docType]['views'])) {

            return [];
        }

        return $this->_config->docTypes[$docType]['views'];
    }

    public function processDocument(Document $document, $sender = null, $receiver = null)
    {
        if (
            $document->type == StatementRequestType::TYPE
            // Изоляция Statement: CYB-3739
            || $document->type == StatementType::TYPE
            || $document->type === SBBOLStmtReqType::TYPE
            || $document->type === SBBOLStatementType::TYPE
            || $document->type === RaiffeisenStatementType::TYPE
            || $document->type === VTBStatementQueryType::TYPE
        ) {
            return $document->status == Document::STATUS_ACCEPTED ?: $document->updateStatus(Document::STATUS_ACCEPTED);
        }

        $keepCreatingState = $document->status === Document::STATUS_CREATING
            && $document->signaturesRequired == 0
            && $document->origin === Document::ORIGIN_WEB
            && in_array($document->type, [VTBFreeClientDocType::TYPE, Auth026Type::TYPE, VTBCredRegType::TYPE, VTBContractUnRegType::TYPE]);

        if ($keepCreatingState) {
            return true;
        }

        if ($this->processCryptoproSigningStatus($document)) {
            return $document->updateStatus(Document::STATUS_SERVICE_PROCESSING);
        }

        return parent::processDocument($document, $sender, $receiver);
    }

    public function processCryptoproSigningStatus(Document $document)
    {
        $typeModelClass = yii::$app->registry->getTypeModelClass($document->type);

        if (!is_subclass_of($typeModelClass,BaseVTBDocumentType::class)
            && $document->type !== VTBRegisterRuType::TYPE
            && $document->type !== VTBRegisterCurType::TYPE
        ) {
            return false;
        }

        /** @var VTBIntegrationSettings $vtbSettings */
        $vtbSettings = Yii::$app->settings->get('VTBIntegration', $document->terminalId);
        if (!$vtbSettings->enableCryptoProSign) {
            return false;
        }

        $signingRequest = CryptoproSigningRequest::findOneByDocument($document->id);
        if ($signingRequest === null) {
            $signingRequest = new CryptoproSigningRequest([
                'documentId' => $document->id,
                'status'     => CryptoproSigningRequest::STATUS_FOR_SIGNING,
            ]);
            $signingRequest->save();
        }

        if ($signingRequest->status === CryptoproSigningRequest::STATUS_FOR_SIGNING) {
            Yii::$app->resque->enqueue(
                VTBDocumentCryptoproSignJob::class,
                ['id' => $document->id]
            );

            return true;
        }

        return false;
    }

    public function onDocumentStatusChange(Document $document)
    {
        if ($document->type === Auth026Type::TYPE) {
            if ($document->status === Document::STATUS_ACCEPTED && $document->extModel->fileName) {
                $finZipDocument = Document::find()
                    ->from(Document::tableName() . ' doc')
                    ->innerJoin(FinZipDocumentExt::tableName() . ' ext',
                        [
                            'and',
                            'ext.documentId = doc.id',
                            ['doc.type' => FinZipType::TYPE],
                            ['doc.direction' => Document::DIRECTION_OUT],
                            ['ext.attachmentUUID' => $document->uuid]
                        ]
                    )->one();
                if ($finZipDocument !== null) {
                    DocumentTransportHelper::createSendingState($finZipDocument);
                }
            }

            /** @var ISO20022Module $isoModule */
            $isoModule = Yii::$app->addon->getModule('ISO20022');
            $isoModule->onDocumentStatusChange($document);

            return;
        } elseif ($document->extModel instanceof PaymentRegisterDocumentExt) {
            if ($document->status === Document::STATUS_DELETED) {
                PaymentRegisterPaymentOrder::updateAll(
                    ['registerId' => null],
                    ['registerId' => $document->id]
                );
            }
        }

        parent::onDocumentStatusChange($document);
    }

    private function registerVTBFreeBankDoc(CyberXmlDocument $cyxDocument, $documentId)
    {
        /** @var VTBFreeBankDocType $typeModel */
        $typeModel = $cyxDocument->getContent()->getTypeModel();

        /** @var FreeBankDoc $freeBankDoc */
        $freeBankDoc = $typeModel->document;

        if (empty($freeBankDoc->DOCATTACHMENT)) {
            return true;
        }

        $attachment = $freeBankDoc->DOCATTACHMENT[0];
        $storedFile = $this->storeDataIn($attachment->fileContent, $attachment->fileName);
        if ($storedFile === null) {
            $this->log('Failed to store VTBFreeBankDoc attachment');
            return false;
        }

        $extModel = BankLetterDocumentExt::findOne(['documentId' => $documentId]);
        if ($extModel === null) {
            $this->log('Cannot find ext model for document');
            return false;
        }

        $extModel->storedFileId = $storedFile->id;
        $extModel->fileName = $attachment->fileName;
        $isSaved = $extModel->save();
        if (!$isSaved) {
            $this->log('Cannot update ext model for document');
            return false;
        }

        return true;
    }

    public function registerVTBPrepareCancellationResponse(CyberXmlDocument $cyxDocument, $documentId)
    {
        /** @var VTBPrepareCancellationResponseType $typeModel */
        $typeModel = $cyxDocument->getContent()->getTypeModel();

        $requestDocument = Document::findOne(['uuid' => $typeModel->requestDocumentUuid]);
        if ($requestDocument === null) {
            $this->log("VTBPrepareCancellationRequest with uuid {$typeModel->requestDocumentUuid} is not found");
            return false;
        }

        /** @var VTBPrepareCancellationRequestExt $requestExtModel */
        $requestExtModel = $requestDocument->extModel;
        $requestExtModel->status = $typeModel->status;
        $requestExtModel->targetDocumentInfo = $typeModel->documentInfo;
        $requestExtModel->targetDocumentVTBReferenceId = $typeModel->vtbReferenceId;

        $isSaved = $requestExtModel->save();
        if (!$isSaved) {
            $this->log('Failed to update VTBPrepareCancellationRequest ext model, errors: ' . var_export($requestExtModel->getErrors(), true));
        }
        return $isSaved;
    }

    public function registerVTBClientTerminalSettings(CyberXmlDocument $cyxDocument, $documentId)
    {
        $vtbTerminalId = VTBHelper::getGatewayTerminalAddress();
        if ($cyxDocument->senderId !== $vtbTerminalId) {
            $this->log('VTBClientTerminalSettings sender id not VTB terminal');
            return false;
        }

        /** @var VTBClientTerminalSettingsType $typeModel */
        $typeModel = $cyxDocument->getContent()->getTypeModel();

        // 1. Обновляем справочник филиалов ВТБ
        $branchesData = array_map(
            function (VTBClientTerminalSettingsBankBranch $branch) {
                return $branch->attributes;
            },
            $typeModel->bankBranches
        );
        DictVTBBankBranch::refreshAll($branchesData);

        // 2. Создаем или обновляем организацию
        $terminal = Terminal::findOne(['terminalId' => $cyxDocument->receiverId]);
        $organization = DictOrganization::findOne(['terminalId' => $terminal->id]);
        if ($organization === null) {
            $organization = new DictOrganization();
        }

        $customer = $typeModel->customer;
        $countryLatin = Countries::getLatinNameByIso3166_1_numeric($customer->countryCode);
        $locationLatin = implode(', ', array_filter([$customer->internationalAddressSettlement, $countryLatin]));

        $organization->setAttributes(
            [
                'terminalId'       => $terminal->id,
                'type'             => $customer->type == 1 ? DictOrganization::TYPE_INDIVIDUAL : DictOrganization::TYPE_ENTITY,
                'propertyTypeCode' => $customer->propertyType,
                'name'             => $customer->name,
                'inn'              => $customer->inn,
                'kpp'              => $customer->kpp,
                'locationLatin'    => (empty($locationLatin) ? null : $locationLatin),
                'nameLatin'        => $customer->internationalName,
                'addressLatin'     => $customer->internationalStreetAddress,
                'state'            => $customer->addressState,
                'city'             => $customer->addressSettlement,
                'street'           => $customer->addressStreet,
                'buildingNumber'   => $customer->addressBuilding,
                'building'         => $customer->addressBuildingBlock,
                'district'         => $customer->addressDistrict,
                'locality'         => $customer->addressSettlement,
                'apartment'        => $customer->addressApartment,
            ],
            false
        );
        $organization->address = $organization->fullAddress;
        $organization->save(false);

        $organizationExists = $organization->refresh();
        if (!$organizationExists) {
            throw new Exception("Organization for terminal {$cyxDocument->receiverId} is not created");
        }

        // 3. Создаем / обновляем счета
        foreach ($typeModel->accounts as $account) {
            $currencyCode = substr($account->number, 5, 3);
            $currency = DictCurrency::findOne(['code' => $currencyCode]);
            if ($currency === null) {
                Yii::info("Currency for account {$account->number} is not found");
                continue;
            }

            $edmAccount = EdmPayerAccount::findOne(['number' => $account->number, 'organizationId' => $organization->id]);
            if ($edmAccount === null ) {
                $edmAccount = new EdmPayerAccount([
                    'organizationId' => $organization->id,
                    'number'         => $account->number,
                    'name'           => "Счет {$currency->name} {$account->number}",
                ]);
            }
            $edmAccount->setAttributes(
                [
                    'bankBik'    => $account->bankBik,
                    'payerName'  => $typeModel->customer->name,
                    'currencyId' => $currency->id,
                ],
                false
            );

            $isSaved = $edmAccount->save();
            if (!$isSaved) {
                Yii::info("Failed to save account {$account->number}, errors: " . var_export($edmAccount->getErrors(), true));
            }
        }

        // 4. Создаем / обновляем код компании в системе получателя
        $terminalRemoteIdAttributes = [
            'terminalId'       => $terminal->id,
            'terminalReceiver' => $vtbTerminalId,
        ];
        $terminalRemoteId = TerminalRemoteId::findOne($terminalRemoteIdAttributes);
        if ($terminalRemoteId === null) {
            $terminalRemoteId = new TerminalRemoteId($terminalRemoteIdAttributes);
        }
        $terminalRemoteId->remoteId = $typeModel->customer->id;
        $isSaved = $terminalRemoteId->save();
        if (!$isSaved) {
            Yii::info('Failed to save account terminal remote id, errors: ' . var_export($terminalRemoteId->getErrors(), true));
        }

        // 5. Добавляем id терминала ВТБ в справочник банков
        foreach ($typeModel->bankBranches as $branch) {
            DictBank::updateAll(
                ['terminalId' => $vtbTerminalId],
                ['bik'        => $branch->bik]
            );
        }

        return true;
    }

    public function registerSBBOLClientTerminalSettings(CyberXmlDocument $cyxDocument, $documentId)
    {
        if (!SBBOLHelper::isGatewayTerminal($cyxDocument->senderId)) {
            $this->log('SBBOLClientTerminalSettings sender is not Sberbank terminal');
            return false;
        }

        /** @var SBBOLClientTerminalSettingsType $typeModel */
        $typeModel = $cyxDocument->getContent()->getTypeModel();

        // 1. Создаем или обновляем организацию
        $terminal = Terminal::findOne(['terminalId' => $cyxDocument->receiverId]);
        $organization = DictOrganization::findOne(['terminalId' => $terminal->id]);

        if ($organization === null) {
            $organization = new DictOrganization();
        }

        $customer = $typeModel->customer;

        $organization->setAttributes(
            [
                'terminalId'       => $terminal->id,
                'type'             => DictOrganization::TYPE_ENTITY,
                'propertyTypeCode' => $customer->propertyType,
                'name'             => $customer->name,
                'inn'              => $customer->inn,
                'kpp'              => $customer->kpp,
                'nameLatin'        => $customer->internationalName,
                'state'            => $customer->addressState,
                'city'             => $customer->addressSettlement,
                'street'           => $customer->addressStreet,
                'buildingNumber'   => $customer->addressBuilding,
                'building'         => $customer->addressBuildingBlock,
                'district'         => $customer->addressDistrict,
                'locality'         => $customer->addressSettlement,
                'apartment'        => $customer->addressApartment,
                'ogrn'             => $customer->ogrn,
                'dateEgrul'        => $customer->dateOgrn
            ],
            false
        );

        $organization->address = $organization->fullAddress;
        $organization->save(false);

        $organizationExists = $organization->refresh();

        if (!$organizationExists) {
            throw new \Exception("Organization for terminal {$cyxDocument->receiverId} is not created");
        }


        // 2. Создаем / обновляем счета
        foreach ($typeModel->accounts as $account) {
            $currencyCode = substr($account->number, 5, 3);
            $currency = DictCurrency::findOne(['code' => $currencyCode]);

            if ($currency === null) {
                Yii::info("Currency for account {$account->number} is not found");
                continue;
            }

            $edmAccount = EdmPayerAccount::findOne(['number' => $account->number, 'organizationId' => $organization->id]);

            if ($edmAccount === null) {
                $edmAccount = new EdmPayerAccount([
                    'organizationId' => $organization->id,
                    'number'         => $account->number,
                    'name'           => "Счет {$currency->name} {$account->number}",
                ]);
            }

            $edmAccount->setAttributes(
                [
                    'bankBik'    => $account->bankBik,
                    'payerName'  => $typeModel->customer->name,
                    'currencyId' => $currency->id,
                ],
                false
            );

            $accountIsSaved = $edmAccount->save();

            // 2.1. Обновляем терминал у банка, к которому привязан счет
            $bank = $edmAccount->bank;
            $bank->terminalId = SBBOLHelper::getGatewayTerminalAddress();
            $bank->save();

            if (!$accountIsSaved) {
                Yii::info("Failed to save account {$account->number}, errors: " . var_export($edmAccount->getErrors(), true));
            }

            // 2.2. Сохраняем в справочник счетов SBBOL
            $edmSbbolAccount = EdmSBBOLAccount::find()
                ->where(['id' => $account->id])
                ->orWhere(['number' => $account->number])
                ->one();
            if ($edmSbbolAccount === null) {
                $edmSbbolAccount = new EdmSBBOLAccount();
            }
            $edmSbbolAccount->setAttributes([
                'id'         => $account->id,
                'number'     => $account->number,
                'customerId' => $account->customerId,
            ]);
            $sbbolAccountIsSaved = $edmSbbolAccount->save();
            if (!$sbbolAccountIsSaved) {
                Yii::info("Failed to save SBBOL account {$account->number}, errors: " . var_export($edmSbbolAccount->getErrors(), true));
            }
        }

        // 3. Записываем в настройки терминала информации из sbbol_customer
        $terminalSettings = Yii::$app->settings->get('app', $cyxDocument->receiverId);
        $terminalSettings->sbbolCustomerSenderName = $customer->senderName;
        $terminalSettings->save();

        return true;
    }

    public function registerSbbol2ClientTerminalSettings(CyberXmlDocument $cyxDocument, $documentId)
    {
        // 1. Проверяем, что документ пришел с терминала "Сбербанк"
        if (!Sbbol2Helper::isGatewayTerminal($cyxDocument->senderId)) {
            $this->log('Sbbol2ClientTerminalSettings sender is not Sberbank terminal');

            return false;
        }

        /** @var Sbbol2ClientTerminalSettingsType $typeModel */
        $typeModel = $cyxDocument->getContent()->getTypeModel();

        // 2. Создаем или обновляем организацию
        $terminal = Terminal::findOne(['terminalId' => $cyxDocument->receiverId]);
        $organization = DictOrganization::findOne(['terminalId' => $terminal->id]);

        if ($organization === null) {
            $organization = new DictOrganization();
        }

        $customer = $typeModel->customer;

        $organization->setAttributes(
            [
                'terminalId'                     => $terminal->id,
                'type'                           => DictOrganization::TYPE_ENTITY,
                'name'                           => $customer->shortName,
                'inn'                            => $customer->inn,
                'kpp'                            => $customer->kpp,
                'ogrn'                           => $customer->ogrn,
                'okpo'                           => $customer->okpo,
                'building'                       => $customer->addressBuilding,
                'city'                           => $customer->addressCity,
                'street'                         => $customer->addressStreet,
            ],
            false
        );

        $organization->address = $organization->fullAddress;
        $organization->save(false);

        $organizationExists = $organization->refresh();

        if (!$organizationExists) {
            throw new \Exception("Organization for terminal {$cyxDocument->receiverId} is not created");
        }

        // 3. Создаем / обновляем счета
        foreach ($typeModel->accounts as $account) {
            $currencyCode = $account->currencyCode;
            $currency = DictCurrency::findOne(['code' => $currencyCode]);

            if ($currency === null) {
                Yii::info("Currency for account {$account->number} is not found");
                continue;
            }

            $edmAccount = EdmPayerAccount::findOne(['number' => $account->number, 'organizationId' => $organization->id]);

            if ($edmAccount === null) {
                $edmAccount = new EdmPayerAccount([
                    'organizationId' => $organization->id,
                    'number'         => $account->number,
                    'name'           => "Счет {$currency->name} {$account->number}",
                ]);
            }

            $edmAccount->setAttributes(
                [
                    'bankBik'    => $account->bic,
                    'payerName'  => $typeModel->customer->shortName,
                    'currencyId' => $currency->id,
                ],
                false
            );

            $accountIsSaved = $edmAccount->save();

            // 4. Обновляем терминал у банка, к которому привязан счет
            $bank = $edmAccount->bank;
            $bank->terminalId = Sbbol2Helper::getGatewayTerminalAddress();
            $bank->save();

            if (!$accountIsSaved) {
                Yii::info("Failed to save account {$account->number}, errors: " . var_export($edmAccount->getErrors(), true));
            }

            $edmSbbolAccount = EdmSBBOLAccount::find()
                ->where(['id' => $account->id])
                ->orWhere(['number' => $account->number])
                ->one();
            if ($edmSbbolAccount === null) {
                $edmSbbolAccount = new EdmSBBOLAccount();
            }
            $edmSbbolAccount->setAttributes([
                'id'         => $account->id,
                'number'     => $account->number,
                'customerId' => $account->customerId,
            ]);
            $sbbolAccountIsSaved = $edmSbbolAccount->save();
            if (!$sbbolAccountIsSaved) {
                Yii::info("Failed to save SBBOL account {$account->number}, errors: " . var_export($edmSbbolAccount->getErrors(), true));
            }
        }

        $terminalSettings = Yii::$app->settings->get('app', $cyxDocument->receiverId);
        $terminalSettings->save();

        return true;
    }

    public function registerRaiffeisenClientTerminalSettings(CyberXmlDocument $cyxDocument, $documentId)
    {
        if (!RaiffeisenHelper::isGatewayTerminal($cyxDocument->senderId)) {
            $this->log('RaiffeisenClientTerminalSettings sender id not Raiffeisen terminal');
            return false;
        }

        /** @var RaiffeisenClientTerminalSettingsType $typeModel */
        $typeModel = $cyxDocument->getContent()->getTypeModel();

        // 1. Создаем или обновляем организацию
        $terminal = Terminal::findOne(['terminalId' => $cyxDocument->receiverId]);
        $organization = DictOrganization::findOne(['terminalId' => $terminal->id]);

        if ($organization === null) {
            $organization = new DictOrganization();
        }

        $customer = $typeModel->customer;

        $organization->setAttributes(
            [
                'terminalId'       => $terminal->id,
                'type'             => DictOrganization::TYPE_ENTITY,
                'propertyTypeCode' => $customer->propertyType,
                'name'             => $customer->name,
                'inn'              => $customer->inn,
                'kpp'              => $customer->kpp,
                'nameLatin'        => $customer->internationalName,
                'state'            => $customer->addressState,
                'city'             => $customer->addressSettlement,
                'street'           => $customer->addressStreet,
                'buildingNumber'   => $customer->addressBuilding,
                'building'         => $customer->addressBuildingBlock,
                'district'         => $customer->addressDistrict,
                'locality'         => $customer->addressSettlement,
                'apartment'        => $customer->addressApartment,
                'ogrn'             => $customer->ogrn,
                'dateEgrul'        => $customer->dateOgrn
            ],
            false
        );

        $organization->address = $organization->fullAddress;
        $organization->save(false);

        $organizationExists = $organization->refresh();

        if (!$organizationExists) {
            throw new \Exception("Organization for terminal {$cyxDocument->receiverId} is not created");
        }


        // 2. Создаем / обновляем счета
        foreach ($typeModel->accounts as $account) {
            $currencyCode = substr($account->number, 5, 3);
            $currency = DictCurrency::findOne(['code' => $currencyCode]);

            if ($currency === null) {
                Yii::info("Currency for account {$account->number} is not found");
                continue;
            }

            $edmAccount = EdmPayerAccount::findOne(['number' => $account->number, 'organizationId' => $organization->id]);

            if ($edmAccount === null) {
                $edmAccount = new EdmPayerAccount([
                    'organizationId' => $organization->id,
                    'number'         => $account->number,
                    'name'           => "Счет {$currency->name} {$account->number}",
                ]);
            }

            $edmAccount->setAttributes(
                [
                    'bankBik'    => $account->bankBik,
                    'payerName'  => $typeModel->customer->name,
                    'currencyId' => $currency->id,
                ],
                false
            );

            $accountIsSaved = $edmAccount->save();

            // 2.1. Обновляем терминал у банка, к которому привязан счет
            $bank = $edmAccount->bank;
            $bank->terminalId = RaiffeisenHelper::getGatewayTerminalAddress();
            $bank->save();

            if (!$accountIsSaved) {
                Yii::info("Failed to save account {$account->number}, errors: " . var_export($edmAccount->getErrors(), true));
            }
        }

        return true;
    }

    public function registerVTBRegisterRu($documentId)
    {
        $document = Document::findOne($documentId);

        $typeModel = CyberXmlDocument::getTypeModel($document->getValidStoredFileId());

        $module = Yii::$app->registry->getTypeModule($typeModel->type);

        $vtbData = [];

        foreach($typeModel->paymentOrders as $paymentOrder) {
            $docAttributes = [];
            $docAttributes['type'] = $paymentOrder->type;
            $docAttributes['uuid'] = Uuid::generate();
            $docAttributes['uuidRemote'] = $document->uuidRemote;
            $docAttributes['sender'] = $document->sender;
            $docAttributes['receiver'] = $document->receiver;
            $docAttributes['terminalId'] = $document->terminalId;
            $docAttributes['direction'] = Document::DIRECTION_IN;
            $docAttributes['origin'] = Document::ORIGIN_WEB;
            $docAttributes['uuidReference'] = $document->uuid;
            $docAttributes['status'] = Document::STATUS_CREATING;

            $cyxDoc = CyberXmlDocument::loadTypeModel($paymentOrder);
            $cyxDoc->docDate = date('c');
            $cyxDoc->docId = $docAttributes['uuid'];
            $cyxDoc->senderId = $docAttributes['sender'];
            $cyxDoc->receiverId = $docAttributes['receiver'];

            if (!$cyxDoc->validateXSD()) {
                Yii::error(
                    'Validation failed for CyberXml document ' . $paymentOrder->type . ': ' . print_r($cyxDoc->getErrors(), true)
                );

                return false;
            }

            $storedFile = $module->storeDataOut($cyxDoc->saveXML());

            $docAttributes['actualStoredFileId'] = $storedFile->id;

            $payDocRu = DocumentHelper::createDocument($docAttributes);

            $vtbData[] = [$cyxDoc, $payDocRu->id];
        }

        $vtbModule = Yii::$app->addon->getModule('VTB');

        $isSuccessful = true;

        foreach($vtbData as $item) {
            $cyxDoc = $item[0];
            $docId = $item[1];

            $result = $vtbModule->registerMessage($cyxDoc, $docId);

            $document = Document::findOne($docId);

            if ($result) {
                $status = Document::STATUS_PROCESSED;
            } else {
                $status = Document::STATUS_PROCESSING_ERROR;
                $isSuccessful = false;
            }

            $document->status = $status;
            $document->save(false);
        }

        return $isSuccessful;
    }

    public function registerVTBRegisterCur($documentId)
    {
        $document = Document::findOne($documentId);

        $registerCur = CyberXmlDocument::getTypeModel($document->getValidStoredFileId());

        $debitAccount = null;
        $currency = null;

        $module = Yii::$app->registry->getTypeModule($registerCur->type);

        $vtbData = [];

        foreach($registerCur->paymentOrders as $typeModel) {
            if (!$debitAccount) {
                $debitAccount = $typeModel->document->PAYERACCOUNT;
            }

            if (!$currency) {
                $currency = DictCurrency::findOne(['code' => $typeModel->document->CURRCODETRANSFER]);
                $currency = $currency !== null ? $currency->name : null;
            }

            // Создание объектов платежных поручений
            $fcoExt = new ForeignCurrencyOperationDocumentExt(['documentId' => $document->id]);
            $fcoExt->loadContentModel($typeModel);
            $isSaved = $fcoExt->save(false);
            if (!$isSaved) {
                Yii::warning('Failed to save payment order to database, errors: ' . var_export($fcoExt->getErrors(), true));
            }

            // Создание PayDocCur
            $docAttributes = [];
            $docAttributes['type'] = $typeModel->type;
            $docAttributes['uuid'] = Uuid::generate();
            $docAttributes['uuidRemote'] = $document->uuidRemote;
            $docAttributes['sender'] = $document->sender;
            $docAttributes['receiver'] = $document->receiver;
            $docAttributes['terminalId'] = $document->terminalId;
            $docAttributes['direction'] = Document::DIRECTION_IN;
            $docAttributes['origin'] = Document::ORIGIN_WEB;
            $docAttributes['uuidReference'] = $document->uuid;
            $docAttributes['status'] = Document::STATUS_CREATING;

            $vtbDoc = $typeModel->document;

            $extAttributes = [];
            $extAttributes['numberDocument'] = $vtbDoc->DOCUMENTNUMBER ?: '0';

            $extAttributes['date'] = $vtbDoc->DOCUMENTDATE !== null ? $vtbDoc->DOCUMENTDATE->format('Y-m-d') : null;
            $extAttributes['debitAccount'] = $vtbDoc->PAYERACCOUNT;
            $extAttributes['creditAccount'] = $vtbDoc->BENEFICIARACCOUNT;
            $extAttributes['currency'] = $currency;
            $extAttributes['sum'] = $vtbDoc->AMOUNTTRANSFER;
            $extAttributes['currencySum'] = $vtbDoc->AMOUNTTRANSFER;

            $cyxDoc = CyberXmlDocument::loadTypeModel($typeModel);
            $cyxDoc->docDate = date('c');
            $cyxDoc->docId = $docAttributes['uuid'];
            $cyxDoc->senderId = $docAttributes['sender'];
            $cyxDoc->receiverId = $docAttributes['receiver'];

            if (!$cyxDoc->validateXSD()) {
                Yii::error(
                    'Validation failed for CyberXml document ' . $typeModel->type . ': ' . print_r($cyxDoc->getErrors(), true)
                );

                return false;
            }

            $storedFile = $module->storeDataOut($cyxDoc->saveXML());

            $docAttributes['actualStoredFileId'] = $storedFile->id;

            $vtbDocument = DocumentHelper::createDocument($docAttributes, $extAttributes);

            $vtbData[] = [$cyxDoc, $vtbDocument->id];
        }

        $extModel = $document->extModel;
        $extModel->documentId = $document->id;
        $extModel->debitAccount = $debitAccount;
        $extModel->paymentsCount = count($registerCur->paymentOrders);
        $extModel->uuid = (string)Uuid::generate();
        $extModel->save();

        $vtbModule = Yii::$app->addon->getModule('VTB');

        $isSuccessful = true;

        foreach($vtbData as $item) {
            $cyxDoc = $item[0];
            $docId = $item[1];

            $result = $vtbModule->registerMessage($cyxDoc, $docId);

            $document = Document::findOne($docId);

            if ($result) {
                $status = Document::STATUS_PROCESSED;
            } else {
                $status = Document::STATUS_PROCESSING_ERROR;
                $isSuccessful = false;
            }

            $document->status = $status;
            $document->save(false);
        }

        return $isSuccessful;
    }

    public function getName(): string
    {
        return Yii::t('edm', 'Banking');
    }

    public function getDeletableDocumentTypes(User $user): array
    {
        $typesAllowedToBeDeleted = [];
        foreach ($this->getDocumentTypeGroups() as $typeGroup) {
            $userCanDelete = $user->can(
                DocumentPermission::DELETE,
                ['serviceId' => static::SERVICE_ID, 'documentTypeGroup' => $typeGroup->id]
            );
            if ($userCanDelete) {
                $typesAllowedToBeDeleted = array_merge($typesAllowedToBeDeleted, $typeGroup->types);
            }
        }

        $types = $this->getConfig()->docTypes;
        $deletableTypes = [];
        foreach (array_keys($types) as $type) {
            $typeWithoutPrefix = preg_replace('/^\w+\:/', '', $type);
            if (!in_array($typeWithoutPrefix, $typesAllowedToBeDeleted)) {
                continue;
            }

            $extModelClass = Yii::$app->registry->getTypeExtModelClass($type);
            if (empty($extModelClass)) {
                continue;
            }

            $isDocumentDeletable = (new $extModelClass())->isDocumentDeletable($user->identity);
            if (!$isDocumentDeletable) {
                continue;
            }

            $deletableTypes[] = $type;
        }

        return $deletableTypes;
    }

    public function getSignableDocumentTypes(User $user): array
    {
        $typesAllowedToBeSigned = [];
        foreach ($this->getDocumentTypeGroups() as $typeGroup) {
            $userCanSign = $user->can(
                DocumentPermission::SIGN,
                ['serviceId' => static::SERVICE_ID, 'documentTypeGroup' => $typeGroup->id]
            );
            if ($userCanSign) {
                $typesAllowedToBeSigned = array_merge($typesAllowedToBeSigned, $typeGroup->types);
            }
        }
        $types = $this->getConfig()->docTypes;

        foreach (array_keys($types) as $type) {
            $typeWithoutPrefix = preg_replace('/^\w+\:/', '', $type);
            if (!in_array($typeWithoutPrefix, $typesAllowedToBeSigned)) {
                continue;
            }
            $typesAllowedToBeSigned[] = $type;
        }

        return $typesAllowedToBeSigned;
    }

    public function processPain002(Pain002Type $typeModel, $documentId = null): void
    {
        $this->updatePaymentRegisterStatusFromPain002($typeModel);
        $this->updateCurrencyPaymentRegisterStatusFromPain002($typeModel);
        $this->updatePain001FxRlsFromPain002($typeModel, $documentId);
    }

    public function updatePain001FxRlsFromPain002(Pain002Type $typeModel, $documentId): void
    {
        /** @var \yii\db\Query $query */
        $query = Document::find()
            ->innerJoin(ForeignCurrencyOperationDocumentExt::tableName() . ' ext', 'document.id = ext.documentId')
            ->where([
                'document.direction' => Document::DIRECTION_OUT,
                'document.type'      => [Pain001FxType::TYPE, Pain001RlsType::TYPE],
                'ext.uuid'           => $typeModel->originalMsgId,
            ]);

        $document = $query->one();

        if ($document === null) {
            return;
        }

        Document::updateAll(['uuidReference' => $document->uuid], ['id' => $documentId]);

        /** @var CurrencyPaymentRegisterDocumentExt $extModel */
        $extModel = $document->extModel;
        if ($typeModel->statusCodeGrp) {
            $extModel->businessStatus = $typeModel->statusCodeGrp;
        }
        if ($typeModel->errorDescriptionGrp) {
            $extModel->businessStatusDescription = $typeModel->errorDescriptionGrp;
        }
        $extModel->save();
        //$documentPain002->uuidReference = $extDocUuid;

        /** @var \SimpleXMLElement $xml */
        $xml = $typeModel->getRawXml();
        $statusElements = $xml->xpath("/*[local-name()='Document']/*[local-name()='CstmrPmtStsRpt']/*[local-name()='OrgnlPmtInfAndSts']/*[local-name()='TxInfAndSts']");
        if (count($statusElements) === 0) {
            $updatedPaymentOrdersCount = ForeignCurrencyOperationDocumentExt::updateBusinessStatusesByRegister(
                $document->id,
                $typeModel->statusCodeGrp,
                $typeModel->errorDescriptionGrp,
                null
            );
            Yii::info("Updated $updatedPaymentOrdersCount currency payment orders from {$document->type} id={$document->id} with status={$typeModel->statusCodeGrp}");
        } else {
            foreach ($statusElements as $statusElement) {
                $paymentUuid = (string)$statusElement->OrgnlInstrId ?: null;
                $statusCode = (string)$statusElement->TxSts ?: null;
                $statusDescription = isset($statusElement->StsRsnInf->AddtlInf)
                    ? ISO20022Type::joinTagsContent($statusElement->StsRsnInf, "./*[local-name()='AddtlInf']")
                    : null;

                if (!$paymentUuid || !$statusCode) {
                    continue;
                }

                $paymentOrder = ForeignCurrencyOperationDocumentExt::findOne([
                    'documentId' => $document->id,
                    'uuid'       => $paymentUuid,
                ]);

                if ($paymentOrder === null) {
                    Yii::info("Cannot find currency payment order with uuid $paymentUuid in register {$document->id}");
                    continue;
                }

                $isUpdated = $paymentOrder->updateBusinessStatus($statusCode, $statusDescription, null);

                if ($isUpdated) {
                    Yii::info("Updated payment order #{$paymentOrder->numberDocument} from {$document->type} id={$document->id} with status=$statusCode");
                    Yii::$app->monitoring->log(
                        'document:documentBusinessStatusChange',
                        'document',
                        $paymentOrder->id,
                        [
                            'businessStatus' => $statusCode,
                            'documentType' => $document->type,
                            'reportType' => $typeModel->getType(),
                            'terminalId' => $document->terminalId
                        ]
                    );
                }
            }
        }
    }

    public function processAuth027(Document $document, Auth027Type $typeModel): void
    {
        if (!$typeModel->originalMsgId) {
            return;
        }

        $referencedDocument = Document::find()
            ->innerJoin(BankLetterDocumentExt::tableName() . ' ext', 'document.id = ext.documentId')
            ->where([
                'document.direction' => Document::DIRECTION_OUT,
                'document.type'      => Auth026Type::TYPE,
                'document.sender'    => $document->receiver,
                'document.receiver'  => $document->sender,
                'ext.uuid'           => $typeModel->originalMsgId,
            ])
            ->one();

        if ($referencedDocument === null) {
            return;
        }

        /** @var BankLetterDocumentExt $extModel */
        $extModel = $referencedDocument->extModel;

        $statusCode = $typeModel->statusCode;
        if (empty($typeModel->statusCode)) {
            $this->log("auth.027 document {$document->id} has empty status code, will not update status");
            return;
        }

        $extModel->businessStatus = $typeModel->statusCode;
        if (!empty($typeModel->errorDescription)) {
            $extModel->businessStatusDescription = $typeModel->errorDescription;
        }

        $isUpdated = $extModel->save();
        if (!$isUpdated) {
            $this->log("Failed to update business status for bank letter ext model, document: {$referencedDocument->id}, errors: " . var_export($extModel->getErrors(), true));
            return;
        }

        $document->uuidReference = $referencedDocument->uuid;
        $document->save(false);

        Yii::info("Updated bank letter document {$referencedDocument->id} from auth.027 document {$document->id} with status=$statusCode");
        Yii::$app->monitoring->log(
            'document:documentBusinessStatusChange',
            'document',
            $referencedDocument->id,
            [
                'businessStatus' => $statusCode,
                'documentType'   => $referencedDocument->type,
                'reportType'     => $document->type,
                'terminalId'     => $referencedDocument->terminalId,
            ]
        );
    }

    public function updatePaymentRegisterStatusFromPain002(Pain002Type $typeModel): void
    {
        $document = Document::find()
            ->innerJoin(PaymentRegisterDocumentExt::tableName() . ' ext', 'document.id = ext.documentId')
            ->where([
                'document.direction' => Document::DIRECTION_OUT,
                'document.type'      => Pain001RubType::TYPE,
                'ext.msgId'          => $typeModel->originalMsgId,
            ])
            ->one();

        if ($document === null) {
            return;
        }

        /** @var PaymentRegisterDocumentExt $extModel */
        $extModel = $document->extModel;

        if (!empty($typeModel->statusCodeGrp)) {
            $extModel->businessStatus = $typeModel->statusCodeGrp;
        }

        if (!empty($typeModel->errorDescriptionGrp)) {
            $extModel->businessStatusDescription = $typeModel->errorDescriptionGrp;
        }

        $extModel->save();

        /** @var \SimpleXMLElement $xml */
        $xml = $typeModel->getRawXml();
        $statusElements = $xml->xpath("/*[local-name()='Document']/*[local-name()='CstmrPmtStsRpt']/*[local-name()='OrgnlPmtInfAndSts']/*[local-name()='TxInfAndSts']");
        if (count($statusElements) === 0) {
            $updatedPaymentOrdersCount = PaymentRegisterPaymentOrder::updateBusinessStatusesByRegister(
                $document->id,
                $typeModel->statusCodeGrp,
                $typeModel->errorDescriptionGrp,
                null
            );
            Yii::info("Updated $updatedPaymentOrdersCount PaymentOrders from ISOPaymentRegister id={$document->id} with status={$typeModel->statusCodeGrp}");
        } else {
            foreach ($statusElements as $statusElement) {
                $paymentUuid = (string)$statusElement->OrgnlInstrId ?: null;
                $statusCode = (string)$statusElement->TxSts ?: null;
                $statusDescription = isset($statusElement->StsRsnInf->AddtlInf)
                    ? ISO20022Type::joinTagsContent($statusElement->StsRsnInf, "./*[local-name()='AddtlInf']")
                    : null;

                if (!$paymentUuid || !$statusCode) {
                    continue;
                }

                $paymentOrder = PaymentRegisterPaymentOrder::findOne([
                    'registerId' => $document->id,
                    'uuid'       => $paymentUuid,
                ]);

                if ($paymentOrder === null) {
                    Yii::info("Cannot find payment order with uuid $paymentUuid in register {$document->id}");
                    continue;
                }

                $isUpdated = $paymentOrder->updateBusinessStatus($statusCode, $statusDescription, null);

                if ($isUpdated) {
                    Yii::info("Updated PaymentOrder #{$paymentOrder->number} from ISOPaymentRegister id={$document->id} with status=$statusCode");
                    Yii::$app->monitoring->log(
                        'document:documentBusinessStatusChange',
                        'document',
                        $paymentOrder->id,
                        [
                            'businessStatus' => $statusCode,
                            'documentType' => PaymentOrderType::TYPE,
                            'reportType' => PaymentStatusReportType::TYPE,
                            'terminalId' => $paymentOrder->terminalId
                        ]
                    );
                }
            }
        }
    }

    public function updateCurrencyPaymentRegisterStatusFromPain002(Pain002Type $typeModel): void
    {
        $document = Document::find()
            ->innerJoin(CurrencyPaymentRegisterDocumentExt::tableName() . ' ext', 'document.id = ext.documentId')
            ->where([
                'document.direction' => Document::DIRECTION_OUT,
                'document.type'      => Pain001FcyType::TYPE,
                'ext.uuid'           => $typeModel->originalMsgId,
            ])
            ->one();

        if ($document === null) {
            return;
        }

        /** @var CurrencyPaymentRegisterDocumentExt $extModel */
        $extModel = $document->extModel;
        if ($typeModel->statusCodeGrp) {
            $extModel->businessStatus = $typeModel->statusCodeGrp;
        }
        if ($typeModel->errorDescriptionGrp) {
            $extModel->businessStatusDescription = $typeModel->errorDescriptionGrp;
        }
        $extModel->save();

        /** @var \SimpleXMLElement $xml */
        $xml = $typeModel->getRawXml();
        $statusElements = $xml->xpath("/*[local-name()='Document']/*[local-name()='CstmrPmtStsRpt']/*[local-name()='OrgnlPmtInfAndSts']/*[local-name()='TxInfAndSts']");
        if (count($statusElements) === 0) {
            $updatedPaymentOrdersCount = ForeignCurrencyOperationDocumentExt::updateBusinessStatusesByRegister(
                $document->id,
                $typeModel->statusCodeGrp,
                $typeModel->errorDescriptionGrp,
                null
            );
            Yii::info("Updated $updatedPaymentOrdersCount currency payment orders from {$document->type} id={$document->id} with status={$typeModel->statusCodeGrp}");
        } else {
            foreach ($statusElements as $statusElement) {
                $paymentUuid = (string)$statusElement->OrgnlInstrId ?: null;
                $statusCode = (string)$statusElement->TxSts ?: null;
                $statusDescription = isset($statusElement->StsRsnInf->AddtlInf)
                    ? ISO20022Type::joinTagsContent($statusElement->StsRsnInf, "./*[local-name()='AddtlInf']")
                    : null;

                if (!$paymentUuid || !$statusCode) {
                    continue;
                }

                $paymentOrder = ForeignCurrencyOperationDocumentExt::findOne([
                    'documentId' => $document->id,
                    'uuid'       => $paymentUuid,
                ]);

                if ($paymentOrder === null) {
                    Yii::info("Cannot find currency payment order with uuid $paymentUuid in register {$document->id}");
                    continue;
                }

                $isUpdated = $paymentOrder->updateBusinessStatus($statusCode, $statusDescription, null);

                if ($isUpdated) {
                    Yii::info("Updated payment order #{$paymentOrder->numberDocument} from {$document->type} id={$document->id} with status=$statusCode");
                    Yii::$app->monitoring->log(
                        'document:documentBusinessStatusChange',
                        'document',
                        $paymentOrder->id,
                        [
                            'businessStatus' => $statusCode,
                            'documentType' => $document->type,
                            'reportType' => $typeModel->getType(),
                            'terminalId' => $document->terminalId
                        ]
                    );
                }
            }
        }
    }

    private function isStatement(string $docType): bool
    {
        return in_array(
            $docType,
            [
                StatementType::TYPE,
                VTBStatementRuType::TYPE,
                SBBOLStatementType::TYPE,
                Sbbol2StatementType::TYPE,
                RaiffeisenStatementType::TYPE,
                Camt052Type::TYPE,
                Camt053Type::TYPE,
                Camt054Type::TYPE,
            ]
        );
    }

    private function updateAccountBalanceFromStatement(int $documentId): void
    {
        try {
            $document = Document::findOne($documentId);
            if (!$document) {
                throw new \Exception("Cannot find document $documentId");
            }

            $extModel = StatementDocumentExt::findOne(['documentId' => $documentId]);
            if (!$extModel) {
                throw new \Exception("Cannot find statement ext model for document $documentId");
            }
            AccountBalance::createOrUpdate(
                $extModel->accountNumber,
                $extModel->closingBalance,
                $extModel->periodEnd,
                $document->dateCreate
            );
        } catch (\Throwable $exception) {
            $this->log("Failed to update account balance from statement, caused by $exception");
        }
    }
}

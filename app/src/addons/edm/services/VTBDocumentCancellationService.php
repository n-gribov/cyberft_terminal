<?php

namespace addons\edm\services;

use addons\edm\EdmModule;
use addons\edm\models\BaseVTBDocument\BaseVTBDocumentType;
use addons\edm\models\VTBCancellationRequest\VTBCancellationRequestExt;
use addons\edm\models\VTBCancellationRequest\VTBCancellationRequestType;
use addons\edm\models\VTBPrepareCancellationRequest\VTBPrepareCancellationRequestExt;
use addons\edm\models\VTBPrepareCancellationRequest\VTBPrepareCancellationRequestForm;
use addons\edm\models\VTBPrepareCancellationRequest\VTBPrepareCancellationRequestType;
use addons\edm\models\VTBRegisterCur\VTBRegisterCurType;
use addons\edm\models\VTBRegisterRu\VTBRegisterRuType;
use addons\edm\services\VTBDocumentCancellationService\CancellationStatus;
use common\document\Document;
use common\helpers\DocumentHelper;
use common\helpers\vtb\VTBHelper;
use common\models\cyberxml\CyberXmlDocument;
use common\models\sbbolxml\request\PayDocCurType;
use common\models\Terminal;
use common\models\UserTerminal;
use common\models\vtbxml\documents\BSDocument;
use common\models\vtbxml\documents\CancellationRequest;
use common\models\vtbxml\documents\PayDocRu;
use common\models\vtbxml\service\SignInfo;
use common\modules\transport\helpers\DocumentTransportHelper;
use Exception;
use Yii;

class VTBDocumentCancellationService
{
    public function documentCanBeCancelled(Document $document): bool
    {
        return $this->userCanCancelDocument($document) && $this->isDocumentCancellable($document);
    }

    public function sendPrepareCancellationRequest(VTBPrepareCancellationRequestForm $form): Document
    {
        $document = $form->document;
        $this->ensureDocumentCanBeCancelled($document);

        $senderTerminal = Terminal::findOne($document->terminalId);
        $receiverTerminalId = VTBHelper::getGatewayTerminalAddress();

        $vtbDocument = $this->extractVtbDocument($document, $form->documentNumber, $form->documentDate);

        $requestTypeModel = new VTBPrepareCancellationRequestType([
            'documentUuid'    => $document->uuid,
            'documentNumber'  => $form->documentNumber ?: null,
            'documentDate'    => $form->documentDate ?: null,
            'vtbDocumentType' => $vtbDocument::TYPE,
            'vtbCustomerId'   => $vtbDocument->CUSTID,
            'messageForBank'  => $form->messageForBank,
        ]);

        $context = DocumentHelper::createDocumentContext(
            $requestTypeModel,
            [
                'type'               => $requestTypeModel->getType(),
                'typeGroup'          => EdmModule::SERVICE_ID,
                'direction'          => Document::DIRECTION_OUT,
                'origin'             => Document::ORIGIN_WEB,
                'terminalId'         => $senderTerminal->id,
                'sender'             => $senderTerminal->terminalId,
                'receiver'           => $receiverTerminalId,
                'status'             => Document::STATUS_ACCEPTED,
                'signaturesRequired' => 0,
            ],
            [
                'targetDocumentUuid'   => $document->uuid,
                'messageForBank'       => $form->messageForBank,
                'status'               => VTBPrepareCancellationRequestExt::STATUS_CREATED,
                'targetDocumentNumber' => $requestTypeModel->documentNumber,
                'targetDocumentDate'   => $requestTypeModel->documentDate,
            ]
        );

        if ($context === false) {
            throw new \Exception('Failed to create document context');
        }

        /** @var Document $requestDocument */
        $requestDocument = $context['document'];
        DocumentHelper::waitForDocumentsToLeaveStatus([$requestDocument->id], Document::STATUS_SERVICE_PROCESSING);
        $requestDocument->refresh();
        DocumentTransportHelper::processDocument($requestDocument);

        return $requestDocument;
    }

    public function proceedCancellation($prepareCancellationRequestDocumentId): CancellationStatus
    {
        $prepareRequestDocument = Yii::$app->terminalAccess->query(
            Document::className(),
            ['id' => $prepareCancellationRequestDocumentId, 'type' => VTBPrepareCancellationRequestType::TYPE]
        )->one();

        if ($prepareRequestDocument === null) {
            Yii::info("VTBPrepareCancellationRequest document $prepareCancellationRequestDocumentId is not found");
            return new CancellationStatus(CancellationStatus::REJECTED);
        }

        /** @var VTBPrepareCancellationRequestExt $prepareRequestExt */
        $prepareRequestExt = $prepareRequestDocument->extModel;

        if ($prepareRequestExt->status ===  VTBPrepareCancellationRequestExt::STATUS_CREATED) {
            return new CancellationStatus(CancellationStatus::PENDING);
        }
        if ($prepareRequestExt->status !== VTBPrepareCancellationRequestExt::STATUS_PROCESSED) {
            Yii::info("VTBPrepareCancellationRequest $prepareCancellationRequestDocumentId was not accepted, status: {$prepareRequestExt->status}");
            return new CancellationStatus(CancellationStatus::REJECTED);
        }

        try {
            $requestDocument = $this->findOrCreateCancellationRequestDocument($prepareRequestDocument);
        } catch (Exception $exception) {
            Yii::info("Failed to create cancellation request document, caused by: $exception");
            return new CancellationStatus(CancellationStatus::REJECTED);
        }

        DocumentHelper::waitForDocumentsToLeaveStatus([$requestDocument->id], Document::STATUS_SERVICE_PROCESSING);
        $requestDocument->refresh();

        if ($requestDocument->status === Document::STATUS_FORSIGNING) {
            return new CancellationStatus(CancellationStatus::SIGNATURE_REQUIRED, $requestDocument);
        }

        if (in_array($requestDocument->status, [Document::STATUS_SENT, Document::STATUS_DELIVERING, Document::STATUS_DELIVERED])) {
            return new CancellationStatus(CancellationStatus::PROCESSED, $requestDocument);
        }

        if (in_array($requestDocument->status, Document::getErrorStatus())) {
            Yii::warning("Sending cancellation request document has failed, status: {$requestDocument->status}");
            return new CancellationStatus(CancellationStatus::REJECTED, $requestDocument);
        }

        return new CancellationStatus(CancellationStatus::PENDING);
    }

    private function findOrCreateCancellationRequestDocument(Document $prepareRequestDocument): Document
    {
        $requestExt = VTBCancellationRequestExt::findOne(['prepareCancellationRequestDocumentId' => $prepareRequestDocument->id]);
        if ($requestExt !== null) {
            return Document::findOne($requestExt->prepareCancellationRequestDocumentId);
        }

        return $this->createCancellationRequestDocument($prepareRequestDocument);
    }

    private function createCancellationRequestDocument(Document $prepareRequestDocument): Document
    {
        /** @var VTBPrepareCancellationRequestExt $prepareRequestExt */
        $prepareRequestExt = $prepareRequestDocument->extModel;

        $targetDocuments = Yii::$app->terminalAccess->query(
            Document::className(),
            [
                'uuid' => $prepareRequestExt->targetDocumentUuid,
                'direction' => Document::DIRECTION_OUT,
            ]
        )->all();
        if (count($targetDocuments) === 0) {
            throw new \Exception("Target document with uuid {$prepareRequestExt->targetDocumentUuid} is not found");
        }
        if (count($targetDocuments) > 1) {
            throw new \Exception("Multiple target documents with uuid {$prepareRequestExt->targetDocumentUuid} are found");
        }

        /** @var Document $targetDocument */
        $targetDocument = $targetDocuments[0];

        /** @var BSDocument $targetVtbDocument */
        $targetVtbDocument = $this->extractVtbDocument(
            $targetDocument,
            $prepareRequestExt->targetDocumentNumber,
            $prepareRequestExt->targetDocumentDate
        );
        list($cancelClient, $cancelDate, $cancelTime) = explode('_', $prepareRequestExt->targetDocumentVTBReferenceId);

        $cancellationRequest = new CancellationRequest([
            'DOCUMENTDATE'             => new \DateTime(),
            'DOCUMENTNUMBER'           => VTBHelper::generateDocumentNumber(),
            'CUSTID'                   => $targetVtbDocument->CUSTID,
            'CANCELCLIENT'             => $cancelClient,
            'CANCELDATECREATE'         => $cancelDate,
            'CANCELTIMECREATE'         => $cancelTime,
            'CANCELDOCTYPEID'          => $targetVtbDocument::TYPE_ID,
            'CANCELCUSTID'             => $targetVtbDocument->CUSTID,
            'CANCELDOCDATE'            => $targetVtbDocument->DOCUMENTDATE,
            'CANCELDOCNUMBER'          => $targetVtbDocument->DOCUMENTNUMBER,
            'CANCELDOCMANDATORYFIELDS' => $prepareRequestExt->targetDocumentInfo,
            'CANCELDOCNOTIFICATION'    => $prepareRequestExt->messageForBank,
            'KBOPID'                   => $targetVtbDocument->KBOPID,
        ]);

        $documentVersion = 3;
        $typeModel = new VTBCancellationRequestType([
            'document'        => $cancellationRequest,
            'documentVersion' => $documentVersion,
            'customerId'      => $targetVtbDocument->CUSTID,
            'signatureInfo'   => new SignInfo(['signedFields' => $cancellationRequest->getSignedFieldsIds($documentVersion)]),
        ]);
        $senderTerminal = Terminal::findOne(['terminalId' => $targetDocument->sender]);

        $context = DocumentHelper::createDocumentContext(
            $typeModel,
            [
                'type'               => $typeModel->getType(),
                'typeGroup'          => EdmModule::SERVICE_ID,
                'direction'          => Document::DIRECTION_OUT,
                'origin'             => Document::ORIGIN_WEB,
                'terminalId'         => $senderTerminal->id,
                'sender'             => $senderTerminal->terminalId,
                'receiver'           => $targetDocument->receiver,
                'status'             => Document::STATUS_CREATING,
            ],
            ['prepareCancellationRequestDocumentId' => $prepareRequestDocument->id]
        );

        if ($context === false) {
            throw new Exception('Failed to create document context');
        }

        $requestDocument = $context['document'];
        DocumentTransportHelper::processDocument($requestDocument, true);

        return $requestDocument;
    }

    private function extractVtbDocument(Document $document, $documentNumber, $documentDate): BSDocument
    {
        $cyxDoc = CyberXmlDocument::read($document->getValidStoredFileId());
        $typeModel = $cyxDoc->getContent()->getTypeModel();

        if ($this->isRegister($document)) {
            return $this->extractVtbDocumentFromRegister($typeModel, $documentNumber, $documentDate);
        } elseif ($typeModel instanceof BaseVTBDocumentType) {
            return $typeModel->document;
        }

        throw new \Exception("Unsupported document type: {$document->type}, document id: {$document->id}");
    }

    private function isRegister(Document $document): bool
    {
        return in_array($document->type, [VTBRegisterRuType::TYPE, VTBRegisterCurType::TYPE]);
    }

    private function extractVtbDocumentFromRegister($typeModel, $documentNumber, $documentDate): BSDocument
    {
        $vtbDocuments = array_values(
            array_filter(
                $typeModel->paymentOrders,
                function ($payment) use ($documentDate, $documentNumber) {
                    /** @var PayDocRu|PayDocCurType $paymentDocument */
                    $paymentDocument = $payment->document;
                    if ($paymentDocument->DOCUMENTNUMBER != $documentNumber) {
                        return false;
                    }
                    if (empty($paymentDocument->DOCUMENTDATE)) {
                        return false;
                    }

                    return $paymentDocument->DOCUMENTDATE->format('Y-m-d') === $documentDate;
                }
            )
        );

        if (count($vtbDocuments) === 0) {
            throw new \Exception("Failed to find document with number $documentNumber and date $documentDate inside payment register");
        }
        if (count($vtbDocuments) > 1) {
            throw new \Exception("Multiple documents with number $documentNumber and date $documentDate have been found inside payment register");
        }

        return $vtbDocuments[0]->document;
    }

    private function ensureDocumentCanBeCancelled(Document $document)
    {
        if (!$this->userCanCancelDocument($document)) {
            throw new \DomainException(Yii::t('edm', 'No permission to call off the document'));
        }
        if (!$this->isDocumentCancellable($document)) {
            throw new \DomainException(Yii::t('edm', 'The document cannot be called off'));
        }
    }

    private function userCanCancelDocument(Document $document): bool
    {
        $user = Yii::$app->user->identity;
        $userTerminalsIds = UserTerminal::getUserTerminalIndexes($user->id);
        if (!in_array($document->terminalId, $userTerminalsIds)) {
            return false;
        }
        return Yii::$app->user->can('documentCreate', ['serviceId' => EdmModule::SERVICE_ID]);
    }

    private function isDocumentCancellable(Document $document): bool
    {
        return VTBHelper::isCancellableDocument($document);
    }
}

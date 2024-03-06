<?php

namespace addons\edm\controllers;

use addons\edm\EdmModule;
use addons\edm\models\DictOrganization;
use addons\edm\models\VTBConfDocInquiry138I\VTBConfDocInquiry138IType;
use addons\edm\models\VTBCurrBuy\VTBCurrBuyType;
use addons\edm\models\VTBCurrDealInquiry181i\VTBCurrDealInquiry181iType;
use addons\edm\models\VTBCurrSell\VTBCurrSellType;
use addons\edm\models\VTBPayDocCur\VTBPayDocCurType;
use addons\edm\models\VTBPrepareCancellationRequest\VTBPrepareCancellationRequestForm;
use addons\edm\models\VTBRegisterCur\VTBRegisterCurType;
use addons\edm\models\VTBTransitAccPayDoc\VTBTransitAccPayDocType;
use common\base\BaseServiceController;
use common\base\BaseType;
use common\document\Document;
use common\document\DocumentPermission;
use common\document\DocumentStatusReportsData;
use common\models\cyberxml\CyberXmlDocument;
use common\models\vtbxml\documents\BSDocument;
use common\models\vtbxml\documents\BSDocumentAttachment;
use Yii;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;

class VtbDocumentsController extends BaseServiceController
{
    private const PRINTABLE_VIEWS = [
        VTBConfDocInquiry138IType::TYPE => 'conf-doc-inquiry-138i',
        VTBPayDocCurType::TYPE => 'pay-doc-cur',
        VTBCurrDealInquiry181iType::TYPE => 'curr-deal-inquiry-181i',
        VTBTransitAccPayDocType::TYPE => 'transit-acc-pay-doc-type',
        VTBCurrSellType::TYPE => 'curr-sell',
        VTBCurrBuyType::TYPE => 'curr-buy',
    ];

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => [
                            'view', 'view-table-record', 'print',
                            'download-attachment',
                            'view-register-cur-pay-doc',
                            'print-pay-doc-cur-from-register',
                        ],
                        'roles' => [DocumentPermission::VIEW],
                        'roleParams' => ['serviceId' => EdmModule::SERVICE_ID, 'documentTypeGroup' => '*'],
                    ],
                ],
            ],
        ];
    }

    public function actionView($id)
    {
        $document = $this->findDocument($id);
        $bsDocument = $this->extractVtbDocument($document);
        $cancellationForm = new VTBPrepareCancellationRequestForm(['document' => $document]);
        $isPrintable = $this->isPrintableDocument($document);

        return $this->render(
            'view',
            compact('document', 'bsDocument', 'cancellationForm', 'isPrintable')
        );
    }

    public function actionPrint($id)
    {
        $this->layout = '/print';

        $document = $this->findDocument($id);
        $typeModel = $this->extractTypeModel($document);
        $senderOrganization = DictOrganization::findOne(['terminalId' => $document->terminalId]);
        $statusReportsData = new DocumentStatusReportsData($document);
        $view = $this->getPrintableView($document);

        return $this->render(
            "print/$view",
            compact('typeModel', 'senderOrganization', 'statusReportsData')
        );
    }

    public function actionPrintPayDocCurFromRegister($id, $number)
    {
        $this->layout = '/print';

        $document = $this->findDocument($id);
        /** @var VTBRegisterCurType $registerTypeModel */
        $registerTypeModel = $this->extractTypeModel($document);

        $typeModel = null;
        foreach ($registerTypeModel->paymentOrders as $paymentOrder) {
            if ($paymentOrder->document->DOCUMENTNUMBER == $number) {
                $typeModel = $paymentOrder;
                break;
            }
        }

        $senderOrganization = DictOrganization::findOne(['terminalId' => $document->terminalId]);
        $statusReportsData = new DocumentStatusReportsData($document);

        return $this->render(
            'print/pay-doc-cur',
            compact('typeModel', 'senderOrganization', 'statusReportsData')
        );
    }

    public function actionViewRegisterCurPayDoc($id, $paymentIndex)
    {
        $document = $this->findDocument($id);

        $cyxDoc = CyberXmlDocument::read($document->getValidStoredFileId());
        $vtbRegisterCur = $cyxDoc->getContent()->getTypeModel();

        $bsDocument = null;

        if (!isset($vtbRegisterCur->paymentOrders[$paymentIndex])) {
            throw new NotFoundHttpException("Cannot find payment with index $paymentIndex in payment register $id");
        }

        $bsDocument = $vtbRegisterCur->paymentOrders[$paymentIndex]->document;

        return $this->render('viewRegisterCurPayDocCur', compact('document', 'bsDocument'));
    }

    public function actionViewTableRecord($id, $fieldId, $index)
    {
        $document = $this->findDocument($id);

        if ($document->type == VTBRegisterCurType::TYPE) {
            $cyxDoc = CyberXmlDocument::read($document->getValidStoredFileId());
            $vtbRegisterCur = $cyxDoc->getContent()->getTypeModel();

            $typeModel = $vtbRegisterCur->paymentOrders[$index];

            $parentBsDocument = $typeModel->document;
        } else {
            $parentBsDocument = $this->extractVtbDocument($document);
        }

        try {
            if (!property_exists($parentBsDocument, $fieldId)) {
                $type = $parentBsDocument::TYPE;
                throw new \Exception("Document $type has no property $fieldId");
            }

            /** @var BSDocument[] $bsDocuments */
            $bsDocuments = $parentBsDocument->$fieldId;
            if ($index >= count($bsDocuments)) {
                throw new \Exception("Record index for field $fieldId is out of range: $index");
            }

            $bsDocument = $bsDocuments[$index];

            return $this->renderPartial(
                '_viewTableRecordModal',
                compact('document', 'bsDocument')
            );
        } catch (\Exception $exception) {
            Yii::warning("Failed to send attachment, caused by: $exception");

            throw new NotFoundHttpException();
        }
    }

    public function actionDownloadAttachment($id, $fieldId, $index)
    {
        $document = $this->findDocument($id);
        $bsDocument = $this->extractVtbDocument($document);

        try {
            if (!property_exists($bsDocument, $fieldId)) {
                $type = $bsDocument::TYPE;

                throw new \Exception("Document $type has no property $fieldId");
            }

            /** @var BSDocumentAttachment[] $attachments */
            $attachments = $bsDocument->$fieldId;
            if ($index >= count($attachments)) {
                throw new \Exception("Attachment index is out of range: $index");
            }

            $attachment = $attachments[$index];
            Yii::$app->response->sendContentAsFile($attachment->fileContent, $attachment->fileName);
        } catch (\Exception $exception) {
            Yii::warning("Failed to send attachment, caused by: $exception");

            throw new NotFoundHttpException();
        }
    }

    private function findDocument($id): Document
    {
        $document = Yii::$app->terminalAccess->findModel(Document::class, $id);
        $this->authorizePermission(
            DocumentPermission::VIEW,
            [
                'serviceId' => EdmModule::SERVICE_ID,
                'document' => $document,
            ]
        );
        return $document;
    }

    private function extractVtbDocument(Document $document): BSDocument
    {
        $typeModel = $this->extractTypeModel($document);
        return $typeModel->document;
    }

    private function extractTypeModel(Document $document): BaseType
    {

        /** @var VTBPrepareCancellationRequestExt $prepareRequestExt */
        $prepareRequestExt = $prepareRequestDocument->extModel;

        /** @var Document $targetDocument */
        $targetDocument = Yii::$app->terminalAccess->query(
            Document::className(),
            ['uuid' => $prepareRequestExt->targetDocumentUuid]
        )->one();
        if ($targetDocument === null) {
            throw new \Exception("Target document with uuid {$prepareRequestExt->targetDocumentUuid} is not found");
        }

        /** @var BSDocument $targetVtbDocument */
        $targetVtbDocument = $this->extractVtbDocument($targetDocument);
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
            [
                'prepareCancellationRequestDocumentId' => $prepareRequestDocument->id,
                'cancelDocumentNum' => $targetVtbDocument->DOCUMENTNUMBER,
                'cancelDocumentType' => $targetVtbDocument::TYPE_ID,
                'cancelDocumentDate' => date_format($targetVtbDocument->DOCUMENTDATE, 'Y-m-d'),
            ]
        );

        if ($context === false) {
            throw new Exception('Failed to create document context');
        }

        $requestDocument = $context['document'];
        DocumentTransportHelper::processDocument($requestDocument, true);

        return $requestDocument;

        $cyxDoc = CyberXmlDocument::read($document->getValidStoredFileId());
        return $cyxDoc->getContent()->getTypeModel();

    }

    private function isPrintableDocument(Document $document): bool
    {
        return array_key_exists($document->type, self::PRINTABLE_VIEWS);
    }

    private function getPrintableView(Document $document): string
    {
        if (!$this->isPrintableDocument($document)) {
            throw new \Exception('Document is not printable');
        }
        return self::PRINTABLE_VIEWS[$document->type];
    }
}

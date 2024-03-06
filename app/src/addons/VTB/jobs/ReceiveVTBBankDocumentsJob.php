<?php

namespace addons\VTB\jobs;

use addons\edm\models\VTBFreeBankDoc\VTBFreeBankDocType;
use addons\VTB\models\soap\messages\WSGetFreeBankDocs\GetFreeBankDocListRequest;
use addons\VTB\models\soap\messages\WSGetFreeBankDocs\GetFreeBankDocRequest;
use addons\VTB\models\soap\services\WSGetFreeBankDocs;
use addons\VTB\models\VTBCustomer;
use addons\VTB\models\VTBIncomingDocument;
use common\document\Document;
use common\helpers\DocumentHelper;
use common\models\vtbxml\documents\FreeBankDoc;
use common\models\vtbxml\service\FreeBankDocList;
use common\models\vtbxml\service\FreeBankDocListItem;
use common\models\vtbxml\service\SignInfo;
use common\modules\transport\helpers\DocumentTransportHelper;
use Resque_Job_DontPerform;
use Yii;
use yii\helpers\ArrayHelper;

class ReceiveVTBBankDocumentsJob extends BaseJob
{
    public $date;

    public function setUp()
    {
        parent::setUp();
        $this->checkDate();
    }

    public function perform()
    {
        $this->log('Checking for new bank documents...');

        $customers = $this->getCustomers();
        foreach ($customers as $customer) {
            foreach ($this->getDates() as $date) {
                try {
                    $this->receiveDocuments($customer, $date);
                } catch (\Exception $exception) {
                    $this->log("Got exception while receiving documents: $exception", true);
                }
            }
        }
    }

    private function getCustomers()
    {
        return VTBCustomer::find()->where(['not', ['terminalId' => null]])->all();
    }

    private function receiveDocuments(VTBCustomer $customer, $date)
    {
        $this->log("Checking for new documents for customer {$customer->customerId}, date: $date...");

        $recordsIds = $this->getPendingDocumentsIds($customer->customerId, $date);
        foreach ($recordsIds as $recordId) {
            try {
                $this->processPendingDocument($customer, $recordId);
            } catch (\Exception $exception) {
                $this->log("Got exception while processing document with record id $recordId: $exception", true);
            }
        }
    }

    private function processPendingDocument(VTBCustomer $customer, $recordId)
    {
        $this->log("Receiving document $recordId...");

        $service = new WSGetFreeBankDocs();
        $request = (new GetFreeBankDocRequest())
            ->setCustID($customer->customerId)
            ->setRecordID($recordId);
        $response = $service->getFreeBankDoc($request);

        if ($response->getBSErrorCode()) {
            $this->log("Got error from web service: {$response->getBSErrorCode()}, {$response->getBSError()}");
            return;
        }

        $freeBankDoc = FreeBankDoc::fromXml($response->getDocument());
        $signInfo = !empty($response->getSignData()) ? SignInfo::fromXml($response->getSignData()) : null;

        if (!empty($freeBankDoc->DESTCUSTID) && $freeBankDoc->DESTCUSTID != $customer->customerId) {
            $this->log("Got invalid customer id in document: {$freeBankDoc->DESTCUSTID}");
            return;
        }

        $document = $this->sendDocument($customer, $freeBankDoc, $signInfo);
        $incomingDocument = new VTBIncomingDocument([
            'documentId' => $document->id,
            'customerId' => $customer->customerId,
            'externalId' => $recordId,
        ]);
        // Сохранить модель в БД
        $isSaved = $incomingDocument->save();
        if (!$isSaved) {
            throw new \Exception('Failed to save VTBIncomingDocument, errors: ' . var_export($incomingDocument->getErrors(), true));
        }
    }

    private function sendDocument(VTBCustomer $customer, FreeBankDoc $freeBankDoc, SignInfo $signInfo = null)
    {
        $typeModel = new VTBFreeBankDocType([
            'customerId' => $customer->id,
            'documentVersion' => 1,
            'document' => $freeBankDoc,
            'signatureInfo' => $signInfo,
        ]);

        $terminal = Yii::$app->exchange->defaultTerminal;

        // Создать контекст документа
        $context = DocumentHelper::createDocumentContext(
            $typeModel,
            [
                'type' => $typeModel->getType(),
                'direction' => Document::DIRECTION_OUT,
                'origin' => Document::ORIGIN_SERVICE,
                'terminalId' => $terminal->id,
                'sender' => $terminal->terminalId,
                'receiver' => $customer->terminalId,
                'signaturesRequired' => 0
            ],
            [
                'subject' => $freeBankDoc->DOCNAME,
            ]
        );

        if ($context === false) {
            throw new \Exception('Failed to create document');
        }

        // Получить документ из контекста
        $document = $context['document'];
        // Создать стейт отправки документа
        DocumentTransportHelper::createSendingState($document);
        return $document;
    }

    private function getPendingDocumentsIds($customerId, $date)
    {
        $service = new WSGetFreeBankDocs();
        $request = (new GetFreeBankDocListRequest())
            ->setCustID($customerId)
            ->setDocumentDate($date);
        $response = $service->getFreeBankDocList($request);
        $docList = FreeBankDocList::fromXml($response->getDocList());

        $allRecordsIds = array_map(
            function (FreeBankDocListItem $item) {
                return $item->recordId;
            },
            $docList->documents
        );
        $processedRecordsIds = ArrayHelper::getColumn(
            VTBIncomingDocument::findAll(['externalId' => $allRecordsIds]),
            'externalId'
        );
        return array_diff($allRecordsIds, $processedRecordsIds);
    }

    private function getDates()
    {
        if ($this->date) {
            return [$this->date];
        }
        $now = new \DateTime();
        $dates = [];
        $dates[] = $now->format('Y-m-d');

        if ($now->format('H') < 2) {
            $dates[] = (new \DateTime('yesterday'))->format('Y-m-d');
        }

        return $dates;
    }

    private function checkDate()
    {
        if ($this->date === null) {
            return;
        }

        if (!preg_match('/^\d{4}\-\d{2}-\d{2}/', $this->date)) {
            throw new Resque_Job_DontPerform('Date must be in yyyy-mm-dd format');
        }

        $dateParts = explode('-', $this->date);
        if (!checkdate($dateParts[1], $dateParts[2], $dateParts[0])) {
            throw new Resque_Job_DontPerform('Invalid date');
        }
    }
}

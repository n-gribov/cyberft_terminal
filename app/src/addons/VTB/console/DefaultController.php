<?php

namespace addons\VTB\console;

use addons\edm\models\BaseVTBDocument\BaseVTBDocumentType;
use addons\VTB\jobs\ReceiveVTBBankDocumentsJob;
use addons\VTB\jobs\SendClientTerminalSettingsJob;
use addons\VTB\jobs\UpdateVTBCustomersDataJob;
use addons\VTB\models\soap\messages\WSGetCustomer\GetAccountsListRequest;
use addons\VTB\models\soap\messages\WSGetCustomer\GetCustsAndBranchesRequest;
use addons\VTB\models\soap\messages\WSGetDocStatus\GetDocStatusRequest;
use addons\VTB\models\soap\messages\WSGetFreeBankDocs\GetFreeBankDocListRequest;
use addons\VTB\models\soap\messages\WSGetFreeBankDocs\GetFreeBankDocRequest;
use addons\VTB\models\soap\messages\WSGetStatement\GetStatementRequest;
use addons\VTB\models\soap\messages\WSImportSignedDocument\ImportSignedDocumentRequest;
use addons\VTB\models\soap\services\WSGetCustomer;
use addons\VTB\models\soap\services\WSGetDocStatus;
use addons\VTB\models\soap\services\WSGetFreeBankDocs;
use addons\VTB\models\soap\services\WSGetStatement;
use addons\VTB\models\soap\services\WSImportSignedDocument;
use addons\VTB\models\VTBCustomerAccount;
use addons\VTB\models\VTBDocumentImportRequest;
use addons\VTB\utils\curl\Curl;
use common\base\ConsoleController;
use common\document\Document;
use common\helpers\vtb\BSDocumentSignedDataBuilder;
use common\helpers\vtb\VTBSignHelper;
use common\models\cyberxml\CyberXmlDocument;
use common\models\vtbxml\documents\FreeClientDoc;
use common\models\vtbxml\documents\StatementQuery;
use common\models\vtbxml\documents\StatementRu;
use common\models\vtbxml\service\SignInfo;
use common\models\vtbxml\service\SignInfoSignature;
use Symfony\Component\Filesystem\Filesystem;
use yii\db\Expression;

class DefaultController extends ConsoleController
{
    /**
     * Метод выводит текст подсказки
     */
    public function actionIndex()
    {
        $this->run('/help', ['VTB']);
    }

    public function actionDownloadWsdl($targetDirPath = './')
    {
        $services = [
            'WSImportDocument',
            'WSImportSignedDocument',
            'WSGetDocStatus',
            'WSPrepareDocForCancel',
            'WSGetStatement',
            'WSGetFreeBankDocs',
            'WSGetFreeBankDocsGOZ',
            'WSGetCustomer',
            'WSGetDict',
        ];

        $settings = $this->module->settings;
        $fs = new Filesystem();

        foreach ($services as $service) {
            $url = $settings->gatewayUrl . "?wsdl/$service";

            try {
                $response = (new Curl())
                    ->executablePath('/opt/cprocsp/bin/amd64/curl')
                    ->url($url)
                    ->cert($settings->clientCertificate)
                    ->insecure($settings->dontVerifyPeer)
                    ->get();

                $filePath = "$targetDirPath/$service.wsdl";
                $fs->dumpFile($filePath, $response->getBody());
            } catch (\Exception $exception) {
                echo "Failed to download $url, caused by: $exception\n";
            }
        }
    }

    public function actionTestSoap($customerId)
    {
        $account = $this->getAccount($customerId);
        $service = new WSGetStatement();
        $request = (new GetStatementRequest())
            ->setCustID($customerId)
            ->setAccount($account->number)
            ->setBIC($account->bankBik)
            ->setStatementType(0)
            ->setStatementDate('2013-06-18');
        $response = $service->getStatement($request);

        var_dump($response);

        $statement = StatementRu::fromXml($response->getStatementDoc());
        $signInfo = SignInfo::fromXml($response->getSignData());
        $signature = $signInfo->signatures[0];

        $isValid = VTBSignHelper::verify($statement, $signInfo->signedFields, $signature->value, $signature->uid);

        var_dump($isValid);
    }

    public function actionGetCustomerInfo($customerId)
    {
        $service = new WSGetCustomer();
        $request = new GetCustsAndBranchesRequest();
        $result = $service->getCustsAndBranches($request);
        echo "Customer:\n";
        var_dump($result);

        $request = (new GetAccountsListRequest())
            ->setCustID($customerId);
        $result = $service->getAccountsList($request);
        echo "Accounts:\n";
        var_dump($result);
    }

    public function actionTestSigning($keyCommonName)
    {
        $statement = new StatementRu(['SENDEROFFICIALS' => 'Ответственный исполнитель']);
        $signature = VTBSignHelper::sign($statement, 1, null, $keyCommonName);

        var_dump($signature);

        $isValid = VTBSignHelper::verify($statement, $statement->getSignedFieldsIds(1), $signature, $keyCommonName);

        var_dump($isValid);
    }

    public function actionSendStatementRequest($customerId, $keyCommonName)
    {
        $document = new StatementQuery(['CUSTID' => $customerId]);
        $signature = VTBSignHelper::sign($document, 3, null, $keyCommonName);
        $signInfo = new SignInfo([
            'signedFields' => $document->getSignedFieldsIds(3),
            'signatures' => [
                new SignInfoSignature(['value' => $signature, 'uid' => $keyCommonName, 'number' => '1', 'cryptLibId' => 8])
            ]
        ]);

        $service = new WSImportSignedDocument();
        $request = (new ImportSignedDocumentRequest())
            ->setCustID($customerId)
            ->setDocScheme(StatementQuery::TYPE)
            ->setDocVersion(3)
            ->setDocData($document->toXml(3))
            ->setSignData($signInfo->toXml());

        $result = $service->importSignedDocument($request);

        var_dump($result);
    }

    public function actionGetStatement($customerId, $statementDate)
    {
        $account = $this->getAccount($customerId);
        $service = new WSGetStatement();
        $request = (new GetStatementRequest())
            ->setCustID($customerId)
            ->setAccount($account->number)
            ->setBIC($account->bankBik)
            ->setStatementType(0)
            ->setStatementDate($statementDate);
        $response = $service->getStatement($request);

        var_dump($response);
    }

    public function actionGetFreeBankDocs($customerId, $date)
    {
        $service = new WSGetFreeBankDocs();
        $request = (new GetFreeBankDocListRequest())
            ->setCustID($customerId)
            ->setDocumentDate($date);
        $result = $service->getFreeBankDocList($request);

        var_dump($result);
    }

    public function actionGetFreeBankDoc($recordId, $customerId)
    {
        $service = new WSGetFreeBankDocs();
        $request = (new GetFreeBankDocRequest())
            ->setCustID($customerId)
            ->setRecordID($recordId);
        $result = $service->getFreeBankDoc($request);

        var_dump($result);
    }

    public function actionSendFreeClientDoc($customerId, $bankBranchId, $keyCommonName)
    {
        $document = new FreeClientDoc([
            'CUSTID' => $customerId,
            'DOCUMENTNUMBER' => time(),
            'DOCUMENTDATE' => new \DateTime(),
            'DOCNAME' => 'test',
            'DOCTEXT' => 'test test test',
            'KBOPID' => $bankBranchId,
            'RECIPIENT' => 'test',
            'DOCTYPE' => 1
        ]);

        $signature = VTBSignHelper::sign($document, 3, null, $keyCommonName);
        $signInfo = new SignInfo([
            'signedFields' => $document->getSignedFieldsIds(3),
            'signatures' => [
                new SignInfoSignature(['value' => $signature, 'uid' => $keyCommonName, 'number' => '1', 'cryptLibId' => 8])
            ]
        ]);

        $service = new WSImportSignedDocument();
        $request = (new ImportSignedDocumentRequest())
            ->setCustID($customerId)
            ->setDocScheme(FreeClientDoc::TYPE)
            ->setDocVersion(3)
            ->setDocData($document->toXml(3))
            ->setSignData($signInfo->toXml());

        $result = $service->importSignedDocument($request);

        var_dump($result);
    }

    public function actionGetDocStatus($customerId, $docScheme, $recordId)
    {
        $service = new WSGetDocStatus();

        $request = (new GetDocStatusRequest())
            ->setCustID($customerId)
            ->setDocScheme($docScheme)
            ->setRecordID($recordId);

        $result = $service->getDocStatus($request);

        var_dump($result);
    }

    public function actionUpdateCustomersData()
    {
        $job = new UpdateVTBCustomersDataJob();
        $job->setUp();
        $job->perform();
    }

    public function actionReceiveDocumentsFromBank($date = null)
    {
        $job = new ReceiveVTBBankDocumentsJob();
        $job->date = $date;
        $job->setUp();
        $job->perform();
    }

    public function actionExtractDocumentData($documentId)
    {
        $document = Document::findOne($documentId);
        $importRequest = VTBDocumentImportRequest::findOne(['documentId' => $documentId]);

        if ($importRequest === null) {
            echo "Import request is not found\n";
            return;
        }

        $cyxDoc = CyberXmlDocument::read($document->getValidStoredFileId());
        /** @var BaseVTBDocumentType $typeModel */
        $typeModel = $cyxDoc->getContent()->getTypeModel();
        $bsDocument = $typeModel->document;

        echo 'Document type: ' . $bsDocument::TYPE . "\n";
        echo "Reference: {$importRequest->externalRequestId}\n";
        echo "Import date: {$importRequest->importAttemptDate}\n";
        echo "Request status: {$importRequest->status}\n";
        echo "Status in VTB: {$importRequest->externalDocumentStatus}\n";

        $signedData = BSDocumentSignedDataBuilder::build($bsDocument, $typeModel->documentVersion);

        file_put_contents("{$importRequest->externalRequestId}-document.txt", $bsDocument->toXml($typeModel->documentVersion));
        file_put_contents("{$importRequest->externalRequestId}-signed-data.txt", $signedData);
        file_put_contents("{$importRequest->externalRequestId}-signature-info.txt", $typeModel->signatureInfo->toXml($typeModel->documentVersion));
    }

    public function actionSendClientTerminalSettings($vtbCustomerId = null)
    {
        $jobArgs = [];
        if (!empty($vtbCustomerId)) {
            $jobArgs['customerId'] = $vtbCustomerId;
        }
        $job = new SendClientTerminalSettingsJob();
        $job->args = $jobArgs;
        $job->setUp();
        $job->perform();
    }

    public function actionStopImportRequests($deltaDays = 3)
    {

        if (!preg_match('/^\d+$/', $deltaDays)) {
            throw new \Exception('Bad delta days value');
        }

        $dateCondition = ['<=', 'dateCreate', new Expression("current_timestamp() - interval $deltaDays day")];

        VTBDocumentImportRequest::updateAll(
            ['status' => VTBDocumentImportRequest::STATUS_SENDING_ERROR],
            [
                'and',
                ['status' => VTBDocumentImportRequest::STATUS_PENDING],
                $dateCondition
            ]
        );

        VTBDocumentImportRequest::updateAll(
            ['status' => VTBDocumentImportRequest::STATUS_PROCESSING_ERROR],
            [
                'and',
                ['status' => VTBDocumentImportRequest::STATUS_SENT],
                $dateCondition
            ]
        );
    }

    public function actionCheckSignature($documentType, $documentXmlFilePath, $signatureInfoXmlFilePath)
    {
        $documentClass = "\common\models\\vtbxml\documents\\$documentType";
        if (!class_exists($documentClass)) {
            throw new \Exception("Class $documentClass is not found");
        }

        $document = $documentClass::fromXml(file_get_contents($documentXmlFilePath));
        $signInfo = SignInfo::fromXml(file_get_contents($signatureInfoXmlFilePath));

        foreach ($signInfo->signatures as $signature) {
            $isValid = VTBSignHelper::verify($document, $signInfo->signedFields, $signature->value, $signature->uid);
            echo "Certificate: {$signature->uid}, signature is " . ($isValid ? 'valid' : 'invalid') . "\n";
        }
    }

    private function getAccount($customerId): VTBCustomerAccount
    {
        $account = VTBCustomerAccount::find()
            ->where(['customerId' => $customerId])
            ->orderBy(['number' => SORT_ASC])
            ->one();

        if ($account === null) {
            throw new \Exception('No accounts found');
        }

        return $account;
    }
}

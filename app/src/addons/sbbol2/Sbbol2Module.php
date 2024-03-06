<?php
namespace addons\sbbol2;

use addons\edm\models\Sbbol2PayDocRu\Sbbol2PayDocRuType;
use addons\edm\models\Sbbol2Statement\Sbbol2StatementType;
use addons\edm\models\StatementRequest\StatementRequestType;
use addons\sbbol2\components\ApiAccessTokenProvider;
use addons\sbbol2\components\ApiFactory;
use addons\sbbol2\helpers\Sbbol2ModuleHelper;
use addons\sbbol2\models\Sbbol2Customer;
use addons\sbbol2\models\Sbbol2CustomerAccount;
use addons\sbbol2\models\Sbbol2DocumentImportRequest;
use addons\sbbol2\models\Sbbol2ScheduledRequest;
use addons\sbbol2\models\Sbbol2UserExt;
use common\base\BaseBlock;
use common\document\Document;
use common\models\cyberxml\CyberXmlDocument;
use common\modules\transport\models\StatusReportType;
use Yii;
use yii\authclient\Collection;

/**
 * @property string $name
 * @property ApiFactory $apiFactory
 * @property ApiAccessTokenProvider $apiAccessTokenProvider
 * @property Collection $authClientCollection
 */
class Sbbol2Module extends BaseBlock
{
    const EXT_USER_MODEL_CLASS = Sbbol2UserExt::class;
    const SERVICE_ID = 'sbbol2';
    const RESOURCE_IN = 'in';
    const RESOURCE_OUT = 'out';
    const RESOURCE_TEMP = 'temp';
    const RESOURCE_IMPORT_ERROR = 'error';

    const SETTINGS_CODE = 'sbbol2:sbbol2';

    public function registerMessage(CyberXmlDocument $cyxDoc, $documentId)
    {
        switch ($cyxDoc->docType) {
            case StatementRequestType::TYPE:
                return $this->requestSbbol2StmtReqDocument($cyxDoc, $documentId);
            case Sbbol2PayDocRuType::TYPE:
                return $this->registerSbbol2PayDocRu($cyxDoc, $documentId);
            default:
                Yii::error("Unsupported document type: {$cyxDoc->docType}");

                return false;
        }
    }

    private function requestSbbol2StmtReqDocument(CyberXmlDocument $cyxDoc, $documentId)
    {
        $statementRequest = $cyxDoc->getContent()->getTypeModel();

//        Yii::info((string) $typeModel);

//        <StatementAccount><AccountNumber>40702840500000003031</AccountNumber>
//        <StatementPeriod><StartDate>2019-06-28</StartDate><EndDate>2019-06-28</EndDate>
//        </StatementPeriod>
        $customer = Sbbol2Customer::findOne(['terminalAddress' => $cyxDoc->senderId]);
        if (!$customer) {
            return true;
        }

        $account = Sbbol2CustomerAccount::findOne([
            'number' => $statementRequest->accountNumber,
            'customerId' => $customer->id
        ]);

        if (!$account) {
            Yii::info('Accounts from request are not found in database');
//            SBBOLModuleHelper::sendStatusReport(
//                $document,
//                'RJCT',
//                'Счет, по которому запрошена выписка, не принадлежит отправителю запроса',
//                StatusReportType::ERROR_CODE_RECIPIENT_REJECTION
//            );
            return false;
        }

        $scheduledRequest = new Sbbol2ScheduledRequest([
            'type' => Sbbol2StatementType::TYPE,
            'status' => Sbbol2ScheduledRequest::STATUS_PENDING,
            'customerId' => $customer->id,
            'requestJsonData' => json_encode([
                'account' => $account->number,
                'receiver' => $cyxDoc->senderId,
                'startDate' => $statementRequest->startDate,
            ])
        ]);

        if (!$scheduledRequest->save()) {
            Yii::info('Could not save scheduled request: ' . $scheduledRequest->errors);

            return false;
        }

        return true;
    }

    private function registerSbbol2PayDocRu(CyberXmlDocument $cyxDocument, $documentId)
    {
        /** @var Sbbol2PayDocRuType $typeModel */
        $typeModel = $cyxDocument->getContent()->getTypeModel();
        $document = Document::findOne($documentId);

        $accountNumber = $typeModel->document->getPayerAccount();
        $account = Sbbol2CustomerAccount::findOne(['number' => $accountNumber]);
        if ($account === null) {
            Yii::info("Account $accountNumber is not found in Sbbol2 accounts dictionary");
            Sbbol2ModuleHelper::sendStatusReport(
                $document,
                'RJCT',
                'Счет плательщика не найден в справочнике',
                StatusReportType::ERROR_CODE_RECIPIENT_REJECTION
            );
            return false;
        }

        $senderTerminalAddress = $cyxDocument->senderId;
        $customer = $account->customer;

        if ($senderTerminalAddress !== $customer->terminalAddress) {
            Yii::info("Sender terminal address ($senderTerminalAddress) does not match sbbol2 customer terminal address ({$customer->terminalAddress})");
            Sbbol2ModuleHelper::sendStatusReport(
                $document,
                'RJCT',
                'Счет плательщика не принадлежит терминалу отправителя',
                StatusReportType::ERROR_CODE_RECIPIENT_REJECTION
            );
            return false;
        }

        $request = new Sbbol2DocumentImportRequest([
            'documentId' => $documentId,
            'status' => Sbbol2DocumentImportRequest::STATUS_PENDING
        ]);

        return $request->save();
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

    public function storeDataTemp($data, $filename = '')
    {
        $fileInfo = Yii::$app->registry->getTempResource(self::SERVICE_ID)->putData($data, $filename);

        return $fileInfo['path'];
    }

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
        return Yii::t('app', 'SBBOL');
    }
}

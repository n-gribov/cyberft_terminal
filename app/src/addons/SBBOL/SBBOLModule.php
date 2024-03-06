<?php
namespace addons\SBBOL;

use addons\edm\models\SBBOLPayDocRu\SBBOLPayDocRuType;
use addons\edm\models\SBBOLStmtReq\SBBOLStmtReqType;
use addons\SBBOL\components\SBBOLSessionManager;
use addons\SBBOL\components\SBBOLTransport;
use addons\SBBOL\helpers\SBBOLModuleHelper;
use addons\SBBOL\models\SBBOLCustomerAccount;
use addons\SBBOL\models\SBBOLOrganization;
use common\base\BaseBlock;
use common\document\Document;
use common\models\cyberxml\CyberXmlDocument;
use common\models\sbbolxml\request\AccType;
use common\modules\transport\helpers\DocumentTransportHelper;
use common\modules\transport\models\StatusReportType;
use Yii;
use yii\console\Application;
use yii\helpers\ArrayHelper;

/**
 * Class SBBOLModule
 * @package addons\SBBOL
 * @property SBBOLTransport $transport
 * @property SBBOLSessionManager $sessionManager
 */
class SBBOLModule extends BaseBlock
{
    const EXT_USER_MODEL_CLASS = '\addons\SBBOL\models\SBBOLUserExt';
    const SERVICE_ID = 'SBBOL';
    const RESOURCE_IN = 'in';
    const RESOURCE_OUT = 'out';
    const RESOURCE_TEMP = 'temp';
    //const RESOURCE_IMPORT = 'in';
    const RESOURCE_IMPORT_ERROR = 'error';

    const SETTINGS_CODE = 'SBBOL:SBBOL';

    public function registerMessage(CyberXmlDocument $cyxDocument, $documentId)
    {
        switch ($cyxDocument->docType) {
            case SBBOLPayDocRuType::TYPE:
                return $this->registerSBBOLPayDocRu($cyxDocument, $documentId);
            case SBBOLStmtReqType::TYPE:
                return $this->registerSBBOLStmtReqDocument($cyxDocument, $documentId);
            default:
                Yii::error("Unsupported document type: {$cyxDocument->docType}");
                return false;
        }
    }

    private function registerSBBOLPayDocRu(CyberXmlDocument $cyxDocument, $documentId)
    {
        /** @var SBBOLPayDocRuType $typeModel */
        $typeModel = $cyxDocument->getContent()->getTypeModel();
        $document = Document::findOne($documentId);

        $accountNumber = $typeModel->request->getPayDocRu()->getPayer()->getPersonalAcc();
        $account = SBBOLCustomerAccount::findOne(['number' => $accountNumber]);
        if ($account === null) {
            Yii::info("Account $accountNumber is not found in SBBOL accounts dictionary");
            DocumentTransportHelper::sendStatusReport(
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
            Yii::info("Sender terminal address ($senderTerminalAddress) does not match SBBOL customer terminal address ({$customer->terminalAddress})");
            DocumentTransportHelper::sendStatusReport(
                $document,
                'RJCT',
                'Счет плательщика не принадлежит терминалу отправителя',
                StatusReportType::ERROR_CODE_RECIPIENT_REJECTION
            );
            return false;
        }

        if ($customer->id !== $typeModel->request->getOrgId()) {
            Yii::info('SBBOL customer id does not match orgId from the document');
            DocumentTransportHelper::sendStatusReport(
                $document,
                'RJCT',
                'Идентификатор организации указан неверно',
                StatusReportType::ERROR_CODE_RECIPIENT_REJECTION
            );
            return false;
        }

        if (empty($typeModel->digest)) {
            Yii::info('Digest in incoming SBBOL document is missing');
            DocumentTransportHelper::sendStatusReport(
                $document,
                'RJCT',
                'Отсутствует дайджест документа',
                StatusReportType::ERROR_CODE_RECIPIENT_REJECTION
            );
            return false;
        }

        $sessionId = $this->sessionManager->findOrCreateSession($customer->holdingHeadId);
        $sendResult = $this->transport->sendAsync(
            $typeModel->request,
            $sessionId,
            null,
            $typeModel->digest,
            $documentId
        );

        if (!$sendResult->isSent()) {
            Yii::info("Failed to send request to SBBOL, error message: {$sendResult->getErrorMessage()}");
            $statusReportIsSent = DocumentTransportHelper::sendStatusReport(
                $document,
                'RJCT',
                'Не удалось отправить документ в Сбербанк',
                StatusReportType::ERROR_CODE_RECIPIENT_REJECTION
            );
            if (!$statusReportIsSent) {
                Yii::info('Failed to send status report');
            }
        }

        return $sendResult->isSent();
    }

    private function registerSBBOLStmtReqDocument(CyberXmlDocument $cyxDocument, $documentId)
    {
        /** @var SBBOLStmtReqType $requestTypeModel */
        $requestTypeModel = $cyxDocument->getContent()->getTypeModel();

        $senderTerminalAddress = $cyxDocument->senderId;
        $organization = SBBOLOrganization::findOne(['terminalAddress' => $senderTerminalAddress]);
        $document = Document::findOne($documentId);

        if ($organization === null) {
            Yii::info("SBBOL organization for terminal {$senderTerminalAddress} is not found");
            DocumentTransportHelper::sendStatusReport(
                $document,
                'RJCT',
                'Отправитель не зарегистрирован в качестве терминала клиента Сбербанк',
                StatusReportType::ERROR_CODE_RECIPIENT_REJECTION
            );
            return false;
        }

        $stmtReq = $requestTypeModel->request->getStmtReq();
        $accountsNumbers = array_map(
            function (AccType $acc) {
                return $acc->value();
            },
            $stmtReq->getAccounts()
        );

        $organizationAccounts = SBBOLCustomerAccount::find()
            ->joinWith('customer as customer')
            ->where(['customer.inn' => $organization->inn])
            ->andWhere(['in', 'number', $accountsNumbers])
            ->all();

        if (empty($organizationAccounts)) {
            Yii::info('Accounts from request are not found in database');
            DocumentTransportHelper::sendStatusReport(
                $document,
                'RJCT',
                'Счет, по которому запрошена выписка, не принадлежит отправителю запроса',
                StatusReportType::ERROR_CODE_RECIPIENT_REJECTION
            );
            return false;
        }

        $customersIds = array_values(
            array_unique(
                ArrayHelper::getColumn($organizationAccounts, 'customerId')
            )
        );
        if (count($customersIds) > 1) {
            Yii::info('Accounts from request belong to different customers');
            DocumentTransportHelper::sendStatusReport(
                $document,
                'RJCT',
                'Счета, по которым запрошена выписка, принадлежит разным клиентам',
                StatusReportType::ERROR_CODE_RECIPIENT_REJECTION
            );
            return false;
        }

        list($statementRequestIsSent, $errorMessage) = SBBOLModuleHelper::sendStatementRequestToSBBOL(
            $organizationAccounts,
            $stmtReq->getBeginDate(),
            $stmtReq->getEndDate()
        );

        if (!$statementRequestIsSent) {
            Yii::info("Failed to send statement request, error message: $errorMessage");
        }

        $statusReportIsSent = $statementRequestIsSent
            ? DocumentTransportHelper::sendStatusReport(
                $document,
                'ACSC',
                null,
                null
            )
            : DocumentTransportHelper::sendStatusReport(
                $document,
                'RJCT',
                'Не удалось отправить запрос выписки в Сбербанк',
                StatusReportType::ERROR_CODE_RECIPIENT_REJECTION
            );

        return $statementRequestIsSent && $statusReportIsSent;
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

    public function registerConsoleControllers(Application $app)
    {
        $this->controllerNamespace = 'addons\SBBOL\console';
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

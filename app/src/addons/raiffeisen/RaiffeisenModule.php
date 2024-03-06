<?php

namespace addons\raiffeisen;

use addons\edm\models\StatementRequest\StatementRequestType;
use addons\raiffeisen\components\RaiffeisenSessionManager;
use addons\raiffeisen\components\RaiffeisenTransport;
use addons\raiffeisen\helpers\RaiffeisenModuleHelper;
use addons\raiffeisen\models\RaiffeisenCustomer;
use addons\raiffeisen\models\RaiffeisenCustomerAccount;
use common\base\BaseBlock;
use common\document\Document;
use common\models\cyberxml\CyberXmlDocument;
use common\modules\transport\models\StatusReportType;
use Yii;
use yii\console\Application;

/**
 * Class RaiffeisenModule
 * @package addons\raiffeisen
 * @property RaiffeisenTransport $transport
 * @property RaiffeisenSessionManager $sessionManager
 */
class RaiffeisenModule extends BaseBlock
{
    const EXT_USER_MODEL_CLASS = '\addons\raiffeisen\models\RaiffeisenUserExt';
    const SERVICE_ID = 'raiffeisen';
    const RESOURCE_IN = 'in';
    const RESOURCE_OUT = 'out';
    const RESOURCE_TEMP = 'temp';
    const RESOURCE_IMPORT_ERROR = 'error';

    const SETTINGS_CODE = 'raiffeisen:raiffeisen';

    public function registerMessage(CyberXmlDocument $cyxDocument, $documentId)
    {
        switch ($cyxDocument->docType) {
            case StatementRequestType::TYPE:
                return $this->registerStatementRequestDocument($cyxDocument, $documentId);
            default:
                Yii::error("Unsupported document type: {$cyxDocument->docType}");
                return false;
        }
    }

    private function registerStatementRequestDocument(CyberXmlDocument $cyxDocument, int $documentId): bool
    {
        /** @var StatementRequestType $requestTypeModel */
        $requestTypeModel = $cyxDocument->getContent()->getTypeModel();

        $senderTerminalAddress = $cyxDocument->senderId;
        $customer = RaiffeisenCustomer::findOne(['terminalAddress' => $senderTerminalAddress]);
        $document = Document::findOne($documentId);

        if ($customer === null) {
            Yii::info("Raiffeisen customer for terminal {$senderTerminalAddress} is not found");
            RaiffeisenModuleHelper::sendStatusReport(
                $document,
                'RJCT',
                'Отправитель не зарегистрирован в качестве терминала клиента Raiffeisen',
                StatusReportType::ERROR_CODE_RECIPIENT_REJECTION
            );
            return false;
        }

        $customerAccount = RaiffeisenCustomerAccount::find()
            ->where(['customerId' => $customer->id])
            ->andWhere(['number' => $requestTypeModel->accountNumber])
            ->one();

        if ($customerAccount === null) {
            Yii::info('Account from request is not found in database');
            RaiffeisenModuleHelper::sendStatusReport(
                $document,
                'RJCT',
                'Счет, по которому запрошена выписка, не принадлежит отправителю запроса',
                StatusReportType::ERROR_CODE_RECIPIENT_REJECTION
            );
            return false;
        }

        if ($requestTypeModel->startDate !== $requestTypeModel->endDate) {
            Yii::info('Statement request date interval is greater than one day');
            RaiffeisenModuleHelper::sendStatusReport(
                $document,
                'RJCT',
                'Выписка по счету Raiffeisen не может быть запрошена за интервал, превышающий один день',
                StatusReportType::ERROR_CODE_RECIPIENT_REJECTION
            );
            return false;
        }

        list($statementRequestIsSent, $errorMessage) = RaiffeisenModuleHelper::sendStatementRequestToRaiffeisen(
            [$customerAccount],
            new \DateTime($requestTypeModel->startDate)
        );

        if (!$statementRequestIsSent) {
            Yii::info("Failed to send statement request, error message: $errorMessage");
        }

        $statusReportIsSent = $statementRequestIsSent
            ? RaiffeisenModuleHelper::sendStatusReport(
                $document,
                'ACSC',
                null,
                null
            )
            : RaiffeisenModuleHelper::sendStatusReport(
                $document,
                'RJCT',
                'Не удалось отправить запрос выписки в Raiffeisen',
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
        $this->controllerNamespace = 'addons\raiffeisen\console';
    }

    public function hasUserAccessSettings(): bool
    {
        return false;
    }

    public function getName(): string
    {
        return Yii::t('app', 'Raiffeisen');
    }
}

<?php

namespace addons\raiffeisen\helpers;

use addons\raiffeisen\models\RaiffeisenCustomer;
use addons\raiffeisen\models\RaiffeisenCustomerAccount;
use addons\raiffeisen\models\RaiffeisenRequest;
use addons\raiffeisen\RaiffeisenModule;
use common\base\BaseType;
use common\document\Document;
use common\helpers\DocumentHelper;
use common\helpers\Lock;
use common\helpers\raiffeisen\RaiffeisenDocumentDigestBuilder;
use common\helpers\Uuid;
use common\models\cyberxml\CyberXmlDocument;
use common\models\raiffeisenxml\RaiffeisenTransportConfig;
use common\models\raiffeisenxml\request\AccountType;
use common\models\raiffeisenxml\request\DigitalSignType;
use common\models\raiffeisenxml\request\Request;
use common\models\raiffeisenxml\request\RequestType\IncomingAType;
use common\models\raiffeisenxml\request\StmtReqTypeRaifType;
use common\models\Terminal;
use common\modules\certManager\components\ssl\X509FileModel;
use common\modules\transport\helpers\DocumentTransportHelper;
use common\modules\transport\models\StatusReportType;
use Yii;
use yii\helpers\ArrayHelper;

class RaiffeisenModuleHelper
{
    // TODO refactor, copypasted from VTBModuleHelper
    public static function sendStatusReport(Document $document, $statusCode, $errorDescription, $errorCode)
    {
        /** @var CyberXmlDocument $cyxDocument */
        $cyxDocument = CyberXmlDocument::read($document->actualStoredFileId);

        $reportTypeModel = new StatusReportType([
            'refDocId' => $document->uuidRemote,
            'statusCode' => $statusCode,
            'errorDescription' => $errorDescription,
            'errorCode' => $errorCode
        ]);

        return self::createAndSendDocument(
            $reportTypeModel,
            $cyxDocument->receiverId,
            $cyxDocument->senderId,
            $document->uuidRemote
        );
    }

    // TODO refactor, copypasted from VTBModuleHelper
    private static function createAndSendDocument(BaseType $typeModel, $senderId, $receiverId, $uuidReference = null)
    {
        $terminal = Terminal::find()->where(['terminalId' => $senderId])->one();

        // Создать контекст документа
        $context = DocumentHelper::createDocumentContext(
            $typeModel,
            [
                'type'               => $typeModel->getType(),
                'direction'          => Document::DIRECTION_OUT,
                'origin'             => Document::ORIGIN_SERVICE,
                'terminalId'         => $terminal->id,
                'sender'             => $terminal->terminalId,
                'receiver'           => $receiverId,
                'signaturesRequired' => 0,
                'uuidReference'      => $uuidReference,
            ]
        );

        if ($context === false) {
            Yii::warning('Failed to create document context');
            return false;
        }

        // Получить документ из контекста
        $reportDocument = $context['document'];
        // Создать стейт отправки документа
        return DocumentTransportHelper::createSendingState($reportDocument);
    }

    /**
     * @param RaiffeisenCustomerAccount[] $accounts
     * @param \DateTime $date
     * @return array
     * @throws \Exception
     */
    public static function sendStatementRequestToRaiffeisen(array $accounts, \DateTime $date)
    {
        if (count($accounts) === 0) {
            return [false, 'Accounts list must not be empty'];
        }

        $customerIds = ArrayHelper::getColumn($accounts, 'customerId');
        if (count(array_unique($customerIds)) > 1) {
            return [false, 'Accounts cannot belong to different customers'];
        }

        $customer = $accounts[0]->customer;
        $holdingHeadCustomer = $customer->holdingHeadCustomer;

        $stmtReq = (new StmtReqTypeRaifType())
            ->setDocDate(new \DateTime())
            ->setDate($date)
            ->setDocExtId((string)Uuid::generate(false))
            ->setStmtType('1')
            ->setDocNumber('2')
            ->setOrgName($customer->shortName)
            ->setInn($customer->inn);

        $sortedAccounts = $accounts;
        ArrayHelper::multisort($sortedAccounts, ['bankBik', 'number']);
        foreach ($sortedAccounts as $account) {
            $stmtReq->addToAccounts(
                (new AccountType($account->number))
                    ->setBic($account->bankBik)
                    ->setName($account->bankName)
            );
        }

        $requestId = (string)Uuid::generate(false);
        $certificateX509 = X509FileModel::loadData($holdingHeadCustomer->certificate);
        $digest = RaiffeisenDocumentDigestBuilder::build($stmtReq, $requestId);
        $signature = RaiffeisenSignHelper::sign(
            $digest,
            $certificateX509->fingerprint,
            $holdingHeadCustomer->privateKeyPassword
        );
        if ($signature === false) {
            return [false, 'Failed to sign document'];
        }

        $digitalSign = (new DigitalSignType())
            ->setValue($signature)
            ->setSN($certificateX509->getSerialNumber())
            ->setSignType($holdingHeadCustomer->signatureType);

        $requestDocument = (new Request())
            ->setStmtReqRaif($stmtReq)
            ->setRequestId($requestId)
            ->setVersion('1.1')
            ->addToSigns($digitalSign);

        /** @var RaiffeisenModule $module */
        $module = Yii::$app->getModule(RaiffeisenModule::SERVICE_ID);

        $sendResult = $module->transport->sendAsync(
            $requestDocument,
            $customer->id,
            ['accountsNumbers' => ArrayHelper::getColumn($accounts, 'number')],
            $digest
        );

        return [$sendResult->isSent(), $sendResult->getErrorMessage()];
    }

    public static function sendIncomingRequest(
        RaiffeisenCustomer $customer,
        callable $logCallback,
        ?array $responseHandlerParams = null
    ): void {
        $holdingHeadCustomer = $customer->holdingHeadCustomer;

        /** @var RaiffeisenModule $module */
        $module = Yii::$app->getModule(RaiffeisenModule::SERVICE_ID);
        $incoming = new IncomingAType();
        $requestId = (string)Uuid::generate(false);
        $certificateX509 = X509FileModel::loadData($holdingHeadCustomer->certificate);
        $digest = RaiffeisenDocumentDigestBuilder::build($incoming, $requestId);
        $signature = RaiffeisenSignHelper::sign(
            $digest,
            $certificateX509->fingerprint,
            $holdingHeadCustomer->privateKeyPassword
        );
        if ($signature === false) {
            throw new \Exception('Failed to sign document');
        }

        $digitalSign = (new DigitalSignType())
            ->setValue($signature)
            ->setSN($certificateX509->getSerialNumber())
            ->setSignType($holdingHeadCustomer->signatureType);

        $requestIncoming = (new Request())
            ->setVersion(RaiffeisenTransportConfig::EXCHANGE_FORMAT_VERSION)
            ->setRequestId($requestId)
            ->setSender($holdingHeadCustomer->shortName)
            ->setIncoming($incoming)
            ->addToSigns($digitalSign);

        $lockName = "raiffeisen-incoming-" . $holdingHeadCustomer->id;
        $lockAttempts = 30;
        while (!Lock::acquire($lockName, 1, 60000)) {
            if ($lockAttempts === 0) {
                throw new \Exception('Failed to acquire lock');
            }
            sleep(2);
            $lockAttempts--;
            $logCallback("Retrying to acquire lock, attempts left: $lockAttempts");
        }

        try {
            if (self::pendingIncomingRequestExists($holdingHeadCustomer->id)) {
                $logCallback("Will not send incoming request for customer {$holdingHeadCustomer->id} because previous request is not processed");
                return;
            }
            $sendResult = $module->transport->sendAsync(
                $requestIncoming,
                $customer->id,
                $responseHandlerParams,
                $digest
            );
        } finally {
            Lock::release($lockName, 1);
        }

        if (!$sendResult->isSent()) {
            throw new \Exception($sendResult->getErrorMessage());
        }
    }


    private static function pendingIncomingRequestExists($holdingHeadCustomerId): bool
    {
        return RaiffeisenRequest::find()
            ->where([
                'status' => [RaiffeisenRequest::STATUS_SENT, RaiffeisenRequest::STATUS_DELIVERED],
                'holdingHeadCustomerId' => $holdingHeadCustomerId,
                'documentType' => 'Incoming',
            ])
            ->exists();
    }
}

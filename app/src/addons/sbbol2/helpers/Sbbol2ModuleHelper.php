<?php
namespace addons\sbbol2\helpers;

use addons\edm\models\PaymentStatusReport\PaymentStatusReportType;
use addons\edm\models\Sbbol2PayDocRu\Sbbol2PayDocRuType;
use addons\sbbol2\models\Sbbol2DocumentStatus;
use addons\SBBOL\helpers\SBBOLSignHelper;
use addons\SBBOL\models\SBBOLCustomerAccount;
use addons\SBBOL\SBBOLModule;
use common\document\Document;
use common\helpers\DocumentHelper;
use common\helpers\Uuid;
use common\models\cyberxml\CyberXmlDocument;
use common\models\sbbolxml\request\AccType;
use common\models\sbbolxml\request\Request;
use common\models\sbbolxml\request\StmtReqType;
use common\models\sbbolxml\SBBOLTransportConfig;
use common\modules\transport\models\StatusReportType;
use DateTime;
use Exception;
use Yii;
use yii\helpers\ArrayHelper;

class Sbbol2ModuleHelper
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

        return DocumentHelper::createAndSendDocument(
            $reportTypeModel,
            $cyxDocument->receiverId,
            $cyxDocument->senderId,
            $document->uuidRemote
        );
    }

    public static function sendPaymentStatusReport(Document $paymentDocument, $bankComment, Sbbol2DocumentStatus $bankStatus)
    {
        /** @var CyberXmlDocument $paymentCyxDocument */
        $paymentCyxDocument = CyberXmlDocument::read($paymentDocument->actualStoredFileId);

        /** @var Sbbol2PayDocRuType $payDocRuTypeModel */
        $payDocRu = $paymentCyxDocument->content->getTypeModel();

        if ($payDocRu === null) {
            Yii::warning('Only Sbbol2PayDocRu status reports are currently supported');

            return false;
        }

        try {
            $amount = $payDocRu->document->getAmount();
            $documentNumber = $payDocRu->document->getNumber();
        } catch (Exception $exception) {
            Yii::warning("Failed to extract document data, caused by: $exception");

            return false;
        }

        $reportTypeModel = new PaymentStatusReportType([
            'refDocId'          => $paymentCyxDocument->docId,
            'refDocDate'        => $paymentCyxDocument->docDate,
            'refSenderId'       => $paymentCyxDocument->senderId,
            'registerId'        => 'noref',
            'registerDate'      => $paymentCyxDocument->docDate,

            'statusCode'        => $bankStatus->getTerminalStatus(),
            'statusDescription' => $bankStatus->getDescription(),
            'statusComment'     => $bankComment,

            'paymentCount'      => 1,
            'paymentSum'        => $amount,
        ]);

        if ($documentNumber) {
            $reportTypeModel->transactionStatus = [
                $documentNumber => [
                    'statusCode'        => $bankStatus->getTerminalStatus(),
                    'statusDescription' => $bankStatus->getDescription(),
                    'statusReason'      => $bankComment,
                ]
            ];
        }


        return DocumentHelper::createAndSendDocument(
            $reportTypeModel,
            $paymentCyxDocument->receiverId,
            $paymentCyxDocument->senderId,
            $paymentDocument->uuidRemote
        );
    }

    /**
     * @param SBBOLCustomerAccount[] $accounts
     * @param DateTime $startDate
     * @param DateTime $endDate
     * @return array
     * @throws Exception
     */
    public static function sendStatementRequestToSBBOL(array $accounts, DateTime $startDate, DateTime $endDate)
    {
        if (count($accounts) === 0) {
            return [false, 'Accounts list must not be empty'];
        }

        $holdingHeadIds = ArrayHelper::getColumn($accounts, 'customer.holdingHeadId');
        if (count(array_unique($holdingHeadIds)) > 1) {
            return [false, 'Accounts cannot belong to different holdings'];
        }

        $stmtReq = (new StmtReqType())
            ->setBeginDate($startDate)
            ->setEndDate($endDate)
            ->setStmtType('101')
            ->setCreateTime(new DateTime())
            ->setDocExtId((string)Uuid::generate(false));

        $sortedAccounts = $accounts;
        ArrayHelper::multisort($sortedAccounts, ['bankBik', 'number']);
        foreach ($sortedAccounts as $account) {
            $stmtReq->addToAccounts(
                (new AccType($account->number))
                    ->setBic($account->bankBik)
                    ->setDocNum(1)
            );
        }

        $holdingHeadId = $accounts[0]->customer->holdingHeadId;
        $senderName = $accounts[0]->customer->senderName;
        $requestDocument = (new Request())
            ->setRequestId((string)Uuid::generate(false))
            ->setVersion(SBBOLTransportConfig::EXCHANGE_FORMAT_VERSION)
            ->setProtocolVersion(SBBOLTransportConfig::PROTOCOL_VERSION)
            ->setOrgId($holdingHeadId)
            ->setSender($senderName)
            ->setStmtReq($stmtReq);

        list($isSigned, $digest) = SBBOLSignHelper::signRequestDocument($requestDocument, $holdingHeadId);
        if (!$isSigned) {
            return [false, 'Failed to sign document'];
        }

        /** @var SBBOLModule $module */
        $module = Yii::$app->getModule('bbol2');

        $sessionId = $module->sessionManager->findOrCreateSession($holdingHeadId);
        $sendResult = $module->transport->sendAsync(
            $requestDocument,
            $sessionId,
            ['accountsNumbers' => ArrayHelper::getColumn($accounts, 'number')],
            $digest
        );

        return [$sendResult->isSent(), $sendResult->getErrorMessage()];
    }

}
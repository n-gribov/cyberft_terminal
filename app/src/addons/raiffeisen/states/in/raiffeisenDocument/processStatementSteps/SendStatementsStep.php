<?php

namespace addons\raiffeisen\states\in\raiffeisenDocument\processStatementSteps;

use addons\edm\models\RaiffeisenStatement\RaiffeisenStatementType;
use addons\edm\models\Statement\StatementType;
use addons\raiffeisen\models\RaiffeisenCustomerAccount;
use addons\raiffeisen\models\RaiffeisenRequest;
use addons\raiffeisen\states\BaseStep;
use addons\raiffeisen\states\in\raiffeisenDocument\ProcessStatementState;
use common\document\Document;
use common\helpers\DocumentHelper;
use common\models\raiffeisenxml\response\Response;
use common\models\raiffeisenxml\response\StatementTypeRaifType;
use common\modules\transport\helpers\DocumentTransportHelper;
use Yii;

/**
 * @property ProcessStatementState $state
 */
class SendStatementsStep extends BaseStep
{
    public function run()
    {
        $statement = $this->state->raiffeisenDocument;
        $customerAccount = RaiffeisenCustomerAccount::findOne(['number' => $statement->getAcc()]);
        if ($customerAccount === null) {
            return $this->fail("Cannot find Raiffeisen customer account with number {$statement->getAcc()}");
        }
        if (empty($customerAccount->customer->terminalAddress)) {
            return $this->fail("Raiffeisen customer {$customerAccount->customerId} has no terminal address");
        }
        return $this->sendStatement($statement, $customerAccount->customer->terminalAddress);
    }

    private function sendStatement(StatementTypeRaifType $statement, $receiverTerminalAddress)
    {
        $responseDocument = (new Response())
            ->setCreateTime(new \DateTime())
            ->addToStatementsRaif($statement);
        $responseTypeModel = new RaiffeisenStatementType(['response' => $responseDocument]);
        $senderTerminal = Yii::$app->terminals->defaultTerminal;
        $filename = $this->createStatementFileName($statement);

        $context = DocumentHelper::createDocumentContext(
            $responseTypeModel,
            [
                'type'               => $responseTypeModel->getType(),
                'direction'          => Document::DIRECTION_OUT,
                'origin'             => Document::ORIGIN_SERVICE,
                'terminalId'         => $senderTerminal->id,
                'sender'             => $senderTerminal->terminalId,
                'receiver'           => $receiverTerminalAddress,
                'signaturesRequired' => 0,
            ],
            null,
            null,
            ['filename' => $filename]
        );
        if (empty($context)) {
            return $this->fail('Failed to create response document');
        }
        $document = $context['document'];
        DocumentTransportHelper::processDocument($document, true);

        return true;
    }

    private function createStatementFileName(StatementTypeRaifType $statement)
    {
        $name = implode(
            '_',
            [
                StatementType::TYPE,
                $statement->getAcc(),
                $statement->getBeginDate()->format('Y-m-d'),
                (new \DateTime())->format('Y-m-d H-i-s')
            ]
        );
        return "$name.xml";
    }

    private function fail($logMessage)
    {
        $this->log($logMessage);
        RaiffeisenRequest::updateStatus($this->state->requestId, RaiffeisenRequest::STATUS_RESPONSE_PROCESSING_ERROR);

        return false;
    }
}

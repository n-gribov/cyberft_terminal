<?php

namespace addons\SBBOL\states\in\sbbolDocument\processStatementSteps;

use addons\edm\models\SBBOLStatement\SBBOLStatementType;
use addons\SBBOL\models\SBBOLCustomer;
use addons\SBBOL\models\SBBOLRequest;
use addons\SBBOL\states\BaseStep;
use addons\SBBOL\states\in\sbbolDocument\ProcessStatementState;
use common\document\Document;
use common\helpers\DocumentHelper;
use common\models\sbbolxml\response\Response;
use common\models\sbbolxml\response\ResponseType\StatementsAType;
use common\models\sbbolxml\response\StatementType;
use common\modules\transport\helpers\DocumentTransportHelper;
use Yii;

/**
 * @property ProcessStatementState $state
 */
class SendStatementsStep extends BaseStep
{
    public function run()
    {
        $statement = $this->state->sbbolDocument;
        $customerId = $statement->getOrgId();
        $customer = SBBOLCustomer::findOne($customerId);
        if ($customer === null) {
            return $this->fail("Cannot find SBBOL customer with id {$customerId}");
        }
        if (empty($customer->terminalAddress)) {
            return $this->fail("SBBOL customer {$customerId} has no terminal address");
        }
        return $this->sendStatement($statement, $customer->terminalAddress);
    }

    private function sendStatement(StatementType $statement, $receiverTerminalAddress)
    {
        $responseDocument = (new Response())
            ->setCreateTime(new \DateTime())
            ->setStatements(
                (new StatementsAType())->addToStatement($statement)
            );
        $responseTypeModel = new SBBOLStatementType(['response' => $responseDocument]);
        $senderTerminal = Yii::$app->exchange->defaultTerminal;
        $filename = $this->createStatementFileName($statement);

        // Создать контекст документа
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
        // Получить документ из контекста
        $document = $context['document'];
        // Отправить документ на обработку в транспортном уровне
        DocumentTransportHelper::processDocument($document, true);

        return true;
    }

    private function createStatementFileName(StatementType $statement)
    {
        $name = implode(
            '_',
            [
                SBBOLStatementType::TYPE,
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
        SBBOLRequest::updateStatus($this->state->requestId, SBBOLRequest::STATUS_RESPONSE_PROCESSING_ERROR);

        return false;
    }
}

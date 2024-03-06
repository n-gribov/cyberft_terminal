<?php

namespace addons\SBBOL\states\in\response\processHugeStatementsSteps;

use addons\edm\models\SBBOLStatement\SBBOLStatementType;
use addons\SBBOL\models\SBBOLCustomer;
use addons\SBBOL\models\SBBOLRequest;
use addons\SBBOL\states\BaseStep;
use addons\SBBOL\states\in\response\ProcessHugeStatementsState;
use common\document\Document;
use common\helpers\DocumentHelper;
use common\models\sbbolxml\response\StatementType;
use common\modules\transport\helpers\DocumentTransportHelper;
use Yii;

/**
 * @property ProcessHugeStatementsState $state
 */
class SendStatementsStep extends BaseStep
{
    public function run()
    {
        $statements = $this->state->response->getStatements()->getStatement();

        $statementsBodies = $this->createStatementsBodies();
        if (empty($statementsBodies)) {
            $this->log('No statements found');
            SBBOLRequest::updateStatus($this->state->requestId, SBBOLRequest::STATUS_PROCESSED);

            return true;
        }

        $hasErrors = false;
        foreach ($statementsBodies as $i => $statementBody) {
            $statement = $statements[$i];
            $isSent = $this->sendStatement($statementBody, $statement);
            if (!$isSent) {
                $hasErrors = true;
            }
        }

        SBBOLRequest::updateStatus(
            $this->state->requestId,
            $hasErrors ? SBBOLRequest::STATUS_RESPONSE_PROCESSING_ERROR : SBBOLRequest::STATUS_PROCESSED
        );

        return true;
    }

    private function sendStatement(string $statementBody, StatementType $statement)
    {
        $customerId = $statement->getOrgId();
        $customer = SBBOLCustomer::findOne($customerId);
        if ($customer === null) {
            $this->log("Cannot find SBBOL customer with id {$customerId}");
            return false;
        }
        if (empty($customer->terminalAddress)) {
            $this->log("SBBOL customer {$customerId} has no terminal address");
            return false;
        }

        $responseTypeModel = new SBBOLStatementType(['rawContent' => $statementBody]);
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
                'receiver'           => $customer->terminalAddress,
                'signaturesRequired' => 0,
            ],
            null,
            null,
            ['filename' => $filename]
        );
        if (empty($context)) {
            $this->log('Failed to create response document');
            return false;
        }
        $document = $context['document'];
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

    private function createStatementsBodies()
    {
        $responseBody = $this->state->responseBody;

        $statementsStartTag = '<Statements>';
        $statementsStart = strpos($responseBody, $statementsStartTag);

        $documentBeginning = substr($responseBody, 0, $statementsStart + strlen($statementsStartTag));

        $statementStartTag = '<Statement';
        $statementEndTag = '</Statement>';

        $offset = $statementsStart + strlen($statementsStartTag);
        $statementsBodies = [];
        while (true) {
            $start = strpos($responseBody, $statementStartTag, $offset);
            $end = strpos($responseBody, $statementEndTag, $offset);

            if ($start === false || $end === false) {
                break;
            }

            $offset = $end + strlen($statementEndTag);
            $statementsBodies[] = $documentBeginning
                . substr($responseBody, $start, $end - $start + strlen($statementEndTag))
                . '</Statements></Response>';
        }

        return $statementsBodies;
    }
}

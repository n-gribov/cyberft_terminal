<?php

namespace addons\raiffeisen\states\in\raiffeisenDocument;

use addons\raiffeisen\states\in\raiffeisenDocument\commonSteps\FinishProcessingStep;
use addons\raiffeisen\states\in\raiffeisenDocument\processStatementSteps\SendStatementsStep;
use common\models\raiffeisenxml\response\StatementTypeRaifType;

/**
 * @property StatementTypeRaifType $raiffeisenDocument
 */
class ProcessStatementState extends BaseRaiffeisenSingleDocumentState
{
    protected $steps = [
        'sendStatements'   => SendStatementsStep::class,
        'finishProcessing' => FinishProcessingStep::class,
    ];
}

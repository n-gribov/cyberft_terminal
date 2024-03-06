<?php

namespace addons\SBBOL\states\in\sbbolDocument;

use addons\SBBOL\states\in\sbbolDocument\commonSteps\FinishProcessingStep;
use addons\SBBOL\states\in\sbbolDocument\processStatementSteps\SendStatementsStep;
use common\models\sbbolxml\response\StatementType;

/**
 * @property StatementType $sbbolDocument
 */
class ProcessStatementState extends BaseSBBOLSingleDocumentState
{
    protected $steps = [
        'sendStatements'   => SendStatementsStep::class,
        'finishProcessing' => FinishProcessingStep::class,
    ];
}

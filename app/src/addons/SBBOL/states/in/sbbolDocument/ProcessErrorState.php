<?php

namespace addons\SBBOL\states\in\sbbolDocument;

use addons\SBBOL\states\in\sbbolDocument\processErrorSteps\HandleErrorStep;
use common\models\sbbolxml\response\ErrorType;

/**
 * @property ErrorType $sbbolDocument
 */
class ProcessErrorState extends BaseSBBOLSingleDocumentState
{
    protected $steps = [
        'handleError' => HandleErrorStep::class,
    ];
}

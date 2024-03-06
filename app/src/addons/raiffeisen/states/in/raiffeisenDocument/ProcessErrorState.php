<?php

namespace addons\raiffeisen\states\in\raiffeisenDocument;

use addons\raiffeisen\states\in\raiffeisenDocument\processErrorSteps\HandleErrorStep;
use common\models\raiffeisenxml\response\ErrorType;

/**
 * @property ErrorType $raiffeisenDocument
 */
class ProcessErrorState extends BaseRaiffeisenSingleDocumentState
{
    protected $steps = [
        'handleError' => HandleErrorStep::class,
    ];
}

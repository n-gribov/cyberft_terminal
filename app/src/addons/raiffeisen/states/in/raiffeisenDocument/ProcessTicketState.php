<?php

namespace addons\raiffeisen\states\in\raiffeisenDocument;

use addons\raiffeisen\states\in\raiffeisenDocument\processTicketSteps\CheckStatusStep;
use addons\raiffeisen\states\in\raiffeisenDocument\processTicketSteps\SaveStatusStep;
use addons\raiffeisen\states\in\raiffeisenDocument\processTicketSteps\SendStatusReportStep;
use common\models\raiffeisenxml\response\TicketType;

/**
 * @property TicketType $raiffeisenDocument
 */
class ProcessTicketState extends BaseRaiffeisenSingleDocumentState
{
    protected $steps = [
        'sendStatusReport' => SendStatusReportStep::class,
        'saveStatus'       => SaveStatusStep::class,
        'checkStatus'      => CheckStatusStep::class,
    ];
}

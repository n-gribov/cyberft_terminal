<?php

namespace addons\SBBOL\states\in\sbbolDocument;

use addons\SBBOL\states\in\sbbolDocument\processTicketSteps\CheckStatusStep;
use addons\SBBOL\states\in\sbbolDocument\processTicketSteps\SaveStatusStep;
use addons\SBBOL\states\in\sbbolDocument\processTicketSteps\SendStatusReportStep;
use common\models\sbbolxml\response\TicketType;

/**
 * @property TicketType $sbbolDocument
 */
class ProcessTicketState extends BaseSBBOLSingleDocumentState
{
    protected $steps = [
        'sendStatusReport' => SendStatusReportStep::class,
        'saveStatus'       => SaveStatusStep::class,
        'checkStatus'      => CheckStatusStep::class,
    ];
}

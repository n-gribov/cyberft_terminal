<?php

namespace addons\SBBOL\states\in\response;

use addons\SBBOL\states\in\response\processAsyncResponseSteps\CheckSignaturesStep;
use addons\SBBOL\states\in\response\processAsyncResponseSteps\LoadResponseFromFileStep;
use addons\SBBOL\states\in\response\processAsyncResponseSteps\ParseXmlStep;
use addons\SBBOL\states\in\response\processAsyncResponseSteps\ValidateXmlStep;
use addons\SBBOL\states\in\response\processHugeStatementsSteps\SendStatementsStep;

class ProcessHugeStatementsState extends ProcessAsyncResponseState
{
    protected $steps = [
        'loadResponseFromFile' => LoadResponseFromFileStep::class,
        'validateXml'          => ValidateXmlStep::class,
        'parseXml'             => ParseXmlStep::class,
        'checkSignatures'      => CheckSignaturesStep::class,
        'sendStatements'       => SendStatementsStep::class,
    ];
}

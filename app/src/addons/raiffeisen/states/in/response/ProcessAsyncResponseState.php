<?php

namespace addons\raiffeisen\states\in\response;

use addons\raiffeisen\states\BaseState;
use addons\raiffeisen\states\in\response\processAsyncResponseSteps\EnqueueRaiffeisenDocumentsProcessingJobsStep;
use addons\raiffeisen\states\in\response\processAsyncResponseSteps\ParseXmlStep;
use addons\raiffeisen\states\in\response\processAsyncResponseSteps\ValidateXmlStep;
use common\models\raiffeisenxml\response\Response;

class ProcessAsyncResponseState extends BaseState
{
    /** @var integer */
    public $requestId;

    /** @var string|null */
    public $responseBody;

    /** @var Response */
    public $response;

    protected $steps = [
        'validateXml'                              => ValidateXmlStep::class,
        'parseXml'                                 => ParseXmlStep::class,
        'enqueueRaiffeisenDocumentsProcessingJobs' => EnqueueRaiffeisenDocumentsProcessingJobsStep::class,
    ];
}

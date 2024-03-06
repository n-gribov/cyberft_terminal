<?php

namespace addons\SBBOL\states\in\response;

use addons\SBBOL\states\BaseState;
use addons\SBBOL\states\in\response\processAsyncResponseSteps\CheckSignaturesStep;
use addons\SBBOL\states\in\response\processAsyncResponseSteps\EnqueueSBBOLDocumentsProcessingJobsStep;
use addons\SBBOL\states\in\response\processAsyncResponseSteps\LoadResponseFromFileStep;
use addons\SBBOL\states\in\response\processAsyncResponseSteps\ParseXmlStep;
use addons\SBBOL\states\in\response\processAsyncResponseSteps\ValidateXmlStep;
use common\models\sbbolxml\response\Response;

class ProcessAsyncResponseState extends BaseState
{
    /** @var integer */
    public $requestId;

    /** @var string|null */
    public $responseBody;

    /** @var string|null */
    public $responseBodyFilePath;

    /** @var Response */
    public $response;

    protected $steps = [
        'loadResponseFromFile'                => LoadResponseFromFileStep::class,
        'validateXml'                         => ValidateXmlStep::class,
        'parseXml'                            => ParseXmlStep::class,
        'checkSignatures'                     => CheckSignaturesStep::class,
        'enqueueSBBOLDocumentsProcessingJobs' => EnqueueSBBOLDocumentsProcessingJobsStep::class,
    ];
}

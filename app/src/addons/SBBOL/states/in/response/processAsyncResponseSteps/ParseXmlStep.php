<?php

namespace addons\SBBOL\states\in\response\processAsyncResponseSteps;

use addons\SBBOL\models\SBBOLRequest;
use addons\SBBOL\states\BaseStep;
use addons\SBBOL\states\in\response\ProcessAsyncResponseState;
use common\helpers\sbbol\SBBOLXmlSerializer;
use common\models\sbbolxml\response\Response;
use Exception;

/**
 * @property ProcessAsyncResponseState $state
 */
class ParseXmlStep extends BaseStep
{
    public function run()
    {
        $hasError = false;

        try {
            $this->state->response = SBBOLXmlSerializer::deserialize($this->state->responseBody, Response::class);
        } catch (Exception $exception) {
            $this->log('Failed to parse XML document, caused by: ' . $exception);
            $hasError = true;
        }

        if ($hasError) {
            SBBOLRequest::updateStatus($this->state->requestId, SBBOLRequest::STATUS_RESPONSE_PROCESSING_ERROR);
        }

        return !$hasError;
    }
}

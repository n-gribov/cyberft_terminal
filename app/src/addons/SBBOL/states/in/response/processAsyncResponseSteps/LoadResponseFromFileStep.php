<?php

namespace addons\SBBOL\states\in\response\processAsyncResponseSteps;

use addons\SBBOL\models\SBBOLRequest;
use addons\SBBOL\states\BaseStep;
use addons\SBBOL\states\in\response\ProcessAsyncResponseState;
use Exception;

/**
 * @property ProcessAsyncResponseState $state
 */
class LoadResponseFromFileStep extends BaseStep
{
    public function run()
    {
        if (!empty($this->state->responseBody)) {
            return true;
        }

        $hasError = false;

        try {
            $responseBodyFilePath = $this->state->responseBodyFilePath;
            if (empty($responseBodyFilePath)) {
                throw new \Exception('Missing response body file path');
            }

            $responseBody = file_get_contents($responseBodyFilePath);
            if (empty($responseBody)) {
                throw new \Exception("Cannot read response body from $responseBodyFilePath");
            }

            $this->state->responseBody = $responseBody;
        } catch (Exception $exception) {
            $this->log('Failed to load response body, caused by: ' . $exception);
            $hasError = true;
        }

        if ($hasError) {
            SBBOLRequest::updateStatus($this->state->requestId, SBBOLRequest::STATUS_RESPONSE_PROCESSING_ERROR);
        }

        return !$hasError;
    }
}

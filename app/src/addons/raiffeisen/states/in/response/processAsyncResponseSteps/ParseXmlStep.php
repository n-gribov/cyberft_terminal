<?php

namespace addons\raiffeisen\states\in\response\processAsyncResponseSteps;

use addons\raiffeisen\models\RaiffeisenRequest;
use addons\raiffeisen\states\BaseStep;
use addons\raiffeisen\states\in\response\ProcessAsyncResponseState;
use common\helpers\raiffeisen\RaiffeisenXmlSerializer;
use common\models\raiffeisenxml\response\Response;
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
            $this->state->response = RaiffeisenXmlSerializer::deserialize($this->state->responseBody, Response::class);
        } catch (Exception $exception) {
            $this->log('Failed to parse XML document, caused by: ' . $exception);
            $hasError = true;
        }

        if ($hasError) {
            RaiffeisenRequest::updateStatus($this->state->requestId, RaiffeisenRequest::STATUS_RESPONSE_PROCESSING_ERROR);
        }

        return !$hasError;
    }
}

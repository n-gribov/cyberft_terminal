<?php

namespace addons\raiffeisen\states\in\response\processAsyncResponseSteps;

use addons\raiffeisen\helpers\ResponseDocumentsExtractor;
use addons\raiffeisen\models\RaiffeisenRequest;
use addons\raiffeisen\states\in\response\ProcessAsyncResponseState;
use addons\raiffeisen\states\BaseStep;
use addons\raiffeisen\states\in\raiffeisenDocument\ProcessErrorState;
use addons\raiffeisen\states\in\raiffeisenDocument\ProcessStatementState;
use addons\raiffeisen\states\in\raiffeisenDocument\ProcessTicketState;

/**
 * @property ProcessAsyncResponseState $state
 */
class EnqueueRaiffeisenDocumentsProcessingJobsStep extends BaseStep
{
    const RAIFFEISEN_DOCUMENTS_JOBS = [
        'Errors'         => ProcessErrorState::class,
        'Tickets'        => ProcessTicketState::class,
        'StatementsRaif' => ProcessStatementState::class,
    ];

    public function run()
    {
        $documentsExtractor = new ResponseDocumentsExtractor($this->state->response);

        $hasProcessableDocuments = false;
        foreach (static::RAIFFEISEN_DOCUMENTS_JOBS as $documentsType => $jobClass) {
            $documents = $documentsExtractor->getDocuments($documentsType);

            if (empty($documents)) {
                continue;
            }
            $hasProcessableDocuments = true;

            $jobClass::enqueueProcessingJobs($documents, $this->state->requestId);
        }

        if (!$hasProcessableDocuments) {
            $this->log('Response has no processable documents');
            RaiffeisenRequest::updateStatus($this->state->requestId, RaiffeisenRequest::STATUS_PROCESSED);
        }

        return true;
    }
}

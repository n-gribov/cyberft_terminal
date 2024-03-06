<?php

namespace addons\SBBOL\states\in\response\processAsyncResponseSteps;

use addons\SBBOL\helpers\ResponseDocumentsExtractor;
use addons\SBBOL\models\SBBOLRequest;
use addons\SBBOL\states\BaseStep;
use addons\SBBOL\states\in\response\ProcessAsyncResponseState;
use addons\SBBOL\states\in\sbbolDocument\ProcessCertificatesState;
use addons\SBBOL\states\in\sbbolDocument\ProcessErrorState;
use addons\SBBOL\states\in\sbbolDocument\ProcessOrganizationInfoState;
use addons\SBBOL\states\in\sbbolDocument\ProcessStatementState;
use addons\SBBOL\states\in\sbbolDocument\ProcessTicketState;

/**
 * @property ProcessAsyncResponseState $state
 */
class EnqueueSBBOLDocumentsProcessingJobsStep extends BaseStep
{
    const SBBOL_DOCUMENTS_JOBS = [
        'Errors'            => ProcessErrorState::class,
        'Tickets'           => ProcessTicketState::class,
        'Statements'        => ProcessStatementState::class,
        'OrganizationsInfo' => ProcessOrganizationInfoState::class,
        'Certificates'      => ProcessCertificatesState::class,
    ];

    public function run()
    {
        $documentsExtractor = new ResponseDocumentsExtractor($this->state->response);

        $hasProcessableDocuments = false;
        foreach (static::SBBOL_DOCUMENTS_JOBS as $documentsType => $jobClass) {
            $documents = $documentsExtractor->getDocuments($documentsType);

            if (empty($documents)) {
                continue;
            }
            $hasProcessableDocuments = true;

            $jobClass::enqueueProcessingJobs($documents, $this->state->requestId);
        }

        if (!$hasProcessableDocuments) {
            $this->log('Response has no processable documents');
            SBBOLRequest::updateStatus($this->state->requestId, SBBOLRequest::STATUS_PROCESSED);
        }

        return true;
    }
}

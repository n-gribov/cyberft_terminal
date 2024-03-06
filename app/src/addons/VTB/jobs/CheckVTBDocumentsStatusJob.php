<?php

namespace addons\VTB\jobs;

use addons\VTB\jobs\CheckVTBDocumentsStatusJob\StatusChecker;
use addons\VTB\models\VTBDocumentImportRequest;

class CheckVTBDocumentsStatusJob extends BaseJob
{
    const MAX_REQUESTS_PER_JOB_RUN = 100;

    public function perform()
    {
        $this->log('Checking for pending requests...');

        $requests = $this->findPendingRequests();
        if (empty($requests)) {
            $this->log('Nothing to do');
            return;
        }

        foreach ($requests as $request) {
            try {
                $this->processRequest($request);
            } catch (\Exception $exception) {
                $this->log("Failed to process request {$request->id}, caused by: $exception", true);
            }
        }
    }

    /**
     * @return VTBDocumentImportRequest[]
     */
    private function findPendingRequests()
    {
        return VTBDocumentImportRequest::find()
            ->where(['status' => VTBDocumentImportRequest::STATUS_SENT])
            ->orderBy(['statusCheckDate' => SORT_ASC, 'dateCreate' => SORT_ASC])
            ->limit(static::MAX_REQUESTS_PER_JOB_RUN)
            ->all();
    }

    private function processRequest(VTBDocumentImportRequest $importRequest)
    {
        $logCallback = function ($message, $isWarning) {
            $this->log($message, $isWarning);
        };
        $statusChecker = new StatusChecker($importRequest, $logCallback);
        $statusChecker->run();
    }
}

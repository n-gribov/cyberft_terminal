<?php

namespace addons\VTB\jobs\CheckVTBDocumentsStatusJob\StatusUpdateHandler;

use addons\VTB\jobs\CheckVTBDocumentsStatusJob\StatusChecker;
use addons\VTB\models\VTBDocumentImportRequest;
use common\models\vtbxml\documents\CancellationRequest;

class CancellationRequestStatusUpdateHandler extends GenericDocumentStatusUpdateHandler
{
    protected function processDocument()
    {
        if (!$this->status->isFinal()) {
            return;
        }

        $this->log('Got finished cancellation request, will fetch cancelled document status...');

        /** @var CancellationRequest $vtbDocument */
        $vtbDocument = $this->getDocumentTypeModel()->document;
        $cancelledRequestExternalId = implode('_', [$vtbDocument->CANCELCUSTID, $vtbDocument->CANCELDATECREATE, $vtbDocument->CANCELTIMECREATE]);
        $cancelledDocumentImportRequest = VTBDocumentImportRequest::findOne(['externalRequestId' => $cancelledRequestExternalId]);
        if ($cancelledDocumentImportRequest === null) {
            $this->log("Cannot find cancelled document import request with external id $cancelledRequestExternalId", true);
            return;
        }
        $statusChecker = new StatusChecker($cancelledDocumentImportRequest, $this->logCallback);
        $statusChecker->run();
    }
}

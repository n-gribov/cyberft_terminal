<?php

namespace addons\VTB\jobs\CheckVTBDocumentsStatusJob\StatusUpdateHandler;

use addons\edm\models\VTBCancellationRequest\VTBCancellationRequestType;
use addons\edm\models\VTBPayDocCur\VTBPayDocCurType;
use addons\edm\models\VTBPayDocRu\VTBPayDocRuType;
use addons\edm\models\VTBStatementQuery\VTBStatementQueryType;
use addons\VTB\models\VTBDocumentStatus;
use common\document\Document;
use common\models\vtbxml\documents\DocInfo;

class StatusUpdateHandlerFactory
{
    public static function create(Document $document, VTBDocumentStatus $newStatus, VTBDocumentStatus $oldStatus, DocInfo $documentInfo = null, $logCallback)
    {
        $handlerClass = static::getHandlerClass($document->type);
        return new $handlerClass($document, $newStatus, $oldStatus, $documentInfo, $logCallback);
    }

    private static function getHandlerClass($documentType)
    {
        switch ($documentType) {
            case VTBPayDocRuType::TYPE:
            case VTBPayDocCurType::TYPE:
                return PaymentDocumentStatusUpdateHandler::class;
            case VTBStatementQueryType::TYPE:
                return StatementQueryStatusUpdateHandler::class;
            case VTBCancellationRequestType::TYPE:
                return CancellationRequestStatusUpdateHandler::class;
            default:
                return GenericDocumentStatusUpdateHandler::class;
        }
    }
}

<?php

namespace addons\SBBOL\states\in\sbbolDocument\processErrorSteps;

use addons\SBBOL\models\SBBOLRequest;
use addons\SBBOL\states\BaseStep;
use addons\SBBOL\states\in\sbbolDocument\ProcessErrorState;
use common\models\sbbolxml\response\ErrorType;
use common\modules\transport\helpers\DocumentTransportHelper;
use common\modules\transport\models\StatusReportType;

/**
 * @property ProcessErrorState $state
 */
class HandleErrorStep extends BaseStep
{
    const TEMPORARY_ERRORS_CODES = [
        '00000000-0000-0000-0000-000000000000', // Service is temporary unavailable
    ];

    const BAD_SESSION_ERROR_CODE = '00000000-0000-0000-0000-000000000002';

    public function run()
    {
        $error = $this->state->sbbolDocument;
        $this->log(
            "Got error response for request {$this->state->requestId}, "
            . "type: {$error->getType()}, "
            . "code: {$error->getCode()}, "
            . "description: {$error->getDesc()}"
        );

        $request = SBBOLRequest::findOne($this->state->requestId);

        if ($error->getCode() === static::BAD_SESSION_ERROR_CODE) {
            $this->log('Bad session, will delete cached session and try next time with a new one');
            $this->state->module->sessionManager->deleteSession($request->customer->holdingHeadId);
        } elseif (!static::isTemporaryError($error)) {

            if ($request->incomingDocumentId) {
                DocumentTransportHelper::sendStatusReport(
                    $request->incomingDocument,
                    'RJCT',
                    $error->getDesc(),
                    StatusReportType::ERROR_CODE_RECIPIENT_REJECTION
                );
            }

            SBBOLRequest::updateStatus($this->state->requestId, SBBOLRequest::STATUS_REJECTED);
        }

        $request->releaseLock();

        return true;
    }

    private static function isTemporaryError(ErrorType $error)
    {
        return in_array($error->getCode(), static::TEMPORARY_ERRORS_CODES, true);
    }

}

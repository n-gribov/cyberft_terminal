<?php

namespace addons\raiffeisen\states\in\raiffeisenDocument\processErrorSteps;

use addons\raiffeisen\models\RaiffeisenRequest;
use addons\raiffeisen\states\BaseStep;
use addons\raiffeisen\states\in\raiffeisenDocument\ProcessErrorState;
use common\models\raiffeisenxml\response\ErrorType;
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
        $error = $this->state->raiffeisenDocument;
        $this->log(
            "Got error response for request {$this->state->requestId}, "
            . "type: {$error->getType()}, "
            . "code: {$error->getCode()}, "
            . "description: {$error->getDesc()}"
        );

        $request = RaiffeisenRequest::findOne($this->state->requestId);

        if ($error->getCode() === static::BAD_SESSION_ERROR_CODE) {
            $this->log('Bad session, will delete cached session and try next time with a new one');
            $this->state->module->sessionManager->deleteSession($request->customer->holdingHeadId);
        } else if (!static::isTemporaryError($error)) {
            if ($request->incomingDocumentId) {
                RaiffeisenModuleHelper::sendStatusReport(
                    $request->incomingDocument,
                    'RJCT',
                    $error->getDesc(),
                    StatusReportType::ERROR_CODE_RECIPIENT_REJECTION
                );
            }
            RaiffeisenRequest::updateStatus($this->state->requestId, RaiffeisenRequest::STATUS_REJECTED);
        }

        $request->releaseLock();

        return true;
    }

    private static function isTemporaryError(ErrorType $error)
    {
        return in_array($error->getCode(), static::TEMPORARY_ERRORS_CODES, true);
    }
}

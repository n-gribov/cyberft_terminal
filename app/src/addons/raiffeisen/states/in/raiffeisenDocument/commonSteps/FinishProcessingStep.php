<?php

namespace addons\raiffeisen\states\in\raiffeisenDocument\commonSteps;

use addons\raiffeisen\models\RaiffeisenRequest;
use addons\raiffeisen\states\BaseStep;
use addons\raiffeisen\states\in\raiffeisenDocument\BaseRaiffeisenSingleDocumentState;

/**
 * @property BaseRaiffeisenSingleDocumentState $state
 */
class FinishProcessingStep extends BaseStep
{
    public function run()
    {
        $request = RaiffeisenRequest::findOne($this->state->requestId);
        if (!$request->hasFinalStatus()) {
            $request->status = RaiffeisenRequest::STATUS_PROCESSED;
            $request->save();
        }

        return true;
    }
}

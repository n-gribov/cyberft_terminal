<?php

namespace addons\SBBOL\states\in\sbbolDocument\commonSteps;

use addons\SBBOL\models\SBBOLRequest;
use addons\SBBOL\states\BaseStep;
use addons\SBBOL\states\in\sbbolDocument\BaseSBBOLSingleDocumentState;

/**
 * @property BaseSBBOLSingleDocumentState $state
 */
class FinishProcessingStep extends BaseStep
{
    public function run()
    {
        $request = SBBOLRequest::findOne($this->state->requestId);
        if (!$request->hasFinalStatus()) {
            $request->status = SBBOLRequest::STATUS_PROCESSED;
            $request->save();
        }

        return true;
    }
}

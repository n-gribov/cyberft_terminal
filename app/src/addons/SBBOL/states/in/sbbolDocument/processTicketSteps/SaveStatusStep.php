<?php

namespace addons\SBBOL\states\in\sbbolDocument\processTicketSteps;

use addons\SBBOL\models\SBBOLRequest;
use addons\SBBOL\states\BaseStep;
use addons\SBBOL\states\in\sbbolDocument\ProcessTicketState;

/**
 * @property ProcessTicketState $state
 */
class SaveStatusStep extends BaseStep
{
    public function run()
    {
        $request = SBBOLRequest::findOne($this->state->requestId);

        $ticket = $this->state->sbbolDocument;
        $statusCode = $ticket->getInfo()->getStatusStateCode();

        if ($request->status === SBBOLRequest::STATUS_SENT) {
            $request->receiverRequestStatus = $statusCode;
        } elseif ($request->status === SBBOLRequest::STATUS_DELIVERED) {
            $request->receiverDocumentStatus = $statusCode;
        }
        $request->save();

        return true;
    }
}

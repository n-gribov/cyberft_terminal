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
        } else if ($request->status === SBBOLRequest::STATUS_DELIVERED) {
            $request->receiverDocumentStatus = $statusCode;
        }
        // Сохранить модель в БД
        $request->save();

        return true;
    }
}

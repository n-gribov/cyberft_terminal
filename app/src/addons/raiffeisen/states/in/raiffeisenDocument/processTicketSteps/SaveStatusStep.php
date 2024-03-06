<?php

namespace addons\raiffeisen\states\in\raiffeisenDocument\processTicketSteps;

use addons\raiffeisen\models\RaiffeisenRequest;
use addons\raiffeisen\states\BaseStep;
use addons\raiffeisen\states\in\raiffeisenDocument\ProcessTicketState;

/**
 * @property ProcessTicketState $state
 */
class SaveStatusStep extends BaseStep
{
    public function run()
    {
        $request = RaiffeisenRequest::findOne($this->state->requestId);

        $ticket = $this->state->raiffeisenDocument;
        $statusCode = $ticket->getInfo()->getStatusStateCode();

        if ($request->status === RaiffeisenRequest::STATUS_SENT) {
            $request->receiverRequestStatus = $statusCode;
        } elseif ($request->status === RaiffeisenRequest::STATUS_DELIVERED) {
            $request->receiverDocumentStatus = $statusCode;
        }
        $request->save();

        return true;
    }
}

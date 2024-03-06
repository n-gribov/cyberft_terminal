<?php

namespace addons\raiffeisen\states\in\raiffeisenDocument\processTicketSteps;

use addons\raiffeisen\helpers\RaiffeisenModuleHelper;
use addons\raiffeisen\models\RaiffeisenRequest;
use addons\raiffeisen\models\TicketStatus;
use addons\raiffeisen\states\BaseStep;
use addons\raiffeisen\states\in\raiffeisenDocument\ProcessTicketState;
use common\modules\transport\models\StatusReportType;

/**
 * @property ProcessTicketState $state
 */
class SendStatusReportStep extends BaseStep
{
    public function run()
    {
        $request = RaiffeisenRequest::findOne($this->state->requestId);

        if (empty($request->incomingDocumentId)) {
            return true;
        }

        $ticket = $this->state->raiffeisenDocument;
        $messageFromBank = $ticket->getInfo()->getMsgFromBank() ? $ticket->getInfo()->getMsgFromBank()->getMessage() : null;
        $newStatusCode = $ticket->getInfo()->getStatusStateCode();
        $previousStatusCode = $request->receiverDocumentStatus ?: $request->receiverRequestStatus;

        if ($newStatusCode === $previousStatusCode) {
            $this->log('Status has not changed, will not send status report');
            return true;
        }

        $this->log("Status has changed form $previousStatusCode to $newStatusCode, will send status report for document {$request->incomingDocumentId}");
        $isSent = $this->sendStatusReport($request, $newStatusCode, $messageFromBank);
        if (!$isSent) {
            $this->log('Status report is not sent');
        }

        return true;
    }

    private function sendStatusReport(RaiffeisenRequest $request, string $statusCode, $messageFromBank)
    {
        $statusReportStatus = $this->getStatusReportStatus($statusCode);
        if (!$statusReportStatus) {
            $this->log("Cannot detect status report status code for Raiffeisen status $statusCode");
            return false;
        }

        $ticketStatus = new TicketStatus($statusCode);
        $isErrorStatus = $statusReportStatus === 'RJCT';

        $description = $this->createStatusReportDescription($ticketStatus->getDescription(), $messageFromBank);

        return RaiffeisenModuleHelper::sendStatusReport(
            $request->incomingDocument,
            $statusReportStatus,
            $isErrorStatus ? $description : null,
            $isErrorStatus ? StatusReportType::ERROR_CODE_RECIPIENT_REJECTION : null
        );
    }

    private function getStatusReportStatus($raiffeisenStatusCode): string
    {
        switch ($raiffeisenStatusCode) {
            case TicketStatus::ACCEPTED:
                return 'ACCP';
            case TicketStatus::IMPLEMENTED:
                return 'ACSC';
            case TicketStatus::EXPORTED:
                return 'ACSP';
            case TicketStatus::VALIDEDS:
                return 'ACTC';
            case TicketStatus::DELAYED:
            case TicketStatus::FRAUDREVIEW:
            case TicketStatus::FRAUDSMS:
                return 'PDNG';
            case TicketStatus::DELIVERED:
            case TicketStatus::CREATED:
                return 'RCVD';
            case TicketStatus::REQUISITE_ERROR:
            case TicketStatus::DECLINED_BY_ABS:
            case TicketStatus::REFUSEDBYBANK:
            case TicketStatus::CARD2:
            case TicketStatus::INVALIDEDS:
            case TicketStatus::EXPORT_ERROR:
            case TicketStatus::RECALL:
            case TicketStatus::FAIL:
                return 'RJCT';
            default:
                return null;
        }
    }

    private function createStatusReportDescription($ticketStatusDescription, $messageFromBank)
    {
        return implode(
            '. ',
            array_filter([$ticketStatusDescription, $messageFromBank])
        );
    }
}

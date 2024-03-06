<?php

namespace addons\SBBOL\states\in\sbbolDocument\processTicketSteps;

use addons\SBBOL\helpers\SBBOLModuleHelper;
use addons\SBBOL\models\SBBOLRequest;
use addons\SBBOL\models\TicketStatus;
use addons\SBBOL\states\BaseStep;
use addons\SBBOL\states\in\sbbolDocument\ProcessTicketState;
use common\modules\transport\helpers\DocumentTransportHelper;
use common\modules\transport\models\StatusReportType;

/**
 * @property ProcessTicketState $state
 */
class SendStatusReportStep extends BaseStep
{
    public function run()
    {
        $request = SBBOLRequest::findOne($this->state->requestId);

        if (empty($request->incomingDocumentId)) {
            return true;
        }

        $ticket = $this->state->sbbolDocument;
        $messageFromBank = $ticket->getInfo()->getMsgFromBank() ? $ticket->getInfo()->getMsgFromBank()->getMessage() : null;
        $newStatusCode = $ticket->getInfo()->getStatusStateCode();
        $previousStatusCode = $request->receiverDocumentStatus ?: $request->receiverRequestStatus;

        if ($newStatusCode === $previousStatusCode) {
            $this->log('Status has not changed, will not send status report');
            return true;
        }

        $this->log("Status has changed form $previousStatusCode to $newStatusCode, will send status report for document {$request->incomingDocumentId}");
        $isSent = $request->documentType === 'PayDocRu'
            ? $this->sendPayDocRuStatusReport($request, $newStatusCode, $messageFromBank)
            : $this->sendDefaultStatusReport($request, $newStatusCode, $messageFromBank);

        if (!$isSent) {
            $this->log('Status report is not sent');
        }

        return true;
    }

    private function sendPayDocRuStatusReport(SBBOLRequest $request, string $statusCode, $messageFromBank)
    {
        $isPaymentStatusReport = in_array(
            $statusCode,
            [
                TicketStatus::REQUISITE_ERROR,
                TicketStatus::DECLINED_BY_ABS,
                TicketStatus::REFUSEDBYBANK,
                TicketStatus::IMPLEMENTED,
                TicketStatus::CARD2,
                TicketStatus::DELAYED,
                TicketStatus::FRAUDREVIEW,
                TicketStatus::FRAUDSMS,
            ]
        );

        $statusReportStatus = $this->getPayDocRuStatusReportStatus($statusCode);
        if (!$statusReportStatus) {
            $this->log("Cannot detect status report status code for SBBOL status $statusCode");
            return false;
        }

        $ticketStatus = new TicketStatus($statusCode);
        $isErrorStatus = $statusReportStatus === 'RJCT';

        $description = $this->createStatusReportDescription($ticketStatus->getDescription(), $messageFromBank);

        return $isPaymentStatusReport
            ? SBBOLModuleHelper::sendPaymentStatusReport(
                $request->incomingDocument,
                $statusReportStatus,
                $statusReportStatus,
                $description,
                $messageFromBank
            )
            : DocumentTransportHelper::sendStatusReport(
                $request->incomingDocument,
                $statusReportStatus,
                $isErrorStatus ? $description : null,
                $isErrorStatus ? StatusReportType::ERROR_CODE_RECIPIENT_REJECTION : null
            );
    }

    private function getPayDocRuStatusReportStatus($sbbolStatusCode)
    {
        switch ($sbbolStatusCode) {
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

    private function sendDefaultStatusReport(SBBOLRequest $request, string $statusCode, $messageFromBank)
    {
        $ticketStatus = new TicketStatus($statusCode);
        $isFinalErrorStatus = $ticketStatus->isError() && !$ticketStatus->shouldRetryFetchResponse();

        if ($isFinalErrorStatus) {
            DocumentTransportHelper::sendStatusReport(
                $request->incomingDocument,
                'RJCT',
                $this->createStatusReportDescription($ticketStatus->getDescription(), $messageFromBank),
                StatusReportType::ERROR_CODE_RECIPIENT_REJECTION
            );
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

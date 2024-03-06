<?php

namespace addons\raiffeisen\states\in\raiffeisenDocument\processTicketSteps;

use addons\raiffeisen\components\RaiffeisenTransport;
use addons\raiffeisen\helpers\RaiffeisenModuleHelper;
use addons\raiffeisen\models\RaiffeisenRequest;
use addons\raiffeisen\models\TicketStatus;
use addons\raiffeisen\states\BaseStep;
use addons\raiffeisen\states\in\raiffeisenDocument\ProcessTicketState;
use common\helpers\Uuid;
use common\models\raiffeisenxml\RaiffeisenTransportConfig;
use common\models\raiffeisenxml\request\Request;
use common\models\raiffeisenxml\request\RequestType\DocIdsAType\DocIdAType;

/**
 * @property ProcessTicketState $state
 */
class CheckStatusStep extends BaseStep
{
    public function run()
    {
        $ticket = $this->state->raiffeisenDocument;

        $request = RaiffeisenRequest::findOne($this->state->requestId);
        $statusCode = $ticket->getInfo()->getStatusStateCode();
        $this->log("Status: $statusCode, request processing status: {$request->status}");

        $ticketStatus = new TicketStatus($statusCode);
        if ($ticketStatus->isError()) {
            $this->log("Got error status: {$ticketStatus->getCode()}, {$ticketStatus->getDescription()}");
            if ($ticketStatus->shouldRetryFetchResponse()) {
                $this->log('Will retry later');
            } else {
                $this->log('Will not retry later');
                $request->status = RaiffeisenRequest::STATUS_REJECTED;
                // Сохранить модель в БД
                $request->save();

                return true;
            }
        }

        if ($request->status === RaiffeisenRequest::STATUS_SENT) {
            // Если запрос доставлен, запрашиваем тикет по статусу документа
            if ($statusCode === TicketStatus::DELIVERED) {
                $request->receiverDocumentId = $ticket->getDocId();

                $requestResult = $this->sendDocumentStatusRequest($request, $ticket->getDocId());
                if ($requestResult->isSent()) {
                    $request->status = RaiffeisenRequest::STATUS_DELIVERED;
                    $request->documentStatusRequestId = $requestResult->getRequestId();
                }
                // Сохранить модель в БД
                $request->save();

                if ($request->documentType === 'StmtReqRaif') {
                    $createTime = $ticket->getCreateTime();
                    if (!$createTime) {
                        $this->log('Statement request ticket has no create time');
                        RaiffeisenRequest::updateStatus($request->id, RaiffeisenRequest::STATUS_RESPONSE_PROCESSING_ERROR);

                        return false;
                    }
                    $request->responseHandlerParams = array_merge(
                        $request->responseHandlerParams,
                        ['createTime' => $createTime->format(\DateTime::ATOM)]
                    );
                    // Сохранить модель в БД
                    $request->save();
                }

                $request->releaseLock();

                return true;
            }
        } else if ($request->status === RaiffeisenRequest::STATUS_DELIVERED) {
            // Обрабатываем статус документа
            if ($request->documentType === 'StmtReqRaif') {
                if ($statusCode === TicketStatus::IMPLEMENTED) {
                    $isSent = $this->sendStatementIncomingRequest($request);

                    if ($isSent) {
                        $request->status = RaiffeisenRequest::STATUS_PROCESSED;
                        // Сохранить модель в БД и вернуть результат сохранения
                        return $request->save();
                    }
                }
            }

            // Запрашиваем статус документа снова
            $requestResult = $this->sendDocumentStatusRequest($request, $ticket->getDocId());
            if ($requestResult->isSent()) {
                $request->documentStatusRequestId = $requestResult->getRequestId();
            }

            $request->releaseLock();

            // Сохранить модель в БД и вернуть результат сохранения
            return $request->save();
        }

        $request->releaseLock();

        return true;
    }

    private function sendDocumentStatusRequest(RaiffeisenRequest $request, string $receiverDocumentId): RaiffeisenTransport\SendRequestResult
    {
        $customer = $request->customer;
        $documentTypeName = $this->getDocumentTypeName($request->documentType);
        $requestDocument = $requestDocument = (new Request())
            ->setRequestId(Uuid::generate(false)->toString())
            ->setVersion(RaiffeisenTransportConfig::EXCHANGE_FORMAT_VERSION)
            ->setDocIds([
                (new DocIdAType())->setDocId($receiverDocumentId)->setDocType($documentTypeName)
            ]);

        return $this->state->module->transport->sendRequest($requestDocument, $customer->holdingHeadId);
    }

    private function sendStatementIncomingRequest(RaiffeisenRequest $importRequest): bool
    {
        try {
            RaiffeisenModuleHelper::sendIncomingRequest(
                $importRequest->customer,
                function ($message) { $this->log($message); },
                ['parentRequestId' => $this->state->requestId]
            );
        } catch (\Exception $exception) {
            $this->log("Failed to send incoming request for statement, caused by: $exception");
            return false;
        }

        return true;
    }

    private function getDocumentTypeName(string $documentType): string
    {
        $typeNames = [
            'StmtReqRaif' => 'Запрос на получение информации о движении средств',
        ];

        return $typeNames[$documentType] ?? $documentType;
    }
}

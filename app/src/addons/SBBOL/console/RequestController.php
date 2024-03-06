<?php

namespace addons\SBBOL\console;

use addons\SBBOL\models\SBBOLCustomer;
use addons\SBBOL\models\soap\request\GetRequestStatusSRPRequest;
use common\helpers\sbbol\SBBOLXmlSerializer;
use common\helpers\Uuid;
use common\models\sbbolxml\request\Request;
use common\models\sbbolxml\request\RequestType\DocIdsAType\DocIdAType;
use common\models\sbbolxml\response\ErrorType;
use common\models\sbbolxml\response\Response;
use common\models\sbbolxml\response\TicketType;
use common\models\sbbolxml\SBBOLTransportConfig;

class RequestController extends BaseController
{
    public function actionIndex()
    {
        $this->run('/help', ['SBBOL/request']);
    }

    public function actionTrackRequestStatus($customerId, $requestId)
    {
        $customer = SBBOLCustomer::findOne($customerId);
        if ($customer === null) {
            echo "Customer $customerId is not found\n";
            return;
        }

        $sessionId = $this->module->sessionManager->findOrCreateSession($customer->holdingHeadId);

        $statusRequestMessage = new GetRequestStatusSRPRequest([
            'sessionId' => $sessionId,
            'orgId' => $customerId,
            'requests' => [$requestId]
        ]);

        $n = 1;
        while (true) {
            $statusResponseMessage = $this->module->transport->send($statusRequestMessage);
            $return = $statusResponseMessage->return;

            if (!is_scalar($return) || strpos($return, '<!--') !== 0) {
                /** @var Response $responseDocument */
                $responseDocument = SBBOLXmlSerializer::deserialize($return, Response::class);

                $errors = $responseDocument->getErrors();
                foreach ($errors as $error) {
                    /** @var ErrorType $error */
                    echo "Got error: {$error->getCode()}, {$error->getDesc()}\n";
                }

                $tickets = $responseDocument->getTickets();
                foreach ($tickets as $ticket) {
                    /** @var TicketType $ticket */
                    echo "Status: {$ticket->getInfo()->getStatusStateCode()}, document: {$ticket->getDocId()}\n";
                }

                if (empty($errors) && empty($tickets)) {
                    echo "The response is not status document\n";
                    return;
                }

                $n++;
                if ($n > 100) {
                    echo "Too much attempts, will stop\n";
                    return;
                }
            } else {
                echo "Status: $return\n";
            }

            sleep(5);
        }
    }

    public function actionTrackDocumentStatus($customerId, $requestId)
    {
        $customer = SBBOLCustomer::findOne($customerId);
        if ($customer === null) {
            echo "Customer $customerId is not found\n";
            return;
        }

        $sessionId = $this->module->sessionManager->findOrCreateSession($customer->holdingHeadId);

        $documentId = $this->fetchDocumentId($customerId, $requestId, $sessionId);
        if ($documentId === null) {
            return;
        }

        $n = 1;
        while (true) {
            $responseDocument = $this->getDocumentStatus($customer, $documentId, $sessionId);

            $errors = $responseDocument->getErrors();
            foreach ($errors as $error) {
                /** @var ErrorType $error */
                echo "Got error: {$error->getCode()}, {$error->getDesc()}\n";
            }

            $tickets = $responseDocument->getTickets();
            foreach ($tickets as $ticket) {
                /** @var TicketType $ticket */
                echo "Document status: {$ticket->getInfo()->getStatusStateCode()}\n";
            }

            if (empty($errors) && empty($tickets)) {
                echo "The response is not status document\n";
                return;
            }

            $n++;
            if ($n > 100) {
                echo "Too much attempts, will stop\n";
                return;
            }

            sleep(5);
        }
    }

    private function fetchDocumentId($customerId, $requestId, $sessionId)
    {
        $statusRequestMessage = new GetRequestStatusSRPRequest([
            'sessionId' => $sessionId,
            'orgId' => $customerId,
            'requests' => [$requestId]
        ]);

        $statusResponseMessage = $this->module->transport->send($statusRequestMessage);
        $return = $statusResponseMessage->return;
        if (is_scalar($return) && strpos($return, '<!--') === 0) {
            echo "Request is not processed, status: $return\n";
            return null;
        }

        /** @var Response $responseDocument */
        $responseDocument = SBBOLXmlSerializer::deserialize($return, Response::class);
        if (empty($responseDocument->getTickets())) {
            echo "Request status response has no tickets\n";
            return null;
        }

        $ticket = $responseDocument->getTickets()[0];
        if (empty($ticket->getDocId())) {
            echo "Request status response has no document id\n";
            return null;
        }

        return $ticket->getDocId();
    }

    private function getDocumentStatus(SBBOLCustomer $customer, $receiverDocumentId, $sessionId): Response
    {
        $requestDocument = $requestDocument = (new Request())
            ->setRequestId(Uuid::generate(false)->toString())
            ->setVersion(SBBOLTransportConfig::EXCHANGE_FORMAT_VERSION)
            ->setProtocolVersion(SBBOLTransportConfig::PROTOCOL_VERSION)
            ->setOrgId($customer->id)
            ->setSender($customer->senderName)
            ->setDocIds([
                (new DocIdAType())->setDocId($receiverDocumentId)
            ]);

        $responseBody = $this->sendRequestAndGetStatus($requestDocument, $sessionId);

        return SBBOLXmlSerializer::deserialize($responseBody, Response::class);
    }
}

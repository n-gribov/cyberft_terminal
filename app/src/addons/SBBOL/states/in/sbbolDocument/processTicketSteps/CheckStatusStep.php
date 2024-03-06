<?php

namespace addons\SBBOL\states\in\sbbolDocument\processTicketSteps;

use addons\SBBOL\components\SBBOLTransport;
use addons\SBBOL\helpers\PartitionedResponseDownloader;
use addons\SBBOL\helpers\SBBOLSignHelper;
use addons\SBBOL\models\SBBOLCustomer;
use addons\SBBOL\models\SBBOLCustomerAccount;
use addons\SBBOL\models\SBBOLKey;
use addons\SBBOL\models\SBBOLRequest;
use addons\SBBOL\models\TicketStatus;
use addons\SBBOL\SBBOLModule;
use addons\SBBOL\states\BaseStep;
use addons\SBBOL\states\in\response\ProcessAsyncResponseState;
use addons\SBBOL\states\in\response\ProcessHugeStatementsState;
use addons\SBBOL\states\in\sbbolDocument\ProcessTicketState;
use common\components\Resque;
use common\helpers\Uuid;
use common\jobs\StateJob;
use common\models\sbbolxml\request\AccountType;
use common\models\sbbolxml\request\PersonalInfoType;
use common\models\sbbolxml\request\Request;
use common\models\sbbolxml\request\RequestType\DocIdsAType\DocIdAType;
use common\models\sbbolxml\request\RequestType\IncomingAType;
use common\models\sbbolxml\SBBOLTransportConfig;
use Yii;

/**
 * @property ProcessTicketState $state
 */
class CheckStatusStep extends BaseStep
{
    public function run()
    {
        $ticket = $this->state->sbbolDocument;

        $request = SBBOLRequest::findOne($this->state->requestId);
        $statusCode = $ticket->getInfo()->getStatusStateCode();
        $this->log("Status: $statusCode, request processing status: {$request->status}");

        $ticketStatus = new TicketStatus($statusCode);
        if ($ticketStatus->isError()) {
            $this->log("Got error status: {$ticketStatus->getCode()}, {$ticketStatus->getDescription()}");
            if ($ticketStatus->shouldRetryFetchResponse()) {
                $this->log('Will retry later');
            } else {
                $this->log('Will not retry later');
                $request->status = SBBOLRequest::STATUS_REJECTED;
                $request->save();

                return true;
            }
        }

        if ($request->status === SBBOLRequest::STATUS_SENT) {
            // Если запрос доставлен, запрашиваем тикет по статусу документа
            if ($statusCode === TicketStatus::DELIVERED) {
                $request->receiverDocumentId = $ticket->getDocId();

                $requestResult = $this->sendDocumentStatusRequest($request->customer, $ticket->getDocId());
                if ($requestResult->isSent()) {
                    $request->status = SBBOLRequest::STATUS_DELIVERED;
                    $request->documentStatusRequestId = $requestResult->getRequestId();
                }
                $request->save();

                if ($request->documentType === 'CertifRequest') {
                    $this->updateLastCertificateNumber($request->customer);
                }

                if ($request->documentType === 'StmtReq') {
                    $createTime = $ticket->getCreateTime();
                    if (!$createTime) {
                        $this->log('Statement request ticket has no create time');
                        SBBOLRequest::updateStatus($request->id, SBBOLRequest::STATUS_RESPONSE_PROCESSING_ERROR);

                        return false;
                    }
                    $request->responseHandlerParams = array_merge(
                        $request->responseHandlerParams,
                        ['createTime' => $createTime->format(\DateTime::ATOM)]
                    );
                    $request->save();
                }

                $request->releaseLock();

                return true;
            }
        } else if ($request->status === SBBOLRequest::STATUS_DELIVERED) {
            // Обрабатываем статус документа
            if ($request->documentType === 'CertifRequest') {
                if ($statusCode === TicketStatus::PUBLISHED_BY_BANK) {
                    $keyId = $request->getResponseHandlerParam('keyId');
                    $requestResult = $this->sendPersonalInfoRequest(
                        $request->customer,
                        [
                            'action' => 'saveNewPublishedCertificate',
                            'keyId'  => $keyId,
                        ]
                    );

                    if ($requestResult->isSent()) {
                        $key = SBBOLKey::findOne($keyId);
                        $key->status = SBBOLKey::STATUS_CERTIFICATE_IS_PUBLISHED;
                        $request->status = SBBOLRequest::STATUS_PROCESSED;

                        return $key->save() && $request->save();
                    }
                }
            } else if ($request->documentType == 'StmtReq') {
                if ($statusCode === TicketStatus::IMPLEMENTED) {
                    $isSent = $this->sendStatementIncomingRequest($request);

                    if ($isSent) {
                        $request->status = SBBOLRequest::STATUS_PROCESSED;

                        return $request->save();
                    }
                }
            }

            // Запрашиваем статус документа снова
            $requestResult = $this->sendDocumentStatusRequest($request->customer, $ticket->getDocId());
            if ($requestResult->isSent()) {
                $request->documentStatusRequestId = $requestResult->getRequestId();
            }

            $request->releaseLock();

            return $request->save();
        }

        if ($request->documentType === 'ActivateCert') {
            if ($statusCode === TicketStatus::ACCEPTED) {
                $keyId = $request->getResponseHandlerParam('keyId');

                $customerActiveKeys = SBBOLKey::find()->joinWith(['keyOwner', 'keyOwner.customer as customer'])
                    ->where(['customer.id' => $request->customerId])
                    ->andWhere(['sbbol_key.status' => SBBOLKey::STATUS_ACTIVE])
                    ->all();

                SBBOLKey::updateAll(['status' => SBBOLKey::STATUS_BLOCKED], ['id' => $customerActiveKeys]);

                $key = SBBOLKey::findOne($keyId);
                $key->status = SBBOLKey::STATUS_ACTIVE;

                $this->state->module->sessionManager->deleteSession($request->customer->holdingHeadId);

                return $key->save() && SBBOLRequest::updateStatus($this->state->requestId, SBBOLRequest::STATUS_PROCESSED);
            }
        }

        if ($statusCode === TicketStatus::RESPONSE_DIVISION) {
            return $this->fetchPartitionedResponse();
        }

        $request->releaseLock();

        return true;
    }

    private function updateLastCertificateNumber(SBBOLCustomer $customer)
    {
        $customer->lastCertNumber += 1;
        $customer->save();
    }

    private function sendDocumentStatusRequest(SBBOLCustomer $customer, string $receiverDocumentId): SBBOLTransport\SendRequestResult
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

        $sessionId = $this->state->module->sessionManager->findOrCreateSession($customer->holdingHeadId);

        return $this->state->module->transport->sendRequest($requestDocument, $sessionId);
    }

    private function sendPersonalInfoRequest(SBBOLCustomer $customer, $responseHandlerParams = null): SBBOLTransport\SendAsyncResult
    {
        $requestDocument = (new Request())
            ->setRequestId(Uuid::generate(false)->toString())
            ->setVersion(SBBOLTransportConfig::EXCHANGE_FORMAT_VERSION)
            ->setProtocolVersion(SBBOLTransportConfig::PROTOCOL_VERSION)
            ->setOrgId($customer->id)
            ->setSender($customer->senderName)
            ->setPersonalInfo((new PersonalInfoType()));

        $sessionId = $this->state->module->sessionManager->findOrCreateSession($customer->holdingHeadId);

        return $this->state->module->transport->sendAsync(
            $requestDocument,
            $sessionId,
            $responseHandlerParams
        );
    }

    private function sendStatementIncomingRequest(SBBOLRequest $importRequest)
    {
        $lastIncomingTime = new \DateTime($importRequest->responseHandlerParams['createTime']);
        $customer = $importRequest->customer;
        $incoming = new IncomingAType();
        $incoming->setLastIncomingTime($lastIncomingTime ?: null);

        $accounts = SBBOLCustomerAccount::find()
            ->where(['in', 'number', $importRequest->responseHandlerParams['accountsNumbers']])
            ->all();

        foreach($accounts as $account) {
            $incoming->addToAccounts(
                (new AccountType($account->number))->setBic($account->bankBik)
            );
        }

        $requestIncoming = (new Request())
            ->setVersion(SBBOLTransportConfig::EXCHANGE_FORMAT_VERSION)
            ->setProtocolVersion(SBBOLTransportConfig::PROTOCOL_VERSION)
            ->setRequestId(Uuid::generate(false)->toString())
            ->setOrgId($customer->id)
            ->setSender($customer->senderName)
            ->setIncoming($incoming);

        list($isSigned, $digest) = SBBOLSignHelper::signRequestDocument($requestIncoming, $customer->holdingHeadId);
        if (!$isSigned) {
            $this->log('Failed to sign Incoming request');
            return false;
        }

        $sessionId = $this->state->module->sessionManager->findOrCreateSession($customer->holdingHeadId);

        $sendResult = $this->state->module->transport->sendAsync(
            $requestIncoming,
            $sessionId,
            ['parentRequestId' => $this->state->requestId],
            $digest
        );

        if (!$sendResult->isSent()) {
            $this->log("Failed to send Incoming request, error: {$sendResult->getErrorMessage()}");
            return false;
        }

        return true;
    }

    private function fetchPartitionedResponse()
    {
        $this->log('Downloading partitioned response...');

        SBBOLRequest::updateStatus($this->state->requestId, SBBOLRequest::STATUS_DOWNLOADING_PARTITIONED_RESPONSE);

        try {
            $partsCount = null;
            foreach ($this->state->sbbolDocument->getOtherParams() as $param) {
                if ($param->getName() === 'packagesNumber') {
                    $partsCount = (int)$param->getValue();
                    break;
                }
            }

            if (empty($partsCount)) {
                throw new \Exception('Cannot detect parts count');
            }

            $request = SBBOLRequest::findOne($this->state->requestId);
            $responseBodyFilePath = null;
            PartitionedResponseDownloader::download(
                $request->receiverRequestId,
                $request->customer,
                $partsCount,
                function ($tmpResultFilePath) use (&$responseBodyFilePath) {
                    $tempResource = Yii::$app->registry->getTempResource(SBBOLModule::SERVICE_ID);
                    $result = $tempResource->putFile($tmpResultFilePath);
                    $responseBodyFilePath = $result['path'];
                }
            );
        } catch (\Exception $exception) {
            $this->log("Failed to fetch partitioned response, caused by: $exception");
            SBBOLRequest::updateStatus($this->state->requestId, SBBOLRequest::STATUS_DOWNLOADING_PARTITIONED_RESPONSE_ERROR);

            return false;
        }

        $stateJobClass = $this->isStatementRequestResponse($request)
            ? ProcessHugeStatementsState::class
            : ProcessAsyncResponseState::class;

        $jobId = Yii::$app->resque->enqueue(
            StateJob::class,
            [
                'stateClass' => $stateJobClass,
                'params' => serialize([
                    'requestId'            => $this->state->requestId,
                    'responseBodyFilePath' => $responseBodyFilePath,
                ])
            ],
            true,
            Resque::INCOMING_QUEUE
        );

        if (empty($jobId)) {
            $this->log('Failed to enqueue response processing job');
            SBBOLRequest::updateStatus($this->state->requestId, SBBOLRequest::STATUS_RESPONSE_PROCESSING_ERROR);
        }

        $request->releaseLock();

        return !empty($jobId);
    }

    private function isStatementRequestResponse(SBBOLRequest $request)
    {
        $parentRequestId = $request->getResponseHandlerParam('parentRequestId');
        if ($request->documentType === 'Incoming' && $parentRequestId) {
            $parentRequest = SBBOLRequest::findOne($parentRequestId);
            return $parentRequest !== null && $parentRequest->documentType === 'StmtReq';
        }

        return false;
    }
}

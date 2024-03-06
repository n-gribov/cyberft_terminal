<?php

namespace addons\sbbol2\jobs;

use addons\sbbol2\apiClient\api\ClientInfoApi;
use addons\sbbol2\apiClient\ApiException;
use addons\sbbol2\services\CustomerImportService;
use common\models\sbbol2\ClientInfo;
use Resque_Job_DontPerform;

class UpdateSberbankCustomerJob extends BaseJob
{
    private $customerId;

    public function setUp()
    {
        parent::setUp();

        $this->customerId = $this->args['customerId'] ?? null;
        if (!$this->customerId) {
            $this->log('Customer id is required', true);
            throw new Resque_Job_DontPerform();
        }
    }

    public function perform()
    {
        $clientInfo = $this->fetchClientInfo();
        $importService = new CustomerImportService();
        $customer = $importService->createOrUpdate($clientInfo);
        $this->log("Customer {$customer->id} is successfully updated");
    }

    private function fetchClientInfo(): ClientInfo
    {
        $accessToken = $this->module->apiAccessTokenProvider->getForCustomer($this->customerId);

        /** @var ClientInfoApi $api */
        $api = $this->module->apiFactory->create(ClientInfoApi::class);
        try {
            return $api->getClientInfoUsingGET($accessToken);
        } catch (ApiException $exception) {
            $this->log("Got error response from Sberbank client info API, status: {$exception->getCode()}, response: {$exception->getResponseBody()}");
            throw $exception;
        }
    }
}

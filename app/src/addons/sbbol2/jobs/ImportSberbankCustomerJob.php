<?php

namespace addons\sbbol2\jobs;

use addons\sbbol2\apiClient\api\ClientInfoApi;
use addons\sbbol2\apiClient\ApiException;
use addons\sbbol2\services\CustomerImportService;
use common\models\sbbol2\ClientInfo;
use Resque_Job_DontPerform;
use Yii;
use yii\authclient\OAuthToken;

class ImportSberbankCustomerJob extends BaseJob
{
    /**
     * @var OAuthToken
     */
    private $authToken;
    private $terminalAddress;
    private $sendCustomerSettings = false;

    public function setUp()
    {
        parent::setUp();

        $this->authToken = isset($this->args['authToken'])
            ? unserialize($this->args['authToken'])
            : null;

        if (empty($this->authToken)) {
            $this->log('Auth token is required', true);
            throw new Resque_Job_DontPerform();
        }
        $this->log(get_class($this->authToken));
        if (!$this->authToken instanceof OAuthToken) {
            $this->log('Auth token must be serialized instance of yii\authclient\OAuthToken', true);
            throw new Resque_Job_DontPerform();
        }

        $this->terminalAddress = $this->args['terminalAddress'] ?? null;
        $this->sendCustomerSettings = $this->args['sendCustomerSettings'] ?? false;
    }

    public function perform()
    {
        $clientInfo = $this->fetchClientInfo();
        $importService = new CustomerImportService();
        $customer = $importService->createOrUpdate($clientInfo, $this->terminalAddress, $this->authToken);
        $this->log("Customer {$customer->id} is successfully imported");

        if ($this->sendCustomerSettings) {
            //CYB-4477 Отправляем настройки после авторизации
            
            Yii::$app->resque->enqueue(SendClientTerminalSettingsJob::class, ['inn' => $customer->inn]);
        }
    }

    private function fetchClientInfo(): ClientInfo
    {
        /** @var ClientInfoApi $api */
        $api = $this->module->apiFactory->create(ClientInfoApi::class);
        try {
            return $api->getClientInfoUsingGET($this->authToken->getToken());
        } catch (ApiException $exception) {
            $this->log("Got error response from Sberbank client info API, status: {$exception->getCode()}, response: {$exception->getResponseBody()}");
            throw $exception;
        }
    }
}

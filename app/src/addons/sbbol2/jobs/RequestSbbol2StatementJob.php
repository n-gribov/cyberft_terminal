<?php
namespace addons\sbbol2\jobs;

use addons\edm\models\Sbbol2Statement\Sbbol2StatementType;
use addons\sbbol2\apiClient\api\StatementApi;
use addons\sbbol2\models\Sbbol2Customer;
use addons\sbbol2\models\Sbbol2CustomerAccount;
use addons\sbbol2\models\Sbbol2ScheduledRequest;
use Yii;

class RequestSbbol2StatementJob extends BaseJob
{
    public function perform()
    {
        $this->log('Queuing statement requests...');

        $this->_api = $this->module->apiFactory->create(StatementApi::class);

        $customers = Sbbol2Customer::find()->all();

        foreach($customers as $customer) {
            if (!$this->module->apiAccessTokenProvider->customerHasActiveToken($customer->id)) {
                continue;
            }

            $this->queueCustomerRequests($customer);
        }
    }

    private function queueCustomerRequests($customer)
    {
        $accounts = Sbbol2CustomerAccount::findAll(['customerId' => $customer->id]);
        $startDate = date('Y-m-d');

        foreach($accounts as $account) {
            $scheduledRequest = new Sbbol2ScheduledRequest([
                'type' => Sbbol2StatementType::TYPE,
                'status' => Sbbol2ScheduledRequest::STATUS_PENDING,
                'customerId' => $customer->id,
                'requestJsonData' => json_encode([
                    'account' => $account->number,
                    'receiver' => $customer->terminalAddress,
                    'startDate' => $startDate,
                ])
            ]);

            if (!$scheduledRequest->save()) {
                $this->log('Could not save scheduled request: ' . $scheduledRequest->errors, true);
            } else {
                $this->log('Scheduled request for account: ' . $account->number);
            }
        }
    }

}

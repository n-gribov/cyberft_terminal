<?php

namespace addons\SBBOL\console;

use addons\SBBOL\jobs\SendClientTerminalSettingsJob;
use addons\SBBOL\models\SBBOLCustomer;
use addons\SBBOL\models\SBBOLOrganization;
use common\helpers\Uuid;
use common\models\sbbolxml\request\PersonalInfoType;
use common\models\sbbolxml\request\Request;
use common\models\sbbolxml\SBBOLTransportConfig;

class CustomerController extends BaseController
{
    /**
     * Метод выводит текст с подсказкой
     */
    public function actionIndex()
    {
        $this->run('/help', ['SBBOL/customer']);
    }

    public function actionList()
    {
        $customers = SBBOLCustomer::find()->all();
        foreach ($customers as $i => $customer) {
            $n = $i + 1;
            echo "$n. {$customer->id} - {$customer->fullName}\n";
        }
    }

    public function actionGetInfo($customerId, $saveToFile = false)
    {
        $customer = SBBOLCustomer::findOne($customerId);
        if ($customer === null) {
            echo "Customer $customerId is not found\n";
            return;
        }

        $sessionId = $this->module->sessionManager->findOrCreateSession($customer->holdingHeadId);

        $requestDocument = (new Request())
            ->setRequestId((string)Uuid::generate(false))
            ->setVersion(SBBOLTransportConfig::EXCHANGE_FORMAT_VERSION)
            ->setProtocolVersion(SBBOLTransportConfig::PROTOCOL_VERSION)
            ->setOrgId($customerId)
            ->setSender($customer->senderName)
            ->setPersonalInfo(new PersonalInfoType());

        echo 'Request ID: ' . $requestDocument->getRequestId() . "\n";

        $responseBody = $this->sendRequestAndGetStatus($requestDocument, $sessionId);

        if ($saveToFile) {
            $fileName = "PersonalInfo-$customerId.xml";
            file_put_contents($fileName, $responseBody);
            echo "Response is saved to $fileName\n";
        } else {
            echo "Response:\n$responseBody\n";
        }
    }

    public function actionSendClientTerminalSettings($organizationInn = null)
    {
        if ($organizationInn) {
            $organization = SBBOLOrganization::findOne($organizationInn);
            if ($organization === null) {
                echo "Organization with INN $organizationInn is not found\n";
                return;
            }
            if (empty($organization->terminalAddress)) {
                echo "Organization with INN $organizationInn has no assigned terminal address\n";
                return;
            }
        }

        $job = new SendClientTerminalSettingsJob();
        if ($organizationInn) {
            echo "Will send settings to organization with INN {$organization->terminalAddress}\n";
            $job->args = ['customerId' => $organizationInn];
        } else {
            echo "Will send settings to all organizations with assigned terminal address\n";
        }

        $job->setUp();
        $job->perform();

        echo "Done\n";
    }

    public function actionUpdateLocality($customerId, $locality)
    {
        if (!$locality) {
            echo "Locality param is empty\n";
            return;
        }
        $locality = 'Москва'; // hack because can't enter russian letters in remote console
        $customer = SBBOLCustomer::findOne($customerId);
        if (!$customer) {
            echo "Customer $customerId is not found\n";
            return;
        }

        $oldLocality = $customer->addressSettlement;
        echo "old locality: $oldLocality, new locality: $locality\n";

        $customer->addressSettlement = $locality;

        // Если модель успешно сохранена в БД
        if ($customer->save()) {
            echo "Saved ok\n";
        } else {
            echo 'Could not save customer: ' . var_export($customer->errors, true) . "\n";
        }
    }

}

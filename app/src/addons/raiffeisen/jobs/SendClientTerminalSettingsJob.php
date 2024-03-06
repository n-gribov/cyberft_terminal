<?php

namespace addons\raiffeisen\jobs;

use addons\edm\models\RaiffeisenClientTerminalSettings\RaiffeisenClientTerminalSettingsAccount;
use addons\edm\models\RaiffeisenClientTerminalSettings\RaiffeisenClientTerminalSettingsCustomer;
use addons\edm\models\RaiffeisenClientTerminalSettings\RaiffeisenClientTerminalSettingsType;
use addons\raiffeisen\models\RaiffeisenCustomerAccount;
use common\document\Document;
use common\helpers\DocumentHelper;
use common\models\Terminal;
use common\modules\transport\helpers\DocumentTransportHelper;
use Exception;
use Resque_Job_DontPerform;
use addons\raiffeisen\models\RaiffeisenCustomer;
use yii\helpers\ArrayHelper;

class SendClientTerminalSettingsJob extends BaseJob
{
    private $customersIds = [];

    public function setUp()
    {
        parent::setUp();

        if (isset($this->args['customerId'])) {

            $customerId = $this->args['customerId'];
            $customer = RaiffeisenCustomer::findOne(['id' => $customerId]);

            if ($customer === null) {
                throw new Resque_Job_DontPerform("Customer $customerId is not found");
            }

            if (empty($customer->terminalAddress)) {
                throw new Resque_Job_DontPerform("Terminal id for customer $customerId is not assigned");
            }

            $this->customersIds = [$customerId];
        } else {
            $customers = RaiffeisenCustomer::find()
                ->where(['not', ['terminalAddress' => null]])
                ->andWhere(['not', ['terminalAddress' => '']])
                ->all();

            if (empty($customers)) {
                throw new Resque_Job_DontPerform('There are no customers with assigned terminal id');
            }

            $this->customersIds = ArrayHelper::getColumn($customers, 'id');
        }
    }

    public function perform()
    {
        foreach ($this->customersIds as $customersId) {
            try {
                $this->sendToClient($customersId);
            } catch (Exception $exception) {
                $this->log("Processing setting for customer $customersId failed, caused by: $exception", true);
            }
        }
    }

    private function sendToClient($customersId)
    {
        $customer = RaiffeisenCustomer::findOne($customersId);

        if ($customer === null) {
            throw new Exception("Raiffeisen customer with id $customersId is not found");
        }

        $accounts = RaiffeisenCustomerAccount::find()
            ->where(['customerId' => $customersId])
            ->orderBy(['number' => SORT_ASC])
            ->all();

        $typeModel = $this->createTypeModel($customer, $accounts);

        $senderTerminal = Terminal::getDefaultTerminal();

        if ($senderTerminal === null) {
            throw new Exception('Default terminal is not found');
        }

        $context = DocumentHelper::createDocumentContext(
            $typeModel,
            [
                'type'               => $typeModel->getType(),
                'direction'          => Document::DIRECTION_OUT,
                'origin'             => Document::ORIGIN_SERVICE,
                'terminalId'         => $senderTerminal->id,
                'sender'             => $senderTerminal->terminalId,
                'receiver'           => $customer->terminalAddress,
                'status'             => Document::STATUS_ACCEPTED,
                'signaturesRequired' => 0,
            ]
        );

        if ($context === false) {
            throw new Exception('Failed to create document context');
        }

        DocumentTransportHelper::processDocument($context['document'], true);
    }

    private function createTypeModel($customer, $accounts)
    {
        $typeModel = new RaiffeisenClientTerminalSettingsType();

        $typeModel->customer = new RaiffeisenClientTerminalSettingsCustomer([
            'name'                 => $customer->shortName,
            'kpp'                  => $customer->kpp,
            'inn'                  => $customer->inn,
            'countryCode'          => $customer->countryCode,
            'addressState'         => $customer->addressState,
            'addressDistrict'      => $customer->addressDistrict,
            'addressSettlement'    => $customer->addressSettlement,
            'addressStreet'        => $customer->addressStreet,
            'addressBuilding'      => $customer->addressBuilding,
            'addressBuildingBlock' => $customer->addressBuildingBlock,
            'addressApartment'     => $customer->addressApartment,
            'propertyType'         => $customer->propertyType,
            'internationalName'    => $customer->internationalName,
            'ogrn'                 => $customer->ogrn,
            'dateOgrn'             => $customer->dateOgrn,
        ]);

        $typeModel->accounts = array_map(
            function (RaiffeisenCustomerAccount $account) {
                return new RaiffeisenClientTerminalSettingsAccount([
                    'number'  => $account->number,
                    'bankBik' => $account->bankBik,
                ]);
            },
            $accounts
        );

        return $typeModel;
    }
}
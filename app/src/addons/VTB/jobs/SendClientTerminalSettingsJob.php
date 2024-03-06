<?php

namespace addons\VTB\jobs;

use addons\edm\models\DictPropertyType;
use addons\edm\models\VTBClientTerminalSettings\VTBClientTerminalSettingsAccount;
use addons\edm\models\VTBClientTerminalSettings\VTBClientTerminalSettingsBankBranch;
use addons\edm\models\VTBClientTerminalSettings\VTBClientTerminalSettingsCustomer;
use addons\edm\models\VTBClientTerminalSettings\VTBClientTerminalSettingsType;
use addons\VTB\models\VTBBankBranch;
use addons\VTB\models\VTBCustomer;
use addons\VTB\models\VTBCustomerAccount;
use common\document\Document;
use common\helpers\DocumentHelper;
use common\models\Terminal;
use common\modules\transport\helpers\DocumentTransportHelper;
use Exception;
use Resque_Job_DontPerform;
use yii\helpers\ArrayHelper;

class SendClientTerminalSettingsJob extends BaseJob
{
    private $customersIds = [];

    public function setUp()
    {
        parent::setUp();

        if (isset($this->args['customerId'])) {
            $customerId = $this->args['customerId'];
            $customer = VTBCustomer::findOne(['customerId' => $customerId]);

            if ($customer === null) {
                throw new Resque_Job_DontPerform("Customer $customerId is not found");
            }
            if (empty($customer->terminalId)) {
                throw new Resque_Job_DontPerform("Terminal id for customer $customerId is not assigned");
            }

            $this->customersIds = [$customerId];
        } else {
            $customers = VTBCustomer::find()
                ->where(['not', ['terminalId' => null]])
                ->andWhere(['not', ['terminalId' => '']])
                ->all();

            if (empty($customers)) {
                throw new Resque_Job_DontPerform('There are no customers with assigned terminal id');
            }

            $this->customersIds = ArrayHelper::getColumn($customers, 'customerId');
        }
    }

    public function perform()
    {
        $bankBranches = VTBBankBranch::find()
            ->orderBy(['branchId' => SORT_ASC])
            ->all();

        foreach ($this->customersIds as $customersId) {
            try {
                $this->sendToClient($customersId, $bankBranches);
            } catch (Exception $exception) {
                $this->log("Processing setting for customer $customersId failed, caused by: $exception", true);
            }
        }
    }

    /**
     * @param integer $customersId
     * @param VTBBankBranch[] $bankBranches
     * @throws \Exception
     */
    private function sendToClient($customersId, $bankBranches)
    {
        $this->log("Sending client terminal settings for VTB customer $customersId...");

        $customer = VTBCustomer::findOne(['customerId' => $customersId]);
        if ($customer === null) {
            throw new Exception("VTB customer with id $customersId is not found");
        }

        $accounts = VTBCustomerAccount::find()
            ->where(['customerId' => $customersId])
            ->orderBy(['number' => SORT_ASC])
            ->all();
        
        // Создать тайп-модель
        $typeModel = $this->createTypeModel($customer, $accounts, $bankBranches);

        $senderTerminal = Terminal::getDefaultTerminal();
        if ($senderTerminal === null) {
            throw new Exception('Default terminal is not found');
        }

        // Создать контекст документа
        $context = DocumentHelper::createDocumentContext(
            $typeModel,
            [
                'type'               => $typeModel->getType(),
                'direction'          => Document::DIRECTION_OUT,
                'origin'             => Document::ORIGIN_SERVICE,
                'terminalId'         => $senderTerminal->id,
                'sender'             => $senderTerminal->terminalId,
                'receiver'           => $customer->terminalId,
                'status'             => Document::STATUS_ACCEPTED,
                'signaturesRequired' => 0,
            ]
        );
        // Если контекст не создался, выбросить исключение
        if ($context === false) {
            throw new Exception('Failed to create document context');
        }
        // Отправить документ на обработку в транспортном уровне
        DocumentTransportHelper::processDocument($context['document'], true);
    }

    /**
     * @param VTBCustomer $customer
     * @param VTBCustomerAccount[] $accounts
     * @param VTBBankBranch[] $bankBranches
     * @return VTBClientTerminalSettingsType
     */
    private function createTypeModel($customer, $accounts, $bankBranches)
    {
        $typeModel = new VTBClientTerminalSettingsType();

        $propertyType = DictPropertyType::findOne(['vtbId' => $customer->propertyTypeId]);

        $typeModel->customer = new VTBClientTerminalSettingsCustomer([
            'id'                             => $customer->customerId,
            'name'                           => $customer->name,
            'type'                           => $customer->type,
            'propertyType'                   => $propertyType !== null ? $propertyType->code : null,
            'kpp'                            => $customer->kpp,
            'inn'                            => $customer->inn,
            'countryCode'                    => $customer->countryCode,
            'addressState'                   => $customer->addressState,
            'addressDistrict'                => $customer->addressDistrict,
            'addressSettlement'              => $customer->addressSettlement,
            'addressStreet'                  => $customer->addressStreet,
            'addressBuilding'                => $customer->addressBuilding,
            'addressBuildingBlock'           => $customer->addressBuildingBlock,
            'addressApartment'               => $customer->addressApartment,
            'internationalName'              => $customer->internationalName,
            'internationalAddressState'      => $customer->internationalAddressState,
            'internationalAddressSettlement' => $customer->internationalAddressSettlement,
            'internationalStreetAddress'     => $customer->internationalStreetAddress,
        ]);

        $typeModel->accounts = array_map(
            function (VTBCustomerAccount $account) {
                return new VTBClientTerminalSettingsAccount([
                    'number'  => $account->number,
                    'bankBik' => $account->bankBik,
                ]);
            },
            $accounts
        );

        $typeModel->bankBranches = array_map(
            function (VTBBankBranch $bankBranch) {
                return new VTBClientTerminalSettingsBankBranch([
                    'branchId'          => $bankBranch->branchId,
                    'bik'               => $bankBranch->bik,
                    'name'              => $bankBranch->name,
                    'fullName'          => $bankBranch->fullName,
                    'internationalName' => $bankBranch->internationalName,
                ]);
            },
            $bankBranches
        );

        return $typeModel;
    }
}

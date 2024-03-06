<?php

namespace addons\SBBOL\jobs;

use addons\edm\models\SBBOLClientTerminalSettings\SBBOLClientTerminalSettingsAccount;
use addons\edm\models\SBBOLClientTerminalSettings\SBBOLClientTerminalSettingsCustomer;
use addons\edm\models\SBBOLClientTerminalSettings\SBBOLClientTerminalSettingsType;
use addons\SBBOL\models\SBBOLCustomerAccount;
use addons\SBBOL\models\SBBOLOrganization;
use common\document\Document;
use common\helpers\DocumentHelper;
use common\models\Terminal;
use common\modules\transport\helpers\DocumentTransportHelper;
use Exception;
use Resque_Job_DontPerform;
use yii\helpers\ArrayHelper;

class SendClientTerminalSettingsJob extends BaseJob
{
    private $organizationsInns = [];

    public function setUp()
    {
        parent::setUp();

        if (isset($this->args['organizationInn'])) {

            $organizationInn = $this->args['organizationInn'];
            $organization = SBBOLOrganization::findOne($organizationInn);

            if ($organization === null) {
                throw new Resque_Job_DontPerform("Organization with INN $organizationInn is not found");
            }

            if (empty($organization->terminalAddress)) {
                throw new Resque_Job_DontPerform("Terminal id for Organization with INN $organizationInn is not assigned");
            }

            $this->organizationsInns = [$organizationInn];
        } else {
            $customers = SBBOLOrganization::find()
                ->where(['not', ['terminalAddress' => null]])
                ->andWhere(['not', ['terminalAddress' => '']])
                ->all();

            if (empty($customers)) {
                throw new Resque_Job_DontPerform('There are no organization with assigned terminal id');
            }

            $this->organizationsInns = ArrayHelper::getColumn($customers, 'id');
        }
    }

    public function perform()
    {
        foreach ($this->organizationsInns as $inn) {
            try {
                $this->sendToClient($inn);
            } catch (Exception $exception) {
                $this->log("Processing setting for organization with INN $inn failed, caused by: $exception", true);
            }
        }
    }

    private function sendToClient($inn)
    {
        $organization = SBBOLOrganization::findOne($inn);

        if ($organization === null) {
            throw new Exception("SBBOL organization with INN $inn is not found");
        }

        $accounts = SBBOLCustomerAccount::find()
            ->joinWith('customer as customer')
            ->where(['customer.inn' => $inn])
            ->orderBy(['number' => SORT_ASC])
            ->all();

        // Создать тайп-модель
        $typeModel = $this->createTypeModel($organization, $accounts);

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
                'receiver'           => $organization->terminalAddress,
                'status'             => Document::STATUS_ACCEPTED,
                'signaturesRequired' => 0,
            ]
        );
        // Если не получен контекст, выбросить исключение
        if ($context === false) {
            throw new Exception('Failed to create document context');
        }

        // Отправить документ на обработку в транспортном уровне
        DocumentTransportHelper::processDocument($context['document'], true);
    }

    private function createTypeModel(SBBOLOrganization $organization, array $accounts)
    {
        $customer = $organization->customers[0];

        $typeModel = new SBBOLClientTerminalSettingsType();

        $typeModel->customer = new SBBOLClientTerminalSettingsCustomer([
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
            'senderName'           => $customer->senderName
        ]);

        $typeModel->accounts = array_map(
            function (SBBOLCustomerAccount $account) {
                return new SBBOLClientTerminalSettingsAccount([
                    'id'         => $account->id,
                    'number'     => $account->number,
                    'bankBik'    => $account->bankBik,
                    'customerId' => $account->customerId,
                ]);
            },
            $accounts
        );

        return $typeModel;
    }
}

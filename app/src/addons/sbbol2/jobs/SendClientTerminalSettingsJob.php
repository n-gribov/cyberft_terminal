<?php

namespace addons\sbbol2\jobs;

use addons\edm\models\Sbbol2ClientTerminalSettings\Sbbol2ClientTerminalSettingsAccount;
use addons\edm\models\Sbbol2ClientTerminalSettings\Sbbol2ClientTerminalSettingsCustomer;
use addons\edm\models\Sbbol2ClientTerminalSettings\Sbbol2ClientTerminalSettingsType;
use addons\sbbol2\jobs\BaseJob;
use addons\sbbol2\models\Sbbol2Customer;
use addons\sbbol2\models\Sbbol2CustomerAccount;
use common\document\Document;
use common\helpers\DocumentHelper;
use common\models\Terminal;
use common\modules\transport\helpers\DocumentTransportHelper;
use Exception;
use Resque_Job_DontPerform;
use yii\helpers\ArrayHelper;

class SendClientTerminalSettingsJob extends BaseJob
{
    private $inns = [];

    public function setUp()
    {
        parent::setUp();

        if (isset($this->args['inn'])) {
            $inn = $this->args['inn'];
            $customer = Sbbol2Customer::findOne(['inn' => $inn]);

            if ($customer === null) {
                throw new Resque_Job_DontPerform("Customer with INN $inn is not found");
            }
            if (empty($customer->terminalAddress)) {
                throw new Resque_Job_DontPerform("Terminal id for customer with INN $inn is not assigned");
            }

            $this->inns = [$inn];
        } else {
            $customers = Sbbol2Customer::find()
                ->where(['not', ['terminalAddress' => null]])
                ->andWhere(['not', ['terminalAddress' => '']])
                ->all();

            if (empty($customers)) {
                throw new Resque_Job_DontPerform('There are no customers with assigned terminal id');
            }

            $this->customersInns = ArrayHelper::getColumn($customers, 'inn');
        }
    }

    public function perform()
    {
        foreach ($this->inns as $inn) {
            try {
                $this->sendToClient($inn);
            } catch (Exception $exception) {
                $this->log("Processing setting for customer $inn failed, caused by: $exception", true);
            }
        }
    }

    /**
     * @param integer $inn
     * @throws \Exception
     */
    private function sendToClient($inn)
    {
        $this->log("Sending client terminal settings for Sbbol2 customer $inn...");

        $customer = Sbbol2Customer::findOne(['inn' => $inn]);
        if ($customer === null) {
            throw new Exception("Sbbol2 customer with id $customer->id is not found");
        }

        $accounts = Sbbol2CustomerAccount::find()
            ->where(['customerId' => $customer->id])
            ->orderBy(['number' => SORT_ASC])
            ->all();

        // Создать тайп-модель
        $typeModel = $this->createTypeModel($customer, $accounts);

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
                'receiver'           => $customer->terminalAddress,
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
     * @param Sbbol2Customer $customer
     * @param Sbbol2CustomerAccount[] $accounts
     * @return Sbbol2ClientTerminalSettingsType
     */
    private function createTypeModel($customer, $accounts)
    {
        $typeModel = new Sbbol2ClientTerminalSettingsType();

        $typeModel->customer = new Sbbol2ClientTerminalSettingsCustomer([
            'id'                             => $customer->id,
            'shortName'                      => $customer->shortName,
            'fullName'                       => $customer->fullName,
            'inn'                            => $customer->inn,
            'kpp'                            => $customer->kpp,
            'ogrn'                           => $customer->ogrn,
            'okato'                          => $customer->okato,
            'okpo'                           => $customer->okpo,
            'orgForm'                        => $customer->orgForm,
            'addressArea'                    => $customer->addressArea,
            'addressBuilding'                => $customer->addressBuilding,
            'addressCity'                    => $customer->addressCity,
            'addressCountryCode'             => $customer->addressCountryCode,
            'addressFlat'                    => $customer->addressFlat,
            'addressHouse'                   => $customer->addressHouse,
            'addressRegion'                  => $customer->addressRegion,
            'addressSettlement'              => $customer->addressSettlement,
            'addressSettlementType'          => $customer->addressSettlementType,
            'addressStreet'                  => $customer->addressStreet,
            'addressZip'                     => $customer->addressZip,
            'terminalAddress'                => $customer->terminalAddress,
        ]);

        $typeModel->accounts = array_map(
            function (Sbbol2CustomerAccount $account) {
                return new Sbbol2ClientTerminalSettingsAccount([
                    'id'           => $account->id,
                    'number'       => $account->number,
                    'bic'          => $account->bic,
                    'currencyCode' => $account->currencyCode,
                    'customerId'   => $account->customerId
                ]);
            },
            $accounts
        );

        return $typeModel;
    }
}

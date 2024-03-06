<?php

use yii\db\Migration;
use addons\edm\models\DictOrganization;
use common\models\Terminal;
use addons\edm\models\EdmPayerAccount;
use addons\edm\models\DictCurrency;
use addons\edm\models\DictBeneficiaryContractor;

class m161213_114332_drop_dict_contactor_table extends Migration
{
    public function up()
    {
        // @EDM Миграция переносит данные из
        // справочника контрагентов в новые справочники Организаций и Получателей
        // После удаляет справочник контрагентов
        // @CYB-3317

        $contactors = Yii::$app->db->createCommand('SELECT * FROM edmDictContractor')->queryAll();

        foreach($contactors as $contactor) {
            // В зависимости от типа контрагента,
            //делаем записи в разные справочники
            if ($contactor['role'] == 'PAYER') {
                // Плательщик
                // Добавление в справочник организации
                $this->addDictOrganization($contactor);
            } else if ($contactor['role'] == 'BENEFICIARY') {
                // Получатель
                // Добавление в справочник получателей
                $this->addDictBeneficiaryContractor($contactor);
            }
        }
    }

    public function down()
    {
        return true;
    }

    /**
     * Метод для добавления контрагента в справочник организаций
     */
    protected function addDictOrganization($item)
    {
        // Допускаем ситуацию,
        // что в старом справочнике контрагентов создавались несколько
        // одинаковых контрагентов плательщиков для того, чтобы добавлять разные счета
        // Поэтому сейчас проверяем наличие совпадений перед записью

        // Ищем совпадения по параметрам - тип, инн, кпп
        $type = $item['type'];
        $inn = $item['inn'];
        $kpp = $item['kpp'];

        $organization = DictOrganization::findOne(['type' => $type, 'inn' => $inn, 'kpp' => $kpp]);

        // Если такая организация еще не создана,
        // записываем её в справочник организаций

        if (!$organization) {
            $organization = new DictOrganization;

            // Имя организации получаем из значения терминала
            $terminal = Terminal::findOne($item['terminalId']);

            // Если терминал найден
            // Получаем его название и записываем его id
            if ($terminal) {
                // Получаем название организации из терминала,
                // в противном случае поставляем terminalId
                $organization->name = empty($terminal->title) ? $terminal->terminalId : $terminal->title;
                $organization->terminalId = $terminal->id;
            }

            $organization->type = $type;
            $organization->inn = $inn;
            $organization->kpp = $kpp;

            /* В этой миграции у DictOrganization еще не созданы доп.поля в таблице,
             * они будут созданы позже в миграциях
             * m170404_142535_org_address,
             * m170704_075050_add_latin_fields_to_edm_dict_organization
             *
             * Чтобы при сохранении здесь не было ошибки, сохранение делается без валидации.
             */

            $organization->save(false);
        }

        // Добавляем записи в справочник Счета организаций
        // Также проверяем наличие счета в справочнике счетов организаций

        $accounts = Yii::$app->db->createCommand('SELECT * FROM edm_account')->queryAll();

        foreach($accounts as $item) {

            // По терминалу счета ищем организацию,
            // которая привязана к тому же терминалу
            $organization = DictOrganization::findOne(['terminalId' => $item['terminalId']]);

            // Если для счета найдена организация, записываем этот счет
            if (!$organization) {
                continue;
            }

            $accountName = $item['title'];
            $accountNumber = $item['number'];
            $bankBik = $item['bankBik'];

            // Проверяем, был ли такой счет ужа добавлен
            $account = EdmPayerAccount::findOne(['organizationId' => $organization->id, 'bankBik' => $bankBik, 'number' => $accountNumber]);

            if ($account) {
                continue;
            }

            // Записываем новый счет
            $account = new EdmPayerAccount;
            $account->name = $accountName;
            $account->organizationId = $organization->id;
            $account->number = $accountNumber;
            $account->bankBik = $bankBik;

            // Т.к. валюта раньше хранилась в текстовом представлении,
            // а теперь в справочнике, то получаем название валюты и ищем её в новом справочнике
            $currencyName = $item['currency'];

            $currency = $this->getDictCurrencyItem($currencyName);

            // Если найдено соответствие валюты, то записываем её для нового счета
            if ($currency) {
                $account->currencyId = $currency->id;
            }

            $account->save();
        }
    }

    /**
     * Метод для добавления контрагента в справочник получателей
     */
    protected function addDictBeneficiaryContractor($item)
    {
        // Создание нового получателя
        $beneficiary = new DictBeneficiaryContractor();
        $beneficiary->name = $item['name'];
        $beneficiary->bankBik = $item['bankBik'];
        $beneficiary->type = $item['type'];
        $beneficiary->inn = $item['inn'];
        $beneficiary->kpp = $item['kpp'];
        $beneficiary->account = $item['account'];
        $beneficiary->terminalId = $item['terminalId'];

        // Т.к. валюта раньше хранилась в текстовом представлении,
        // а теперь в справочнике, то получаем название валюты и ищем её в новом справочнике
        $currencyName = $item['currency'];

        $currency = $this->getDictCurrencyItem($currencyName);

        // Если найдено соответствие валюты, то записываем её для нового счета
        if ($currency) {
            $beneficiary->currencyId = $currency->id;
        }

        $beneficiary->save();
    }


    /**
     * Метод получает элемент
     * справочника валют по наименованию валюты
     * @param $item
     * @return null|static
     */
    protected function getDictCurrencyItem($item)
    {
        return DictCurrency::findOne(['name' => $item]);
    }

}

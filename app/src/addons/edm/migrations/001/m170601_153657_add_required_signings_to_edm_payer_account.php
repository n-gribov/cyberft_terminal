<?php

use yii\db\Migration;
use addons\edm\models\EdmPayerAccount;
use addons\edm\EdmModule;

class m170601_153657_add_required_signings_to_edm_payer_account extends Migration
{
    public function up()
    {
        // Добавление колонки для хранения количества
        // необходимых личных подписей для конкретного счета
        // @CYB-3709
        $this->addColumn('edmPayersAccounts', 'requireSignQty', $this->integer());

        // Для существующих настроек устанавливаем глобальные настройки
        $module = Yii::$app->addon->getModule(EdmModule::SERVICE_ID);

        // Получаем все имеющиеся счета
        $accounts = EdmPayerAccount::find()->all();

        foreach($accounts as $account) {
            $organization = $account->edmDictOrganization;
            $terminal = $organization->terminal;

            // Если не найден терминал, не получаем количество подписей
            if (!isset($terminal->terminalId)) {
                continue;
            }

            // Настройки модуля EDM по терминалу
            $terminalId = $terminal->terminalId;
            $settings = $module->getSettings($terminalId);

            // Устанавливаем количество подписей
            $account->requireSignQty = $settings->signaturesNumber;
            $account->save(false);
        }

    }

    public function down()
    {
        $this->dropColumn('edmPayersAccounts', 'requireSignQty');
    }
}

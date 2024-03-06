<?php

use yii\db\Migration;
use common\settings\SettingsAR;

class m180109_131333_add_common_notification_settings extends Migration
{
    public function up()
    {
        // @CYB-3734
        // Создание настроек общих настроек оповещения (для всех терминалов)
        // из настроек терминала по-умолчанию
        $defaultTerminal = Yii::$app->terminals->getDefaultTerminalId();
        $code = 'monitor:Notification';

        $settings = SettingsAR::findOne(['code' => $code, 'terminalId' => $defaultTerminal]);

        if ($settings) {
            $defaultSettings = new SettingsAR;
            $defaultSettings->code = $settings->code;
            $defaultSettings->data = $settings->data;
            $defaultSettings->terminalId = null;
            $defaultSettings->save();
        }
    }

    public function down()
    {
        return true;
    }
}

<?php

use common\modules\autobot\models\Autobot;
use yii\db\Migration;

/**
 * Добавление поля статуса для ключа контролера
 * @task CYB-3942
 */
class m181005_090409_add_status_to_autobot extends Migration
{
    public function up()
    {
        $this->addColumn('autobot', 'status', $this->string());

        // Все имеющиеся ключи делаем активными
        Autobot::updateAll(['status' => Autobot::STATUS_ACTIVE]);

        // Все основные ключи контролера делаем используемыми для подписания
        Autobot::updateAll(['status' => Autobot::STATUS_USED_FOR_SIGNING], ['primary' => true]);
    }

    public function down()
    {
        $this->dropColumn('autobot', 'status');
        return true;
    }
}

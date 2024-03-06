<?php

use yii\db\Migration;
use common\helpers\RedisHelper;

class m160608_140406_convert_settings_from_redis_to_mysql extends Migration
{
    public function up()
    {
        $module = \Yii::$app->getModule('swiftfin');
        if (empty($module)) {
            return true;
        }

        $redis = \Yii::$app->redis;
        if (empty($redis)) {
            return true;
        }
        $key = RedisHelper::getKeyName('swiftfin');
        $redisSettings = $redis->get($key);
        $redisSettings = unserialize($redisSettings);
        $module->settings->setAttributes($redisSettings);
        // Сохранить настройки в БД
        $module->settings->save();

        return true;
    }

    public function down()
    {
        return true;
    }

}

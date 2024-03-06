<?php

use yii\db\Migration;
use common\helpers\RedisHelper;

class m160609_073329_convert_settings_from_redis_to_mysql extends Migration
{
    public function up()
    {

        $module = \Yii::$app->getModule('finzip');

        if (empty($module)) {
            return true;
        }

        $redis = \Yii::$app->redis;
        if (empty($redis)) {
            return true;
        }

        $key = RedisHelper::getKeyName('finzip');
        $redisSettings = $redis->get($key);
        $redisSettings = unserialize($redisSettings);
        $module->settings->setAttributes($redisSettings);
        $module->settings->save();

        return true;
    }

    public function down()
    {
        return true;
    }

}
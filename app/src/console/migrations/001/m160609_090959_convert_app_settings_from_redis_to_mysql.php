<?php

use common\helpers\RedisHelper;
use yii\db\Migration;

class m160609_090959_convert_app_settings_from_redis_to_mysql extends Migration
{
    public function up()
    {
        $redis = Yii::$app->redis;

        if (empty($redis)) {
            return true;
        }

        $settings = OldSettings::findOne(['code' => 'app']);
        if (empty($settings)) {
            $settings = new OldSettings(['code' => 'app']);
        }

        $key = RedisHelper::getKeyName('app');
        $settings->data = $redis->get($key);
        $settings->save(false);
        
        return true;
    }

    public function down()
    {
        return true;
    }
}

/**
 * Используем класс OldSettings для предотвращения конфликтов с новыми настройками
 */
class OldSettings extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'settings';
    }
}

<?php
namespace common\helpers;

use Yii;

/**
 * Acquire exclusive lock on some resources in multitask environment
 *
 * @author nikolaenko
 */
class Lock
{
    /*
     * @return true if lock acquired or null
     *
     * if lock failed, caller must wait for random time and repeat
     */
    public static function acquire($resourceKeyName, $value, $timeout = 1000)
    {
        return Yii::$app->redis->executeCommand('set', [$resourceKeyName, $value, 'NX', 'PX', $timeout]);
    }

    public static function release($resourceKeyName, $value)
    {
        $result = Yii::$app->redis->executeCommand('get', [$resourceKeyName]);

        if ($result == $value) {
            Yii::$app->redis->executeCommand('del', [$resourceKeyName]);
        }
    }

}
<?php
/**
 * Yii bootstrap file.
 * Used for enhanced IDE code autocompletion.
 */
class Yii extends \yii\BaseYii
{
    /**
     * @var BaseApplication|WebApplication|ConsoleApplication the application instance
     */
    public static $app;
}

spl_autoload_register(['Yii', 'autoload'], true, true);
Yii::$classMap = include(__DIR__ . '/../vendor/yiisoft/yii2/classes.php');
Yii::$container = new yii\di\Container;

/**
 * Class BaseApplication
 * Used for properties that are identical for both WebApplication and ConsoleApplication
 * @property \common\db\RedisConnection $redis
 * @property \common\components\Storage $storage
 * @property \common\components\RbacMenu $menu
 * @property \common\components\Registry $registry
 * @property \common\components\Resque $resque
 * @property \common\components\Terminal\Exchange $exchange
 * @property \common\components\ElasticSearch $elasticsearch
 * @property \common\components\Addon $addon
 * @property \common\components\CommandBus $commandBus
 * @property \common\components\TerminalAccess $terminalAccess
 * @property \common\components\EdmAccountAccess $edmAccountAccess
 * @property \common\components\xmlsec\XMLSec $xmlsec
 */
abstract class BaseApplication extends yii\base\Application {}

/**
 * Class WebApplication
 * Include only Web application related components here
 *
 * @property common\db\RedisConnection $cache
 */
class WebApplication extends yii\web\Application {}

/**
 * Class ConsoleApplication
 * Include only Console application related components here
 */
class ConsoleApplication extends yii\console\Application{}
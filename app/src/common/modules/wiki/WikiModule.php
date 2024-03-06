<?php

namespace common\modules\wiki;

use Yii;
use yii\base\Module;

class WikiModule extends Module
{
    const SERVICE_ID = 'wiki';

//    public function bootstrap($app)
//    {
//        /**
//         * Настройки контроллеров для консоли
//         */
//        if ($app instanceof ConsoleApplication) {
//            $app->controllerMap[$this->id] = [
//                'class'  => 'common\modules\transport\console\DefaultController',
//                'module' => $this,
//            ];
//        }
//    }

    public function init()
    {
        parent::init();

        $this->registerTranslations();
    }

    public function registerTranslations()
    {
        Yii::$app->i18n->translations['modules/wiki/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'basePath' => '@common/modules/wiki/messages',
            'fileMap' => [
                'modules/wiki/default' => 'default.php',
                'modules/wiki/models' => 'models.php'
            ]
        ];
    }

    public static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t('modules/wiki/' . $category, $message, $params, $language);
    }

    public static function info($message)
    {
        Yii::info(static::formatLogMessage($message));
    }

    public static function error($message)
    {
        Yii::error(static::formatLogMessage($message));
    }

    public static function warning($message)
    {
        Yii::warning(static::formatLogMessage($message));
    }

    protected static function formatLogMessage($message)
    {
        return "<Module " . static::SERVICE_ID . "> " . $message;
    }
}
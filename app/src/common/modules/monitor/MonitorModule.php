<?php

namespace common\modules\monitor;

use common\helpers\StringHelper;
use common\modules\monitor\events\BaseEvent;
use ReflectionClass;
use Yii;
use yii\base\BootstrapInterface;
use yii\base\Exception;
use yii\base\InvalidParamException;
use yii\base\Module;
use yii\base\UnknownClassException;
use yii\console\Application as BaseConsoleApplication;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;
use common\modules\monitor\models\CheckerAR;
use common\modules\monitor\models\CheckerSettingsAR;
use function addons\edm\console\findEventClasses;

class MonitorModule extends Module implements BootstrapInterface
{
    public $settings = [];

    public function bootstrap($app)
    {
        /**
         * Настройки контроллеров для консоли
         */
        if ($app instanceof BaseConsoleApplication) {
            $this->controllerNamespace          = 'common\modules\monitor\console';
            Yii::$app->controllerMap[$this->id] = [
                'class'  => 'common\modules\\'.$this->id.'\console\DefaultController',
                'module' => $this,
            ];
        }

        Yii::$app->registry->registerRegularJob(
            'common\modules\monitor\jobs\DispatchCheckers', 10
        );

        // Запуск джоба проверки срока истечения сертификата КриптоПро
        // Раз в сутки
        Yii::$app->registry->registerRegularJob(
            'common\modules\monitor\jobs\CryptoProCertChecker', 86400
        );
    }

    /**
     * @return string
     */
    private function getNamespace()
    {
        $moduleReflection = new ReflectionClass($this->className());

        return $moduleReflection->getNamespaceName();
    }

    public function getCheckers()
    {
        return [
            'documentForSigning',
            'documentProcessError',
            'documentRegistered',
            'failedLogin',
            'cryptoProCertExpired',
            'processingConnection',
            'sftpOpenFailed',
            'changeCertStatus',
            'undeliveredMessage',
            'certsExpired'
        ];
    }

    public function getEvents()
    {
        return [
            'documentForSigning',
            'documentProcessError',
            'documentRegistered',
            'documentStatusChange',
            'failedLogin',
            'stompFailed',
            'sftpOpenFailed',
        ];
    }

    /**
     * Get checker
     *
     * @param string $code Code
     * @return \common\modules\monitor\checkerClassName
     * @throws InvalidParamException
     * @throws UnknownClassException
     */
    public function getChecker($code)
    {
        if (!in_array($code, $this->checkers)) {

            throw new InvalidParamException('Wrong checker code: ' . $code);
        }

        $checkerClassName = $this->getNamespace() . '\checkers\\' . Inflector::classify($code . 'Checker');

        try {
            $checker = new $checkerClassName;
            $checker->loadData();

            return $checker;
        } catch (Exception $ex) {
            throw new UnknownClassException('Cannot create checker class by code ' . $code
                    . "\n" . $ex->getMessage());
        }
    }

    /**
     * Получение списка включенных чекеров
     * @return array
     */
    public function getActiveCheckers()
    {
        $tableCheckersSettings = CheckerSettingsAR::tableName();
        $tableCheckers = CheckerAR::tableName();

        $activeCheckers = CheckerSettingsAR::find()->select("{$tableCheckers}.code")
            ->innerJoin($tableCheckers, "{$tableCheckers}.id = {$tableCheckersSettings}.checkerId")
            ->where(["{$tableCheckersSettings}.active" => 1])
            ->asArray()
            ->distinct()
            ->all();

        return ArrayHelper::getColumn($activeCheckers, 'code');
    }

    public function findEventClasses(): array
    {
        $namespaces = ['common\events\\'];
        foreach (Yii::$app->modules as $moduleId => $module) {
            if (is_array($module)) {
                $module = Yii::$app->getModule($moduleId);
            }
            $reflection = new \ReflectionClass($module);
            $namespaces[] = $reflection->getNamespaceName() . '\events\\';
        }

        return array_reduce(
            $namespaces,
            function (array $classes, string $namespace): array {
                return array_merge($classes, $this->findEventClassesInNameSpace($namespace));
            },
            []
        );
    }

    private function findEventClassesInNameSpace($namespace): array
    {
        $directory = Yii::getAlias('@project/') . str_replace('\\', '/', $namespace);
        if (!is_dir($directory)) {
            return [];
        }

        $classes = [];
        foreach (scandir($directory) as $fileName) {
            if ($fileName === '.' || $fileName === '..') {
                continue;
            } elseif (is_dir("$directory$fileName")) {
                $classes = array_merge($classes, $this->findEventClassesInNameSpace("$namespace$fileName\\"));
            } elseif (StringHelper::endsWith($fileName, '.php')) {
                $className = $namespace . str_replace('.php', '', $fileName);
                $reflection = new \ReflectionClass($className);
                if (!$reflection->isAbstract() && $reflection->isSubclassOf(BaseEvent::class)) {
                    $classes[] = $className;
                }
            }
        }
        return $classes;
    }
}

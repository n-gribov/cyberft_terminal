<?php

namespace console\controllers;

use common\components\storage\Resource;
use common\models\Terminal;
use Exception;
use Yii;
use yii\console\Controller;
use yii\helpers\Console;
use common\helpers\FileHelper;

/**
 * Base system command
 * @property array $setup - настройки текущей установки
 */
class AppController extends Controller
{
    protected $_setup;

    public function init()
    {
        parent::init();
        $this->color = true;
    }

    public function actions()
    {
        $actions = parent::actions();
        $actions['export-data'] = 'console\controllers\actions\ExportDataAction';
        $actions['import-data'] = 'console\controllers\actions\ImportDataAction';

        return $actions;
    }

    /**
     * Метод выводит текст подсказки
     */
    public function actionIndex()
    {
        $this->run('/help', ['app']);
        return Controller::EXIT_CODE_NORMAL;
    }

    /**
     * Delete redis keys by pattern
     * @param $pattern
     */
    public function actionDeleteRedisKeys($pattern)
    {
        passthru("redis-cli keys \"{$pattern}\" | xargs -L1 -I '$' echo '\"$\"' | xargs redis-cli del");
    }

    /**
     * After update application
     */
    public function actionUpdate()
    {
        $this->output(PHP_EOL . '****** Applying DB migrations ******', Console::FG_GREEN);

        $migrationPath = Yii::getAlias('@console/migrations');
        $handle = opendir($migrationPath);

        $subDirs = [];
        while($entry = readdir($handle)) {
            if ($entry == '.' || $entry == '..') {
                continue;
            }

            if (is_dir($migrationPath . '/' . $entry)) {
                $subDirs[] = $entry;
            }
        }

        sort($subDirs);
        $subDirs[] = null; // Последними применяются миграции вне поддиректорий

        foreach($subDirs as $dir) {

            echo 'Applying group /' . $dir . "\n";

            $this->run('migrate/up', [
                'migrationPath' => $migrationPath . '/' . $dir,
                'interactive' => 0
            ]);

            static::actionMigrateUpAddons($dir);
        }

        $this->output(PHP_EOL . '****** Initializing resources ******', Console::FG_GREEN);
        $this->actionInitResources();

        $this->output(PHP_EOL . '****** Flushing assets ******', Console::FG_GREEN);
        $this->run('app/flush-assets');

        $this->output(PHP_EOL . '****** Compressing assets ******', Console::FG_GREEN);
        $this->run('asset/compress', [
            Yii::getAlias('@common/config/assets-config.php'), Yii::getAlias('@common/config/assets-prod.php')
        ]);

	return Controller::EXIT_CODE_NORMAL;
    }

    /**
     * Use migration addon
     */
    public function actionMigrateUpAddons($dir)
    {
        $addonsPath = Yii::getAlias('@addons');
        $migratePath = '/migrations/';

        foreach (array_keys(Yii::$app->addon->getRegisteredAddons()) as $addon) {
            Yii::$app->db->schema->refresh();
            $addonMigrationPath = $addonsPath . '/' . $addon . $migratePath . '/' . $dir;
            if (is_dir($addonMigrationPath)) {
                $this->output(PHP_EOL . 'Migrating addon ' . $addon . '/' . $dir, Console::FG_YELLOW);
                $this->run('migrate/up', [
                    'migrationPath' => $addonMigrationPath,
                    'interactive' => 0
                ]);
            }
        }
    }

    /**
     * Before remove application
     */
    public function actionBeforeRemove()
    {
	$this->run('user/rbac-purge');
        return Controller::EXIT_CODE_NORMAL;
    }

    /**
     * Init storage
     */
    public function actionInitResources()
    {
        $dirs = [
            '@logs', // Логи приложения
            '@import', // Хранилище документов для импорта внутрь Терминала
            '@export', // Хранилище документов для экспорта из Терминала
            '@cftcp',
            '@storage', // Хранилище документов, ключей и сертификатов внутри Терминала
            '@temp', // Каталог для временных файлов. Имеет динамически создаваемую вложенную структуру.
            '@userKeyStorage', // Каталог для пользовательских ключей
            '@temp/cert'
        ];

        foreach ($dirs as $dir) {
            $path = Yii::getAlias($dir);
            FileHelper::createDirectory($path, 0755, true);
            chown($path, 'www-data');
            chgrp($path, 'www-data');
        }

        $resources = Yii::$app->registry->getResources();

        foreach (array_keys($resources) as $serviceId) {
            $this->output('\\' . $serviceId);

            foreach($resources[$serviceId] as $resourceType => $resourceId) {
                $this->output('   |--\\' . $resourceType);

                foreach ($resourceId as $resourceKey => $resourceConfig) {
                    try {
                        $resource = new Resource($resourceConfig);
                        $this->stdout('   |  |-- ' . $resourceKey);
                        $path = $resource->getPath();
                        if (!is_dir($path)) {
                            if (!FileHelper::createDirectory($path, $resource->permissions,
                                true, $resource->getUseSftp())) {
                                throw new Exception('Error while processing directory');
                            }

                            if ($resource->getUseSftp()) {
                                $resource->chmod($path, $resource->permissions);
                            } else {
                                chown($path, 'www-data');
                                chgrp($path, 'www-data');
                            }
                        }
                        $this->output(' - OK', Console::FG_YELLOW);
                    } catch(Exception $ex) {
                        $this->error(' - ERROR' . PHP_EOL . $ex->getMessage());
                    }
                }

            }
        }

        return Controller::EXIT_CODE_NORMAL;
    }

    public function actionSetTerminalId($terminalId = '')
    {
        if (empty($terminalId)) {
            $terminalId  = $this->prompt('Enter terminal address:', [
                'required' => true,
                'pattern' => '/^[a-zA-Z@0-9]{12}$/'
            ]);
        }

        $model = new Terminal([
            'terminalId' => $terminalId,
            'isDefault' => true
        ]);

        if (!$model->save()) {
            $this->error('Could not set terminal id:'
                . PHP_EOL . ' * '. implode(PHP_EOL . ' * ', $model->getFirstErrors())
            );

            return Controller::EXIT_CODE_ERROR;
        }

        return Controller::EXIT_CODE_NORMAL;
    }

    public function actionConfig($dataFilePath = '')
    {
        if (file_exists($dataFilePath)) {
            $predefinedData = json_decode(file_get_contents($dataFilePath), true);
        }

        $this->output("Configuring application", Console::FG_GREEN);
        $terminalId = !empty($predefinedData['terminalId'])
                ? $predefinedData['terminalId']
                : null
        ;
        if (Controller::EXIT_CODE_NORMAL != $this->actionSetTerminalId($terminalId)) {
            return Controller::EXIT_CODE_ERROR;
        }

        $this->output('Creating administrator account', Console::FG_YELLOW);
        $adminEmail = empty($predefinedData['adminEmail'])
                ? $this->prompt('Email:', ['required' => true])
                : $predefinedData['adminEmail']
            ;

        $adminPassword = empty($predefinedData['adminPassword'])
                ? $this->prompt('Password:', ['required' => true])
                : $predefinedData['adminPassword']
            ;

        if (Controller::EXIT_CODE_NORMAL != $this->run('user/add-admin', [$adminEmail, $adminPassword])) {
            return Controller::EXIT_CODE_ERROR;
        }

        if (
           empty($predefinedData['noSecurityOfficers'])
           && $this->confirm(PHP_EOL . PHP_EOL . 'Activate security officers application mode?', false)
        ) {
            $this->output('Creating LSO account', Console::FG_YELLOW);
            $lsoEmail= empty($predefinedData['lsoEmail'])
                    ? $this->prompt('Email:', ['required' => true])
                    : empty($predefinedData['lsoEmail'])
                ;
            $lsoPassword = empty($predefinedData['lsoPassword'])
                    ? $this->prompt('Password:', ['required' => true])
                    : $predefinedData['lsoPassword']
                ;
            if (Controller::EXIT_CODE_NORMAL !=
                    $this->run('user/add-secure-officer', ['lso', $lsoEmail, $lsoPassword])
            ) {
                return Controller::EXIT_CODE_ERROR;
            }

            $this->output('Creating RSO account', Console::FG_YELLOW);
            $rsoEmail= empty($predefinedData['rsoEmail'])
                    ? $this->prompt('Email:', ['required' => true])
                    : empty($predefinedData['rsoEmail'])
                ;
            $rsoPassword = empty($predefinedData['rsoPassword'])
                    ? $this->prompt('Password:', ['required' => true])
                    : $predefinedData['rsoPassword']
                ;
            if (Controller::EXIT_CODE_NORMAL !=
                    $this->run('user/add-secure-officer', ['rso', $rsoEmail, $rsoPassword])
            ) {
                return Controller::EXIT_CODE_ERROR;
            }
        }
    }

    /**
     * Delete assets from assets directory
     */
    public function actionFlushAssets()
    {
        $path = Yii::getAlias('@backend/web/assets');
        $files = glob($path . '/all-*');

        foreach($files as $file) {
            unlink($file);
        }

        $this->output('Flush assets finished', Console::FG_GREEN);
    }

    protected function output($string)
    {
        $args = func_get_args();
        $string .= PHP_EOL;
        array_shift($args);
        $string = Console::ansiFormat($string, $args);

        return Console::stdout($string);
    }

    protected function error($message)
    {
        $this->output($message, Console::FG_RED);
    }
}

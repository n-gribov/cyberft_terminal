<?php
namespace common\components;

use Yii;
use yii\base\Component;
use common\base\DynamicModel;

class Addon extends Component implements \yii\base\BootstrapInterface
{
	protected $_addons = [];

	public function getRegisteredAddons()
    {
        return $this->_addons;
    }

    public function bootstrap($app)
	{
		$this->scanDirs();

		/**
		 * Настройки контроллеров для консоли
		 */
        if ($app instanceof \yii\console\Application) {
			foreach ($this->_addons as $serviceId => $module) {
				$module->registerConsoleControllers($app);
			}
        }
	}

	private function scanDirs()
	{
		$dir = Yii::getAlias('@addons');
		$handle = opendir($dir);
		$dirList = [];
        while($entry = readdir($handle)) {
			if ($entry == '.' || $entry == '..') {
				continue;
			}

			$path = $dir . '/' . $entry;
			if (is_dir($path)) {
				// ищем новый config-файл
				$propsFile = $path . '/config/properties.php';
				if (is_file($propsFile)) {
					$dirList[$entry] = new DynamicModel(
						require($propsFile)
					);
				} else {
					$dirList[$entry] = false;
				}
            }
        }
        closedir($handle);

        ksort($dirList);

		foreach($dirList as $addon => $config) {
			if ($config) {
                $this->registerAddon($addon, $config);
			}
		}
	}

	protected function registerAddon($addon, $config)
	{
        $moduleClass = $config->class;
		Yii::$app->setModule($addon, ['class' => $moduleClass]);

		$module = Yii::$app->getModule($config->serviceName);
		$this->_addons[$config->serviceName] = $module;
		$module->setUp($config);

		if (!empty($config->resources)) {
			foreach($config->resources as $type => $resData) {
				$this->registerResources($config->serviceName, $type, $resData);
			}
		}

		if (!empty($config->docTypes)) {
			foreach($config->docTypes as $docType => $docTypeData) {
                $docTypeData['module'] = $config->serviceName;
				Yii::$app->registry->registerType(
					$docType,
					$docTypeData
				);
			}
		}

		if (!empty($config->menu)) {
			Yii::$app->registry->registerMenuItems(
				$config->serviceName,
				$config->menu
			);
		}

		if (!empty($config->regularJobs)) {
			foreach($config->regularJobs as $jobData) {
				if (!isset($jobData['descriptor'])) {
					$jobData['descriptor'] = 'default';
				}

                if (!isset($jobData['params'])) {
					$jobData['params'] = null;
				}

				Yii::$app->registry->registerRegularJob(
						$jobData['class'], $jobData['interval'],
						$jobData['params'], $jobData['descriptor']
                );
			}
		}

		if (!empty($config->components)) {
            foreach($config->components as $id => $definition) {
                $module->set($id, $definition);
            }
        }

        $module->set('settings', function () use ($moduleClass) {
            return Yii::$app->settings->get($moduleClass::SETTINGS_CODE);
        });
	}

	protected function registerResources($serviceName, $type, $resource)
	{
		if (!isset($resource['dirs'])) {
			$resource['dirs'] = [];
		}

        if ($serviceName == 'ISO20022') {
            $module = Yii::$app->getModule('ISO20022');
            $settings = $module->getSettings();
            if ($settings->sftpEnable) {
                $sftpParams = [
                    'host' => $settings->sftpHost,
                    'port' => $settings->sftpPort,
                    'path' => $settings->sftpPath,
                    'username' => $settings->sftpUser,
                    'password' => $settings->sftpPassword,
                ];
            }
        }

		switch($type) {
			case 'storage':
				Yii::$app->registry->registerStorageResources(
						$serviceName, $resource['path'], $resource['dirs']);
				break;
			case 'import':
                Yii::$app->registry->registerImportResources(
						$serviceName, $resource['path'], $resource['dirs'],
                        (isset($sftpParams) ? $sftpParams : null));
				break;
			case 'export':
				Yii::$app->registry->registerExportResources(
						$serviceName, $resource['path'], $resource['dirs'],
                        (isset($sftpParams) ? $sftpParams : null));
				break;
			case 'temp':
				Yii::$app->registry->registerTempResources(
						$serviceName, $resource['path'], $resource['dirs']);
				break;
		}
	}

	/**
	 * Возвращает экземпляр класса модуля блока
	 *
	 * @param string $serviceId
	 * @return \yii\base\Module
	 */
	public function getModule($serviceId)
    {
		if (array_key_exists($serviceId, $this->_addons)) {
			return $this->_addons[$serviceId];
		}

		return null;
	}


}

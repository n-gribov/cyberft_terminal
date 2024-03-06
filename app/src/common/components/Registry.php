<?php
namespace common\components;

use Yii;
use yii\helpers\ArrayHelper;
use yii\base\Component;
use common\components\storage\Resource;

class Registry extends Component
{
	const DEFAULT_RESOURCE_ID = 'default';

	const REDIS_PREFIX = 'cyberft';
	const REDIS_REGULAR_JOBS = 'regularJobs';

	protected $_types;
	protected $_autoprocesses;
	protected $_resourcesTemp;
	protected $_resourcesStorage;
	protected $_resourcesExport;
	protected $_resourcesImport;
	protected $_regularJobs;

	public function init()
	{
		parent::init();
		$this->_resourcesTemp = [];
		$this->_resourcesStorage = [];
		$this->_resourcesExport = [];
		$this->_resourcesImport = [];
	}

	/**
	 * Прописывает параметры типа в реестр
	 *
	 * @param string $type
	 * @param array $params массив параметров типа
	 * [
	 *		'module'			=> <module id>
	 *		'contentClass'	=> <CyberXMLDocumentContent implementation class name>
	 * ]
	 */
	public function registerType($type, $params)
	{
		$this->_types[$type] = $params;
	}

	public function registerTypes($types)
	{
		foreach ($types as $type => $params) {
			$this->registerType($type, $params);
		}
	}

    public function getDefaultResourceId()
    {
        return self::DEFAULT_RESOURCE_ID;
    }

    public function getResources($resourceId = null)
    {
        $resources = [];

        if ( $resourceId && array_key_exists($resourceId, $this->_resourcesTemp) ) {
            $resources['temp'] = $this->_resourcesTemp[$resourceId];
        } else {
            $resources['temp'] = $this->_resourcesTemp;
        }

        if ( $resourceId && array_key_exists($resourceId, $this->_resourcesStorage) ) {
            $resources['storage'] = $this->_resourcesStorage[$resourceId];
        } else {
            $resources['storage'] = $this->_resourcesStorage;
        }

        if ( $resourceId && array_key_exists($resourceId, $this->_resourcesExport) ) {
            $resources['export'] = $this->_resourcesExport[$resourceId];
        } else {
            $resources['export'] = $this->_resourcesExport;
        }

        if ( $resourceId && array_key_exists($resourceId, $this->_resourcesImport) ) {
            $resources['import'] = $this->_resourcesImport[$resourceId];
        } else {
            $resources['import'] = $this->_resourcesImport;
        }

        return $resources;
    }

	public function getTypeModule($type, $typeGroup = null)
	{
        $moduleId = null;

        if ($typeGroup) {
            $name = $typeGroup . ':' . $type;
            if (
                array_key_exists($name, $this->_types)
                && !empty($this->_types[$name]['module'])
            ) {
                $moduleId = $this->_types[$name]['module'];
            }
        }

		if (
			!$moduleId && array_key_exists($type, $this->_types)
			&& !empty($this->_types[$type]['module'])
		) {
            $moduleId = $this->_types[$type]['module'];
        }

		if ($moduleId) {
            $module = \Yii::$app->getModule($moduleId);

            if (empty($module)) {
                \Yii::error('Could not find module ' . $moduleId);
                throw new \Exception('Could not find module: ' . $moduleId);
            }

            return $module;
        }

		return null;
	}

	/**
	 * Функция возвращает вектор типов документов, зарегистрированных модулем
	 * @param string $moduleServiceId ID модуля-сервиса
	 * @return array Массив, чьи ключи - имена типов, зарегистрированных модулем
	 */
	public function getModuleTypes($moduleServiceId)
	{
		$docTypesArray = [];

		foreach($this->_types as $type => $registry) {
			if (array_key_exists('module', $registry) && $moduleServiceId === $registry['module']) {
				$docTypesArray[] = $type;
			}
		}

		return array_flip($docTypesArray);
	}

	public function getRegister()
	{
		return $this->_types;
	}

    /**
     * Register temp resource
     *
     * @param string  $serviceId     Resource service ID
     * @param string  $path          Resource path
     * @param array   $dirs          Resource directory
     * @return boolean
     */
	public function registerTempResources($serviceId, $path, $dirs = [])
	{
        // temp resources must ignore sftp settings
		return $this->registerResources($this->_resourcesTemp, $serviceId, $path, $dirs, 'temp');
	}

    /**
     * Register storage resource
     *
     * @param string  $serviceId     Resource service ID
     * @param string  $path          Resource path
     * @param array   $dirs          Resource directory
     * @param boolean $settingsSftp  Resource SFTP settings
     * @return boolean
     */
	public function registerStorageResources($serviceId, $path, $dirs = [], $settingsSftp = null)
	{
		return $this->registerResources($this->_resourcesStorage, $serviceId, $path, $dirs, 'storage', $settingsSftp);
	}

    /**
     * Register export resource
     *
     * @param string  $serviceId     Resource service ID
     * @param string  $path          Resource path
     * @param array   $dirs          Resource directory
     * @param boolean $settingsSftp  Resource SFTP settings
     * @return boolean
     */
	public function registerExportResources($serviceId, $path, $dirs = [], $settingsSftp = null)
	{
		return $this->registerResources($this->_resourcesExport, $serviceId, $path, $dirs, 'export', $settingsSftp);
	}

    /**
     * Register import resource
     *
     * @param string  $serviceId     Resource service ID
     * @param string  $path          Resource path
     * @param array   $dirs          Resource directory
     * @param boolean $settingsSftp  Resource SFTP settings
     * @return boolean
     */
	public function registerImportResources($serviceId, $path, $dirs = [], $settingsSftp = null)
	{
		return $this->registerResources($this->_resourcesImport, $serviceId, $path, $dirs, 'import', $settingsSftp);
	}

	/**
	 * Register resource
     *
     * @param array   $resourceArray List of resources
	 * @param string  $serviceId     Resource service ID
	 * @param string  $path          Resource path
	 * @param array   $dirs          Resource directory
     * @param string  $type          Resource type
     * @param arrqay  $settingsSftp  SFTP settings
     * @return boolean
	 */
	private function registerResources(& $resourceArray, $serviceId, $path, $dirs = [], $type='undefined', $settingsSftp = null)
	{

		if (empty($dirs)) {
			$dirs = [
				self::DEFAULT_RESOURCE_ID => []
			];
		}

		$result = true;

		foreach($dirs as $dirId => $dir) {
            
			if (!is_array($dir)) {
				$dirId = $dir;
				$dir = ['directory'	=> $dir];
			}

            $adapterOptions = ['servicePath' => $path];

            $ignoreSftp = isset($dir['ignoreSftp']) ? $dir['ignoreSftp'] : false;

            if (!$ignoreSftp && !is_null($settingsSftp)) {
                $adapterOptions['type'] = $type;
                $adapterOptions['serviceId'] = $serviceId;
                $adapterOptions['settingsSftp'] = $settingsSftp;
            }

            if (isset($dir['adapterClass'])) {
                $adapterClass = $dir['adapterClass'];
                unset($dir['adapterClass']);
            } else {
                $adapterClass = null;
            }

            $adapterOptions = ArrayHelper::merge(
                    $adapterOptions,
                    $dir
            );

            //$resource = new Resource($resourceSettings);

            $resourceArray[$serviceId][$dirId] = [
                'type'        => $type,
                'serviceId'   => $serviceId,
                'id'          => $dirId,
                'adapterOptions' => $adapterOptions,
                'adapterClass' => $adapterClass
            ];

		}

		return $result;
	}

	/**
	 * @param        $serviceId
	 * @param string $dirId
	 * @return null|Resource
	 */
	public function getTempResource($serviceId, $dirId = self::DEFAULT_RESOURCE_ID)
	{
		return $this->getResource($this->_resourcesTemp, $serviceId, $dirId);
	}

	/**
	 * @param        $serviceId
	 * @param string $dirId
	 * @return null|Resource
	 */
	public function getStorageResource($serviceId, $dirId = self::DEFAULT_RESOURCE_ID)
	{
		return $this->getResource($this->_resourcesStorage, $serviceId, $dirId);
	}

	/**
	 * @param        $serviceId
	 * @param string $dirId
	 * @return null|Resource
	 */
	public function getExportResource($serviceId, $dirId = self::DEFAULT_RESOURCE_ID)
	{
		return $this->getResource($this->_resourcesExport, $serviceId, $dirId);
	}

    public function getTerminalExportResource($serviceId, $terminalAddress, $dirId = self::DEFAULT_RESOURCE_ID)
    {
        $resourceSettings = $this->getResourceSettings($this->_resourcesExport, $serviceId, $dirId);
        if (!$resourceSettings) {
            return null;
        }

        $terminalResourceSettings = $this->addTerminalAddressToExportPath($resourceSettings, $terminalAddress);

        return new Resource($terminalResourceSettings);
	}

    private function addTerminalAddressToExportPath($resourceSettings, $terminalAddress)
    {
        $insertTerminalAddress = function ($path, $prefix, $terminalAddress) {
            $isSupportedPath = $path === $prefix || strpos($path, "$prefix/") === 0;
            if (!$isSupportedPath) {
                return null;
            }
            return substr_replace($path, "$prefix/$terminalAddress", 0, strlen($prefix));
        };

        $globalPath = $resourceSettings['adapterOptions']['servicePath'];
        $terminalPath = $insertTerminalAddress($globalPath, '@export', $terminalAddress)
            ?: $insertTerminalAddress($globalPath, Yii::getAlias('@export'), $terminalAddress);

        if (!$terminalPath) {
            Yii::warning('Cannot add terminal address to export path, export resource path must start with @export alias or path equal to resolved @export alias');
            return $resourceSettings;
        }

        $resourceSettings['adapterOptions']['servicePath'] = $terminalPath;
        return $resourceSettings;
	}

	/**
	 * @param        $serviceId
	 * @param string $dirId
	 * @return Resource|null
	 */
	public function getImportResource($serviceId, $dirId = self::DEFAULT_RESOURCE_ID)
	{
		return $this->getResource($this->_resourcesImport, $serviceId, $dirId);
	}

	/**
	 * @param array $resourceArray
	 * @param string $serviceId
	 * @param string $dirId
	 * @return Resource|null
	 */
	private function getResource($resourceArray, $serviceId, $dirId = self::DEFAULT_RESOURCE_ID)
    {
		$resourceSettings = $this->getResourceSettings($resourceArray, $serviceId, $dirId);
		return $resourceSettings
            ? new Resource($resourceSettings)
            : null;
	}

    private function getResourceSettings($resourceArray, $serviceId, $dirId)
    {
        if (
            array_key_exists($serviceId, $resourceArray)
            && array_key_exists($dirId, $resourceArray[$serviceId])
        ) {
            return $resourceArray[$serviceId][$dirId];
        }

        return null;
	}

	public function registerMenuItems($serviceId, $config)
	{
		if (isset(Yii::$app->getComponents()['menu'])) {
			Yii::$app->menu->addItem($serviceId, $config);
		}
	}

	/**
	 * Регистрация повторяющихся джобов
	 *
	 * @param type $job
	 * @param type $interval
	 * @param type $args
	 * @param type $descriptor - идентифицирующий ключ для блокирующих задач,
	 * в которых используются аргументы в качестве параметра блокировки
	 * @param type $ttl
	 */
	public function registerRegularJob($job, $interval, $args = null, $descriptor = 'default', $ttl = null)
	{
		$this->_regularJobs[$job][$descriptor] = [
			'job'	=> $job,
			'interval'	=> $interval,
			'args'	=> !empty($args) ? $args : [],
			'ttl'	=> $ttl
		];
	}

	public function getRegularJobs()
	{
		return $this->_regularJobs;
	}

	public function storeRegularJobData($job, $descriptor, $data)
	{
		$default = [
			'dateStart'	=> time(),
			'token'	=> '',
			'ttl'	=> '',
		];

		$data = ArrayHelper::merge($default, $data);

		Yii::$app->redis->hset(
			self::REDIS_PREFIX . ':' . self::REDIS_REGULAR_JOBS,
			"{$job}:{$descriptor}", json_encode($data)
		);
	}

	public function getRegularJobData($job, $descriptor)
	{
		$default = [
			'dateStart'	=> '',
			'token'	=> '',
			'ttl'	=> '',
		];

		$value = Yii::$app->redis->hget(
			self::REDIS_PREFIX . ':' . self::REDIS_REGULAR_JOBS,
			"{$job}:{$descriptor}"
		);
		$value = json_decode($value, true);
		if (empty($value)) {
			$value = [];
		}

		return ArrayHelper::merge($default, $value);
	}

	/**
	 * Функция возвращает значение именованного атрибута, который был ассоциирован
	 * с типом в момент его регистрации модулем.
	 * @param string $type
	 * @param string $attrName
	 * @return mixed|null
	 */
    public function getTypeRegisteredAttribute($type, $typeGroup, $attrName)
    {
        if ($typeGroup) {
            $name = $typeGroup . ':' . $type;
            if (
                array_key_exists($name, $this->_types)
                && !empty($this->_types[$name][$attrName])
            ) {
                return $this->_types[$name][$attrName];
            }
        }

        if (
            array_key_exists($type, $this->_types)
            && !empty($this->_types[$type][$attrName])
        ) {
            return $this->_types[$type][$attrName];
        }

        return null;
    }

    public function getTypeExtModelClass($type, $typeGroup = null)
    {
        return $this->getTypeRegisteredAttribute($type, $typeGroup, 'extModelClass');
    }

    public function getTypeContentClass($type, $typeGroup = null)
    {
        return $this->getTypeRegisteredAttribute($type, $typeGroup, 'contentClass');
    }

    public function getTypeModelClass($type, $typeGroup = null)
    {
        return $this->getTypeRegisteredAttribute($type, $typeGroup, 'typeModelClass');
    }
}
<?php

namespace common\components\storage;

use common\helpers\FileHelper;
use yii\base\BaseObject;

/**
 * Storage resource class
 */
class Resource extends BaseObject
{
    public $adapterOptions;
    public $adapterClass;
    private $_adapter;

    public $id;

    /**
     * @var string $serviceId Service ID
     */
	public $serviceId;

	/**
	 * @var string $type Type
	 */
	public $type = 'undefined';

    public function __construct($config = [])
    {
        parent::__construct($config);
    }

    public function init()
    {
        if (!empty($this->adapterOptions['settingsSftp'])) {
            $this->_adapter = new SftpAdapter($this->adapterOptions);
        } else if (!empty($this->adapterClass)) {
            $this->_adapter = new $this->adapterClass($this->adapterOptions);
        } else {
            $this->_adapter = new PartitionedAdapter($this->adapterOptions);
        }
    }

    /**
	 * Get path
	 *
	 * @return string
	 */
	public function getPath()
	{
        return $this->_adapter->getPath();
	}

	/**
	 * Save file into storage
	 *
	 * @param string $path     File path
	 * @param string $filename File name
	 * @return boolean|string Return false if error, fileinfo array if success
	 */
	public function putFile($path, $filename = '')
	{
        $fileInfo = $this->_adapter->putFile($path, $filename);

        if (!empty($fileInfo)) {
            $this->changePermissions($fileInfo['path']);
        }

        $fileInfo['serviceId'] = $this->serviceId;
        $fileInfo['resourceId'] = $this->id;

		return $fileInfo;
	}

	/**
	 * Save data into storage
	 *
	 * @param string $data     Data to save
	 * @param string $filename File name
	 * @return boolean|string Return FALSE if error, path if success
	 */
	public function putData($data, $filename = '')
	{
        $fileInfo = $this->_adapter->putData($data, $filename);

        if (!empty($fileInfo)) {
            $this->changePermissions($fileInfo['path']);
        }
        $fileInfo['resourceType'] = $this->type;
        $fileInfo['serviceId'] = $this->serviceId;
        $fileInfo['resourceId'] = $this->id;

		return $fileInfo;
	}

	/**
	 * Save stream into storage
	 *
	 * @param stream $readStream Stream to save
	 * @param string $filename   File name
	 * @return string Storage path
	 */
	public function putStream($readStream, $filename = '')
	{
        $fileInfo = $this->_adapter->putStream($readStream, $filename);

        if (!empty($fileInfo)) {
            $this->changePermissions($fileInfo['path']);
        }

        $fileInfo['serviceId'] = $this->serviceId;
        $fileInfo['resourceId'] = $this->id;

        return $fileInfo;
	}

    public function getDirSubfolders($dir, $fullPath = true)
    {
        return $this->_adapter->getDirSubfolders($dir, $fullPath);
    }

	/**
	 * Проверяет, валиден ли указанный путь для данного ресурса
	 * @param string $path
	 * @return boolean
	 */
	public function checkPath($path)
	{
        return $this->_adapter->checkPath($path);
	}

	/**
	 * Содержимое папки ресурса
	 * @return array
	 */
    public function getContents($path = null)
	{
		return $this->_adapter->getContents($path);
	}

	/**
	 * Delete old files
	 *
	 * @param integer $maxDays Maximum lifetime
	 */
	public function deleteOldFiles($maxDays)
	{
		return $this->_adapter->deleteOldFiles($maxDays);
	}

	private function changePermissions($path)
	{
        switch ($this->type) {
            case 'temp':
                $permissions = 0777;
                break;
            default :
                $permissions = $this->_adapter->permissions;
                break;
        }

        $this->chmod($path, $permissions);
	}

    public function chmod($path, $permissions)
    {
        return $this->_adapter->chmod($path, $permissions);
    }

    public function createDir($dirName)
    {
        return $this->_adapter->createDir($dirName);
    }

    public function copyFile($file, $destinationDirPathName = null)
    {
        if (is_null($destinationDirPathName)) {
            $destinationDirPath = $this->path;
        } else {
            $destinationDirPath = $this->path . '/' . $destinationDirPathName;
        }

        if (!is_dir($destinationDirPath)) {
            if (!$this->createDir($destinationDirPathName)) {
                return false;
            }
        }

        $outPath = $destinationDirPath . '/' . FileHelper::mb_basename($file);
        if (copy($file, $outPath)) {
            return $outPath;
        } else {
            return false;
        }
    }

    public function moveFile($file, $destinationDirPathName = null)
    {
        if (is_null($destinationDirPathName)) {
            $destinationDirPath = $this->path;
        } else {
            $destinationDirPath = $this->path . '/' . $destinationDirPathName;
        }

        if (!is_dir($destinationDirPath)) {
            if (!$this->createDir($destinationDirPathName)) {
                return false;
            }
        }

        $outPath = $destinationDirPath . '/' . FileHelper::mb_basename($file);
        if (rename($file, $outPath)) {
            return $outPath;
        } else {
            return false;
        }
    }

    public function updateData($relPath, $data)
    {
        return $this->_adapter->updateData($relPath, $data);
    }

    public function getPermissions()
    {
        return $this->_adapter->permissions;
    }

    public function getServicePath()
    {
        return $this->_adapter->servicePath;
    }

    public function getUseSftp()
    {
        return !empty($this->adapterOptions['settingsSftp']);
    }

    public function getUsePartition()
    {
        return $this->_adapter->usePartition;
    }

    public function isArchivable($relPath = null)
    {
        return $this->_adapter->isArchivable($relPath);
    }

    public function archive($relPath)
    {
        return $this->_adapter->archive($relPath);
    }

    public function getRealPath($relPath)
    {
        return $this->_adapter->getRealPath($relPath);
    }

}


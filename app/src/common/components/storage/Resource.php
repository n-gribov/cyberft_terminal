<?php

namespace common\components\storage;

use common\helpers\FileHelper;
use yii\base\BaseObject;

/**
 * Класс ресурса хранилища данных
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

    /**
     * Метод инициализирует ресурс
     */
    public function init()
    {
        // Если заданы настройки SFTP
        if (!empty($this->adapterOptions['settingsSftp'])) {
            // Создать в ресурсе SFTP адаптер
            $this->_adapter = new SftpAdapter($this->adapterOptions);
        } else if (!empty($this->adapterClass)) {
            // Если указан класс адаптера, создаеть его
            $this->_adapter = new $this->adapterClass($this->adapterOptions);
        } else {
            // Задать адаптер по умолчанию
            $this->_adapter = new PartitionedAdapter($this->adapterOptions);
        }
    }

    /**
     * Метод получает путь к корневой папке ресурса
     *
     * @return string
     */
    public function getPath()
    {
        return $this->_adapter->getPath();
    }

    /**
     * Метод сохраняет файл в ресурс
     *
     * @param string $path     File path
     * @param string $filename File name
     * @return boolean|string Return false if error, fileinfo array if success
     */
    public function putFile($path, $filename = '')
    {
        // Сохранить файл через адаптер и получить информацию о файле
        $fileInfo = $this->_adapter->putFile($path, $filename);

        if (!empty($fileInfo)) {
            $this->changePermissions($fileInfo['path']);
        }

        $fileInfo['serviceId'] = $this->serviceId;
        $fileInfo['resourceId'] = $this->id;

        // Вернуть информацию о файле
        return $fileInfo;
    }

    /**
     * Метод сохраняет данные в хранилище
     *
     * @param string $data     Data to save
     * @param string $filename File name
     * @return boolean|string Return false if error, path if success
     */
    public function putData($data, $filename = '')
    {
        // Сохранить данные через адаптер и получить информацию о файле
        $fileInfo = $this->_adapter->putData($data, $filename);

        if (!empty($fileInfo)) {
            $this->changePermissions($fileInfo['path']);
        }
        $fileInfo['resourceType'] = $this->type;
        $fileInfo['serviceId'] = $this->serviceId;
        $fileInfo['resourceId'] = $this->id;

        // Вернуть информацию о файле
        return $fileInfo;
    }

    /**
     * Метод сохраняет данные из потока в хранилище
     * 
     * @param stream $readStream Stream to save
     * @param string $filename   File name
     * @return string Storage path
     */
    public function putStream($readStream, $filename = '')
    {
        // Сохранить данные из потока через адаптер и получить информацию о файле
        $fileInfo = $this->_adapter->putStream($readStream, $filename);

        if (!empty($fileInfo)) {
            $this->changePermissions($fileInfo['path']);
        }

        $fileInfo['serviceId'] = $this->serviceId;
        $fileInfo['resourceId'] = $this->id;

        // Вернуть информацию о файле
        return $fileInfo;
    }

    /**
     * Метод получает вложенные папки в папке
     * @param type $dir
     * @param type $fullPath
     * @return type
     */
    public function getDirSubfolders($dir, $fullPath = true)
    {
        return $this->_adapter->getDirSubfolders($dir, $fullPath);
    }

    /**
     * Метод проверяет, валиден ли указанный путь для данного ресурса
     * @param string $path
     * @return boolean
     */
    public function checkPath($path)
    {
        return $this->_adapter->checkPath($path);
    }

    /**
     * Метод возвращает содержимое папки ресурса
     * @return array
     */
    public function getContents($path = null)
    {
        return $this->_adapter->getContents($path);
    }

    /**
     * Метод удаляет старые файлы
     *
     * @param integer $maxDays Maximum lifetime
     */
    public function deleteOldFiles($maxDays)
    {
        return $this->_adapter->deleteOldFiles($maxDays);
    }

    /**
     * Метод изменяет права доступа к папке
     * @param type $path
     */
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

    /**
     * Метод изменяет права доступа к папке через адаптер
     * @param type $path
     * @param type $permissions
     * @return type
     */
    public function chmod($path, $permissions)
    {
        // Изменить права через адаптер 
        return $this->_adapter->chmod($path, $permissions);
    }

    /**
     * Метод создаёт папку
     * @param type $dirName
     * @return type
     */
    public function createDir($dirName)
    {
        // Создать папку через адаптер
        return $this->_adapter->createDir($dirName);
    }

    /**
     * Метод копирует файл
     * @param type $file
     * @param type $destinationDirPathName
     * @return bool|string
     */
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

    /**
     * Метод переименовывает / перемещает файл
     * @param type $file
     * @param type $destinationDirPathName
     * @return bool|string
     */
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

    /**
     * Метод обновляет данные в файле
     * @param type $relPath
     * @param type $data
     * @return type
     */
    public function updateData($relPath, $data)
    {
        return $this->_adapter->updateData($relPath, $data);
    }

    /**
     * Метод возвращает права доступа
     * @return type
     */
    public function getPermissions()
    {
        return $this->_adapter->permissions;
    }

    /**
     * Метод возвращает путь для сервиса
     * @return type
     */
    public function getServicePath()
    {
        return $this->_adapter->servicePath;
    }
    
    /**
     * Метод возвращает возможность использования SFTP
     * @return type
     */
    public function getUseSftp()
    {
        return !empty($this->adapterOptions['settingsSftp']);
    }

    /**
     * Метод возвращает возможность использования разбиения на папки
     * @return type
     */
    public function getUsePartition()
    {
        return $this->_adapter->usePartition;
    }

    /**
     * Метод возвращает возможность использования архивации
     * @return type
     */
    public function isArchivable($relPath = null)
    {
        return $this->_adapter->isArchivable($relPath);
    }

    /**
     * Метод выполняет архивирование папки
     * @param type $relPath
     * @return type
     */
    public function archive($relPath)
    {
        return $this->_adapter->archive($relPath);
    }

    /**
     * Метод возвращает эффективный путь к файлу
     * @param type $relPath
     * @return type
     */
    public function getRealPath($relPath)
    {
        return $this->_adapter->getRealPath($relPath);
    }

}

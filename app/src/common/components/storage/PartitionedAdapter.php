<?php
namespace common\components\storage;

use common\helpers\FileHelper;
use common\helpers\Lock;
use PharData;
use Yii;

/**
 * Адаптер хранения файлов с автоматическим разбиением на папки
 */

class PartitionedAdapter extends BaseAdapter
{
    private $_path;

    public function init()
    {
        $this->_path = Yii::getAlias($this->_servicePath);

        if (!empty($this->directory)) {
            $this->_path .= '/' . $this->directory;
        }
    }

    /**
     * Create path to save file
     *
     * @param string  $filename File name
     * @return string
     */
    public function createPath($filename = '')
    {
        $path = $this->_path;

        if ($this->usePartition) {
            $path .= '/' . $this->getActualDir($path);
        }

        if (!empty($filename)) {
            $path .= '/' . $filename;
        }

        return $path;
    }

    /**
     * Get actual dir
     *
     * @param string $path Path
     * @return string
     */
    private function getActualDir($path)
    {
        $count = 10;
        $keyVal = FileHelper::uniqueName();
        while(!Lock::acquire($path, $keyVal)) {
            usleep(mt_rand(1000, 10000));
            $count--;
            if ($count < 1) {
                break; // failsafe to the lockless technique
            }
        }

        $subdirs = glob($path . '/0?????');
        if (empty($subdirs)) {
            $actualDir = '000000';
            FileHelper::createDirectory($path . '/' . $actualDir, $this->permissions, true);
        } else {
            $lastDir = end($subdirs);
            $contents = FileHelper::findFiles($lastDir);
            $actualDir = basename($lastDir);
            $fileCount = count($contents);
            if ($fileCount >= $this->_maxFiles) {
                $prevNumber = (int) $actualDir;
                if ($prevNumber > 999998) {
                // При достижении числа 999999 сбрасываем нумерацию на 0
                    $prevNumber = 0;
                } else {
                    $prevNumber++;
                }
                $actualDir = str_pad($prevNumber, 6, '0', STR_PAD_LEFT);
                FileHelper::createDirectory($path . '/' . $actualDir, $this->permissions, true);
            }
        }

        Lock::release($path, $keyVal);

        return $actualDir;
    }

    public function getContents($path = null, $full = true)
    {
        if (is_null($path)) {
            $path = $this->path;
        }

        $items = scandir($path);
        foreach ($items as $key => &$fileName) {
            if (in_array($fileName, ['.', '..'])) {
                unset($items[$key]);
            } else {
                $fullPath = $path . '/' . $fileName;
                if (is_dir($fullPath)) {
                    unset($items[$key]);
                } else {
                    if ($full) {
                        $fileName = $fullPath;
                    }
                }
            }
        }

        return array_values($items);
    }

    public function chmod($path, $permissions)
    {
        return chmod($path, $permissions);
    }

    /**
     * Get service path
     *
     * @return string
     */
    public function getServicePath()
    {
        return $this->_servicePath;
    }

    public function createDir($dirName)
    {
        $createdPath = $this->path . '/' . $dirName;

        if (FileHelper::createDirectory($createdPath, $this->permissions, true)) {
            return $createdPath;
        }

        return false;
    }

    /**
     * Delete old files
     *
     * @param integer $maxDays Maximum lifetime
     */
    public function deleteOldFiles($maxDays)
    {
        $dirs = array_merge([$this->_path], $this->getDirSubfolders($this->_path));

        foreach ($dirs as $dir) {
            $command = 'find ' . $dir . ' -type f -mtime +' . $maxDays . ' -exec rm -rf {} \;';
            passthru($command);
        }
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
        if (empty($filename)) {
            $filename = FileHelper::uniqueName();
        } else {
            $filename = FileHelper::mb_basename($filename);
        }

        $filePath = $this->createPath() . '/' . $this->createFileName($filename);
        $writeStream = fopen($filePath, 'w');

        stream_copy_to_stream($readStream, $writeStream);
        fclose($readStream);
        fclose($writeStream);
        // Вернуть информацию о файле
        return $this->getFileInfo($filePath);
    }

    public function putData($data, $filename = '')
    {
        $savePath = $this->createPath() . '/' . $this->createFileName($filename);

        if (file_put_contents($savePath, $data) === false) {
            return false;
        }
        // Вернуть информацию о файле
        return $this->getFileInfo($savePath);
    }
    
    /**
     * Метод копирует файл в хранилище
     * @param type $path
     * @param type $filename
     * @return bool
     */
    public function putFile($path, $filename = '')
    {
        // Создать путь для копирования
        $savePath = $this->createPath() . '/' . $this->createFileName($filename);
        // Скопировать
        if (!copy($path,  $savePath)) {
            return false;
        }
        // Вернуть информацию о файле
        return $this->getFileInfo($savePath);
    }

    /**
     * Метод возвращает информацию о файле
     * @param type $path
     * @return type
     */
    public function getFileInfo($path)
    {
        return [
            'fsname' => $this->fsname(),
            'servicePath' => $this->_servicePath,
            'dir' => $this->directory,
            'path' => $path,
            'relativePath' => substr($path, strlen($this->path) + 1)
        ];
    }

    /**
     * Метод обновляет данные в файле
     * @param type $relPath
     * @param type $data
     * @return null
     */
    public function updateData($relPath, $data)
    {
        // Получить полный путь к файлу
        $path = $this->getPath($relPath);
        // Если файл существует
        if (is_file($path)) {
            // Поместить данные в файл
            if (file_put_contents($path, $data) === false) {
                return null;
            }
        } else {
            // Иначе искать в архиве
            $tarPath = dirname($path) . '.tar';
            // если нет архива, вернуть null
            if (!is_file($tarPath)) {
                return null;
            }
            // Открыть архив
            $phar = new PharData($tarPath);
            // Поместить данные в архив
            $phar->addFromString(basename($path), $data);
            // Наёти путь в кеше для этого файла
            $cachePath = $this->getPath() . '/.cache/' . str_replace('/', '_', $relPath);
            // Если файл существовал в кеше, удалить его, т.к. данные изменились)
            if (file_exists($cachePath)) {
                unlink($cachePath);
            }
        }
        // Вернуть информацию о файле
        return $this->getFileInfo($path);
    }

    public function isArchivable($relPath = null)
    {
        if (!$this->usePartition) {
            return false;
        }

        $path = $this->getPath();
        if ($relPath) {
            $path .= '/' . $relPath;
        }

        $contents = $this->getContents($path);

        return count($contents) >= $this->_maxFiles;
    }

    public function archive($relPath)
    {
        $path = $this->getPath($relPath);

        $tarPath = $this->getPath() . '/' . str_replace('/', '_', $relPath) . '.tar';

        // Создается архив tar с именем $tarPath
        $phar = new PharData($tarPath);
        // В архив помещаются файлы из папки $path
        $phar->buildFromDirectory($path);

        // Перед удалением папки нужно в случае необходимости создать следующую,
        // чтобы нумерация папок не сбросилась
        $this->getActualDir($this->getPath());

        // Удаляется текущая папка с файлами.
        // Перед удалением она переименовывается, чтобы не фигурировать в getsubdirs
        // Возможен случай, когда переименованная папка уже существует
        // (некорректное завершение предыдущей операции), и переименование тогда не сработает.
        // Поэтому сначала проверяем, есть ли она, и чистим
        $newPath = $this->getPath() . '/.' . $relPath;
        if (is_dir($newPath)) {
            $this->removeDirectory($newPath);
        }
        // А теперь уже переименовываем и чистим.
        rename($path, $newPath);
        $contents = $this->removeDirectory($newPath);

        return $contents;
    }

    private function removeDirectory($path)
    {
        $contents = $this->getContents($path, false);

        foreach($contents as $file) {
            unlink($path . '/' . $file);
        }

        rmdir($path);

        return $contents;
    }

    public function getPath($relPath = null)
    {
        if (!$relPath) {
            return $this->_path;
        }

        return $this->_path . '/' . $relPath;
    }

    public function fsname()
    {
        return 'local';
    }

    public function getRealPath($relPath)
    {
        $path = $this->getPath($relPath);

        if (is_file($path)) {
            return $path;
        }

        $tarPath = dirname($path);
        $tarName = basename($tarPath);
        $tarPath .= '.tar';

        if (!is_file($tarPath)) {
            return null;
        }

        $cachePath = $this->getPath() . '/.cache';
        $filePath = $cachePath . '/' . $tarName . '_' . basename($relPath);

        if (!file_exists($filePath)) {
            $phar = new PharData($tarPath);
            $phar->extractTo($cachePath, basename($path));
            rename($cachePath . '/' . basename($path), $filePath);
        }

        return $filePath;
    }

}
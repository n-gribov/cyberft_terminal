<?php
namespace common\helpers;

use ZipArchive;

class ArchiveFileZip implements ArchiveFileInterface
{
    /** @var ZipArchive $_zip */
    private $_zip;
    private $_zipPath;

    /**
     * @param type $zipPath
     * @return ArchiveFileZip
     */
    public static function createArchive($zipPath)
    {
        $instance = new static();
        $result = $instance->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE);

        return $result !== false ? $instance : false;
    }

    /**
     * @param type $zipPath
     * @return ArchiveFileZip
     */
    public static function openArchive($zipPath)
    {
        $instance = new static();
        $result = $instance->open($zipPath);

        return $result !== false ? $instance : false;
    }

    public function getStream($name)
    {
        return $this->_zip ? $this->_zip->getStream($name) : null;
    }

    public function extract($file, $destination)
    {
        $zip = $this->open($file);
        $result = $zip->extractTo($destination);

        return $result;
    }

    public function statIndex($file, $index)
    {
        $zip = $this->open($file);

        return $zip->statindex($index);
    }

    /**
     *  Если файл не null, то открывает новый зип, иначе возвращает уже открытый (если есть),
     *  или переоткрывает старый (если был)
     *
     * @param type $zipPath путь к зип-файлу
     * @param type $flags   флаги создания (см. ZipArchive)
     * @return ZipArchive
     */
    public function open($zipPath = null, $flags = null)
    {
        if ($zipPath) { // указан путь. закрываем все что было открыто и готовим новый путь
            $this->close();
            $this->_zipPath = $zipPath;
        }

        if ($this->_zip) { // если есть открытый зип, возвращаем его
            return $this->_zip;
        }

        // если передан путь к файлу, файл должен существовать и быть читабелен,
        // кроме случая, когда переданы флаги на создание нового файла или перезапись
        if ($this->_zipPath && (
                ($flags & (ZipArchive::CREATE | ZipArchive::OVERWRITE))
                || is_readable($this->_zipPath))
        ) { // если есть путь, то открывам новый зип
            $this->_zip = new ZipArchive();
            $result = $this->_zip->open($this->_zipPath, $flags);

            return $result ? $this->_zip : false;
        }

        return false;
    }

    public function close()
    {
        if ($this->_zip) {
            $this->_zip->close();
            $this->_zip = null;
        }
    }

    /**
     * Закрывает зип и удаляет файл
     */
    public function purge()
    {
        $this->close();

        if ($this->_zipPath && is_readable($this->_zipPath)) {
            unlink ($this->_zipPath);
        }

        $this->_zipPath = null;
    }

    public function addFile($filePath, $localname, $zipFilenameEncoding = null)
    {
        if ($zipFilenameEncoding) {
            $localname = iconv('UTF-8', $zipFilenameEncoding, $localname);
        }

        $this->_zip->addFile($filePath, $localname);
    }

    public function addFromString($contents, $localname, $zipFilenameEncoding = null)
    {
        if ($zipFilenameEncoding) {
            $localname = iconv('UTF-8', $zipFilenameEncoding, $localname);
        }

        return $this->_zip->addFromString($localname, $contents);
    }

    public function deleteIndex($index)
    {
        return $this->_zip->deleteIndex($index);
    }

    public function getFromIndex($index)
    {
        return $this->_zip->getFromIndex($index);
    }

    public function getFromName($name)
    {
        return $this->_zip->getFromName($name);
    }

    public function getNameFromIndex($i)
    {
        return $this->_zip->getNameIndex($i);
    }

    public function getFileList($zipFilenameEncoding = null)
    {
        $files = [];

        for ($i = 0; $i < $this->_zip->numFiles; $i++) {
            $name = $this->_zip->getNameIndex($i);
            $name = static::fixFilenameEncoding($name, $zipFilenameEncoding);

            $files[] = $name;
        }

        return $files;
    }

    private static function fixFilenameEncoding($fileName, $sourceEncoding = null)
    {
        // Если имена файлов были не в кодировке UTF-8, зип отдает их в виде,
        // как будто они были декодированы из ibm437 в UTF-8.
        // Для нормального декодирования нужно имя, отданное зипом, закодировать обратно в ibm437,
        // таким образом будет восстановлена исходная кодировка,
        // и затем из этой кодировки перекодировать уже в UTF-8 (при необходимости).

        try {
            // строка в любом случае отдается в UTF-8, и мы не можем определить без хитрой эвристики,
            // это истинный UTF-8 или перекодированный из ibm437
            // поэтому пытаемся перекодировать в любом случае.
            // настоящий UTF-8 при перекодировании в ibm437 даст ошибку, которую мы отловим.
            $fileName = iconv('UTF-8', 'ibm437', $fileName);

            if ($sourceEncoding) {
                return iconv($sourceEncoding, 'UTF-8', $fileName);
            }
        } catch(\Exception $ex) {

        }

        return $fileName;
    }

    /**
     * Возвращает файл зип-архива в виде строки
     * @return string|boolean
     */
    public function asString()
    {
        // Зип-архив должен быть закрыт, чтобы финализировалось содержимое
        $this->close();

        if ($this->_zipPath && is_readable($this->_zipPath)) {
            return file_get_contents($this->_zipPath);
        }

        return false;
    }

    public function getPath()
    {
        return $this->_zipPath;
    }

}

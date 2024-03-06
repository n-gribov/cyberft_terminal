<?php
namespace common\helpers;

use Yii;
use common\helpers\FileHelper;

class ZipHelper
{
    public static function getTempFileName()
    {
        return Yii::getAlias('@temp/' . FileHelper::uniqueName() . '.zip');
    }

    /**
     * Создает копию зип-архива во временном файле (для случаев, когда исходный зип нельзя модифицировать)
     * @param type $path
     * @return ArchiveFileZip
     */
    public static function copyArchiveFileZipFromFile($path)
    {
        $tempFile = static::getTempFileName();
        if (copy($path, $tempFile)) {
            return ArchiveFileZip::openArchive($tempFile);
        }

        return false;
    }

    /**
     * @return ArchiveFileZip
     */
    public static function createTempArchiveFileZip()
    {
        $path = static::getTempFileName();

        return ArchiveFileZip::createArchive($path);
    }

    /**
     * @param type $zipStr
     * @return ArchiveFileZip
     */
    public static function createArchiveFileZipFromString($zipStr, $path = null)
    {
        if (!$path) {
            $path = static::getTempFileName();
        }
        file_put_contents($path, $zipStr);

        return ArchiveFileZip::openArchive($path);
    }

    public static function packStringToString($str, $localName = 'rawData.xml', $encoding = null)
    {
        $tempFile = static::getTempFileName();

        $zip = ArchiveFileZip::createArchive($tempFile);

        if ($encoding) {
            $localName = iconv('UTF-8', $encoding, $localName);
        }

        $zip->addFromString($str, $localName);
        $zip->close();

        $out = file_get_contents($tempFile);

        $zip->purge();

        return $out;
    }

    public static function packFileToString($filePath, $localName = 'rawData.xml', $encoding = null)
    {
        $tempFile = static::getTempFileName();

        $zip = ArchiveFileZip::createArchive($tempFile);

        if ($encoding) {
            $localName = iconv('UTF-8', $encoding, $localName);
        }

        $zip->addFile($filePath, $localName);
        $out = $zip->asString();

        $zip->purge();

        return $out;
    }

    public static function unpackStringFromString($zipStr)
    {
        $zip = static::createArchiveFileZipFromString($zipStr);
        $str = $zip->getFromIndex(0);
        $zip->purge();

        return $str;
    }

}

<?php

namespace common\helpers;

use Exception;
use Yii;
use yii\helpers\BaseFileHelper;

class FileHelper extends BaseFileHelper
{
    /**
     * @inheritdoc
     */
    public static function createDirectory($path, $mode = 0775, $recursive = true, $isSSH2 = false)
    {
        if (is_dir($path)) {
            return true;
        }

        $parentDir = dirname($path);

        if ($recursive && !is_dir($parentDir) && $isSSH2 === false) {
            static::createDirectory($parentDir, $mode, true);
        }

        try {
            $result = mkdir($path, $mode);
            if (!$isSSH2) {
                chmod($path, $mode);
            }
        } catch (Exception $e) {
            throw new Exception("Failed to create directory '$path': " . $e->getMessage(), $e->getCode(), $e);
        }

        return $result;
    }

    public static function mb_pathinfo($path)
    {
        $out = [];

        $basename = static::mb_basename($path);
        $out['basename'] = $basename;

        $dotPos = strrpos($basename, '.');
        if ($dotPos !== false) {
            $out['extension'] = substr($basename, $dotPos + 1);
            $out['filename'] = static::mb_basename(substr($basename, 0, $dotPos));
        } else {
            $out['filename'] = static::mb_basename($basename);
        }

        return $out;
    }

    public static function mb_basename($path)
    {
        $parts = explode('/', $path);

        return is_array($parts) ? end($parts) : $path;
    }

    public static function getExtensionByMimeType($mimeType, $magicFile = null)
    {
        $extensions = static::getExtensionsByMimeType($mimeType, $magicFile);

        if (!empty($extensions)) {
            return array_pop($extensions);
        }

        return null;
    }

    /**
     * Prepends given filename with suggested directory structure
     * based on first chunks of string
     *
     * @param string $fileName
     */
    public static function inflateFilename($fileName)
    {
        $length = mb_strlen($fileName);

        if ($length <= 3) {
            return $fileName;
        }

        $chunkSize = $length < 6 ? 1 : 2;

        $matches = [];
        preg_match_all('~.{1,' . $chunkSize . '}~u', $fileName, $matches);

        $chunks = & $matches[0];

        return implode('/', [$chunks[0], $chunks[1], $chunks[2], $fileName]);
    }

    public static function storeTempFile($path, $fileName = '')
    {
        $stream = fopen($path, 'r+');

        do {
            $fileName = static::uniqueName() . ($fileName ? '.' . $fileName: '');
            $filePath = static::inflateFilename($fileName);
        } while (Yii::$app->fsTemp->has($filePath));

        if (!Yii::$app->fsTemp->writeStream($filePath, $stream)) {
            return false;
        }

        return $filePath;
    }

    public static function uniqueName($prefix = null)
    {
        return $prefix . getmypid() . uniqid();
    }

    public static function exportFileToPath($filePath, $fileName, $fileContent)
    {
        $result = [];

        $exportPath = Yii::getAlias('@docExport/');
        $exportPath = $exportPath . $filePath;

        static::createDirectory($exportPath);

        $exportPath .= '/' . $fileName;

        if (file_put_contents($exportPath, $fileContent)) {
            $result['path'] = $exportPath;
        }

        return $result;
    }

}
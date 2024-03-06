<?php

namespace common\modules\certManager\utils;


use Exception;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ZipArchive;

class DocxTemplate
{
    private $tempPath;
    private $workDirPath;

    public function __construct($tempPath)
    {
        $this->tempPath = $tempPath;
    }

    public function render($templatePath, $data)
    {
        $this->unpackTemplate($templatePath);
        $this->processContentTemplate($data);
        $fileContent = $this->packDocument();
        $this->removeWorkDir();
        return $fileContent;
    }

    private static function uniqueName($prefix = null)
    {
        return $prefix . getmypid() . uniqid();
    }

    private function unpackTemplate($templatePath)
    {
        $this->workDirPath = $this->tempPath . '/' . static::uniqueName();
        $zip = new ZipArchive;
        if ($zip->open($templatePath) === true) {
            $zip->extractTo($this->contentDirPath());
            $zip->close();
        } else {
            throw new Exception("Failed to unpack $templatePath to {$this->contentDirPath()}");
        }
    }

    private function processContentTemplate($data)
    {
        $documentPath = $this->contentDirPath() . '/word/document.xml';
        $documentTemplate = file_get_contents($documentPath);
        if ($documentTemplate === false) {
            throw new Exception("Failed to read $documentPath");
        }
        $documentContent = $this->putDataToTemplate($documentTemplate, $data);
        $bytesWritten = file_put_contents($documentPath, $documentContent);
        if ($bytesWritten === false) {
            throw new Exception("Failed to write to $documentPath");
        }
    }

    private function putDataToTemplate($documentString, $data)
    {
        return preg_replace_callback(
            '/{{\s*(?:<.*?>)*\s*([\da-z_]+)\s*(?:<.*?>)*\s*}}/i',
            function ($matches) use ($data) {
                $dataKey = $matches[1];
                $replacement = @$data[$dataKey];
                return static::encodeForTemplate($replacement);
            },
            $documentString
        );
    }

    private static function encodeForTemplate($string)
    {
        $string = htmlspecialchars($string, ENT_XML1, 'UTF-8');
        $string = trim($string);
        $string = preg_replace('/[\r\n]+/', '<w:br/>', $string);
        return $string;
    }

    private function contentDirPath()
    {
        return $this->workDirPath . '/content';
    }

    private function packDocument()
    {
        $zip = new ZipArchive();
        $docxFilePath = $this->workDirPath . '/result.docx';
        $zip->open($docxFilePath, ZipArchive::CREATE);

        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($this->contentDirPath()),
            RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($files as $name => $file)
        {
            if ($file->isDir()) {
                continue;
            }
            $filePath = $file->getRealPath();
            $relativePath = substr($filePath, strlen(realpath($this->contentDirPath())) + 1);
            $zip->addFile($filePath, $relativePath);
        }
        $zip->close();

        $fileContent = file_get_contents($docxFilePath);
        if ($fileContent === false) {
            throw new Exception("Failed to read $docxFilePath");
        }
        return $fileContent;
    }

    private function removeWorkDir()
    {
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($this->workDirPath, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::CHILD_FIRST
        );

        foreach ($files as $file) {
            $path = $file->getRealPath();
            if ($file->isDir()) {
                rmdir($path);
            } else {
                unlink($path);
            }
        }

        rmdir($this->workDirPath);
    }
}

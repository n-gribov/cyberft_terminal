<?php

namespace addons\SBBOL\helpers;

use addons\SBBOL\models\SBBOLCustomer;
use addons\SBBOL\models\soap\request\GetResponsePartSRPRequest;
use addons\SBBOL\models\soap\response\GetResponsePartSRPResponse;
use addons\SBBOL\SBBOLModule;
use common\helpers\sbbol\SBBOLXmlSerializer;
use common\models\sbbolxml\response\Response;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Filesystem\Filesystem;
use Yii;

class PartitionedResponseDownloader
{
    /** @var string */
    private $requestId;

    /** @var SBBOLCustomer */
    private $customer;

    /** @var int */
    private $partsCount;

    /** @var SBBOLModule */
    private $module;

    /** @var Filesystem */
    private $fs;

    private $tmpDirPath;
    private $zipFiles = [];

    private function __construct(string $requestId, SBBOLCustomer $customer, int $partsCount)
    {
        $this->requestId = $requestId;
        $this->customer = $customer;
        $this->partsCount = $partsCount;
        $this->module = Yii::$app->getModule('SBBOL');
        $this->fs = new Filesystem();
    }

    public static function download(string $requestId, SBBOLCustomer $customer, int $partsCount, callable $onComplete)
    {
        $instance = new static($requestId, $customer, $partsCount);
        return $instance->downloadInternal($onComplete);
    }

    private function downloadInternal(callable $onComplete)
    {
        try {
            $this->createTmpDir();
            $this->downloadParts();
            $this->extractParts();
            $this->mergeParts();
            $onComplete($this->getResultFilePath());
        } catch (\Exception $exception) {
            throw $exception;
        } finally {
            $this->cleanup();
        }
    }

    private function downloadParts()
    {
        $sessionId = $this->module->sessionManager->findOrCreateSession($this->customer->holdingHeadId);

        for ($partNumber = 1; $partNumber <= $this->partsCount; $partNumber++) {
            $this->downloadPart($partNumber, $sessionId);
        }
    }

    private function downloadPart(int $partNumber, string $sessionId)
    {
        $request = new GetResponsePartSRPRequest([
            'request' => $this->requestId,
            'part' => $partNumber,
            'orgId' => $this->customer->id,
            'sessionId' => $sessionId,
        ]);

        /** @var GetResponsePartSRPResponse $response */
        $response = $this->module->transport->send($request);

        /** @var Response $responseDocument */
        $responseDocument = SBBOLXmlSerializer::deserialize($response->return, Response::class);

        $attachment = $responseDocument->getResponsePart()->getAttachment();
        $content = base64_decode($attachment->getBody());
        $this->saveZipFile($content, $attachment->getAttachmentName());
    }

    private function extractParts()
    {
        foreach ($this->zipFiles as $path) {
            $this->extractZipFile($path, $this->getExtractedFilesDir());
        }
    }

    private function mergeParts()
    {
        $resultFilePath = $this->getResultFilePath();
        $this->fs->touch($resultFilePath);
        foreach ($this->getExtractedFilesPaths() as $path) {
            $content = file_get_contents($path);
            $this->fs->appendToFile($resultFilePath, $content);
        }
    }

    private function cleanup()
    {
        if ($this->fs->exists($this->tmpDirPath)) {
            $this->fs->remove($this->tmpDirPath);
        }
    }

    private function createTmpDir()
    {
        $this->tmpDirPath = '/tmp/' . getmypid() . '-' . Uuid::uuid1()->toString();
        $this->fs->mkdir($this->tmpDirPath);
    }

    private function saveZipFile($content, $fileName)
    {
        $path = $this->tmpDirPath . '/' . $fileName;
        $this->fs->dumpFile($path, $content);
        $this->zipFiles[] = $path;
    }

    private function extractZipFile($zipPath, $targetPath)
    {
        $zipArchive = new \ZipArchive();
        $openResult = $zipArchive->open($zipPath);
        if ($openResult !== true) {
            throw new \Exception("Failed to open zip archive $zipPath, error code: $openResult");
        }

        $isExtracted = $zipArchive->extractTo($targetPath);
        $zipArchive->close();
        if (!$isExtracted) {
            throw new \Exception("Failed to extract zip archive $zipPath to $targetPath");
        }
    }

    private function getExtractedFilesDir()
    {
        return "{$this->tmpDirPath}/extracted";
    }

    private function getResultFilePath()
    {
        return $this->tmpDirPath . '/result';
    }

    private function getExtractedFilesPaths()
    {
        $filesNames = array_diff(
            scandir($this->getExtractedFilesDir()),
            ['.', '..']
        );

        return array_map(
            function ($fileName) {
                return "{$this->getExtractedFilesDir()}/$fileName";
            },
            $filesNames
        );
    }
}

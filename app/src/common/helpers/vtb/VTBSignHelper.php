<?php

namespace common\helpers\vtb;

use common\models\vtbxml\documents\BSDocument;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Filesystem\Filesystem;
use Yii;

class VTBSignHelper
{
    const BASE_TMP_PATH = '/tmp/cyberft-vtb-sign-helper';

    private $fs;

    private function __construct()
    {
        $this->fs = new Filesystem();
    }

    public static function sign(BSDocument $document, $version, $keyPassword, $keyCommonName)
    {
        $instance = new static();
        return $instance->signInternal($document, $version, $keyPassword, $keyCommonName);
    }

    public static function verify(BSDocument $document, array $signedFields, $signature, $certCommonName)
    {
        $instance = new static();
        return $instance->verifyInternal($document, $signedFields, $signature, $certCommonName);
    }


    private function signInternal(BSDocument $document, $version, $keyPassword, $keyCommonName)
    {
        $tmpDirPath = $this->createTmpDir();

        $data = BSDocumentSignedDataBuilder::build($document, $version);

        try {
            $signedDataFilePath = "$tmpDirPath/signed-data";
            $this->fs->dumpFile($signedDataFilePath, $data);

            $command = '/opt/cprocsp/bin/amd64/cryptcp'
                . ' -signf'
                . ' -nochain'
                . ' -dn ' . escapeshellarg($keyCommonName)
                . ' -pin ' . escapeshellarg($keyPassword)
                . ' -dir ' . escapeshellarg($tmpDirPath)
                . ' ' . escapeshellarg($signedDataFilePath);

            $isSuccess = $this->executeCryptcpCommand($command);
            if (!$isSuccess) {
                Yii::info('Signing failed');
                return false;
            }

            $signatureFilePath = "$signedDataFilePath.sgn";
            $signature = file_get_contents($signatureFilePath);
            if (empty($signature)) {
                throw new \Exception("Failed to read signature from $signatureFilePath");
            }
            return preg_replace('/[\r\n]/', '', $signature);
        } catch (\Exception $exception) {
            Yii::info($exception);
            return false;
        } finally {
            $this->fs->remove($tmpDirPath);
        }
    }

    private function verifyInternal(BSDocument $document, array $signedFields, $signature, $certCommonName)
    {
        $tmpDirPath = $this->createTmpDir();

        $data = BSDocumentSignedDataBuilder::buildForFields($document, $signedFields);

        try {
            $signedDataFilePath = "$tmpDirPath/signed-data";
            $signatureFilePath = "$signedDataFilePath.sgn";
            $this->fs->dumpFile($signedDataFilePath, $data);
            $this->fs->dumpFile($signatureFilePath, $signature);

            $command = '/opt/cprocsp/bin/amd64/cryptcp'
                . ' -vsignf'
                . ' -nochain'
                . ' -dn ' . escapeshellarg($certCommonName)
                . ' -dir ' . escapeshellarg($tmpDirPath)
                . ' ' . escapeshellarg($signedDataFilePath);

            $isSuccess = $this->executeCryptcpCommand($command);
            if (!$isSuccess) {
                Yii::info('Signature validation failed');
            }

            return $isSuccess;
        } catch (\Exception $exception) {
            Yii::info($exception);
            return false;
        } finally {
            $this->fs->remove($tmpDirPath);
        }
    }

    private function createTmpDir()
    {
        if (!$this->fs->exists(static::BASE_TMP_PATH)) {
            $this->fs->mkdir(static::BASE_TMP_PATH);
            $this->fs->chmod(static::BASE_TMP_PATH, 0777);
        }
        $tmpDirPath = static::BASE_TMP_PATH . '/' . getmypid() . '-' . Uuid::uuid1()->toString();
        $this->fs->mkdir($tmpDirPath);
        return $tmpDirPath;
    }

    private function executeCryptcpCommand($command)
    {
        exec($command, $outputLines, $resultCode);
        $statusString = !empty($outputLines) ? end($outputLines) : null;
        if ($resultCode === 0 && $statusString === '[ReturnCode: 0]') {
            return true;
        }

        $output = implode(', ', $outputLines);
        Yii::info("Cryptcp failed, result: $resultCode, output: $output");
        return false;
    }
}

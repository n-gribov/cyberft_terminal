<?php

namespace addons\SBBOL\helpers;

use Exception;
use FilesystemIterator;
use Symfony\Component\Filesystem\Filesystem;
use ZipArchive;

class CryptoProHelper
{
    const CONTAINERS_PATH = '/var/opt/cprocsp/keys/www-data';
    const CSPTEST_EXECUTABLE_PATH = '/opt/cprocsp/bin/amd64/csptest';
    const CERTMGR_EXECUTABLE_PATH = '/opt/cprocsp/bin/amd64/certmgr';

    public static function importContainerFromZipFile($zipPath)
    {
        $fs = new Filesystem();
        $containerPath = static::generateContainerPath($fs, static::CONTAINERS_PATH);
        $fs->mkdir($containerPath);

        try {
            $containersBefore = static::getContainers();
            self::extractZipFile($zipPath, $containerPath);
            $fs->chmod(new FilesystemIterator($containerPath), 0600);
            $containersAfter = static::getContainers();
            $newContainers = array_values(array_diff($containersAfter, $containersBefore));
            if (count($newContainers) !== 1) {
                throw new Exception('CryptoPro has not added container');
            }

            return $newContainers[0];
        } catch (Exception $exception) {
            if ($fs->exists($containerPath)) {
                $fs->remove($containerPath);
            }
            throw new Exception("Failed to import container, caused by: $exception");
        }
    }

    public static function installCertificate($certificatePath)
    {
        $command = static::CERTMGR_EXECUTABLE_PATH . ' -inst -file ' . escapeshellarg($certificatePath);
        $output = shell_exec($command);
        if ($output === null) {
            throw new Exception("Failed to install certificate from $certificatePath, certmgr finished with error");
        }

        if (strpos($output, '[ErrorCode: 0x00000000]') === false) {
            throw new Exception("Failed to install certificate from $certificatePath, output: $output");
        }
    }

    public static function installCertificateIntoContainer($certificatePath, $containerName, $containerPassword)
    {
        $command = static::CERTMGR_EXECUTABLE_PATH
            . ' -inst -file ' . escapeshellarg($certificatePath)
            . ' -cont ' . escapeshellarg($containerName);

        $descriptors = [
            0 => ['pipe', 'r'],
            1 => ['pipe', 'w'],
            2 => ['pipe', 'w'],
        ];

        $pipes = null;
        $process = proc_open($command, $descriptors, $pipes);

        // certmgr may ask password many times
        fwrite($pipes[0], $containerPassword);
        fwrite($pipes[0], "\n");
        fwrite($pipes[0], $containerPassword);
        fwrite($pipes[0], "\n");
        fwrite($pipes[0], $containerPassword);
        fwrite($pipes[0], "\n");

        $output = stream_get_contents($pipes[1]);
        fclose($pipes[1]);
        proc_close($process);

        if (strpos($output, '[ErrorCode: 0x00000000]') === false) {
            throw new Exception("Failed to import certificate to $containerName, output: $output");
        }
    }

    public static function getContainers(): array
    {
        $cmd = static::CSPTEST_EXECUTABLE_PATH . ' -keyset -enum_cont -verifycontext -fqcn';
        $output = shell_exec($cmd);
        $outputLines = preg_split('/[\r\n]+/', $output);

        return array_values(
            array_filter(
                $outputLines,
                function ($line) {
                    return strpos($line, '\\\\.\\HDIMAGE\\') === 0;
                }
            )
        );
    }

    private static function extractZipFile($zipPath, $targetPath)
    {
        $zipArchive = new ZipArchive();
        $openResult = $zipArchive->open($zipPath);
        if ($openResult !== true) {
            throw new Exception("Failed to open zip archive $zipPath, error code: $openResult");
        }

        $isExtracted = $zipArchive->extractTo($targetPath);

        $zipArchive->close();
        if (!$isExtracted) {
            throw new Exception("Failed to extract zip archive $zipPath to $targetPath");
        }
    }

    private static function generateContainerPath(Filesystem $fs, $basePath)
    {
        for ($i = 0; $i < 1000; $i++) {
            $path = $basePath . '/sbbol.' . str_pad($i, 3, '0', STR_PAD_LEFT);
            if (!$fs->exists($path)) {
                return $path;
            }
        }

        throw new Exception('Failed to generate container path');
    }

}

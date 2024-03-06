<?php

namespace common\helpers;

use Yii;

class DsgostSigner
{
    private const DSGOSTXML_COMMAND_BASE = 'LD_LIBRARY_PATH="$LD_LIBRARY_PATH:/opt/cprocsp/lib/amd64" /var/www/cyberft/app/src/bin/dsgostxml';

    /**
     * @var bool
     */
    private $useReverseByteOrder;

    public function __construct(bool $useReverseByteOrder = false)
    {
        $this->useReverseByteOrder = $useReverseByteOrder;
    }

    /**
     * Вычисляет подпись в формате CMS для старого SBBOL
     * @todo использует cryptopro напрямую, необходима доработка dsgostxml
     * @param string $data string to be signed
     * @param string $certId cert fingerprint
     * @param string $keyPassword key password
     * @return string|boolean signature in binary string or false
     */
    public function signCMS(string $data, string $certId, string $keyPassword)
    {
        $dataPath = tempnam('/tmp', '');
        $outPath = tempnam('/tmp', '');

        try {
            file_put_contents($dataPath, $data);

            $command = '/opt/cprocsp/bin/amd64/cryptcp'
                . ' -sign'
                . ' -thumbprint ' . $certId
                . ' -nochain'
                . ' -pin ' . $keyPassword
                . ' ' . $dataPath . ' ' . $outPath;


            $isSuccess = $this->executeCryptcpCommand($command);
            if (!$isSuccess) {
                Yii::info('Signature validation failed');

                return false;
            }

            $signature = base64_decode(file_get_contents($outPath));
            return $signature;

        } catch (\Exception $ex) {
            Yii::info(__METHOD__ . " error: $ex");

            return false;
        } finally {
            if (file_exists($dataPath)) {
                unlink($dataPath);
            }
            if (file_exists($outPath)) {
                unlink($outPath);
            }
        }
    }

    /**
     * Проверяет подпись в формате CMS для старого SBBOL
     * @todo использует cryptopro напрямую, необходима доработка dsgostxml
     * @todo в данный момент проверка успешно осуществляется стандартным методом DsgostSigner::verify
     * @param string $data signed data
     * @param string $signature
     * @param string $certId cert fingerprint
     * @return boolean
     */
    public function verifyCMS(string $data, string $signature, string $certId): bool
    {
        $dataPath = tempnam('/tmp', '');
        try {
            file_put_contents($dataPath, $data);
            // надо $signature засунуть в "$dataPath.sgn"?
            $command = '/opt/cprocsp/bin/amd64/cryptcp'
                . ' -verify'
                . ' -thumbprint ' . $certId
                . ' -nochain'
                . ' ' . $dataPath;

            $isSuccess = $this->executeCryptcpCommand($command);
            if (!$isSuccess) {
                Yii::info('Signature validation failed');
            }

            return $isSuccess;
        } catch (\Exception $exception) {
            Yii::info($exception);

            return false;
        } finally {
            if (file_exists($dataPath)) {
                unlink($dataPath);
            }
        }
    }

    private function executeCryptcpCommand($command)
    {
        $resultCode = 0;
        $outputLines = [];

        exec($command, $outputLines, $resultCode);
        $statusString = !empty($outputLines) ? end($outputLines) : null;
        if ($resultCode === 0 && $statusString === '[ReturnCode: 0]') {
            return true;
        }

        Yii::info("Cryptcp failed, result: $resultCode, output: " . implode(', ', $outputLines));

        return false;
    }

    /**
     * @param string $data
     * @param string $certId
     * @param string|null $keyPassword
     * @return bool|string
     */
    public function sign(string $data, string $certId, ?string $keyPassword)
    {
        try {
            $command = static::DSGOSTXML_COMMAND_BASE
                    . ' sign --cert=' . escapeshellarg($certId)
                    . ' --alg=2012_512';

            $pipes = null;
            $descriptorspec = [
                0 => ['pipe', 'r'],
                1 => ['pipe', 'w'],
                2 => ['pipe', 'w'],
            ];

            $process = proc_open($command, $descriptorspec, $pipes);

            if (!is_resource($process)) {
                throw new \Exception('dsgostxml launch error');
            }

            fwrite($pipes[0], base64_encode($keyPassword));
            fwrite($pipes[0], "\n");
            fwrite($pipes[0], base64_encode($data));
            fclose($pipes[0]);

            $out = stream_get_contents($pipes[1]);
            fclose($pipes[1]);

            $error = stream_get_contents($pipes[2]);
            fclose($pipes[2]);
            $exitCode = $this->getProcessExitCode($process);
            proc_close($process);

            if (!empty($error)) {
                throw new \Exception("dsgostxml has returned error: $error");
            }
            if ($exitCode !== 0) {
                throw new \Exception("dsgostxml has returned error status code: $exitCode");
            }

            $signatureHex = trim($out);
            $signature = hex2bin($signatureHex);

            return $this->useReverseByteOrder ? strrev($signature) : $signature;
        } catch (\Exception $exception) {
            Yii::info("dsgostxml signing has failed, {$exception->getMessage()}");
        }

        return false;
    }

    public function verify(string $data, string $signature, string $certId): bool
    {
        $hexSignature = $this->useReverseByteOrder
                ? bin2hex(strrev($signature))
                : bin2hex($signature);
        $command =  static::DSGOSTXML_COMMAND_BASE
            . ' verify'
            . ' --cert=' . escapeshellarg($certId)
            . ' --signature=' . escapeshellarg($hexSignature);

        try {
            $pipes = null;

            $descriptorspec = [
                0 => ['pipe', 'r'],
                1 => ['pipe', 'w'],
                2 => ['pipe', 'w'],
            ];

            $process = proc_open($command, $descriptorspec, $pipes);

            if (!is_resource($process)) {
                throw new \Exception('dsgostxml launch error');
            }

            fwrite($pipes[0], base64_encode($data));
            fclose($pipes[0]);

            stream_get_contents($pipes[1]);
            fclose($pipes[1]);

            $error = stream_get_contents($pipes[2]);
            fclose($pipes[2]);
            $exitCode = $this->getProcessExitCode($process);
            proc_close($process);

            if (!empty($error)) {
                throw new \Exception("dsgostxml has returned error: $error");
            }
            if ($exitCode !== 0) {
                throw new \Exception("dsgostxml has returned error status code: {$exitCode}");
            }
        } catch (\Exception $exception) {
            Yii::info("dsgostxml signature verification has failed, {$exception->getMessage()}");

            return false;
        }

        return true;
    }

    public function hash(string $data, string $algorithm)
    {
        try {
            $this->ensureIsValidHashAlgorithm($algorithm);
            $command = static::DSGOSTXML_COMMAND_BASE . ' hash --alg=' . escapeshellarg($algorithm);

            $pipes = null;
            $descriptorspec = [
                0 => ['pipe', 'r'],
                1 => ['pipe', 'w'],
                2 => ['pipe', 'w'],
            ];

            $process = proc_open($command, $descriptorspec, $pipes);

            if (!is_resource($process)) {
                throw new \Exception('dsgostxml launch error');
            }

            fwrite($pipes[0], base64_encode($data));
            fclose($pipes[0]);

            $out = stream_get_contents($pipes[1]);
            fclose($pipes[1]);

            $error = stream_get_contents($pipes[2]);
            fclose($pipes[2]);
            $exitCode = $this->getProcessExitCode($process);
            proc_close($process);

            if (!empty($error)) {
                throw new \Exception("dsgostxml has returned error: $error");
            }
            if ($exitCode !== 0) {
                throw new \Exception("dsgostxml has returned error status code: $exitCode");
            }

            $hashHex = trim($out);
            return hex2bin($hashHex);
        } catch (\Exception $exception) {
            Yii::info("dsgostxml hash calculation has failed, {$exception->getMessage()}");
        }

        return false;
    }

    private function getProcessExitCode($process)
    {
        $status = proc_get_status($process);
        $tryCount = 1;
        while ($status['running'] === true && $tryCount < 5000) {
            usleep(1000);
            $status = proc_get_status($process);
        }
        return $status['exitcode'];
    }

    private function ensureIsValidHashAlgorithm(string $algorithm): void
    {
        $supportedAlgorithms = ['2001', '2012_256', '2012_512'];
        if (!in_array($algorithm, $supportedAlgorithms)) {
            throw new \Exception("Unsupported hash algorithm: $algorithm");
        }
    }

}

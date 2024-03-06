<?php

namespace common\components\cryptography\drivers;

use common\helpers\FileHelper;
use common\helpers\CryptoProHelper;
use common\models\CryptoproContainerCollection;
use Exception;
use Yii;

class DriverCryptoPro
{
    const SHA1_HASH = 'SHA1 Hash';
    const CONTAINER = 'Container';
    const SUBJECT = 'Subject';
    const SERIAL = 'Serial';
    const NOT_VALID_AFTER = 'Not valid after';
    const KEYID = 'SubjKeyID';
    const SIGNATURE_ALGORITHM = 'Signature Algorithm';

    /**
     * Get list of hardware readers
     *
     * @return array|boolean Return list of hardware readers or false on failure
     * @throws Exception
     */
    public function getHardwareReaderList()
    {
        $result     = [];
        $output     = null;
        $returnVar = null;

        try {
            exec('list_pcsc', $output, $returnVar);
            if ($returnVar !== 0) {
                throw new Exception('Error getting list of hardware readers. Check pcscd service and CryptoPro');
            }

            foreach ($output as $value) {
                $delimer = strpos($value, ':');
                if ($delimer !== false) {
                    $value = substr($value, $delimer + 1);
                }

                $result[] = trim($value);
            }

            return $result;
        } catch (Exception $ex) {
            Yii::info($ex->getMessage(), 'system');

            return false;
        }
    }

    /**
     * Install ceritificate to CryptoPro
     *
     * @param string $certificatePath Path for certificate
     * @param string $containerName   Name of container
     * @param string $containerType   Type of container
     * @return boolean
     * @throws Exception
     */
    public function installCertificate($certificatePath, $containerName = null, $containerType = 'hdimage')
    {
        try {
            if (!is_file($certificatePath)) {
                throw new Exception("{$certificatePath} is not a file");
            }

            if (empty($containerName)) {
                $output = $this->getCommandOutput(
                        'certmgr', 'inst',
                        ['file' => $certificatePath]
                );
                $collection = $this->getCertInfo($output);

                if ($collection->errorCode != '0x00000000') {
                    throw new Exception("Error installing certificate from {$certificatePath}");
                }
            } else {
                $output = CryptoProHelper::getCommandOutput(
                        'cryptcp', 'instcert',
                        ['cont' => "'\\.\\" . $containerType . "\\" . $containerName . "' " . $certificatePath]
                );

                if (!$output) {
                    throw new Exception("Error installing certificate from {$certificatePath}. Check certificate type, CryptoPro container type and name");
                }
            }

            return true;
        } catch (Exception $ex) {
            Yii::info($ex->getMessage(), 'system');

            return false;
        }
    }

    public function deleteCertificate(string $fingerprint): bool
    {
        try {
            $output = $this->getCommandOutput(
                'certmgr',
                'delete',
                ['thumbprint' => $fingerprint]
            );
            $collection = $this->getCertInfo($output);
            if ($collection->errorCode != '0x00000000') {
                throw new Exception("Error deleting certificate $fingerprint, output: $output");
            }
            return true;
        } catch (\Exception $exception) {
            Yii::info($exception->getMessage(), 'system');
            return false;
        }
    }

    /**
     * Restart pcscd service
     *
     * @return boolean
     * @throws Exception
     */
    public function restartPcscdService()
    {
        try {
            $command    = 'service pcscd restart';
            $output     = null;
            $returnVar = null;

            exec($command, $output, $returnVar);
            if ($returnVar !== 0) {
                throw new Exception('Error restarting service pcscd. Check your access rights or cprocsp-rdr-pcsc package installation');
            }

            return true;
        } catch (Exception $ex) {
            Yii::info($ex->getMessage(), 'system');

            return false;
        }
    }

    /**
     * Get list of containers
     *
     * @return array|boolean Retrun list of containers from readers or false on failure
     * @throws Exception
     */
    public function getContainers()
    {
        try{
            $result = [];
            $output = $this->getCommandOutput('csptest', ['keyset', 'enum_cont', 'fqcn', 'verifyc']);
            $collection = $this->getCertInfo($output);
            if (!$collection->errorCodeOK()) {
                throw new Exception('Error getting list of containers from readers. Check CryptoPro installation');
            }

            foreach ($output as $value) {
                if ($value{0} == '\\') {
                    $result[] = $value;
                }
            }

            return $result;
        } catch (Exception $ex) {
            Yii::info($ex->getMessage(), 'system');

            return false;
        }
    }

    /**
     * Add hardware reader to CryptoPro
     *
     * @param string $reader Hardware name
     * @return boolean
     * @throws Exception
     */
    public function addHardwareReader($reader)
    {
        try {
            $output = $this->getCommandOutput('cpconfig', null,
                ['hardware' => 'reader', 'add' => '"' . $reader . '"']
            );

            $collection = $this->getCertInfo($output);
            if (!$collection->errorCodeOK()) {
                throw new Exception("Error adding hardware reader {$reader} - Check reader name and permissions");
            }

            return true;
        } catch (Exception $ex) {
            Yii::info($ex->getMessage(), 'system');

            return false;
        }
    }

    /**
     * Install certificate from specified container
     *
     * @param string $containerName Container name
     * @return array|boolean Return ['hash', 'body', 'owner'] or false on error
     * @throws Exception
     */
    public function installCertificateFromContainer($containerName)
    {
        try {
            $output = $this->getCommandOutput(
                    'certmgr', 'inst',
                    ['cont' => '"' . $containerName . '"']
            );
\Yii::info($output);
            $collection = $this->getCertInfo($output);
            if (!$collection->errorCodeOK()) {
                throw new Exception("Error installing certificate from container {$containerName}");
            }

            $container = $collection->first();
            if (!$container) {
                throw new Exception('Error getting certificate hash');
            }

            $hash = $container[static::SHA1_HASH];
            if (!$hash) {
                throw new Exception('Error getting certificate hash');
            }

            $serial = $container[static::SERIAL];
            if (!$serial) {
                throw new Exception('Error getting serial number');
            }

            $expireDate = $container[static::NOT_VALID_AFTER];
            if (!$expireDate) {
                throw new Exception('Error getting expire date');
            }

            $owner = $container[static::SUBJECT];
            $body = $this->getCertificateBody($hash);
            if ($body === false) {
                throw new Exception('Error getting certificate body');
            }

            return [
                'hash' => $hash, 'serial' => $serial, 'body' => $body,
                'owner' => $owner, 'expireDate' => $expireDate
            ];
        } catch (Exception $ex) {
            Yii::info($ex->getMessage(), 'system');

            return false;
        }
    }

    /**
     * Get certificate body
     *
     * @param string $certificateHash Certificate hash
     * @return string|boolean Certificate body in PEM format or false on error
     * @throws Exception
     */
    public function getCertificateBody($certificateHash)
    {
        try {
            if (empty($certificateHash)) {
                throw new Exception('Certificate hash is empty');
            }

            $certificateFile = Yii::getAlias('@temp/') . FileHelper::uniqueName();

            $output = $this->getCommandOutput('cryptcp', ['copycert', 'nochain', 'der'],
                ['thumbprint' => $certificateHash, 'df' => $certificateFile]
            );

            $collection = $this->getCertInfo($output);
            if (!$collection->errorCodeOK()) {
                throw new Exception("Error exporting certificate {$certificateHash} to file {$certificateFile}");
            }

            return $this->convertCertificateToPEM($certificateFile);
        } catch (\Exception $ex) {
            Yii::info($ex->getMessage(), 'system');

            return false;
        } finally {
            unlink($certificateFile);
        }
    }

    /**
     * Sign document
     *
     * @param string $documentIn       Input document
     * @param string $documentOut      Output document
     * @param string $certificateHash  Hash of certificate
     * @param string $containerPin     PIN for container
     * @param string $signPath         Signature path
     * @param string $algo             Signature & digest algorithm
     * @return boolean
     * @throws Exception
     */
    public function sign($documentIn, $documentOut, $certificateHash, $containerPin, $signPath, $algo = null)
    {
        try {
            if (empty($documentIn) || empty($documentOut) || empty($certificateHash)
                //|| empty($containerPin)
                        || empty($signPath))
            {
                throw new Exception('Empty input params');
            }

            if (!is_file($documentIn)) {
                throw new Exception('Input document for signature is not a file');
            }

            if (!is_file($documentOut)) {
                throw new Exception('Output document for signature is not a file');
            }

            if (!$this->checkCyberftCrypt()) {
                throw new Exception('Cyberft-crypt not found');
            }

            return $this->signDocument($documentIn, $documentOut, $certificateHash, $containerPin, $signPath, $algo);
        } catch (\Exception $ex) {
            Yii::info($ex->getMessage(), 'system');

            return false;
        }
    }

    /**
     * Verify document
     *
     * @param string $document         Output document
     * @param string $certificateHash  Hash of certificate
     * @param string $signPath         Signature path
     * @return boolean
     * @throws Exception
     */
    public function verify($document, $certificateHash, $signPath)
    {
        try {
            if (empty($document) || empty($certificateHash) || empty($signPath)) {
                throw new Exception('Empty input params');
            }

            if (!is_file($document)) {
                throw new Exception('Document for signature is not a file');
            }

            if (!$this->checkCyberftCrypt()) {
                throw new Exception('Cyberft-crypt not found');
            }
            /** @todo refactoring */
            $algo = CryptoProHelper::getKeyAlgo($certificateHash);

            return $this->verifyDocument($document, $certificateHash, $signPath, $algo);
        } catch (\Exception $ex) {
            Yii::info($ex->getMessage(), 'system');

            return false;
        }
    }

    protected function createCommand($command, $params)
    {
        $command = 'LD_LIBRARY_PATH="/usr/local/openssl-1.1.1/lib/:${LD_LIBRARY_PATH}" cyberft-crypt ' . $command;
        foreach($params as $key => $value) {
            if ($key == 'cert' || $key == 'sigpath' || $key == 'alg') {
                $value = '"' . $value . '"';
            }
            $command .= ' --' . $key . '=' . $value;
        }

        return $command;
    }

    /**
     * Sign document using cyberft-crypt
     *
     * @param string $documentIn       Input document
     * @param string $documentOut      Output document
     * @param string $certificateHash  Hash of certificate
     * @param string $containerPin     PIN for container
     * @param string $signPath         Signature path
     * @param string $algo             Signature & digest algorithm
     * @return boolean
     * @throws Exception
     */
    protected function signDocument($documentIn, $documentOut, $certificateHash, $containerPin, $signPath, $algo = null)
    {
        $params = [
            'cpagent' => 'builtin',
            'provider' => 'cryptopro',
            'facility' => 'syslog',
            'cert' => $certificateHash,
            'sigpath' => $signPath
        ];

        /** @todo refactoring */
        if ($algo == 'gostr2012-256') {
            $params['alg'] = '2012_256';
        } else if ($algo == 'gostr2012-512') {
            $params['alg'] = '2012_512';
        }

        try {
            $command = $this->createCommand('sign', $params);
            $pipes = null;
            $body = file_get_contents($documentIn);
            $descriptorspec = [
                0 => ['pipe', 'r'],
                1 => ['file', $documentOut, 'w'],
                2 => ['pipe', 'w'],
            ];

            $process = proc_open($command, $descriptorspec, $pipes);
            if (!is_resource($process)) {
                throw new Exception('Run cyberft-crypt error');
            }

            fwrite($pipes[0], base64_encode($containerPin) . PHP_EOL . $body);
            fclose($pipes[0]);

            $error = stream_get_contents($pipes[2]);
            fclose($pipes[2]);

            if (!empty($error)) {
                throw new Exception($error);
            }

            proc_close($process);

            return true;
        } catch (\Exception $ex) {
            Yii::error($ex->getMessage(), 'system');

            return false;
        }
    }

    /**
     * Verify document using cyberft-crypt
     *
     * @param string $document         Output document
     * @param string $certificateHash  Hash of certificate
     * @param string $signPath         Signature path
     * @return boolean
     * @throws Exception
     */
    protected function verifyDocument($document, $certificateHash, $signPath, $algo = null)
    {
        try {

            $params = [
                'provider' => 'cryptopro',
                'cpagent' => 'builtin',
                'facility' => 'syslog',
                'cert' => $certificateHash,
                'sigpath' => $signPath
            ];

            /** @todo refactoring */
            if ($algo == 'gostr2012-256') {
                $params['alg'] = '2012_256';
            } else if ($algo == 'gostr2012-512') {
                $params['alg'] = '2012_512';
            }

            $command = $this->createCommand('verify', $params);

            $pipes = null;
            $descriptorspec = [
                0 => ['pipe', 'r'],
                1 => ['pipe', 'w'],
                2 => ['pipe', 'w'],
            ];

            $process = proc_open($command, $descriptorspec, $pipes);
            if (!is_resource($process)) {
                throw new Exception('Run cyberft-crypt error');
            }

            fwrite($pipes[0], file_get_contents($document));
            fclose($pipes[0]);

            $error = stream_get_contents($pipes[2]);
            fclose($pipes[2]);
            fclose($pipes[1]);

            if (!empty($error)) {
                throw new Exception($error);
            }

            proc_close($process);

            return true;
        } catch (\Exception $ex) {
            Yii::info($ex->getMessage(), 'system');

            return false;
        }
    }

    /**
     * Check on cyberft crypt
     *
     * @return boolean
     * @throws Exception
     */
    protected function checkCyberftCrypt()
    {
        try {
            ob_start();

            passthru('cyberft-crypt');
            $out = ob_get_contents();

            ob_end_clean();

            if (strpos($out, 'command not found') !== false) {
                throw new Exception('cyberft-crypt not found');
            }

            return true;
        } catch (\Exception $ex) {
            Yii::info($ex->getMessage(), 'system');

            return false;
        }
    }

    /**
     * Convert certificate from DER to PEM
     *
     * @param string $certificatePath Path to certificate
     * @return string|boolean Return certificate body or false on error
     * @see https://www.openssl.org/docs/manmaster/apps/x509.html
     * @throws Exception
     */
    protected function convertCertificateToPEM($certificatePath)
    {
        try {
            $command    = 'LD_LIBRARY_PATH="/usr/local/openssl-1.1.1/lib:${LD_LIBRARY_PATH}" openssl x509 -inform DER';
            $pipes = null;
            $descriptorspec = [
                0 => ['pipe', 'r'],
                1 => ['pipe', 'w'],
                2 => ['pipe', 'w'],
            ];

            $process = proc_open($command, $descriptorspec, $pipes);

            if (!is_resource($process)) {
                throw new Exception('Error running openssl');
            }

            fwrite($pipes[0], file_get_contents($certificatePath));
            fclose($pipes[0]);

            $error = stream_get_contents($pipes[2]);
            fclose($pipes[2]);

            if (!empty($error)) {
                throw new Exception($error);
            }

            $result = stream_get_contents($pipes[1]);
            fclose($pipes[1]);

            proc_close($process);

            return $result;
        } catch (\Exception $ex) {
            Yii::info($ex->getMessage(), 'system');

            return false;
        }
    }

    public function checkContainerPassword($containerName, $password)
    {
        $cmd = "/opt/cprocsp/bin/amd64/csptest -keyset -check -cont '{$containerName}'";
        $pipesParams = [
            '0' => ['pipe', 'r'],
            '1' => ['pipe', 'w'],
        ];

        $pipes = null;
        $process = proc_open($cmd, $pipesParams, $pipes);
        $output = null;

        if (is_resource($process)) {
            // Если пароль непустой, потребуется его ввод, иначе не потребуется.
            if ($password) {
                fwrite($pipes['0'], $password . "\n");
            }
            fclose($pipes['0']);

            $output = stream_get_contents($pipes['1']);
            proc_close($process);
        }

        if ($output) {
            $collection = $this->getCertInfo(explode("\n", $output));
            if ($collection->errorCodeOK()) {
                return true;
            }
        }

        return false;
    }

    /**
     * Функция возвращает коллекцию контейнеров
     * @param array|string $source Массив строк вывода программы certmgr
     *                             или строка - путь к файлу сертификата
     * @param array $filter Фильтр [ключ => значение, ...] для выборки нужных контейнеров
     * @return CryptoproContainerCollection
     */
    public function getCertInfo($source = null, $filter = [])
    {
        $output = null;
        if (!$source) {
            $output = $this->getCommandOutput('certmgr', 'list');
        } else if (is_array($source)) {
            $output = &$source;
        } else {
            $output = $this->getCommandOutput('certmgr', 'list', ['file' => $source]);
        }

        if (isset($filter[static::SHA1_HASH])) {
            $filter[static::SHA1_HASH] = strtolower($filter[static::SHA1_HASH]);
        }

        return new CryptoproContainerCollection($output, $filter);
    }

    public function getCommandOutput($command, $actions = null, $params = [])
    {
        if ($command{0} != '/') {
            $command = '/opt/cprocsp/bin/amd64/' . $command;
        }

//        $command = "PATH=\"\$PATH:\$(ls -d /opt/cprocsp/{s,}bin/*|tr '\\n' ':')\" " . $command;

        if ($actions) {
            $command .= ' -' . (is_array($actions) ? implode(' -', $actions) : $actions);
        }
        foreach($params as $key => $value) {
            $command .= ' -' . $key . ' ' . $value;
        }
\Yii::info($command);
        $returnVar = null;
        $output = null;
        exec($command, $output, $returnVar);

        return $output;
    }

    public function checkCPLicense()
    {
        // Проверка актуальности лицензии КриптоПро
        $output = static::getCommandOutput('/opt/cprocsp/sbin/amd64/cpconfig', ['license', 'view']);

        if (empty($output)) {
            return false;
        }

        foreach($output as $line) {
            if (stripos($line, 'License is expired') !== false) {
                return false;
            }
        }

        return true;
    }

    public function trimSerial($serial)
    {
        if ($serial{0} == '0' && $serial{1} == 'x') {
            return substr($serial, 2);
        }

        return $serial;
    }

    public function prefixSerial($serial)
    {
        if ($serial{0} != '0' || $serial{1} != 'x') {
            return '0x' . $serial;
        }

        return $serial;
    }

}
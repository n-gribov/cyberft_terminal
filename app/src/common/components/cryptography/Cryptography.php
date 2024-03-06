<?php

namespace common\components\cryptography;

use common\components\cryptography\drivers\DriverCryptoPro;
use common\components\cryptography\drivers\DriverOpenSSL;
use yii\base\Component;

class Cryptography extends Component implements CryptographyInterface
{
    /*
     * Свойство для хранения закэшированной
     * текущей версии cyberft crypt
     */
    private $cyberftCryptVersion = '';

    /**
     * Get CryptoPro driver
     * @return DriverCryptoPro
     */
    public function getDriverCryptoPro()
    {
        return new DriverCryptoPro();
    }

    /**
     * Get OpenSSL driver
     * @return DriverOpenSSL
     */
    public function getDriverOpenSSL()
    {
        return new DriverOpenSSL();
    }

    /**
     * Get fingerprint
     * @param string  $certificate    Certificate. X.509 resource or string. String may contain key path or key in PEM format
     * @param string  $hash_algorithm Hash algorithms
     * @param boolean $raw_output     Output type. Raw or hex
     * @return string|boolean Return fingerprint or false on error
     */
    public function getFingerprint($certificate, $hash_algorithm = 'sha1', $raw_output = false)
    {
       $driver = $this->getDriverOpenSSL();

       return $driver->getFingerprint($certificate, $hash_algorithm, $raw_output);
    }

    public function sign($data, $privateKey, $password = null)
    {
        // not implemented
        return false;
    }

    /**
     * Verify data
	 * @param string         $data          Data to verify
	 * @param string         $signature     Signature to verify
	 * @param string         $certificate   Certificate or public key. File path or string in PEM format
     * @param integer|string $signature_alg Signature algorithms
     * @return boolean
     */
    public function verify($data, $signature, $certificate, $signature_alg = 'sha1')
    {
        $driver = $this->getDriverOpenSSL();

        return $driver->verify($data, $signature, $certificate, $signature_alg);
    }

    /**
     * Метод получает текущую версию инструмента подписания
     */
    public function getCyberftCryptVersion()
    {
        // Проверка закэшированного значения свойства
        if (empty($this->cyberftCryptVersion)) {
            // Получение результата выполнения команды получения версии cyberft-crypt
            $output = null;
            $return_var = null;

            $command = 'LD_LIBRARY_PATH="/usr/local/openssl-1.1.1/lib:${LD_LIBRARY_PATH}" /var/www/cyberft/app/src/bin/cyberft-crypt version 2>&1';

            exec($command, $output, $return_var);

            if (empty($output) || !isset($output[0])) {
                $this->cyberftCryptVersion = 'n/a';
            }

            // Строка с результатом выполнения команды
            $result = $output[0];

            // Возвращаем необходимую подстроку
            $this->cyberftCryptVersion = substr($result, 0);
        }

        return $this->cyberftCryptVersion;
    }

}

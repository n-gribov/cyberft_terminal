<?php

namespace console\controllers;

use common\helpers\CryptoProHelper;
use common\helpers\FileHelper;
use common\models\CryptoproKey;
use Exception;
use Yii;
use yii\console\Controller;

class CryptoproController extends Controller
{
    public $modelCerts = [
        'fileact' => 'addons\fileact\models\FileactCryptoproCert',
        'iso20022' => 'addons\ISO20022\models\ISO20022CryptoproCert',
    ];

    /**
     * Метод выводит текст подсказки
     */
    public function actionIndex()
    {
        $this->run('/help', ['cryptopro']);
        return Controller::EXIT_CODE_NORMAL;
    }

    /**
     * Get list of available readers
     *
     * @return integer Return 0 if success or 1 on error
     */
    public function actionGetReaders()
    {
        $driver = Yii::$app->cryptography->getDriverCryptoPro();

        $result = $driver->getHardwareReaderList();
        if ($result === false) {
            echo "Get list of readers error. See log for more information.\n";
            return self::EXIT_CODE_ERROR;
        }

        if (count($result) === 0) {
            echo "0 readers mount\n";

            return self::EXIT_CODE_NORMAL;
        }

        echo "Available readers: \n";

        foreach ($result as $value) {
            echo "{$value}\n";
        }

        return self::EXIT_CODE_NORMAL;
    }

    /**
     * Restart reader (pcscd) service
     *
     * @return integer Return 0 if success or 1 on error
     */
    public function actionRestartReaderService()
    {
        $driver = Yii::$app->cryptography->getDriverCryptoPro();

        $result = $driver->restartPcscdService();
        if ($result === false) {
            echo "Restart service problem. Maybe you don't have enough rights?\n";

            return self::EXIT_CODE_ERROR;
        }

        echo "Service was restarted\n";

        return self::EXIT_CODE_NORMAL;
    }

    /**
     * Add specific reader for CryptoPro
     *
     * @param string $reader Reader name
     * @return integer Return 0 if success or 1 on error
     */
    public function actionAddReader($reader)
    {
        if (empty($reader)) {
            echo "You should set reader. You can get list of available readers using command './yii cryptopro/get-readers'\n";

            return self::EXIT_CODE_ERROR;
        }

        $driver = Yii::$app->cryptography->getDriverCryptoPro();

        $result = $driver->addHardwareReader($reader);
        if ($result === false) {
            echo "Add reader error. See log for more information.\n";

            return self::EXIT_CODE_ERROR;
        }

        echo "Reader [{$reader}] was added\n";

        return self::EXIT_CODE_NORMAL;
    }

    /**
     * Get list of containers on reades
     *
     * @return integer Return 0 if success or 1 on error
     */
    public function actionGetContainers()
    {
        $driver = Yii::$app->cryptography->getDriverCryptoPro();

        $result = $driver->getContainers();
        if ($result === false) {
            echo "Get list of containers from readers error. See log for more information.\n";
            return self::EXIT_CODE_ERROR;
        }

        if (count($result) === 0) {
            echo "0 containers available on readers in this moment";
            return self::EXIT_CODE_NORMAL;
        }

        echo "Available containers:\n";

        foreach ($result as $value) {
            echo "{$value}\n";
        }

        return self::EXIT_CODE_NORMAL;
    }

    /**
     * Install certificate from specific container
     *
     * @param string $containerName Container name
     * @return integer Return 0 if success or 1 on error
     */
    public function actionInstallCertificateFromContainer($containerName)
    {
        $containerNameRaw = stripcslashes($containerName);
        $containerName = str_replace('"', "\\\"", $containerNameRaw);

        if (empty($containerName)) {
            echo "You should set container name. You can get list of available containers using command './yii cryptopro/get-containers'\n";

            return self::EXIT_CODE_ERROR;
        }

        $driver = Yii::$app->cryptography->getDriverCryptoPro();

        $result = $driver->installCertificateFromContainer($containerName);
        if ($result === false) {
            echo "Install certificate from container[{$containerName}] error. See log for more information.\n";

            return self::EXIT_CODE_ERROR;
        }

        $dbResult = $this->addCryptoProCertificateToDB($result);
        if ($dbResult === 1) {
            echo "Add certificate to DB error\n";

            return self::EXIT_CODE_ERROR;
        }

        echo "Certificate[{$result['hash']}] from container[{$containerName}] was added.\n";

        return self::EXIT_CODE_NORMAL;
    }

    /**
     * Update certificate from specific container
     *
     * @param string $containerName Container name
     * @return integer 0 if success or 1 on error
     */
    public function actionUpdateCertificateFromContainer($containerName)
    {
        if (empty($containerName)) {
            echo "You should set container name. You can get list of available containers using command './yii cryptopro/get-containers'\n";

            return self::EXIT_CODE_ERROR;
        }

        $driver = Yii::$app->cryptography->getDriverCryptoPro();
        $result = $driver->installCertificateFromContainer($containerName);
        if ($result === false) {
            echo "Error installing certificate from container[{$containerName}]. See log for more information.\n";

            return self::EXIT_CODE_ERROR;
        }

        $dbResult = $this->updateCryptoProCertificateToDB($result);
        if ($dbResult === 1) {
            echo "Error adding certificate to database\n";

            return self::EXIT_CODE_ERROR;
        }

        echo "Certificate[{$result['hash']}] from container[{$containerName}] was added.\n";

        return self::EXIT_CODE_NORMAL;
    }

    /**
     * Install certificates from readers
     *
     * @return integer Return 0 if success or 1 on error
     */
    public function actionInstallCertificates()
    {
        $readerResult = $this->addReaders();
        if ($readerResult === false) {
            echo "Add readers error. See log for more information.\n";

            return self::EXIT_CODE_ERROR;
        }

        echo 'Added ' . count($readerResult) . " readers.\n";

        $certificateResult = $this->addCertificates();
        if ($certificateResult === false) {
            echo "Install certificate error. See log for more information.\n";

            return self::EXIT_CODE_ERROR;
        }

        echo "Installed " . count($certificateResult) . " certificates.\n";

        return self::EXIT_CODE_NORMAL;
    }

    /**
     * Add CryptoPro certificate to DB
     *
     * @param string $result Array of cert attributes
     * @return integer Return -1 if certificate already exist, 0 if added or 1 on error
     * @throws Exception
     */
    protected function addCryptoProCertificateToDB($result)
    {
        $hash = $result['hash'];
        try {
            $count = CryptoproKey::find()->where(['keyId' => $hash])->count();
            if ((int) $count !== 0) {
                Yii::warning("Certificate[{$hash}] already exist [{$count}].");

                return -1;
            }

            $date = $result['expireDate'];

            // Преобразовываем дату в нужный формат для записи в БД
            // Убираем лишний пробел между датой и временем
            $date = str_replace('  ', ' ', $date);

            // Заменяем / на . для более удобного преобразования даты
            $date = str_replace('/', '.', $date);

            // Преобразуем дату в timestamp
            $date = strtotime($date);

            // Преобразуем дату в нужный формат
            $date = date('Y-m-d H:i:s', $date);

            $key = new CryptoproKey([
                'keyId' => $hash,
                'certData'  => $result['body'],
                'ownerName' => $result['owner'],
                'serialNumber' => CryptoProHelper::driver()->trimSerial($result['serial']),
                'expireDate' => $date
            ]);

            if (!$key->save()) {
                throw new Exception('Error adding CryptoPro key to database');
            }

            return 0;
        } catch (Exception $ex) {
            \Yii::warning($ex->getMessage());

            return 1;
        }
    }

    protected function updateCryptoProCertificateToDB($hash, $body, $owner, $serial = null)
    {
        try {
            $key = CryptoproKey::findOne(['keyId' => $hash]);
            if (empty($key)) {
                $key = new CryptoproKey();
            }

            $key->keyId     = $hash;
            $key->certData  = $body;
            $key->ownerName = $owner;
            $key->serialNumber = $serial;

            if (!$key->save()) {
                throw new Exception('Error adding CryptoPro key to database');
            }

            return 0;
        } catch (Exception $ex) {
            \Yii::warning($ex->getMessage());

            return 1;
        }
    }

    /**
     * Add available readers
     *
     * @return array|boolean List of added readers or false on error
     * @throws Exception
     */
    private function addReaders()
    {
        try {
            $result = [];
            $driver = Yii::$app->cryptography->getDriverCryptoPro();

            $readers = $driver->getHardwareReaderList();
            if ($readers === false) {
                throw new Exception("Get list of readers error. See log for more information.");
            }

            foreach ($readers as $value) {
                $readerResult = $driver->addHardwareReader($value);
                if ($readerResult === false) {
                    Yii::warning("Add reader[{$value}] error!");
                } else {
                    $result[] = $value;
                }
            }

            return $result;
        } catch (\Exception $ex) {
            Yii::warning($ex->getMessage());

            return false;
        }
    }

    /**
     * Add certificates from containers
     *
     * @return array|boolean List of added certificates or FLASE on error
     * @throws Exception
     */
    private function addCertificates()
    {
        try {
            $result = [];
            $driver = Yii::$app->cryptography->getDriverCryptoPro();

            $containers = $driver->getContainers();

            if ($containers === false) {
                throw new Exception("Get list of containers from readers error. See log for more information.");
            }

            foreach ($containers as $value) {
                $certificateResult = $driver->installCertificateFromContainer($value);

                if ($certificateResult !== false) {
                    $dbResult = $this->addCryptoProCertificateToDB($certificateResult);
                    if ($dbResult === 0) {
                        $result[] = $certificateResult['hash'];
                    }
                }
            }

            return $result;
        } catch (\Exception $ex) {
            Yii::warning($ex->getMessage());

            return false;
        }
    }

    /**
     * Add certificate from terminal; add 'ignore-status' to keep status in DB
     */
    public function actionAddCertificatesFromTerminal($model, $ignoreStatus = null)
    {
        $isIgnored = ($ignoreStatus === 'ignore-status');

        try {
            $model = strtolower($model);

            if (!isset($this->modelCerts[$model])) {
                throw new Exception('Model certificates not found.');
            }

            $modelClass = $this->modelCerts[$model];
            $certsQuery = $modelClass::find();
            if (!$isIgnored) {
                $certsQuery->where(['status' => $modelClass::STATUS_NOT_READY]);
            }

            $certs = $certsQuery->all();
            $driver = Yii::$app->cryptography->getDriverCryptoPro();

            foreach ($certs as $cert) {
                $tempFile = Yii::getAlias('@temp/') . FileHelper::uniqueName();

                if (file_put_contents($tempFile, $cert->certData)) {
                    if (false === $driver->installCertificate($tempFile)) {
                        throw new Exception('Get list of containers from readers error. See log for more information.');
                    }

                    if (!$isIgnored) {
                        $cert->status = $modelClass::STATUS_READY;
                        if ($cert->save(false, ['status'])) {
                            Yii::info('CryptoPro certificate #' . $cert->id . ' activated', 'system');
                        }
                    } else {
                        Yii::info('CryptoPro certificate #' . $cert->id . ' added and status not changed', 'system');
                    }
                }
            }

            return true;
        } catch (\Exception $ex) {
            Yii::info($ex->getMessage(), 'system');
            echo $ex->getMessage() . PHP_EOL;

            return self::EXIT_CODE_ERROR;
        }
    }
}

<?php
namespace common\helpers;

use common\components\cryptography\drivers\DriverCryptoPro;
use common\helpers\Address;
use common\models\CryptoproCert;
use common\models\CryptoproContainerCollection;
use common\models\CryptoproKey;
use common\models\CryptoproKeyBeneficiary;
use common\models\cyberxml\CyberXmlDocument;
use common\models\Terminal;
use common\modules\certManager\components\ssl\X509FileModel;
use Exception;
use Yii;
use yii\helpers\BaseInflector;

class CryptoProHelper
{
    const DEFAULT_DIGEST_ALGO = 'http://www.w3.org/2001/04/xmldsig-more#gostr3411';
    const DEFAULT_SIGNATURE_ALGO = 'http://www.w3.org/2001/04/xmldsig-more#gostr34102001-gostr3411';

    const GOSTR2012_DIGEST_ALGO_256 = 'urn:ietf:params:xml:ns:cpxmlsec:algorithms:gostr34112012-256';
    const GOSTR2012_SIGNATURE_ALGO_256 = 'urn:ietf:params:xml:ns:cpxmlsec:algorithms:gostr34102012-gostr34112012-256';

    const GOSTR2012_DIGEST_ALGO_512 = 'urn:ietf:params:xml:ns:cpxmlsec:algorithms:gostr34112012-512';
    const GOSTR2012_SIGNATURE_ALGO_512 = 'urn:ietf:params:xml:ns:cpxmlsec:algorithms:gostr34102012-gostr34112012-512';

    const SHA256_DIGEST_ALGO = 'http://www.w3.org/2001/04/xmlenc#sha256';
    const SHA256_SIGNATURE_ALGO = 'http://www.w3.org/2001/04/xmldsig-more#rsa-sha256';

    private static $_driver;

    public static function updateSignatureTemplate($signatureTemplate, $signatureId, $fingerprint, $algo = null, $certBody = null)
    {
        $signatureAlgo = static::DEFAULT_SIGNATURE_ALGO;
        $digestAlgo = static::DEFAULT_DIGEST_ALGO;
        if ($algo == 'gostr2012-256') {
            $signatureAlgo = static::GOSTR2012_SIGNATURE_ALGO_256;
            $digestAlgo = static::GOSTR2012_DIGEST_ALGO_256;
        } else if ($algo == 'gostr2012-512') {
            $signatureAlgo = static::GOSTR2012_SIGNATURE_ALGO_512;
            $digestAlgo = static::GOSTR2012_DIGEST_ALGO_512;
        } else if ($algo === 'sha256') {
            $signatureAlgo = static::SHA256_SIGNATURE_ALGO;
            $digestAlgo = static::SHA256_DIGEST_ALGO;
        }

        $subjectName = null;
        if ($certBody) {
            $x509 = X509FileModel::loadData($certBody);
            $subjectName = htmlentities($x509->subjectString, ENT_XML1);
            $certBody = CertsHelper::linearize($certBody);
        }

        //$signingTime = '2020-09-09T15:57:39+03:00'; //date('c');
        $signingTime = date('c');

        return str_replace(
            ['{SIGNATURE_ID}', '{FINGERPRINT}', '{SIGNATURE_ALGO}', '{DIGEST_ALGO}',
                '{SUBJECTNAME}', '{CERTIFICATE}', '{SIGNING_TIME}'],
            [$signatureId, $fingerprint, $signatureAlgo, $digestAlgo, $subjectName, $certBody, $signingTime],
            $signatureTemplate,
        );
    }

    public static function getKeyAlgo($fingerprint)
    {
        $collection = static::getCertInfo(null, [DriverCryptoPro::SHA1_HASH => $fingerprint]);
        $container = $collection->first();
        if ($container) {
            $algo = $container[DriverCryptoPro::SIGNATURE_ALGORITHM];
            if (strpos($algo, '34.11-2012/34.10-2012') !== false) {
                if (strpos($algo, '512') !== false) {
                    return 'gostr2012-512';
                } else {
                    return 'gostr2012-256';
                }
            }
        }

        return null;
    }

    public static function sign($moduleName, $model, $returnModel = false)
    {
        $passwordKey = getenv('COOKIE_VALIDATION_KEY');
        $driver = Yii::$app->cryptography->getDriverCryptoPro();

        $sender = ($model instanceof CyberXmlDocument) ? $model->senderId : $model->sender;
        $receiver = ($model instanceof CyberXmlDocument) ? $model->receiverId : $model->receiver;

        $cryptoProSignKeysAll = CryptoproKey::findByTerminalId(Terminal::getIdByAddress($sender));
        $cryptoProSignKeys = [];

        // Фильтруем ключи в зависимости от указанного в документе получателя (CYB-4581)
        foreach ($cryptoProSignKeysAll as $cryptoProSignKey) {
            $cryptoProBeneficiaries = CryptoproKeyBeneficiary::findAll(['keyId' => $cryptoProSignKey->id]);
            if (count($cryptoProBeneficiaries) == 0) {
                $cryptoProSignKeys[] = $cryptoProSignKey;
                continue;
            }
            $cryptoProBeneficiaries = CryptoproKeyBeneficiary::findAll(
                [
                    'keyId' => $cryptoProSignKey->id,
                    'terminalId' => $receiver
                ]);
            if (count($cryptoProBeneficiaries) != 0) {
                $cryptoProSignKeys[] = $cryptoProSignKey;
            }
        }

        $module = Yii::$app->getModule($moduleName);

        $templatePath = Yii::getAlias('@temp/') . FileHelper::uniqueName();
        $tmpOutPath = Yii::getAlias('@temp/') . FileHelper::uniqueName();

        if (file_put_contents($tmpOutPath, '1') === false) {
            throw new Exception('Could not prepare output file for CryptoPro signing');
        }

        if (empty($cryptoProSignKeys)) {
            Yii::info("No CryptoPro keys found for terminal {$model->sender}");
        }

        try {
            /** @var CryptoproKey $cryptoProSignKey */
            foreach ($cryptoProSignKeys as $cryptoProSignKey) {
                $hash = $module->settings->useSerial
                        ? $cryptoProSignKey->serialNumber
                        : $cryptoProSignKey->keyId;

                $algo = $cryptoProSignKey->algo;
                if (!$algo) {
                    $algo = static::getKeyAlgo($cryptoProSignKey->keyId);
                }

                $password = Yii::$app->security->decryptByKey(base64_decode($cryptoProSignKey->password), $passwordKey);
                $signatureId = uniqid();
                $signatureIdFull = $model->getPrefixSignature() . $signatureId;
                $sigPath = "//*[@Id='$signatureIdFull']";

                $signatureTemplate = $model->getSignatureTemplate($signatureId, $hash, $algo, $cryptoProSignKey->certData);

                if (!file_put_contents($templatePath, $signatureTemplate)) {
                    throw new Exception('Error saving xml template file for CryptoPro signing');
                }

                $result = $driver->sign(
                    $templatePath,
                    $tmpOutPath,
                    $cryptoProSignKey->keyId,
                    $password,
                    $sigPath,
                    $algo
                );

                if (!$result) {
                    throw new Exception('CryptoPro signing error');
                }

                $dom = new \DOMDocument('1.0', 'UTF-8');
                $dom->loadXML(file_get_contents($tmpOutPath), LIBXML_PARSEHUGE);
                $xpath = new \DOMXPath($dom);
                $rootNamespace = $dom->lookupNamespaceUri($dom->namespaceURI);
                $xpath->registerNameSpace('x', $rootNamespace);

                $objectNode = $xpath->query("//ds:Signature[@Id='{$signatureIdFull}']/ds:Object")->item(0);

                $keyInfo = new \SimpleXMLElement('<ds:KeyInfo xmlns:ds="http://www.w3.org/2000/09/xmldsig#"/>');
                $x509Data = $keyInfo->addChild('X509Data');
                $x509 = X509FileModel::loadData($cryptoProSignKey->certData);
                $x509Data->X509SubjectName = $x509->subjectString;
                $x509Data->X509Certificate = CertsHelper::linearize($cryptoProSignKey->certData);
                $keyInfo->KeyName = $hash;

                $elementDom = $dom->importNode(dom_import_simplexml($keyInfo), true);
                $objectNode->parentNode->insertBefore($elementDom, $objectNode);

                $out = $dom->saveXML();
                $model->loadFromString($out);
            }

            return $returnModel ? $model : true;

        } catch (Exception $ex) {
            Yii::error($ex->getMessage() . PHP_EOL . $ex->getTraceAsString());

            return null;
        } finally {
            if (is_readable($templatePath)) {
                unlink($templatePath);
            }
            if (is_readable($tmpOutPath)) {
                unlink($tmpOutPath);
            }
        }
    }

    public static function verify($moduleName, $documentPath, $senderId, $receiverId)
    {
        if (is_readable($documentPath)) {

            $certModel = CryptoproCert::getInstance($moduleName);
            $certs = $certModel::findAll([
                'status' => CryptoproCert::STATUS_READY,
                'senderTerminalAddress' => $senderId,
                'terminalId' => Terminal::getIdByAddress(Address::fixSender($receiverId)),
            ]);

            $certMap = [];
            $serialMap = [];
            foreach ($certs as $cert) {
                $fingerprint = strtoupper($cert->keyId);
                $certMap[$fingerprint] = $cert->serialNumber;
                $serialMap[$cert->serialNumber] = $fingerprint;
            }

            if (count($certMap)) {
                libxml_use_internal_errors(true);
                $xml = simplexml_load_file($documentPath, 'SimpleXMLElement', LIBXML_PARSEHUGE);
                var_export(libxml_get_errors());
                $keys = [];
                $xml->registerXPathNamespace('c', 'http://www.w3.org/2000/09/xmldsig#');
                $signatures = $xml->xpath('//c:Signature');
                $index = 1;
                foreach ($signatures as $signature) {
                    $dom = dom_import_simplexml($signature);
                    $nodeList = $dom->getElementsByTagName('KeyName');
                    if (!empty($nodeList)) {
                        $hash = strtoupper($nodeList->item(0)->nodeValue);
                    }
                    if (!empty($hash)) {
                        /**
                         * проверить, есть ли в наличии серт с таким фингерпринтом
                         */
                        if (!isset($certMap[$hash])) {
                            // фингерпринт не опознан, попробуем найти по сериалу
                            if (isset($serialMap[$hash])) {
                                $hash = $serialMap[$hash];
                            }
                        }

                        $keys[$hash] = $index;
                    }
                    unset($hash);
                    $index++;
                }

                $actualKeys = [];
                foreach ($certMap as $keyId => $serial) {
                    if (isset($keys[$keyId])) {
                        $actualKeys[$keyId] = $keys[$keyId];
                    }
                }

                if (count($certMap) != count($actualKeys)) {
                    Yii::info('The number of required signatures does not match the number of entries in the file', 'system');

                    return false;
                }

                $driver = Yii::$app->cryptography->getDriverCryptoPro();
                foreach ($actualKeys as $certificateHash => $index) {
                    $sigPath = "(//*[local-name() = 'Signature'])[{$index}]";

                    if (false === $driver->verify($documentPath, $certificateHash, $sigPath)) {
                        return false;
                    }
                }

                return true;
            }

            return 'skipped';
        }

        return false;
    }

    /**
     * Функция подготавливает сертификат КриптоПро к добавлению в certmng
     * Модифицированная копипаста кода из console cryptopro/addCertificatesFromTerminal
     */
    public static function addCertificateFromTerminal($cert)
    {
        try {
            $tempFile = Yii::getAlias('@temp/') . FileHelper::uniqueName();

            if (file_put_contents($tempFile, $cert->certData)) {

                $container= self::installCertificate($tempFile);

                if ($container === false) {
                    throw new Exception('Error adding certificate - see log for more information');
                }

                if (is_array($container) && isset($container[DriverCryptoPro::SERIAL])) {
                    // Если найден серийный номер, записываем его в модель сертификата
                    $serial = static::driver()->trimSerial($container[DriverCryptoPro::SERIAL]);
                    $cert->serialNumber = $serial;
                    // Сохранить модель в БД
                    $cert->save();
                }

            }
        } catch (\Exception $ex) {
            echo $ex->getMessage() . PHP_EOL;

            return false;
        }
    }

    public static function downloadCertificate($ownerName, $fingerprint, $certData, $fileExt = 'cer')
    {
        // Формируем имя для скачиваемого сертификата
        // Преобразуем имя владельца в slug-вид
        $owner = BaseInflector::slug($ownerName, '-');

        // Полное имя файла
        $filename = $owner . '-' . $fingerprint . '.' . $fileExt;

        return \Yii::$app->response->sendContentAsFile($certData, $filename);
    }

    /**
     * Функция непосредственно добавляет сертификат КриптоПро в certmgr
     * @param $certificatePath
     * @return bool
     */
    public static function installCertificate($certificatePath)
    {
        try {
            if (!is_file($certificatePath)) {
                throw new Exception("[{$certificatePath}] is not a file");
            }

            $collection = static::getCertInfo($certificatePath);
            $container = $collection->first();
            if (!$container) {
                throw new Exception("Install certificate[{$certificatePath}] error - check certificate path");
            }

            $sha1hash = isset($container[DriverCryptoPro::SHA1_HASH]) ? $container[DriverCryptoPro::SHA1_HASH] : false;
            if ($sha1hash) {
                // Если из нового сертификата получен serial number,
                // ищем его среди текущих добавленных сертификатов
                $collection = static::getCertInfo(null, [DriverCryptoPro::SHA1_HASH => $sha1hash]);

                if (!empty($collection->getContainers())) {
                    return true;
                }
            }

            // Добавляем сертификат, если он не найден
            if (static::driver()->installCertificate($certificatePath)) {
                return $container;
            }

        } catch (Exception $ex) {
            Yii::info($ex->getMessage(), 'system');
        }

        return false;
    }

    public static function deleteCertificate(string $fingerprint): bool
    {
        return static::driver()->deleteCertificate($fingerprint);
    }

    /**
     * Проверка состояния лицензии КриптоПро
     */
    public static function checkCPLicense()
    {
        return static::driver()->checkCPLicense();
    }

    /**
     * Функция возвращает коллекцию контейнеров
     * @param array|string $source Массив строк вывода программы certmgr
     *                             или строка - путь к файлу сертификата
     * @param array $filter Фильтр [ключ => значение, ...] для выборки нужных контейнеров
     * @return CryptoproContainerCollection
     */
    public static function getCertInfo($source = null, $filter = [])
    {
        return static::driver()->getCertInfo($source, $filter);
    }

    public static function getCommandOutput($command, $actions = null, $params = [])
    {
        return static::driver()->getCommandOutput($command, $actions, $params);
    }

    public static function checkContainerPassword($containerName, $password)
    {
        return static::driver()->checkContainerPassword($containerName, $password);
    }

    public static function driver(): DriverCryptoPro
    {
        if (!static::$_driver) {
            static::$_driver = Yii::$app->cryptography->getDriverCryptoPro();
        }

        return static::$_driver;
    }

}

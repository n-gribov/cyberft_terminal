<?php

namespace common\modules\certManager\components\ssl;

use yii\web\UploadedFile;

/**
 * Class X509FileModel
 *
 * @property string $filePath
 * @property string $fingerprint - sha256
 * @property string $subjectName - string subject info
 * @property array $subject - parsed subject info
 * @property string $issuerName - string subject info
 * @property array $issuer - parsed issuer info
 * @property \DateTime $validTo
 * @property \DateTime $validFrom
 * @property integer $version
 * @property integer $isFile - is really file found by path
 * @property \yii\web\UploadedFile $newFile - file for save or replace current file
 * @package common\modules\certManager\components\ssl
 */
class X509FileModel extends \common\base\Model {

    protected $_resource;
    protected $_filePath;
    protected $_body;
    protected $_rawData;
    protected $_fingerprint;
    protected $_isFile = false;
    protected $type;

    /**
     * @var \yii\web\UploadedFile путь до нового файла который необходимо использовать в каченстве сертификата
     */
    protected $_newFile;

    /**
     * Функция подгружает сертификат из указанного параметром файла
     * @param $filePath Файл сертификата
     * @return X509FileModel
     */
    public static function loadFile($filePath)
    {
        $item = new self;

        return $item->setFilePath($filePath)->initProperties($filePath);
    }

    /**
     * Функция формирует сертификат из переданных данных
     * @param string $body данные сертификата
     * @return X509FileModel
     */
    public static function loadData($body)
    {
        $item = new self;

        return $item->initProperties($body);
    }

    public function rules()
    {
        return [
            [
                'newFile', 'file',
                'extensions' => ['crt', 'pem', 'der'],
                'skipOnEmpty' => true,
//				'mimeTypes' => ['application/x-x509-ca-cert'],
                'checkExtensionByMimeType' => false
            ],
            ['newFile', 'validateBody']
        ];
    }

    /**
     *
     * @return bool
     */
    public function validateBody($attribute, $params)
    {
        // пока кондово, если положат какую-либо чушь, то хотя бы одна из функций вальнется
        try {
            if ($this->_newFile) {
                $path = $this->_newFile;
            } else {
                $path = $this->_filePath;
            }

            if (($r = $this->readCertFile($path))) {
                openssl_x509_free($r);
                $this->initProperties($path);

                return true;
            } else {
                $this->addError($attribute, \Yii::t('app/cert', 'Source file could not be read'));

                return false;
            }
        } catch (\Exception $e) {
            $this->addError($attribute, \Yii::t('app/cert', 'Source file could not be read'));

            return false;
        }
    }

    public function attributes()
    {
        return [
            'filePath',
            'fingerprint',
            'subject',
            'subjectName',
            'issuer',
            'issuerName',
            'validTo',
            'validFrom',
            'version',
        ];
    }

    public function attributeLabels()
    {
        return [
            'filePath' => \Yii::t('app/cert', 'Certificate file path'),
            'newFile' => \Yii::t('app/cert', 'New certificate'),
            'fingerprint' => \Yii::t('app/cert', 'Certificate Thumbprint'),
            'serialNumber' => \Yii::t('app/cert', 'Serial Number'),
            'subjectName' => \Yii::t('app/cert', 'Subject Name'),
            'subject' => \Yii::t('app/cert', 'Subject'),
            'issuerName' => \Yii::t('app/cert', 'Issuer Name'),
            'issuer' => \Yii::t('app/cert', 'Issuer'),
            'validTo' => \Yii::t('app/cert', 'Valid before'),
            'validFrom' => \Yii::t('app/cert', 'Valid from'),
            'version' => \Yii::t('app/cert', 'Format version'),
            'body' => \Yii::t('app/cert', 'Body'),
        ];
    }

    public function getFingerprint(bool $forceUppercase = true)
    {
        if (!is_resource($this->_resource)) {
            return null;
        }

        $fingerprint = openssl_x509_fingerprint($this->_resource, 'sha1', false);
        return $forceUppercase ? strtoupper($fingerprint) : $fingerprint;
    }

    public function getSerialNumber()
    {
        $serialNumber = $this->_rawData['serialNumber'] ?? null;
        if ($serialNumber === '' || $serialNumber === null) {
            return null;
        }

        // Если есть что-то кроме цифр, то считаем, что значение уже в hex
        if (preg_match('/\D/', $serialNumber)) {
            return preg_replace('/^0x/i', '', $serialNumber);
        }

        // Серийный номер возвращается в десятиричном виде, нужно перевести в hex
        $hexSerial = strtoUpper(gmp_strval(gmp_init($serialNumber, 10), 16));
        // Добить до четного количества байтов лидирующим нулём
        if (strlen($hexSerial) & 1) {
            $hexSerial = '0' . $hexSerial;
        }

        return $hexSerial;
    }

    public function getBody()
    {
        if ($this->type === 'DER') {
            return $this->der2pem($this->_body);
        } else {
            return $this->_body;
        }
    }

    public function getRawData()
    {
        return $this->_rawData;
    }

    /**
     * @return array|null
     */
    public function getSubject()
    {
        return isset($this->_rawData['subject']) ? $this->_rawData['subject'] : null;
    }

    /**
     * @return string|null
     */
    public function getSubjectString()
    {
        if (!isset($this->_rawData['subject'])) {
            return null;
        }

        $out = [];
        foreach ($this->_rawData['subject'] as $key => $value) {
            $out[] = $key . '=' . $value;
        }

        return implode(';', $out);
    }

    public function getSubjectCN()
    {
        if (!isset($this->_rawData['subject'])) {
            return null;
        }
        return $this->_rawData['subject']['CN'];
    }

    /**
     * @return string|null
     */
    public function getSubjectName()
    {
        // В случае использования не латинских символов $this->_rawData['name'] может быть закодирована в hex, поэтому
        // соберем имя из subject.

        if (!isset($this->_rawData['subject'])) {
            return null;
        }

        $str = '';
        foreach ($this->_rawData['subject'] as $k => $v) {
            $str .= '/' . $k . '=' . $v;
        }

        return $this->fixEncoding($str);
    }

    /**
     * @return string|null
     */
    public function getIssuer()
    {
        return isset($this->_rawData['issuer']) ? $this->_rawData['issuer'] : null;
    }

    /**
     * @return string|null
     */
    public function getIssuerName()
    {
        if (!isset($this->_rawData['issuer'])) {
            return null;
        }

        $str = '';
        foreach ($this->_rawData['issuer'] as $k => $v) {
            $str .= '/' . $k . '=' . $v;
        }

        return $this->fixEncoding($str);
    }

    /**
     * @return string|null
     */
    public function getVersion()
    {
        return isset($this->_rawData['version']) ? $this->_rawData['version'] : null;
    }

    /**
     * @return \DateTime|null
     */
    public function getValidTo()
    {
        return isset($this->_rawData['validTo']) ? static::parseValidityDateTime($this->_rawData['validTo']) : null;
    }

    /**
     * @return \DateTime|null
     */
    public function getValidFrom()
    {
        return isset($this->_rawData['validFrom']) ? static::parseValidityDateTime($this->_rawData['validFrom']) : null;
    }

    /**
     * @return \DateTime|null
     */
    private static function parseValidityDateTime($value)
    {
        $format = strlen($value) > 13 ? 'YmdHis?' : 'ymdHis?'; // e.g. 170906130457Z or 20570827130457Z
        $dateTime = date_create_from_format($format, $value);

        return $dateTime === false ? null : $dateTime;
    }

    /**
     * @param string|UploadedFile $newFile
     * @return $this
     */
    public function setNewFile($newFile)
    {
        if (is_a($newFile, UploadedFile::className())) {
            $this->_newFile = $newFile->tempName;
        } else {
            $this->_newFile = $newFile;
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getNewFile()
    {
        return $this->_newFile;
    }

    /**
     * @return string
     */
    public function getFilePath()
    {
        return $this->_filePath;
    }

    /**
     * @param string $filePath
     * @return $this
     */
    public function setFilePath($filePath)
    {
        $this->_filePath = $filePath;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getIsFile()
    {
        return $this->_isFile;
    }

    public function __destruct()
    {
        if (isset($this->_resource)) {
            openssl_x509_free($this->_resource);
        }
    }

    protected function initProperties($path)
    {
        if ($this->isFile($path)) {
            $this->_resource = $this->readCertFile($path);
            $this->_body = file_get_contents($path);
            $this->_isFile = true;
        } else {
            $this->_resource = $this->readCertBody($path);
            $this->_body = $path;
        }
        $this->_rawData = openssl_x509_parse($this->_resource);

        return $this;
    }

    private function isFile(string $path): bool
    {
        try {
            return is_file($path);
        } catch (\Throwable $exception) {
            return false;
        }
    }

    /**
     * @param $path
     * @return resource
     */
    protected function readCertFile($path)
    {
        if ('application/octet-stream' === mime_content_type($path)) {
            $this->type = 'DER';

            return openssl_x509_read($this->der2pem(file_get_contents($path)));
        } else {
            $this->type = 'PEM';

            return openssl_x509_read('file://' . $path);
        }
    }

    /**
     * Читает тело сертификата из строки
     * @param string $body
     * @return resource
     */
    protected function readCertBody($body)
    {
        if (static::isCertificate($body)) {
            $this->type = 'PEM';
            $pemBody = $body;
        } else {
            $this->type = 'DER';
            $pemBody = $this->der2pem($body);
        }

        return openssl_x509_read($pemBody);
    }

    /**
     * @param $der_data
     * @return string
     */
    protected function der2pem($der_data)
    {
        $pem = chunk_split(base64_encode($der_data), 64, "\n");

        return "-----BEGIN CERTIFICATE-----\n" . $pem . "-----END CERTIFICATE-----\n";
    }

    /**
     * @param $pem_data
     * @return string
     */
    protected function pem2der($pem_data)
    {
        $begin = 'CERTIFICATE-----';
        $end = '-----END';
        $pem_data = substr($pem_data, strpos($pem_data, $begin) + strlen($begin));
        $pem_data = substr($pem_data, 0, strpos($pem_data, $end));
        $der = base64_decode($pem_data);

        return $der;
    }

    public static function isCertificate($data)
    {
        $search = '-----BEGIN CERTIFICATE';

        return substr($data, 0, strlen($search)) === $search;
    }

    private function fixEncoding($string)
    {
        // Данные сертификата могут быть закодированы в cp1251, но при чтении openssl_x509_parse
        // они воспринимаются PHP как строки в utf8 и их нужно принудительно раскодировать.
        try {
            if (preg_match('/[а-я]/iu', $string)) {
                return $string;
            }
            if (preg_match('/[а-я]/iu', utf8_decode($string))) {
                return utf8_decode($string);
            }
        } catch (\Exception $exception) {
            
        }
        return $string;
    }
}

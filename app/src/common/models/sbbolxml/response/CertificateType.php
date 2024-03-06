<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing CertificateType
 *
 * Данные одного сертификата
 * XSD Type: Certificate
 */
class CertificateType
{

    /**
     * @property string $__value
     */
    private $__value = null;

    /**
     * Серийный номер сертификата
     *
     * @property string $sN
     */
    private $sN = null;

    /**
     * Идентификатор средства подписи. Заполнять ВСЕГДА
     *  ДЛЯ ТК
     *
     * @property string $signDeviceId
     */
    private $signDeviceId = null;

    /**
     * Тип криптографии
     *
     * @property string $cryptoTypeId
     */
    private $cryptoTypeId = null;

    /**
     * клиентский сертификат
     *
     * @property boolean $client
     */
    private $client = null;

    /**
     * Банковский сертификат
     *
     * @property boolean $bank
     */
    private $bank = null;

    /**
     * Сертификат УЦ или корневой сертификат
     *
     * @property boolean $root
     */
    private $root = null;

    /**
     * Формат сертификата:
     *  X509 - сертфикат в формате X.509 (по умолчанию)
     *  P7S - сертфикат в CMS -контейнере
     *
     * @property string $format
     */
    private $format = null;

    /**
     * Признак активности
     *
     * @property boolean $active
     */
    private $active = null;

    /**
     * Признак действительности
     *
     * @property boolean $valid
     */
    private $valid = null;

    /**
     * Сслылка на контейнер хранения закрытого ключа
     *
     * @property string $containerName
     */
    private $containerName = null;

    /**
     * Construct
     *
     * @param string $value
     */
    public function __construct($value)
    {
        $this->value($value);
    }

    /**
     * Gets or sets the inner value
     *
     * @param string $value
     * @return string
     */
    public function value()
    {
        if ($args = func_get_args()) {
            $this->__value = $args[0];
        }
        return $this->__value;
    }

    /**
     * Gets a string value
     *
     * @return string
     */
    public function __toString()
    {
        return strval($this->__value);
    }

    /**
     * Gets as sN
     *
     * Серийный номер сертификата
     *
     * @return string
     */
    public function getSN()
    {
        return $this->sN;
    }

    /**
     * Sets a new sN
     *
     * Серийный номер сертификата
     *
     * @param string $sN
     * @return static
     */
    public function setSN($sN)
    {
        $this->sN = $sN;
        return $this;
    }

    /**
     * Gets as signDeviceId
     *
     * Идентификатор средства подписи. Заполнять ВСЕГДА
     *  ДЛЯ ТК
     *
     * @return string
     */
    public function getSignDeviceId()
    {
        return $this->signDeviceId;
    }

    /**
     * Sets a new signDeviceId
     *
     * Идентификатор средства подписи. Заполнять ВСЕГДА
     *  ДЛЯ ТК
     *
     * @param string $signDeviceId
     * @return static
     */
    public function setSignDeviceId($signDeviceId)
    {
        $this->signDeviceId = $signDeviceId;
        return $this;
    }

    /**
     * Gets as cryptoTypeId
     *
     * Тип криптографии
     *
     * @return string
     */
    public function getCryptoTypeId()
    {
        return $this->cryptoTypeId;
    }

    /**
     * Sets a new cryptoTypeId
     *
     * Тип криптографии
     *
     * @param string $cryptoTypeId
     * @return static
     */
    public function setCryptoTypeId($cryptoTypeId)
    {
        $this->cryptoTypeId = $cryptoTypeId;
        return $this;
    }

    /**
     * Gets as client
     *
     * клиентский сертификат
     *
     * @return boolean
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Sets a new client
     *
     * клиентский сертификат
     *
     * @param boolean $client
     * @return static
     */
    public function setClient($client)
    {
        $this->client = $client;
        return $this;
    }

    /**
     * Gets as bank
     *
     * Банковский сертификат
     *
     * @return boolean
     */
    public function getBank()
    {
        return $this->bank;
    }

    /**
     * Sets a new bank
     *
     * Банковский сертификат
     *
     * @param boolean $bank
     * @return static
     */
    public function setBank($bank)
    {
        $this->bank = $bank;
        return $this;
    }

    /**
     * Gets as root
     *
     * Сертификат УЦ или корневой сертификат
     *
     * @return boolean
     */
    public function getRoot()
    {
        return $this->root;
    }

    /**
     * Sets a new root
     *
     * Сертификат УЦ или корневой сертификат
     *
     * @param boolean $root
     * @return static
     */
    public function setRoot($root)
    {
        $this->root = $root;
        return $this;
    }

    /**
     * Gets as format
     *
     * Формат сертификата:
     *  X509 - сертфикат в формате X.509 (по умолчанию)
     *  P7S - сертфикат в CMS -контейнере
     *
     * @return string
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * Sets a new format
     *
     * Формат сертификата:
     *  X509 - сертфикат в формате X.509 (по умолчанию)
     *  P7S - сертфикат в CMS -контейнере
     *
     * @param string $format
     * @return static
     */
    public function setFormat($format)
    {
        $this->format = $format;
        return $this;
    }

    /**
     * Gets as active
     *
     * Признак активности
     *
     * @return boolean
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Sets a new active
     *
     * Признак активности
     *
     * @param boolean $active
     * @return static
     */
    public function setActive($active)
    {
        $this->active = $active;
        return $this;
    }

    /**
     * Gets as valid
     *
     * Признак действительности
     *
     * @return boolean
     */
    public function getValid()
    {
        return $this->valid;
    }

    /**
     * Sets a new valid
     *
     * Признак действительности
     *
     * @param boolean $valid
     * @return static
     */
    public function setValid($valid)
    {
        $this->valid = $valid;
        return $this;
    }

    /**
     * Gets as containerName
     *
     * Сслылка на контейнер хранения закрытого ключа
     *
     * @return string
     */
    public function getContainerName()
    {
        return $this->containerName;
    }

    /**
     * Sets a new containerName
     *
     * Сслылка на контейнер хранения закрытого ключа
     *
     * @param string $containerName
     * @return static
     */
    public function setContainerName($containerName)
    {
        $this->containerName = $containerName;
        return $this;
    }


}


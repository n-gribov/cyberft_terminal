<?php

namespace common\models\raiffeisenxml\response\ResponseType\CertificatesAType;

/**
 * Class representing CertificateAType
 *
 * Данные одного сертификата в бинарном представлении
 */
class CertificateAType
{

    /**
     * @property string $__value
     */
    private $__value = null;

    /**
     * Системный идентификатор сертификата.
     *
     * @property string $certificateGuid
     */
    private $certificateGuid = null;

    /**
     * Идентификатор средства подписи. Заполнять ВСЕГДА ДЛЯ ТК
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
     * @property bool $client
     */
    private $client = null;

    /**
     * Банковский сертификат
     *
     * @property bool $bank
     */
    private $bank = null;

    /**
     * Ссылка на закрытый ключ
     *
     * @property string $privateKeyRef
     */
    private $privateKeyRef = null;

    /**
     * Сертификат активен с
     *
     * @property \DateTime $validFrom
     */
    private $validFrom = null;

    /**
     * Сартификат активен до
     *
     * @property \DateTime $validTill
     */
    private $validTill = null;

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
     * Gets as certificateGuid
     *
     * Системный идентификатор сертификата.
     *
     * @return string
     */
    public function getCertificateGuid()
    {
        return $this->certificateGuid;
    }

    /**
     * Sets a new certificateGuid
     *
     * Системный идентификатор сертификата.
     *
     * @param string $certificateGuid
     * @return static
     */
    public function setCertificateGuid($certificateGuid)
    {
        $this->certificateGuid = $certificateGuid;
        return $this;
    }

    /**
     * Gets as signDeviceId
     *
     * Идентификатор средства подписи. Заполнять ВСЕГДА ДЛЯ ТК
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
     * Идентификатор средства подписи. Заполнять ВСЕГДА ДЛЯ ТК
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
     * @return bool
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
     * @param bool $client
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
     * @return bool
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
     * @param bool $bank
     * @return static
     */
    public function setBank($bank)
    {
        $this->bank = $bank;
        return $this;
    }

    /**
     * Gets as privateKeyRef
     *
     * Ссылка на закрытый ключ
     *
     * @return string
     */
    public function getPrivateKeyRef()
    {
        return $this->privateKeyRef;
    }

    /**
     * Sets a new privateKeyRef
     *
     * Ссылка на закрытый ключ
     *
     * @param string $privateKeyRef
     * @return static
     */
    public function setPrivateKeyRef($privateKeyRef)
    {
        $this->privateKeyRef = $privateKeyRef;
        return $this;
    }

    /**
     * Gets as validFrom
     *
     * Сертификат активен с
     *
     * @return \DateTime
     */
    public function getValidFrom()
    {
        return $this->validFrom;
    }

    /**
     * Sets a new validFrom
     *
     * Сертификат активен с
     *
     * @param \DateTime $validFrom
     * @return static
     */
    public function setValidFrom(\DateTime $validFrom)
    {
        $this->validFrom = $validFrom;
        return $this;
    }

    /**
     * Gets as validTill
     *
     * Сартификат активен до
     *
     * @return \DateTime
     */
    public function getValidTill()
    {
        return $this->validTill;
    }

    /**
     * Sets a new validTill
     *
     * Сартификат активен до
     *
     * @param \DateTime $validTill
     * @return static
     */
    public function setValidTill(\DateTime $validTill)
    {
        $this->validTill = $validTill;
        return $this;
    }


}


<?php

namespace common\models\raiffeisenxml\response\OrgInfoType\SignDevicesAType;

/**
 * Class representing SignDeviceAType
 */
class SignDeviceAType
{

    /**
     * Токен
     *
     * @property bool $token
     */
    private $token = null;

    /**
     * Токен БСС
     *
     * @property bool $tokenBss
     */
    private $tokenBss = null;

    /**
     * SafeTouch
     *
     * @property bool $safeTouch
     */
    private $safeTouch = null;

    /**
     * Идентификатор средства подписи
     *
     * @property string $signDeviceId
     */
    private $signDeviceId = null;

    /**
     * Идентификатор типа криптографии
     *
     * @property string $cryptotypeId
     */
    private $cryptotypeId = null;

    /**
     * Название типа криптографии (Напрмиер Инфокрипт, OneTimePassword и т.д.)
     *
     * @property string $cryptoTypeName
     */
    private $cryptoTypeName = null;

    /**
     * Наименование средства подписи
     *
     * @property string $profileName
     */
    private $profileName = null;

    /**
     * Номер/номер телефона для отправки смс
     *
     * @property string $telForSms
     */
    private $telForSms = null;

    /**
     * Идентификатор активного сертификата
     *
     * @property string $activeSertId
     */
    private $activeSertId = null;

    /**
     * Серийный номер активного сертификата
     *
     * @property string $sN
     */
    private $sN = null;

    /**
     * Поставщик сертификата (активного)
     *
     * @property string $issuer
     */
    private $issuer = null;

    /**
     * Содержит данные сертификатов
     *
     * @property \common\models\raiffeisenxml\response\OrgInfoType\SignDevicesAType\SignDeviceAType\CertificatesAType\CertificateAType[] $certificates
     */
    private $certificates = null;

    /**
     * Gets as token
     *
     * Токен
     *
     * @return bool
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Sets a new token
     *
     * Токен
     *
     * @param bool $token
     * @return static
     */
    public function setToken($token)
    {
        $this->token = $token;
        return $this;
    }

    /**
     * Gets as tokenBss
     *
     * Токен БСС
     *
     * @return bool
     */
    public function getTokenBss()
    {
        return $this->tokenBss;
    }

    /**
     * Sets a new tokenBss
     *
     * Токен БСС
     *
     * @param bool $tokenBss
     * @return static
     */
    public function setTokenBss($tokenBss)
    {
        $this->tokenBss = $tokenBss;
        return $this;
    }

    /**
     * Gets as safeTouch
     *
     * SafeTouch
     *
     * @return bool
     */
    public function getSafeTouch()
    {
        return $this->safeTouch;
    }

    /**
     * Sets a new safeTouch
     *
     * SafeTouch
     *
     * @param bool $safeTouch
     * @return static
     */
    public function setSafeTouch($safeTouch)
    {
        $this->safeTouch = $safeTouch;
        return $this;
    }

    /**
     * Gets as signDeviceId
     *
     * Идентификатор средства подписи
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
     * Идентификатор средства подписи
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
     * Gets as cryptotypeId
     *
     * Идентификатор типа криптографии
     *
     * @return string
     */
    public function getCryptotypeId()
    {
        return $this->cryptotypeId;
    }

    /**
     * Sets a new cryptotypeId
     *
     * Идентификатор типа криптографии
     *
     * @param string $cryptotypeId
     * @return static
     */
    public function setCryptotypeId($cryptotypeId)
    {
        $this->cryptotypeId = $cryptotypeId;
        return $this;
    }

    /**
     * Gets as cryptoTypeName
     *
     * Название типа криптографии (Напрмиер Инфокрипт, OneTimePassword и т.д.)
     *
     * @return string
     */
    public function getCryptoTypeName()
    {
        return $this->cryptoTypeName;
    }

    /**
     * Sets a new cryptoTypeName
     *
     * Название типа криптографии (Напрмиер Инфокрипт, OneTimePassword и т.д.)
     *
     * @param string $cryptoTypeName
     * @return static
     */
    public function setCryptoTypeName($cryptoTypeName)
    {
        $this->cryptoTypeName = $cryptoTypeName;
        return $this;
    }

    /**
     * Gets as profileName
     *
     * Наименование средства подписи
     *
     * @return string
     */
    public function getProfileName()
    {
        return $this->profileName;
    }

    /**
     * Sets a new profileName
     *
     * Наименование средства подписи
     *
     * @param string $profileName
     * @return static
     */
    public function setProfileName($profileName)
    {
        $this->profileName = $profileName;
        return $this;
    }

    /**
     * Gets as telForSms
     *
     * Номер/номер телефона для отправки смс
     *
     * @return string
     */
    public function getTelForSms()
    {
        return $this->telForSms;
    }

    /**
     * Sets a new telForSms
     *
     * Номер/номер телефона для отправки смс
     *
     * @param string $telForSms
     * @return static
     */
    public function setTelForSms($telForSms)
    {
        $this->telForSms = $telForSms;
        return $this;
    }

    /**
     * Gets as activeSertId
     *
     * Идентификатор активного сертификата
     *
     * @return string
     */
    public function getActiveSertId()
    {
        return $this->activeSertId;
    }

    /**
     * Sets a new activeSertId
     *
     * Идентификатор активного сертификата
     *
     * @param string $activeSertId
     * @return static
     */
    public function setActiveSertId($activeSertId)
    {
        $this->activeSertId = $activeSertId;
        return $this;
    }

    /**
     * Gets as sN
     *
     * Серийный номер активного сертификата
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
     * Серийный номер активного сертификата
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
     * Gets as issuer
     *
     * Поставщик сертификата (активного)
     *
     * @return string
     */
    public function getIssuer()
    {
        return $this->issuer;
    }

    /**
     * Sets a new issuer
     *
     * Поставщик сертификата (активного)
     *
     * @param string $issuer
     * @return static
     */
    public function setIssuer($issuer)
    {
        $this->issuer = $issuer;
        return $this;
    }

    /**
     * Adds as certificate
     *
     * Содержит данные сертификатов
     *
     * @return static
     * @param \common\models\raiffeisenxml\response\OrgInfoType\SignDevicesAType\SignDeviceAType\CertificatesAType\CertificateAType $certificate
     */
    public function addToCertificates(\common\models\raiffeisenxml\response\OrgInfoType\SignDevicesAType\SignDeviceAType\CertificatesAType\CertificateAType $certificate)
    {
        $this->certificates[] = $certificate;
        return $this;
    }

    /**
     * isset certificates
     *
     * Содержит данные сертификатов
     *
     * @param int|string $index
     * @return bool
     */
    public function issetCertificates($index)
    {
        return isset($this->certificates[$index]);
    }

    /**
     * unset certificates
     *
     * Содержит данные сертификатов
     *
     * @param int|string $index
     * @return void
     */
    public function unsetCertificates($index)
    {
        unset($this->certificates[$index]);
    }

    /**
     * Gets as certificates
     *
     * Содержит данные сертификатов
     *
     * @return \common\models\raiffeisenxml\response\OrgInfoType\SignDevicesAType\SignDeviceAType\CertificatesAType\CertificateAType[]
     */
    public function getCertificates()
    {
        return $this->certificates;
    }

    /**
     * Sets a new certificates
     *
     * Содержит данные сертификатов
     *
     * @param \common\models\raiffeisenxml\response\OrgInfoType\SignDevicesAType\SignDeviceAType\CertificatesAType\CertificateAType[] $certificates
     * @return static
     */
    public function setCertificates(array $certificates)
    {
        $this->certificates = $certificates;
        return $this;
    }


}


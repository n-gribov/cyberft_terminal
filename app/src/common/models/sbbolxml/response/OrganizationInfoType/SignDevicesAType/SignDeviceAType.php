<?php

namespace common\models\sbbolxml\response\OrganizationInfoType\SignDevicesAType;

/**
 * Class representing SignDeviceAType
 */
class SignDeviceAType
{

    /**
     * Идентификатор криптопрофиля
     *
     * @property string $signDeviceId
     */
    private $signDeviceId = null;

    /**
     * Псевдоним
     *
     * @property string $alias
     */
    private $alias = null;

    /**
     * Наименование криптопрофиля
     *
     * @property string $profileName
     */
    private $profileName = null;

    /**
     * Должность
     *
     * @property string $post
     */
    private $post = null;

    /**
     * Идентификатор типа криптографии
     *
     * @property string $cryptotypeId
     */
    private $cryptotypeId = null;

    /**
     * Название типа криптографии (Напрмиер Инфокрипт,
     *  OneTimePassword и т.д.)
     *
     * @property string $cryptoTypeName
     */
    private $cryptoTypeName = null;

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
     * Признак "Использовать в операциях подписи"
     *  1 - использовать
     *  0 - не использовать
     *
     * @property boolean $signUse
     */
    private $signUse = null;

    /**
     * Поставщик сертификата (активного)
     *
     * @property string $issuer
     */
    private $issuer = null;

    /**
     * Содержит данные сертификатов
     *
     * @property \common\models\sbbolxml\response\CertificateType[] $certificates
     */
    private $certificates = null;

    /**
     * Gets as signDeviceId
     *
     * Идентификатор криптопрофиля
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
     * Идентификатор криптопрофиля
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
     * Gets as alias
     *
     * Псевдоним
     *
     * @return string
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * Sets a new alias
     *
     * Псевдоним
     *
     * @param string $alias
     * @return static
     */
    public function setAlias($alias)
    {
        $this->alias = $alias;
        return $this;
    }

    /**
     * Gets as profileName
     *
     * Наименование криптопрофиля
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
     * Наименование криптопрофиля
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
     * Gets as post
     *
     * Должность
     *
     * @return string
     */
    public function getPost()
    {
        return $this->post;
    }

    /**
     * Sets a new post
     *
     * Должность
     *
     * @param string $post
     * @return static
     */
    public function setPost($post)
    {
        $this->post = $post;
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
     * Название типа криптографии (Напрмиер Инфокрипт,
     *  OneTimePassword и т.д.)
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
     * Название типа криптографии (Напрмиер Инфокрипт,
     *  OneTimePassword и т.д.)
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
     * Gets as signUse
     *
     * Признак "Использовать в операциях подписи"
     *  1 - использовать
     *  0 - не использовать
     *
     * @return boolean
     */
    public function getSignUse()
    {
        return $this->signUse;
    }

    /**
     * Sets a new signUse
     *
     * Признак "Использовать в операциях подписи"
     *  1 - использовать
     *  0 - не использовать
     *
     * @param boolean $signUse
     * @return static
     */
    public function setSignUse($signUse)
    {
        $this->signUse = $signUse;
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
     * @param \common\models\sbbolxml\response\CertificateType $certificate
     */
    public function addToCertificates(\common\models\sbbolxml\response\CertificateType $certificate)
    {
        $this->certificates[] = $certificate;
        return $this;
    }

    /**
     * isset certificates
     *
     * Содержит данные сертификатов
     *
     * @param scalar $index
     * @return boolean
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
     * @param scalar $index
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
     * @return \common\models\sbbolxml\response\CertificateType[]
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
     * @param \common\models\sbbolxml\response\CertificateType[] $certificates
     * @return static
     */
    public function setCertificates(array $certificates)
    {
        $this->certificates = $certificates;
        return $this;
    }


}


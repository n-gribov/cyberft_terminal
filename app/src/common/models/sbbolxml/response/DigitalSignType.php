<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing DigitalSignType
 *
 *
 * XSD Type: DigitalSign
 */
class DigitalSignType
{

    /**
     * Идентификатор поставщика сертификата
     *  Например, " issuer="E=IdleCA, C=RU, S=Idle CA, L=Idle CA, O=Idle CA, OU=Idle CA, CN=Idle CA" "
     *
     * @property string $issuer
     */
    private $issuer = null;

    /**
     * Серийный номер сертификата
     *
     * @property string $sN
     */
    private $sN = null;

    /**
     * Значение ЭП
     *
     * @property string $value
     */
    private $value = null;

    /**
     * Имя схемы подписи
     *
     * @property string $digestName
     */
    private $digestName = null;

    /**
     * Версия схемы подписи
     *
     * @property string $digestVersion
     */
    private $digestVersion = null;

    /**
     * Gets as issuer
     *
     * Идентификатор поставщика сертификата
     *  Например, " issuer="E=IdleCA, C=RU, S=Idle CA, L=Idle CA, O=Idle CA, OU=Idle CA, CN=Idle CA" "
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
     * Идентификатор поставщика сертификата
     *  Например, " issuer="E=IdleCA, C=RU, S=Idle CA, L=Idle CA, O=Idle CA, OU=Idle CA, CN=Idle CA" "
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
     * Gets as value
     *
     * Значение ЭП
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Sets a new value
     *
     * Значение ЭП
     *
     * @param string $value
     * @return static
     */
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }

    /**
     * Gets as digestName
     *
     * Имя схемы подписи
     *
     * @return string
     */
    public function getDigestName()
    {
        return $this->digestName;
    }

    /**
     * Sets a new digestName
     *
     * Имя схемы подписи
     *
     * @param string $digestName
     * @return static
     */
    public function setDigestName($digestName)
    {
        $this->digestName = $digestName;
        return $this;
    }

    /**
     * Gets as digestVersion
     *
     * Версия схемы подписи
     *
     * @return string
     */
    public function getDigestVersion()
    {
        return $this->digestVersion;
    }

    /**
     * Sets a new digestVersion
     *
     * Версия схемы подписи
     *
     * @param string $digestVersion
     * @return static
     */
    public function setDigestVersion($digestVersion)
    {
        $this->digestVersion = $digestVersion;
        return $this;
    }


}


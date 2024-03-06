<?php

namespace common\models\raiffeisenxml\request;

/**
 * Class representing DigitalSignType
 *
 *
 * XSD Type: DigitalSign
 */
class DigitalSignType
{

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
     * Идентификатор поставщика сертификата
     *  Например, " issuer="E=IdleCA, C=RU, S=Idle CA, L=Idle CA, O=Idle CA, OU=Idle CA, CN=Idle CA" "
     *
     * @property string $issuer
     */
    private $issuer = null;

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
     * Тип подписи
     *
     * @property string $signType
     */
    private $signType = null;

    /**
     * ФИО УЛК, наложившего данную подпись.
     *
     * @property string $fio
     */
    private $fio = null;

    /**
     * Должность УЛК, наложившего подпись.
     *
     * @property string $position
     */
    private $position = null;

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

    /**
     * Gets as signType
     *
     * Тип подписи
     *
     * @return string
     */
    public function getSignType()
    {
        return $this->signType;
    }

    /**
     * Sets a new signType
     *
     * Тип подписи
     *
     * @param string $signType
     * @return static
     */
    public function setSignType($signType)
    {
        $this->signType = $signType;
        return $this;
    }

    /**
     * Gets as fio
     *
     * ФИО УЛК, наложившего данную подпись.
     *
     * @return string
     */
    public function getFio()
    {
        return $this->fio;
    }

    /**
     * Sets a new fio
     *
     * ФИО УЛК, наложившего данную подпись.
     *
     * @param string $fio
     * @return static
     */
    public function setFio($fio)
    {
        $this->fio = $fio;
        return $this;
    }

    /**
     * Gets as position
     *
     * Должность УЛК, наложившего подпись.
     *
     * @return string
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Sets a new position
     *
     * Должность УЛК, наложившего подпись.
     *
     * @param string $position
     * @return static
     */
    public function setPosition($position)
    {
        $this->position = $position;
        return $this;
    }


}


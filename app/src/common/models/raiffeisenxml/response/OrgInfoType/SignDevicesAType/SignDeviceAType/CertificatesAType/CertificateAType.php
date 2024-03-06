<?php

namespace common\models\raiffeisenxml\response\OrgInfoType\SignDevicesAType\SignDeviceAType\CertificatesAType;

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
     * Признак активности
     *
     * @property bool $active
     */
    private $active = null;

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
     * Gets as active
     *
     * Признак активности
     *
     * @return bool
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
     * @param bool $active
     * @return static
     */
    public function setActive($active)
    {
        $this->active = $active;
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


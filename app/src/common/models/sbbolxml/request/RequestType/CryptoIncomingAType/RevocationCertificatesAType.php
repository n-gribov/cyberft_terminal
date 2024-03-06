<?php

namespace common\models\sbbolxml\request\RequestType\CryptoIncomingAType;

/**
 * Class representing RevocationCertificatesAType
 */
class RevocationCertificatesAType
{

    /**
     * @property boolean $__value
     */
    private $__value = null;

    /**
     * Дата и время используемого обновления списка отозванных сертификатов
     *
     * @property \DateTime $updateTime
     */
    private $updateTime = null;

    /**
     * Construct
     *
     * @param boolean $value
     */
    public function __construct($value)
    {
        $this->value($value);
    }

    /**
     * Gets or sets the inner value
     *
     * @param boolean $value
     * @return boolean
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
     * Gets as updateTime
     *
     * Дата и время используемого обновления списка отозванных сертификатов
     *
     * @return \DateTime
     */
    public function getUpdateTime()
    {
        return $this->updateTime;
    }

    /**
     * Sets a new updateTime
     *
     * Дата и время используемого обновления списка отозванных сертификатов
     *
     * @param \DateTime $updateTime
     * @return static
     */
    public function setUpdateTime(\DateTime $updateTime)
    {
        $this->updateTime = $updateTime;
        return $this;
    }


}


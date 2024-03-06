<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing AccWithDateType
 *
 *
 * XSD Type: AccWithDate
 */
class AccWithDateType
{

    /**
     * @property string $__value
     */
    private $__value = null;

    /**
     * БИК
     *
     * @property string $bic
     */
    private $bic = null;

    /**
     * Дата и время записи последней операции
     *
     * @property \DateTime $lastModifyDate
     */
    private $lastModifyDate = null;

    /**
     * Дата выписки
     *
     * @property \DateTime $date
     */
    private $date = null;

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
     * Gets as bic
     *
     * БИК
     *
     * @return string
     */
    public function getBic()
    {
        return $this->bic;
    }

    /**
     * Sets a new bic
     *
     * БИК
     *
     * @param string $bic
     * @return static
     */
    public function setBic($bic)
    {
        $this->bic = $bic;
        return $this;
    }

    /**
     * Gets as lastModifyDate
     *
     * Дата и время записи последней операции
     *
     * @return \DateTime
     */
    public function getLastModifyDate()
    {
        return $this->lastModifyDate;
    }

    /**
     * Sets a new lastModifyDate
     *
     * Дата и время записи последней операции
     *
     * @param \DateTime $lastModifyDate
     * @return static
     */
    public function setLastModifyDate(\DateTime $lastModifyDate)
    {
        $this->lastModifyDate = $lastModifyDate;
        return $this;
    }

    /**
     * Gets as date
     *
     * Дата выписки
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Sets a new date
     *
     * Дата выписки
     *
     * @param \DateTime $date
     * @return static
     */
    public function setDate(\DateTime $date)
    {
        $this->date = $date;
        return $this;
    }


}


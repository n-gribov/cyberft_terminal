<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing ConfDocType
 *
 *
 * XSD Type: ConfDoc
 */
class ConfDocType
{

    /**
     * Признак: 0 - документ имеет номер; 1 - документ без номера
     *
     * @property boolean $numCheck
     */
    private $numCheck = null;

    /**
     * Номер подтверждающего документа
     *
     * @property string $num
     */
    private $num = null;

    /**
     * Дата подтверждающего документа
     *
     * @property \DateTime $date
     */
    private $date = null;

    /**
     * Gets as numCheck
     *
     * Признак: 0 - документ имеет номер; 1 - документ без номера
     *
     * @return boolean
     */
    public function getNumCheck()
    {
        return $this->numCheck;
    }

    /**
     * Sets a new numCheck
     *
     * Признак: 0 - документ имеет номер; 1 - документ без номера
     *
     * @param boolean $numCheck
     * @return static
     */
    public function setNumCheck($numCheck)
    {
        $this->numCheck = $numCheck;
        return $this;
    }

    /**
     * Gets as num
     *
     * Номер подтверждающего документа
     *
     * @return string
     */
    public function getNum()
    {
        return $this->num;
    }

    /**
     * Sets a new num
     *
     * Номер подтверждающего документа
     *
     * @param string $num
     * @return static
     */
    public function setNum($num)
    {
        $this->num = $num;
        return $this;
    }

    /**
     * Gets as date
     *
     * Дата подтверждающего документа
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
     * Дата подтверждающего документа
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


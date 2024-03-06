<?php

namespace common\models\raiffeisenxml\request;

/**
 * Class representing ContractType
 *
 *
 * XSD Type: Contract
 */
class ContractType
{

    /**
     * 0 - с номером, 1 - без номера
     *
     * @property bool $numCheck
     */
    private $numCheck = null;

    /**
     * Номер
     *
     * @property string $num
     */
    private $num = null;

    /**
     * Дата
     *
     * @property \DateTime $date
     */
    private $date = null;

    /**
     * Gets as numCheck
     *
     * 0 - с номером, 1 - без номера
     *
     * @return bool
     */
    public function getNumCheck()
    {
        return $this->numCheck;
    }

    /**
     * Sets a new numCheck
     *
     * 0 - с номером, 1 - без номера
     *
     * @param bool $numCheck
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
     * Номер
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
     * Номер
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
     * Дата
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
     * Дата
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


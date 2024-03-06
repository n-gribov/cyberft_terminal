<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing VBKInfoContractType
 *
 * Информация о контраке/ кредитном договоре
 * XSD Type: VBKInfoContract
 */
class VBKInfoContractType
{

    /**
     * 0 - с номером, 1 - без номера
     *
     * @property boolean $numCheck
     */
    private $numCheck = null;

    /**
     * Номер контракта
     *
     * @property string $num
     */
    private $num = null;

    /**
     * Дата контракта
     *
     * @property \DateTime $date
     */
    private $date = null;

    /**
     * Gets as numCheck
     *
     * 0 - с номером, 1 - без номера
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
     * 0 - с номером, 1 - без номера
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
     * Номер контракта
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
     * Номер контракта
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
     * Дата контракта
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
     * Дата контракта
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


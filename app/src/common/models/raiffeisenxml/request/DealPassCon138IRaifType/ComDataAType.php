<?php

namespace common\models\raiffeisenxml\request\DealPassCon138IRaifType;

/**
 * Class representing ComDataAType
 */
class ComDataAType
{

    /**
     * Признак наличия номера контракта
     *  0- с номером, 1- без номера
     *
     * @property bool $checkNum
     */
    private $checkNum = null;

    /**
     * Признак наличия суммы контракта: 0- с суммой, 1- без суммы
     *
     * @property bool $checkSum
     */
    private $checkSum = null;

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
     * Валюта цены контракта
     *
     * @property \common\models\raiffeisenxml\request\DealPassCon138IRaifType\ComDataAType\CurrAType $curr
     */
    private $curr = null;

    /**
     * Сумма контракта
     *
     * @property float $sum
     */
    private $sum = null;

    /**
     * Дата завершения исполнения обязательств
     *
     * @property \DateTime $endDate
     */
    private $endDate = null;

    /**
     * Gets as checkNum
     *
     * Признак наличия номера контракта
     *  0- с номером, 1- без номера
     *
     * @return bool
     */
    public function getCheckNum()
    {
        return $this->checkNum;
    }

    /**
     * Sets a new checkNum
     *
     * Признак наличия номера контракта
     *  0- с номером, 1- без номера
     *
     * @param bool $checkNum
     * @return static
     */
    public function setCheckNum($checkNum)
    {
        $this->checkNum = $checkNum;
        return $this;
    }

    /**
     * Gets as checkSum
     *
     * Признак наличия суммы контракта: 0- с суммой, 1- без суммы
     *
     * @return bool
     */
    public function getCheckSum()
    {
        return $this->checkSum;
    }

    /**
     * Sets a new checkSum
     *
     * Признак наличия суммы контракта: 0- с суммой, 1- без суммы
     *
     * @param bool $checkSum
     * @return static
     */
    public function setCheckSum($checkSum)
    {
        $this->checkSum = $checkSum;
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

    /**
     * Gets as curr
     *
     * Валюта цены контракта
     *
     * @return \common\models\raiffeisenxml\request\DealPassCon138IRaifType\ComDataAType\CurrAType
     */
    public function getCurr()
    {
        return $this->curr;
    }

    /**
     * Sets a new curr
     *
     * Валюта цены контракта
     *
     * @param \common\models\raiffeisenxml\request\DealPassCon138IRaifType\ComDataAType\CurrAType $curr
     * @return static
     */
    public function setCurr(\common\models\raiffeisenxml\request\DealPassCon138IRaifType\ComDataAType\CurrAType $curr)
    {
        $this->curr = $curr;
        return $this;
    }

    /**
     * Gets as sum
     *
     * Сумма контракта
     *
     * @return float
     */
    public function getSum()
    {
        return $this->sum;
    }

    /**
     * Sets a new sum
     *
     * Сумма контракта
     *
     * @param float $sum
     * @return static
     */
    public function setSum($sum)
    {
        $this->sum = $sum;
        return $this;
    }

    /**
     * Gets as endDate
     *
     * Дата завершения исполнения обязательств
     *
     * @return \DateTime
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * Sets a new endDate
     *
     * Дата завершения исполнения обязательств
     *
     * @param \DateTime $endDate
     * @return static
     */
    public function setEndDate(\DateTime $endDate)
    {
        $this->endDate = $endDate;
        return $this;
    }


}


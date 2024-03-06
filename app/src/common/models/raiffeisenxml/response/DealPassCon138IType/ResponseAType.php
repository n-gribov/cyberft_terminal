<?php

namespace common\models\raiffeisenxml\response\DealPassCon138IType;

/**
 * Class representing ResponseAType
 */
class ResponseAType
{

    /**
     * Признак наличия номера контракта
     *  1- отмечено, что без номера
     *
     * @property bool $checkNum
     */
    private $checkNum = null;

    /**
     * Признак наличия суммы контракта
     *  1- отмечено, что без суммы
     *
     * @property bool $checkSum
     */
    private $checkSum = null;

    /**
     * Номер контракта
     *  Либо 12#$%% либо "б/н"
     *
     * @property string $num
     */
    private $num = null;

    /**
     * Дата подписания контракта
     *
     * @property \DateTime $date
     */
    private $date = null;

    /**
     * Валюта цены контракта
     *
     * @property \common\models\raiffeisenxml\response\DPCurrType $curr
     */
    private $curr = null;

    /**
     * Сумма контракта
     *
     * @property \common\models\raiffeisenxml\response\CurrAmountType $sum
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
     *  1- отмечено, что без номера
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
     *  1- отмечено, что без номера
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
     * Признак наличия суммы контракта
     *  1- отмечено, что без суммы
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
     * Признак наличия суммы контракта
     *  1- отмечено, что без суммы
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
     *  Либо 12#$%% либо "б/н"
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
     *  Либо 12#$%% либо "б/н"
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
     * Дата подписания контракта
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
     * Дата подписания контракта
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
     * @return \common\models\raiffeisenxml\response\DPCurrType
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
     * @param \common\models\raiffeisenxml\response\DPCurrType $curr
     * @return static
     */
    public function setCurr(\common\models\raiffeisenxml\response\DPCurrType $curr)
    {
        $this->curr = $curr;
        return $this;
    }

    /**
     * Gets as sum
     *
     * Сумма контракта
     *
     * @return \common\models\raiffeisenxml\response\CurrAmountType
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
     * @param \common\models\raiffeisenxml\response\CurrAmountType $sum
     * @return static
     */
    public function setSum(\common\models\raiffeisenxml\response\CurrAmountType $sum)
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


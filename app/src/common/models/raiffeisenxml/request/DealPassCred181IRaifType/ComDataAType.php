<?php

namespace common\models\raiffeisenxml\request\DealPassCred181IRaifType;

/**
 * Class representing ComDataAType
 */
class ComDataAType
{

    /**
     * Признак наличия номера кредитного договора
     *  0- с номером, 1- без номера
     *
     * @property bool $checkNum
     */
    private $checkNum = null;

    /**
     * Признак наличия суммы кредитного договора: 0- с суммой, 1- без суммы
     *
     * @property bool $checkSum
     */
    private $checkSum = null;

    /**
     * Номер кредитного договора
     *
     * @property string $num
     */
    private $num = null;

    /**
     * Дата кредитного договора
     *
     * @property \DateTime $date
     */
    private $date = null;

    /**
     * Валюта цены кредитного договора
     *
     * @property \common\models\raiffeisenxml\request\DealPassCred181IRaifType\ComDataAType\CurrAType $curr
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
     * Сумма, подлежащая зачислению на счета за рубежом
     *
     * @property float $sumTransfer
     */
    private $sumTransfer = null;

    /**
     * Сумма, погашение которой предполагается за счет валютной выручки
     *
     * @property float $sumCurr
     */
    private $sumCurr = null;

    /**
     * Код срока привлечения
     *
     * @property string $tranche
     */
    private $tranche = null;

    /**
     * Gets as checkNum
     *
     * Признак наличия номера кредитного договора
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
     * Признак наличия номера кредитного договора
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
     * Признак наличия суммы кредитного договора: 0- с суммой, 1- без суммы
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
     * Признак наличия суммы кредитного договора: 0- с суммой, 1- без суммы
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
     * Номер кредитного договора
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
     * Номер кредитного договора
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
     * Дата кредитного договора
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
     * Дата кредитного договора
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
     * Валюта цены кредитного договора
     *
     * @return \common\models\raiffeisenxml\request\DealPassCred181IRaifType\ComDataAType\CurrAType
     */
    public function getCurr()
    {
        return $this->curr;
    }

    /**
     * Sets a new curr
     *
     * Валюта цены кредитного договора
     *
     * @param \common\models\raiffeisenxml\request\DealPassCred181IRaifType\ComDataAType\CurrAType $curr
     * @return static
     */
    public function setCurr(\common\models\raiffeisenxml\request\DealPassCred181IRaifType\ComDataAType\CurrAType $curr)
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

    /**
     * Gets as sumTransfer
     *
     * Сумма, подлежащая зачислению на счета за рубежом
     *
     * @return float
     */
    public function getSumTransfer()
    {
        return $this->sumTransfer;
    }

    /**
     * Sets a new sumTransfer
     *
     * Сумма, подлежащая зачислению на счета за рубежом
     *
     * @param float $sumTransfer
     * @return static
     */
    public function setSumTransfer($sumTransfer)
    {
        $this->sumTransfer = $sumTransfer;
        return $this;
    }

    /**
     * Gets as sumCurr
     *
     * Сумма, погашение которой предполагается за счет валютной выручки
     *
     * @return float
     */
    public function getSumCurr()
    {
        return $this->sumCurr;
    }

    /**
     * Sets a new sumCurr
     *
     * Сумма, погашение которой предполагается за счет валютной выручки
     *
     * @param float $sumCurr
     * @return static
     */
    public function setSumCurr($sumCurr)
    {
        $this->sumCurr = $sumCurr;
        return $this;
    }

    /**
     * Gets as tranche
     *
     * Код срока привлечения
     *
     * @return string
     */
    public function getTranche()
    {
        return $this->tranche;
    }

    /**
     * Sets a new tranche
     *
     * Код срока привлечения
     *
     * @param string $tranche
     * @return static
     */
    public function setTranche($tranche)
    {
        $this->tranche = $tranche;
        return $this;
    }


}


<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing ComDataType
 *
 * Общие сведения о контракте/кредитном договоре
 * XSD Type: ComData
 */
class ComDataType
{

    /**
     * Признак наличия номера контракта/кредитного договора: 0- с номером, 1- без номера
     *
     * @property boolean $checkNum
     */
    private $checkNum = null;

    /**
     * Признак наличия суммы контракта/кредитного договора: 0- с суммой, 1- без суммы
     *
     * @property boolean $checkSum
     */
    private $checkSum = null;

    /**
     * Номер контракта/кредитного договора
     *
     * @property string $num
     */
    private $num = null;

    /**
     * Дата контракта/кредитного договора
     *
     * @property \DateTime $date
     */
    private $date = null;

    /**
     * Валюта цены контракта/кредитного договора
     *
     * @property \common\models\sbbolxml\request\DPCurrType $curr
     */
    private $curr = null;

    /**
     * Сумма контракта/кредитного договора
     *
     * @property \common\models\sbbolxml\request\CurrAmountType $sum
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
     * Признак наличия номера контракта/кредитного договора: 0- с номером, 1- без номера
     *
     * @return boolean
     */
    public function getCheckNum()
    {
        return $this->checkNum;
    }

    /**
     * Sets a new checkNum
     *
     * Признак наличия номера контракта/кредитного договора: 0- с номером, 1- без номера
     *
     * @param boolean $checkNum
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
     * Признак наличия суммы контракта/кредитного договора: 0- с суммой, 1- без суммы
     *
     * @return boolean
     */
    public function getCheckSum()
    {
        return $this->checkSum;
    }

    /**
     * Sets a new checkSum
     *
     * Признак наличия суммы контракта/кредитного договора: 0- с суммой, 1- без суммы
     *
     * @param boolean $checkSum
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
     * Номер контракта/кредитного договора
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
     * Номер контракта/кредитного договора
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
     * Дата контракта/кредитного договора
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
     * Дата контракта/кредитного договора
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
     * Валюта цены контракта/кредитного договора
     *
     * @return \common\models\sbbolxml\request\DPCurrType
     */
    public function getCurr()
    {
        return $this->curr;
    }

    /**
     * Sets a new curr
     *
     * Валюта цены контракта/кредитного договора
     *
     * @param \common\models\sbbolxml\request\DPCurrType $curr
     * @return static
     */
    public function setCurr(\common\models\sbbolxml\request\DPCurrType $curr)
    {
        $this->curr = $curr;
        return $this;
    }

    /**
     * Gets as sum
     *
     * Сумма контракта/кредитного договора
     *
     * @return \common\models\sbbolxml\request\CurrAmountType
     */
    public function getSum()
    {
        return $this->sum;
    }

    /**
     * Sets a new sum
     *
     * Сумма контракта/кредитного договора
     *
     * @param \common\models\sbbolxml\request\CurrAmountType $sum
     * @return static
     */
    public function setSum(\common\models\sbbolxml\request\CurrAmountType $sum)
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


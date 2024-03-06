<?php

namespace common\models\raiffeisenxml\request\MandatorySaleRaifType;

/**
 * Class representing SellAType
 */
class SellAType
{

    /**
     * Общая сумма продажи
     *
     * @property float $sum
     */
    private $sum = null;

    /**
     * Тип сделки
     *
     * @property string $dealType
     */
    private $dealType = null;

    /**
     * Сумма обязательной продажи
     *
     * @property float $totalSellSum
     */
    private $totalSellSum = null;

    /**
     * Сумма продажи сверх установленного размера
     *
     * @property float $overSellSum
     */
    private $overSellSum = null;

    /**
     * Процент суммы для обязательной продажи
     *
     * @property float $percent
     */
    private $percent = null;

    /**
     * Реквизиты счета зачисления рублей (номер счета д.б. заполнен)
     *
     * @property \common\models\raiffeisenxml\request\AccountNoNameType $acc
     */
    private $acc = null;

    /**
     * Gets as sum
     *
     * Общая сумма продажи
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
     * Общая сумма продажи
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
     * Gets as dealType
     *
     * Тип сделки
     *
     * @return string
     */
    public function getDealType()
    {
        return $this->dealType;
    }

    /**
     * Sets a new dealType
     *
     * Тип сделки
     *
     * @param string $dealType
     * @return static
     */
    public function setDealType($dealType)
    {
        $this->dealType = $dealType;
        return $this;
    }

    /**
     * Gets as totalSellSum
     *
     * Сумма обязательной продажи
     *
     * @return float
     */
    public function getTotalSellSum()
    {
        return $this->totalSellSum;
    }

    /**
     * Sets a new totalSellSum
     *
     * Сумма обязательной продажи
     *
     * @param float $totalSellSum
     * @return static
     */
    public function setTotalSellSum($totalSellSum)
    {
        $this->totalSellSum = $totalSellSum;
        return $this;
    }

    /**
     * Gets as overSellSum
     *
     * Сумма продажи сверх установленного размера
     *
     * @return float
     */
    public function getOverSellSum()
    {
        return $this->overSellSum;
    }

    /**
     * Sets a new overSellSum
     *
     * Сумма продажи сверх установленного размера
     *
     * @param float $overSellSum
     * @return static
     */
    public function setOverSellSum($overSellSum)
    {
        $this->overSellSum = $overSellSum;
        return $this;
    }

    /**
     * Gets as percent
     *
     * Процент суммы для обязательной продажи
     *
     * @return float
     */
    public function getPercent()
    {
        return $this->percent;
    }

    /**
     * Sets a new percent
     *
     * Процент суммы для обязательной продажи
     *
     * @param float $percent
     * @return static
     */
    public function setPercent($percent)
    {
        $this->percent = $percent;
        return $this;
    }

    /**
     * Gets as acc
     *
     * Реквизиты счета зачисления рублей (номер счета д.б. заполнен)
     *
     * @return \common\models\raiffeisenxml\request\AccountNoNameType
     */
    public function getAcc()
    {
        return $this->acc;
    }

    /**
     * Sets a new acc
     *
     * Реквизиты счета зачисления рублей (номер счета д.б. заполнен)
     *
     * @param \common\models\raiffeisenxml\request\AccountNoNameType $acc
     * @return static
     */
    public function setAcc(\common\models\raiffeisenxml\request\AccountNoNameType $acc)
    {
        $this->acc = $acc;
        return $this;
    }


}


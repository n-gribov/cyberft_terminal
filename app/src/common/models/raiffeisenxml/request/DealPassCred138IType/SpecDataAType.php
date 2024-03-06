<?php

namespace common\models\raiffeisenxml\request\DealPassCred138IType;

/**
 * Class representing SpecDataAType
 */
class SpecDataAType
{

    /**
     * 8.1 Процентные платежи, предусмотренные кредитным договором (за искл.
     *  платежей по возврату основного долга)
     *
     * @property \common\models\raiffeisenxml\request\DealPassCred138IType\SpecDataAType\PercentAType $percent
     */
    private $percent = null;

    /**
     * 8.2 Иные платежи, предусмотренные кредитным договором (за искл. платежей по возврату
     *  основного долга и процентных платежей,
     *
     *  указанный в п.8.1)
     *
     * @property string $otherPay
     */
    private $otherPay = null;

    /**
     * 8.3 Сумма задолженности по основному долгу на дату, предшествующую
     *  дате оформления ПС
     *
     * @property \common\models\raiffeisenxml\request\CurrAmountType $debtSum
     */
    private $debtSum = null;

    /**
     * Gets as percent
     *
     * 8.1 Процентные платежи, предусмотренные кредитным договором (за искл.
     *  платежей по возврату основного долга)
     *
     * @return \common\models\raiffeisenxml\request\DealPassCred138IType\SpecDataAType\PercentAType
     */
    public function getPercent()
    {
        return $this->percent;
    }

    /**
     * Sets a new percent
     *
     * 8.1 Процентные платежи, предусмотренные кредитным договором (за искл.
     *  платежей по возврату основного долга)
     *
     * @param \common\models\raiffeisenxml\request\DealPassCred138IType\SpecDataAType\PercentAType $percent
     * @return static
     */
    public function setPercent(\common\models\raiffeisenxml\request\DealPassCred138IType\SpecDataAType\PercentAType $percent)
    {
        $this->percent = $percent;
        return $this;
    }

    /**
     * Gets as otherPay
     *
     * 8.2 Иные платежи, предусмотренные кредитным договором (за искл. платежей по возврату
     *  основного долга и процентных платежей,
     *
     *  указанный в п.8.1)
     *
     * @return string
     */
    public function getOtherPay()
    {
        return $this->otherPay;
    }

    /**
     * Sets a new otherPay
     *
     * 8.2 Иные платежи, предусмотренные кредитным договором (за искл. платежей по возврату
     *  основного долга и процентных платежей,
     *
     *  указанный в п.8.1)
     *
     * @param string $otherPay
     * @return static
     */
    public function setOtherPay($otherPay)
    {
        $this->otherPay = $otherPay;
        return $this;
    }

    /**
     * Gets as debtSum
     *
     * 8.3 Сумма задолженности по основному долгу на дату, предшествующую
     *  дате оформления ПС
     *
     * @return \common\models\raiffeisenxml\request\CurrAmountType
     */
    public function getDebtSum()
    {
        return $this->debtSum;
    }

    /**
     * Sets a new debtSum
     *
     * 8.3 Сумма задолженности по основному долгу на дату, предшествующую
     *  дате оформления ПС
     *
     * @param \common\models\raiffeisenxml\request\CurrAmountType $debtSum
     * @return static
     */
    public function setDebtSum(\common\models\raiffeisenxml\request\CurrAmountType $debtSum)
    {
        $this->debtSum = $debtSum;
        return $this;
    }


}


<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing DealPassSpecDataType
 *
 * Специальные сведения о кредитном договоре
 * XSD Type: DealPassSpecData
 */
class DealPassSpecDataType
{

    /**
     * Процентные платежи, предусмотренные кредитным договором (за искл. платежей по возврату основного долга)
     *
     * @property \common\models\sbbolxml\response\PercentType $percent
     */
    private $percent = null;

    /**
     * Иные платежи, предусмотренные кредитным договором (за искл. платежей по возврату основного долга и процентных платежей, указанный в п.8.1)
     *
     * @property string $otherPay
     */
    private $otherPay = null;

    /**
     * Сумма задолженности по основному долгу на дату, предшествующую дате оформления ПС
     *
     * @property \common\models\sbbolxml\response\CurrAmountType $debtSum
     */
    private $debtSum = null;

    /**
     * Gets as percent
     *
     * Процентные платежи, предусмотренные кредитным договором (за искл. платежей по возврату основного долга)
     *
     * @return \common\models\sbbolxml\response\PercentType
     */
    public function getPercent()
    {
        return $this->percent;
    }

    /**
     * Sets a new percent
     *
     * Процентные платежи, предусмотренные кредитным договором (за искл. платежей по возврату основного долга)
     *
     * @param \common\models\sbbolxml\response\PercentType $percent
     * @return static
     */
    public function setPercent(\common\models\sbbolxml\response\PercentType $percent)
    {
        $this->percent = $percent;
        return $this;
    }

    /**
     * Gets as otherPay
     *
     * Иные платежи, предусмотренные кредитным договором (за искл. платежей по возврату основного долга и процентных платежей, указанный в п.8.1)
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
     * Иные платежи, предусмотренные кредитным договором (за искл. платежей по возврату основного долга и процентных платежей, указанный в п.8.1)
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
     * Сумма задолженности по основному долгу на дату, предшествующую дате оформления ПС
     *
     * @return \common\models\sbbolxml\response\CurrAmountType
     */
    public function getDebtSum()
    {
        return $this->debtSum;
    }

    /**
     * Sets a new debtSum
     *
     * Сумма задолженности по основному долгу на дату, предшествующую дате оформления ПС
     *
     * @param \common\models\sbbolxml\response\CurrAmountType $debtSum
     * @return static
     */
    public function setDebtSum(\common\models\sbbolxml\response\CurrAmountType $debtSum)
    {
        $this->debtSum = $debtSum;
        return $this;
    }


}


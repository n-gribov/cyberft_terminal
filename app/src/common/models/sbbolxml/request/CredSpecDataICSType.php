<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing CredSpecDataICSType
 *
 * Специальные сведения о кредитном договоре
 * XSD Type: CredSpecDataICS
 */
class CredSpecDataICSType
{

    /**
     * Процентные платежи, предусмотренные кредитным договором (за исключением платежей
     *  по возврату основного долга)
     *
     * @property \common\models\sbbolxml\request\CredPercentPaymentsICSType $percent
     */
    private $percent = null;

    /**
     * Сумма задолженности по основному долгу на дату, предшествующую дате оформления
     *  ПС
     *
     * @property \common\models\sbbolxml\request\CurrAmountType $debtSum
     */
    private $debtSum = null;

    /**
     * Gets as percent
     *
     * Процентные платежи, предусмотренные кредитным договором (за исключением платежей
     *  по возврату основного долга)
     *
     * @return \common\models\sbbolxml\request\CredPercentPaymentsICSType
     */
    public function getPercent()
    {
        return $this->percent;
    }

    /**
     * Sets a new percent
     *
     * Процентные платежи, предусмотренные кредитным договором (за исключением платежей
     *  по возврату основного долга)
     *
     * @param \common\models\sbbolxml\request\CredPercentPaymentsICSType $percent
     * @return static
     */
    public function setPercent(\common\models\sbbolxml\request\CredPercentPaymentsICSType $percent)
    {
        $this->percent = $percent;
        return $this;
    }

    /**
     * Gets as debtSum
     *
     * Сумма задолженности по основному долгу на дату, предшествующую дате оформления
     *  ПС
     *
     * @return \common\models\sbbolxml\request\CurrAmountType
     */
    public function getDebtSum()
    {
        return $this->debtSum;
    }

    /**
     * Sets a new debtSum
     *
     * Сумма задолженности по основному долгу на дату, предшествующую дате оформления
     *  ПС
     *
     * @param \common\models\sbbolxml\request\CurrAmountType $debtSum
     * @return static
     */
    public function setDebtSum(\common\models\sbbolxml\request\CurrAmountType $debtSum)
    {
        $this->debtSum = $debtSum;
        return $this;
    }


}


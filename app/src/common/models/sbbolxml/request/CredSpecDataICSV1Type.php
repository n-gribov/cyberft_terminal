<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing CredSpecDataICSV1Type
 *
 * Специальные сведения о кредитном договоре
 * XSD Type: CredSpecDataICSV1
 */
class CredSpecDataICSV1Type
{

    /**
     * Процентные платежи, предусмотренные кредитным договором (за исключением платежей
     *  по возврату основного долга)
     *
     * @property \common\models\sbbolxml\request\CredPercentPaymentsICSType $percent
     */
    private $percent = null;

    /**
     * Специальные сведения о Кредитном договоре
     *
     * @property \common\models\sbbolxml\request\CurrAmountTypeV1Type $debtSum
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
     * Специальные сведения о Кредитном договоре
     *
     * @return \common\models\sbbolxml\request\CurrAmountTypeV1Type
     */
    public function getDebtSum()
    {
        return $this->debtSum;
    }

    /**
     * Sets a new debtSum
     *
     * Специальные сведения о Кредитном договоре
     *
     * @param \common\models\sbbolxml\request\CurrAmountTypeV1Type $debtSum
     * @return static
     */
    public function setDebtSum(\common\models\sbbolxml\request\CurrAmountTypeV1Type $debtSum)
    {
        $this->debtSum = $debtSum;
        return $this;
    }


}


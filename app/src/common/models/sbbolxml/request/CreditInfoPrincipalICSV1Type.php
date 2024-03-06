<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing CreditInfoPrincipalICSV1Type
 *
 * Платеж в счет основного долга
 * XSD Type: CreditInfoPrincipalICSV1
 */
class CreditInfoPrincipalICSV1Type
{

    /**
     * Сумма платежа
     *
     * @property \common\models\sbbolxml\request\CurrAmountTypeV1Type $payment
     */
    private $payment = null;

    /**
     * Доля в общей сумме кредита, %
     *  Формат XX.YY с двумя знаками после запятой
     *
     * @property float $credPercent
     */
    private $credPercent = null;

    /**
     * Gets as payment
     *
     * Сумма платежа
     *
     * @return \common\models\sbbolxml\request\CurrAmountTypeV1Type
     */
    public function getPayment()
    {
        return $this->payment;
    }

    /**
     * Sets a new payment
     *
     * Сумма платежа
     *
     * @param \common\models\sbbolxml\request\CurrAmountTypeV1Type $payment
     * @return static
     */
    public function setPayment(\common\models\sbbolxml\request\CurrAmountTypeV1Type $payment)
    {
        $this->payment = $payment;
        return $this;
    }

    /**
     * Gets as credPercent
     *
     * Доля в общей сумме кредита, %
     *  Формат XX.YY с двумя знаками после запятой
     *
     * @return float
     */
    public function getCredPercent()
    {
        return $this->credPercent;
    }

    /**
     * Sets a new credPercent
     *
     * Доля в общей сумме кредита, %
     *  Формат XX.YY с двумя знаками после запятой
     *
     * @param float $credPercent
     * @return static
     */
    public function setCredPercent($credPercent)
    {
        $this->credPercent = $credPercent;
        return $this;
    }


}


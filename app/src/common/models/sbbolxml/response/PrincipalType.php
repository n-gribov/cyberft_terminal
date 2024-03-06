<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing PrincipalType
 *
 * В счет основного долга
 * XSD Type: Principal
 */
class PrincipalType
{

    /**
     * Сумма платежа
     *
     * @property \common\models\sbbolxml\response\CurrAmountType $payment
     */
    private $payment = null;

    /**
     * Доля в общей сумме
     *
     * @property float $share
     */
    private $share = null;

    /**
     * Gets as payment
     *
     * Сумма платежа
     *
     * @return \common\models\sbbolxml\response\CurrAmountType
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
     * @param \common\models\sbbolxml\response\CurrAmountType $payment
     * @return static
     */
    public function setPayment(\common\models\sbbolxml\response\CurrAmountType $payment)
    {
        $this->payment = $payment;
        return $this;
    }

    /**
     * Gets as share
     *
     * Доля в общей сумме
     *
     * @return float
     */
    public function getShare()
    {
        return $this->share;
    }

    /**
     * Sets a new share
     *
     * Доля в общей сумме
     *
     * @param float $share
     * @return static
     */
    public function setShare($share)
    {
        $this->share = $share;
        return $this;
    }


}


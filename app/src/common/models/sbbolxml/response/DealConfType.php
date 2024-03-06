<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing DealConfType
 *
 * Сообщение о подтверждении сделки
 * XSD Type: DealConfType
 */
class DealConfType
{

    /**
     * Заголовок
     *
     * @property \common\models\sbbolxml\response\HDRType $hDR
     */
    private $hDR = null;

    /**
     * Информация по сделке
     *
     * @property \common\models\sbbolxml\response\DealType $deal
     */
    private $deal = null;

    /**
     * ПИ
     *
     * @property \common\models\sbbolxml\response\SIType $sI
     */
    private $sI = null;

    /**
     * Электронная подпись
     *
     * @property \common\models\sbbolxml\response\DigitalSignType $sign
     */
    private $sign = null;

    /**
     * Gets as hDR
     *
     * Заголовок
     *
     * @return \common\models\sbbolxml\response\HDRType
     */
    public function getHDR()
    {
        return $this->hDR;
    }

    /**
     * Sets a new hDR
     *
     * Заголовок
     *
     * @param \common\models\sbbolxml\response\HDRType $hDR
     * @return static
     */
    public function setHDR(\common\models\sbbolxml\response\HDRType $hDR)
    {
        $this->hDR = $hDR;
        return $this;
    }

    /**
     * Gets as deal
     *
     * Информация по сделке
     *
     * @return \common\models\sbbolxml\response\DealType
     */
    public function getDeal()
    {
        return $this->deal;
    }

    /**
     * Sets a new deal
     *
     * Информация по сделке
     *
     * @param \common\models\sbbolxml\response\DealType $deal
     * @return static
     */
    public function setDeal(\common\models\sbbolxml\response\DealType $deal)
    {
        $this->deal = $deal;
        return $this;
    }

    /**
     * Gets as sI
     *
     * ПИ
     *
     * @return \common\models\sbbolxml\response\SIType
     */
    public function getSI()
    {
        return $this->sI;
    }

    /**
     * Sets a new sI
     *
     * ПИ
     *
     * @param \common\models\sbbolxml\response\SIType $sI
     * @return static
     */
    public function setSI(\common\models\sbbolxml\response\SIType $sI)
    {
        $this->sI = $sI;
        return $this;
    }

    /**
     * Gets as sign
     *
     * Электронная подпись
     *
     * @return \common\models\sbbolxml\response\DigitalSignType
     */
    public function getSign()
    {
        return $this->sign;
    }

    /**
     * Sets a new sign
     *
     * Электронная подпись
     *
     * @param \common\models\sbbolxml\response\DigitalSignType $sign
     * @return static
     */
    public function setSign(\common\models\sbbolxml\response\DigitalSignType $sign)
    {
        $this->sign = $sign;
        return $this;
    }


}


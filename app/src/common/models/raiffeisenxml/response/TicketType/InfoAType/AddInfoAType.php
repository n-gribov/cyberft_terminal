<?php

namespace common\models\raiffeisenxml\response\TicketType\InfoAType;

/**
 * Class representing AddInfoAType
 */
class AddInfoAType
{

    /**
     * Доп. информация для квитанции по поручению на перевод
     *  валюты
     *
     * @property \common\models\raiffeisenxml\response\PayDocCurTicketType $payDocCur
     */
    private $payDocCur = null;

    /**
     * Доп. информация для квитанции по поручению на покупку,
     *  продажу, конверсию
     *
     * @property \common\models\raiffeisenxml\response\CurrConvTicketType $currConv
     */
    private $currConv = null;

    /**
     * @property \common\models\raiffeisenxml\response\MandatorySaleTicketType $mandatorySale
     */
    private $mandatorySale = null;

    /**
     * @property \common\models\raiffeisenxml\response\DealPassTicketType $dealPass
     */
    private $dealPass = null;

    /**
     * Gets as payDocCur
     *
     * Доп. информация для квитанции по поручению на перевод
     *  валюты
     *
     * @return \common\models\raiffeisenxml\response\PayDocCurTicketType
     */
    public function getPayDocCur()
    {
        return $this->payDocCur;
    }

    /**
     * Sets a new payDocCur
     *
     * Доп. информация для квитанции по поручению на перевод
     *  валюты
     *
     * @param \common\models\raiffeisenxml\response\PayDocCurTicketType $payDocCur
     * @return static
     */
    public function setPayDocCur(\common\models\raiffeisenxml\response\PayDocCurTicketType $payDocCur)
    {
        $this->payDocCur = $payDocCur;
        return $this;
    }

    /**
     * Gets as currConv
     *
     * Доп. информация для квитанции по поручению на покупку,
     *  продажу, конверсию
     *
     * @return \common\models\raiffeisenxml\response\CurrConvTicketType
     */
    public function getCurrConv()
    {
        return $this->currConv;
    }

    /**
     * Sets a new currConv
     *
     * Доп. информация для квитанции по поручению на покупку,
     *  продажу, конверсию
     *
     * @param \common\models\raiffeisenxml\response\CurrConvTicketType $currConv
     * @return static
     */
    public function setCurrConv(\common\models\raiffeisenxml\response\CurrConvTicketType $currConv)
    {
        $this->currConv = $currConv;
        return $this;
    }

    /**
     * Gets as mandatorySale
     *
     * @return \common\models\raiffeisenxml\response\MandatorySaleTicketType
     */
    public function getMandatorySale()
    {
        return $this->mandatorySale;
    }

    /**
     * Sets a new mandatorySale
     *
     * @param \common\models\raiffeisenxml\response\MandatorySaleTicketType $mandatorySale
     * @return static
     */
    public function setMandatorySale(\common\models\raiffeisenxml\response\MandatorySaleTicketType $mandatorySale)
    {
        $this->mandatorySale = $mandatorySale;
        return $this;
    }

    /**
     * Gets as dealPass
     *
     * @return \common\models\raiffeisenxml\response\DealPassTicketType
     */
    public function getDealPass()
    {
        return $this->dealPass;
    }

    /**
     * Sets a new dealPass
     *
     * @param \common\models\raiffeisenxml\response\DealPassTicketType $dealPass
     * @return static
     */
    public function setDealPass(\common\models\raiffeisenxml\response\DealPassTicketType $dealPass)
    {
        $this->dealPass = $dealPass;
        return $this;
    }


}


<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing SmsTimeoutsType
 *
 * Таймауты действия смс-пароля
 * XSD Type: SmsTimeouts
 */
class SmsTimeoutsType
{

    /**
     * Таймаут в миллисекундах
     *
     * @property integer $generalSmsTimeout
     */
    private $generalSmsTimeout = null;

    /**
     * Таймаут в миллисекундах
     *
     * @property integer $fraudSmsTimeout
     */
    private $fraudSmsTimeout = null;

    /**
     * @property \common\models\sbbolxml\response\DigitalSignType $sign
     */
    private $sign = null;

    /**
     * Gets as generalSmsTimeout
     *
     * Таймаут в миллисекундах
     *
     * @return integer
     */
    public function getGeneralSmsTimeout()
    {
        return $this->generalSmsTimeout;
    }

    /**
     * Sets a new generalSmsTimeout
     *
     * Таймаут в миллисекундах
     *
     * @param integer $generalSmsTimeout
     * @return static
     */
    public function setGeneralSmsTimeout($generalSmsTimeout)
    {
        $this->generalSmsTimeout = $generalSmsTimeout;
        return $this;
    }

    /**
     * Gets as fraudSmsTimeout
     *
     * Таймаут в миллисекундах
     *
     * @return integer
     */
    public function getFraudSmsTimeout()
    {
        return $this->fraudSmsTimeout;
    }

    /**
     * Sets a new fraudSmsTimeout
     *
     * Таймаут в миллисекундах
     *
     * @param integer $fraudSmsTimeout
     * @return static
     */
    public function setFraudSmsTimeout($fraudSmsTimeout)
    {
        $this->fraudSmsTimeout = $fraudSmsTimeout;
        return $this;
    }

    /**
     * Gets as sign
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
     * @param \common\models\sbbolxml\response\DigitalSignType $sign
     * @return static
     */
    public function setSign(\common\models\sbbolxml\response\DigitalSignType $sign)
    {
        $this->sign = $sign;
        return $this;
    }


}


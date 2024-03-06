<?php

namespace common\models\sbbolxml\request\RequestType;

/**
 * Class representing PayDocRuSMSAType
 */
class PayDocRuSMSAType
{

    /**
     * @property string $sessionId
     */
    private $sessionId = null;

    /**
     * @property string $oneTimePassword
     */
    private $oneTimePassword = null;

    /**
     * Платёжное поручение рублёвое
     *
     * @property \common\models\sbbolxml\request\PayDocRuType $payDocRu
     */
    private $payDocRu = null;

    /**
     * Gets as sessionId
     *
     * @return string
     */
    public function getSessionId()
    {
        return $this->sessionId;
    }

    /**
     * Sets a new sessionId
     *
     * @param string $sessionId
     * @return static
     */
    public function setSessionId($sessionId)
    {
        $this->sessionId = $sessionId;
        return $this;
    }

    /**
     * Gets as oneTimePassword
     *
     * @return string
     */
    public function getOneTimePassword()
    {
        return $this->oneTimePassword;
    }

    /**
     * Sets a new oneTimePassword
     *
     * @param string $oneTimePassword
     * @return static
     */
    public function setOneTimePassword($oneTimePassword)
    {
        $this->oneTimePassword = $oneTimePassword;
        return $this;
    }

    /**
     * Gets as payDocRu
     *
     * Платёжное поручение рублёвое
     *
     * @return \common\models\sbbolxml\request\PayDocRuType
     */
    public function getPayDocRu()
    {
        return $this->payDocRu;
    }

    /**
     * Sets a new payDocRu
     *
     * Платёжное поручение рублёвое
     *
     * @param \common\models\sbbolxml\request\PayDocRuType $payDocRu
     * @return static
     */
    public function setPayDocRu(\common\models\sbbolxml\request\PayDocRuType $payDocRu)
    {
        $this->payDocRu = $payDocRu;
        return $this;
    }


}


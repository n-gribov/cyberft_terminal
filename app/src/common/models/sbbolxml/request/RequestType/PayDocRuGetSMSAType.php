<?php

namespace common\models\sbbolxml\request\RequestType;

/**
 * Class representing PayDocRuGetSMSAType
 */
class PayDocRuGetSMSAType
{

    /**
     * Платёжное поручение рублёвое
     *
     * @property \common\models\sbbolxml\request\PayDocRuType $payDocRu
     */
    private $payDocRu = null;

    /**
     * @property string $sessionId
     */
    private $sessionId = null;

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


}


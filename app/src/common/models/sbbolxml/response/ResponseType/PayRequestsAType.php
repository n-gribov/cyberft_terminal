<?php

namespace common\models\sbbolxml\response\ResponseType;

/**
 * Class representing PayRequestsAType
 */
class PayRequestsAType
{

    /**
     * Входящее платежное требование
     *
     * @property \common\models\sbbolxml\response\PayRequestType[] $payRequest
     */
    private $payRequest = array(
        
    );

    /**
     * Adds as payRequest
     *
     * Входящее платежное требование
     *
     * @return static
     * @param \common\models\sbbolxml\response\PayRequestType $payRequest
     */
    public function addToPayRequest(\common\models\sbbolxml\response\PayRequestType $payRequest)
    {
        $this->payRequest[] = $payRequest;
        return $this;
    }

    /**
     * isset payRequest
     *
     * Входящее платежное требование
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetPayRequest($index)
    {
        return isset($this->payRequest[$index]);
    }

    /**
     * unset payRequest
     *
     * Входящее платежное требование
     *
     * @param scalar $index
     * @return void
     */
    public function unsetPayRequest($index)
    {
        unset($this->payRequest[$index]);
    }

    /**
     * Gets as payRequest
     *
     * Входящее платежное требование
     *
     * @return \common\models\sbbolxml\response\PayRequestType[]
     */
    public function getPayRequest()
    {
        return $this->payRequest;
    }

    /**
     * Sets a new payRequest
     *
     * Входящее платежное требование
     *
     * @param \common\models\sbbolxml\response\PayRequestType[] $payRequest
     * @return static
     */
    public function setPayRequest(array $payRequest)
    {
        $this->payRequest = $payRequest;
        return $this;
    }


}


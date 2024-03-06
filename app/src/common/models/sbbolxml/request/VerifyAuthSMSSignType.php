<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing VerifyAuthSMSSignType
 *
 *
 * XSD Type: VerifyAuthSMSSign
 */
class VerifyAuthSMSSignType
{

    /**
     * Код для подписи, при использовании открытого канала передается хеш по ГОСТ 3411
     *
     * @property string $smsCode
     */
    private $smsCode = null;

    /**
     * Gets as smsCode
     *
     * Код для подписи, при использовании открытого канала передается хеш по ГОСТ 3411
     *
     * @return string
     */
    public function getSmsCode()
    {
        return $this->smsCode;
    }

    /**
     * Sets a new smsCode
     *
     * Код для подписи, при использовании открытого канала передается хеш по ГОСТ 3411
     *
     * @param string $smsCode
     * @return static
     */
    public function setSmsCode($smsCode)
    {
        $this->smsCode = $smsCode;
        return $this;
    }


}


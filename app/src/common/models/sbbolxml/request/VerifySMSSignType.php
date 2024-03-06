<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing VerifySMSSignType
 *
 *
 * XSD Type: VerifySMSSign
 */
class VerifySMSSignType
{

    /**
     * Код для подписи, при использовании открытого канала передается хеш по ГОСТ 3411
     *
     * @property string $smsCode
     */
    private $smsCode = null;

    /**
     * Идентификатор смс
     *
     * @property string $smsId
     */
    private $smsId = null;

    /**
     * GUID криптопрофиля
     *
     * @property string $cryptoProfileID
     */
    private $cryptoProfileID = null;

    /**
     * Параметры запроса на подпись с помощью SMS
     *
     * @property \common\models\sbbolxml\request\SMSSignReqParamsType $sMSSignReqParams
     */
    private $sMSSignReqParams = null;

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

    /**
     * Gets as smsId
     *
     * Идентификатор смс
     *
     * @return string
     */
    public function getSmsId()
    {
        return $this->smsId;
    }

    /**
     * Sets a new smsId
     *
     * Идентификатор смс
     *
     * @param string $smsId
     * @return static
     */
    public function setSmsId($smsId)
    {
        $this->smsId = $smsId;
        return $this;
    }

    /**
     * Gets as cryptoProfileID
     *
     * GUID криптопрофиля
     *
     * @return string
     */
    public function getCryptoProfileID()
    {
        return $this->cryptoProfileID;
    }

    /**
     * Sets a new cryptoProfileID
     *
     * GUID криптопрофиля
     *
     * @param string $cryptoProfileID
     * @return static
     */
    public function setCryptoProfileID($cryptoProfileID)
    {
        $this->cryptoProfileID = $cryptoProfileID;
        return $this;
    }

    /**
     * Gets as sMSSignReqParams
     *
     * Параметры запроса на подпись с помощью SMS
     *
     * @return \common\models\sbbolxml\request\SMSSignReqParamsType
     */
    public function getSMSSignReqParams()
    {
        return $this->sMSSignReqParams;
    }

    /**
     * Sets a new sMSSignReqParams
     *
     * Параметры запроса на подпись с помощью SMS
     *
     * @param \common\models\sbbolxml\request\SMSSignReqParamsType $sMSSignReqParams
     * @return static
     */
    public function setSMSSignReqParams(\common\models\sbbolxml\request\SMSSignReqParamsType $sMSSignReqParams)
    {
        $this->sMSSignReqParams = $sMSSignReqParams;
        return $this;
    }


}


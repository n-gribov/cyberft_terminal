<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing PayRequestType
 *
 *
 * XSD Type: PayRequest
 */
class PayRequestType
{

    /**
     * Идентификатор документа в УС
     *
     * @property string $docExtId
     */
    private $docExtId = null;

    /**
     * Идентификатор организации в СББОЛ
     *
     * @property string $orgId
     */
    private $orgId = null;

    /**
     * Реквизиты платёжного документа
     *
     * @property \common\models\sbbolxml\response\AccDocType $accDoc
     */
    private $accDoc = null;

    /**
     * Реквизиты плательщика
     *
     * @property \common\models\sbbolxml\response\ContragentType $payer
     */
    private $payer = null;

    /**
     * Реквизиты получателя
     *
     * @property \common\models\sbbolxml\response\ClientType $payee
     */
    private $payee = null;

    /**
     * Поля специфичные для платежного требования и инкасового поручения
     *
     * @property \common\models\sbbolxml\response\PayRequestType\PayReqInfoAType $payReqInfo
     */
    private $payReqInfo = null;

    /**
     * @property \common\models\sbbolxml\response\DigitalSignType $sign
     */
    private $sign = null;

    /**
     * Gets as docExtId
     *
     * Идентификатор документа в УС
     *
     * @return string
     */
    public function getDocExtId()
    {
        return $this->docExtId;
    }

    /**
     * Sets a new docExtId
     *
     * Идентификатор документа в УС
     *
     * @param string $docExtId
     * @return static
     */
    public function setDocExtId($docExtId)
    {
        $this->docExtId = $docExtId;
        return $this;
    }

    /**
     * Gets as orgId
     *
     * Идентификатор организации в СББОЛ
     *
     * @return string
     */
    public function getOrgId()
    {
        return $this->orgId;
    }

    /**
     * Sets a new orgId
     *
     * Идентификатор организации в СББОЛ
     *
     * @param string $orgId
     * @return static
     */
    public function setOrgId($orgId)
    {
        $this->orgId = $orgId;
        return $this;
    }

    /**
     * Gets as accDoc
     *
     * Реквизиты платёжного документа
     *
     * @return \common\models\sbbolxml\response\AccDocType
     */
    public function getAccDoc()
    {
        return $this->accDoc;
    }

    /**
     * Sets a new accDoc
     *
     * Реквизиты платёжного документа
     *
     * @param \common\models\sbbolxml\response\AccDocType $accDoc
     * @return static
     */
    public function setAccDoc(\common\models\sbbolxml\response\AccDocType $accDoc)
    {
        $this->accDoc = $accDoc;
        return $this;
    }

    /**
     * Gets as payer
     *
     * Реквизиты плательщика
     *
     * @return \common\models\sbbolxml\response\ContragentType
     */
    public function getPayer()
    {
        return $this->payer;
    }

    /**
     * Sets a new payer
     *
     * Реквизиты плательщика
     *
     * @param \common\models\sbbolxml\response\ContragentType $payer
     * @return static
     */
    public function setPayer(\common\models\sbbolxml\response\ContragentType $payer)
    {
        $this->payer = $payer;
        return $this;
    }

    /**
     * Gets as payee
     *
     * Реквизиты получателя
     *
     * @return \common\models\sbbolxml\response\ClientType
     */
    public function getPayee()
    {
        return $this->payee;
    }

    /**
     * Sets a new payee
     *
     * Реквизиты получателя
     *
     * @param \common\models\sbbolxml\response\ClientType $payee
     * @return static
     */
    public function setPayee(\common\models\sbbolxml\response\ClientType $payee)
    {
        $this->payee = $payee;
        return $this;
    }

    /**
     * Gets as payReqInfo
     *
     * Поля специфичные для платежного требования и инкасового поручения
     *
     * @return \common\models\sbbolxml\response\PayRequestType\PayReqInfoAType
     */
    public function getPayReqInfo()
    {
        return $this->payReqInfo;
    }

    /**
     * Sets a new payReqInfo
     *
     * Поля специфичные для платежного требования и инкасового поручения
     *
     * @param \common\models\sbbolxml\response\PayRequestType\PayReqInfoAType $payReqInfo
     * @return static
     */
    public function setPayReqInfo(\common\models\sbbolxml\response\PayRequestType\PayReqInfoAType $payReqInfo)
    {
        $this->payReqInfo = $payReqInfo;
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


<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing PayRequestType
 *
 *
 * XSD Type: PayRequest
 */
class PayRequestType extends DocBaseType
{

    /**
     * Параметр, указывающий на тип докмента (integer): 06 – инкассовое поручение; 02 –
     *  платежное требование.
     *
     * @property string $type
     */
    private $type = null;

    /**
     * Реквизиты платёжного документа
     *
     * @property \common\models\sbbolxml\request\AccDocType $accDoc
     */
    private $accDoc = null;

    /**
     * Реквизиты плательщика
     *
     * @property \common\models\sbbolxml\request\PayRequestClientType $payer
     */
    private $payer = null;

    /**
     * Реквизиты получателя
     *
     * @property \common\models\sbbolxml\request\PayRequestClientType $payee
     */
    private $payee = null;

    /**
     * Налоговая информация: поля 101, 104 - 110 платёжного поручения
     *
     * @property \common\models\sbbolxml\request\BudgetDepartmentalInfoType $departmentalInfo
     */
    private $departmentalInfo = null;

    /**
     * Поля специфичные для платежного требования и инкасового поручения
     *
     * @property \common\models\sbbolxml\request\PayRequestType\PayReqInfoAType $payReqInfo
     */
    private $payReqInfo = null;

    /**
     * Gets as type
     *
     * Параметр, указывающий на тип докмента (integer): 06 – инкассовое поручение; 02 –
     *  платежное требование.
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Sets a new type
     *
     * Параметр, указывающий на тип докмента (integer): 06 – инкассовое поручение; 02 –
     *  платежное требование.
     *
     * @param string $type
     * @return static
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * Gets as accDoc
     *
     * Реквизиты платёжного документа
     *
     * @return \common\models\sbbolxml\request\AccDocType
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
     * @param \common\models\sbbolxml\request\AccDocType $accDoc
     * @return static
     */
    public function setAccDoc(\common\models\sbbolxml\request\AccDocType $accDoc)
    {
        $this->accDoc = $accDoc;
        return $this;
    }

    /**
     * Gets as payer
     *
     * Реквизиты плательщика
     *
     * @return \common\models\sbbolxml\request\PayRequestClientType
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
     * @param \common\models\sbbolxml\request\PayRequestClientType $payer
     * @return static
     */
    public function setPayer(\common\models\sbbolxml\request\PayRequestClientType $payer)
    {
        $this->payer = $payer;
        return $this;
    }

    /**
     * Gets as payee
     *
     * Реквизиты получателя
     *
     * @return \common\models\sbbolxml\request\PayRequestClientType
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
     * @param \common\models\sbbolxml\request\PayRequestClientType $payee
     * @return static
     */
    public function setPayee(\common\models\sbbolxml\request\PayRequestClientType $payee)
    {
        $this->payee = $payee;
        return $this;
    }

    /**
     * Gets as departmentalInfo
     *
     * Налоговая информация: поля 101, 104 - 110 платёжного поручения
     *
     * @return \common\models\sbbolxml\request\BudgetDepartmentalInfoType
     */
    public function getDepartmentalInfo()
    {
        return $this->departmentalInfo;
    }

    /**
     * Sets a new departmentalInfo
     *
     * Налоговая информация: поля 101, 104 - 110 платёжного поручения
     *
     * @param \common\models\sbbolxml\request\BudgetDepartmentalInfoType $departmentalInfo
     * @return static
     */
    public function setDepartmentalInfo(\common\models\sbbolxml\request\BudgetDepartmentalInfoType $departmentalInfo)
    {
        $this->departmentalInfo = $departmentalInfo;
        return $this;
    }

    /**
     * Gets as payReqInfo
     *
     * Поля специфичные для платежного требования и инкасового поручения
     *
     * @return \common\models\sbbolxml\request\PayRequestType\PayReqInfoAType
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
     * @param \common\models\sbbolxml\request\PayRequestType\PayReqInfoAType $payReqInfo
     * @return static
     */
    public function setPayReqInfo(\common\models\sbbolxml\request\PayRequestType\PayReqInfoAType $payReqInfo)
    {
        $this->payReqInfo = $payReqInfo;
        return $this;
    }


}


<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing VBKInfoType
 *
 *
 * XSD Type: VBKInfo
 */
class VBKInfoType
{

    /**
     * Номер строки
     *
     * @property integer $lineNumber
     */
    private $lineNumber = null;

    /**
     * Номер ПС
     *
     * @property string $dealPassNum
     */
    private $dealPassNum = null;

    /**
     * Информация о контракте/кредитном договоре
     *
     * @property \common\models\sbbolxml\request\ContractType $contract
     */
    private $contract = null;

    /**
     * Запросить информацию из ВБК
     *
     * @property \common\models\sbbolxml\request\VBKInfoReqParamType $vBKInfoReqParam
     */
    private $vBKInfoReqParam = null;

    /**
     * Gets as lineNumber
     *
     * Номер строки
     *
     * @return integer
     */
    public function getLineNumber()
    {
        return $this->lineNumber;
    }

    /**
     * Sets a new lineNumber
     *
     * Номер строки
     *
     * @param integer $lineNumber
     * @return static
     */
    public function setLineNumber($lineNumber)
    {
        $this->lineNumber = $lineNumber;
        return $this;
    }

    /**
     * Gets as dealPassNum
     *
     * Номер ПС
     *
     * @return string
     */
    public function getDealPassNum()
    {
        return $this->dealPassNum;
    }

    /**
     * Sets a new dealPassNum
     *
     * Номер ПС
     *
     * @param string $dealPassNum
     * @return static
     */
    public function setDealPassNum($dealPassNum)
    {
        $this->dealPassNum = $dealPassNum;
        return $this;
    }

    /**
     * Gets as contract
     *
     * Информация о контракте/кредитном договоре
     *
     * @return \common\models\sbbolxml\request\ContractType
     */
    public function getContract()
    {
        return $this->contract;
    }

    /**
     * Sets a new contract
     *
     * Информация о контракте/кредитном договоре
     *
     * @param \common\models\sbbolxml\request\ContractType $contract
     * @return static
     */
    public function setContract(\common\models\sbbolxml\request\ContractType $contract)
    {
        $this->contract = $contract;
        return $this;
    }

    /**
     * Gets as vBKInfoReqParam
     *
     * Запросить информацию из ВБК
     *
     * @return \common\models\sbbolxml\request\VBKInfoReqParamType
     */
    public function getVBKInfoReqParam()
    {
        return $this->vBKInfoReqParam;
    }

    /**
     * Sets a new vBKInfoReqParam
     *
     * Запросить информацию из ВБК
     *
     * @param \common\models\sbbolxml\request\VBKInfoReqParamType $vBKInfoReqParam
     * @return static
     */
    public function setVBKInfoReqParam(\common\models\sbbolxml\request\VBKInfoReqParamType $vBKInfoReqParam)
    {
        $this->vBKInfoReqParam = $vBKInfoReqParam;
        return $this;
    }


}


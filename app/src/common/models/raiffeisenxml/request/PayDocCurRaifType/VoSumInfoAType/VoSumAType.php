<?php

namespace common\models\raiffeisenxml\request\PayDocCurRaifType\VoSumInfoAType;

/**
 * Class representing VoSumAType
 */
class VoSumAType
{

    /**
     * Код вида валютной операции
     *
     * @property string $vo
     */
    private $vo = null;

    /**
     * Валюта и сумма поручения
     *
     * @property \common\models\raiffeisenxml\request\PayDocCurRaifType\VoSumInfoAType\VoSumAType\SumAType $sum
     */
    private $sum = null;

    /**
     * @property \common\models\raiffeisenxml\request\DealPassDataType $dealPassData
     */
    private $dealPassData = null;

    /**
     * @property \common\models\raiffeisenxml\request\ContractType $contractData
     */
    private $contractData = null;

    /**
     * Gets as vo
     *
     * Код вида валютной операции
     *
     * @return string
     */
    public function getVo()
    {
        return $this->vo;
    }

    /**
     * Sets a new vo
     *
     * Код вида валютной операции
     *
     * @param string $vo
     * @return static
     */
    public function setVo($vo)
    {
        $this->vo = $vo;
        return $this;
    }

    /**
     * Gets as sum
     *
     * Валюта и сумма поручения
     *
     * @return \common\models\raiffeisenxml\request\PayDocCurRaifType\VoSumInfoAType\VoSumAType\SumAType
     */
    public function getSum()
    {
        return $this->sum;
    }

    /**
     * Sets a new sum
     *
     * Валюта и сумма поручения
     *
     * @param \common\models\raiffeisenxml\request\PayDocCurRaifType\VoSumInfoAType\VoSumAType\SumAType $sum
     * @return static
     */
    public function setSum(\common\models\raiffeisenxml\request\PayDocCurRaifType\VoSumInfoAType\VoSumAType\SumAType $sum)
    {
        $this->sum = $sum;
        return $this;
    }

    /**
     * Gets as dealPassData
     *
     * @return \common\models\raiffeisenxml\request\DealPassDataType
     */
    public function getDealPassData()
    {
        return $this->dealPassData;
    }

    /**
     * Sets a new dealPassData
     *
     * @param \common\models\raiffeisenxml\request\DealPassDataType $dealPassData
     * @return static
     */
    public function setDealPassData(\common\models\raiffeisenxml\request\DealPassDataType $dealPassData)
    {
        $this->dealPassData = $dealPassData;
        return $this;
    }

    /**
     * Gets as contractData
     *
     * @return \common\models\raiffeisenxml\request\ContractType
     */
    public function getContractData()
    {
        return $this->contractData;
    }

    /**
     * Sets a new contractData
     *
     * @param \common\models\raiffeisenxml\request\ContractType $contractData
     * @return static
     */
    public function setContractData(\common\models\raiffeisenxml\request\ContractType $contractData)
    {
        $this->contractData = $contractData;
        return $this;
    }


}


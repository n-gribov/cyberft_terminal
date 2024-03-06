<?php

namespace common\models\sbbolxml\request\ContractAddType;

/**
 * Class representing ContractAmountAType
 */
class ContractAmountAType
{

    /**
     * Сумма контракта
     *
     * @property \common\models\sbbolxml\request\ContractAddType\ContractAmountAType\ContractSummAType $contractSumm
     */
    private $contractSumm = null;

    /**
     * Сумма аванса
     *
     * @property float $advanceSumm
     */
    private $advanceSumm = null;

    /**
     * Согласованный размер прибыли по контракту
     *
     * @property float $profitSumm
     */
    private $profitSumm = null;

    /**
     * Сумма расходов (возмещаемые расходы)
     *
     * @property float $expSumm
     */
    private $expSumm = null;

    /**
     * Gets as contractSumm
     *
     * Сумма контракта
     *
     * @return \common\models\sbbolxml\request\ContractAddType\ContractAmountAType\ContractSummAType
     */
    public function getContractSumm()
    {
        return $this->contractSumm;
    }

    /**
     * Sets a new contractSumm
     *
     * Сумма контракта
     *
     * @param \common\models\sbbolxml\request\ContractAddType\ContractAmountAType\ContractSummAType $contractSumm
     * @return static
     */
    public function setContractSumm(\common\models\sbbolxml\request\ContractAddType\ContractAmountAType\ContractSummAType $contractSumm)
    {
        $this->contractSumm = $contractSumm;
        return $this;
    }

    /**
     * Gets as advanceSumm
     *
     * Сумма аванса
     *
     * @return float
     */
    public function getAdvanceSumm()
    {
        return $this->advanceSumm;
    }

    /**
     * Sets a new advanceSumm
     *
     * Сумма аванса
     *
     * @param float $advanceSumm
     * @return static
     */
    public function setAdvanceSumm($advanceSumm)
    {
        $this->advanceSumm = $advanceSumm;
        return $this;
    }

    /**
     * Gets as profitSumm
     *
     * Согласованный размер прибыли по контракту
     *
     * @return float
     */
    public function getProfitSumm()
    {
        return $this->profitSumm;
    }

    /**
     * Sets a new profitSumm
     *
     * Согласованный размер прибыли по контракту
     *
     * @param float $profitSumm
     * @return static
     */
    public function setProfitSumm($profitSumm)
    {
        $this->profitSumm = $profitSumm;
        return $this;
    }

    /**
     * Gets as expSumm
     *
     * Сумма расходов (возмещаемые расходы)
     *
     * @return float
     */
    public function getExpSumm()
    {
        return $this->expSumm;
    }

    /**
     * Sets a new expSumm
     *
     * Сумма расходов (возмещаемые расходы)
     *
     * @param float $expSumm
     * @return static
     */
    public function setExpSumm($expSumm)
    {
        $this->expSumm = $expSumm;
        return $this;
    }


}


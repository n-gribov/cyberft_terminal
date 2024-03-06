<?php

namespace common\models\sbbolxml\request\ContractAddType\ContractAmountAType;

/**
 * Class representing ContractSummAType
 */
class ContractSummAType
{

    /**
     * Сумма контракта
     *
     * @property float $summ
     */
    private $summ = null;

    /**
     * Трёхбуквенный код валюты
     *
     * @property string $currIsoCode
     */
    private $currIsoCode = null;

    /**
     * Gets as summ
     *
     * Сумма контракта
     *
     * @return float
     */
    public function getSumm()
    {
        return $this->summ;
    }

    /**
     * Sets a new summ
     *
     * Сумма контракта
     *
     * @param float $summ
     * @return static
     */
    public function setSumm($summ)
    {
        $this->summ = $summ;
        return $this;
    }

    /**
     * Gets as currIsoCode
     *
     * Трёхбуквенный код валюты
     *
     * @return string
     */
    public function getCurrIsoCode()
    {
        return $this->currIsoCode;
    }

    /**
     * Sets a new currIsoCode
     *
     * Трёхбуквенный код валюты
     *
     * @param string $currIsoCode
     * @return static
     */
    public function setCurrIsoCode($currIsoCode)
    {
        $this->currIsoCode = $currIsoCode;
        return $this;
    }


}


<?php

namespace common\models\sbbolxml\request\ActAddType\GOZActAType;

/**
 * Class representing ActSummAType
 */
class ActSummAType
{

    /**
     * Сумма акта
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
     * Сумма акта
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
     * Сумма акта
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


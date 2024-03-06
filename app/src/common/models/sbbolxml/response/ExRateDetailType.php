<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing ExRateDetailType
 *
 * Значение котировок валют для данного вида курса
 * XSD Type: ExRateDetail
 */
class ExRateDetailType
{

    /**
     * Код ТБ, в котором устанавливается курс
     *
     * @property string $regionId
     */
    private $regionId = null;

    /**
     * Запись о валюте, против которой рассчитывается курс
     *
     * @property \common\models\sbbolxml\response\ExRateDetailType\CcyAType $ccy
     */
    private $ccy = null;

    /**
     * @property \common\models\sbbolxml\response\CurrencyRateType[] $currencyRates
     */
    private $currencyRates = null;

    /**
     * Gets as regionId
     *
     * Код ТБ, в котором устанавливается курс
     *
     * @return string
     */
    public function getRegionId()
    {
        return $this->regionId;
    }

    /**
     * Sets a new regionId
     *
     * Код ТБ, в котором устанавливается курс
     *
     * @param string $regionId
     * @return static
     */
    public function setRegionId($regionId)
    {
        $this->regionId = $regionId;
        return $this;
    }

    /**
     * Gets as ccy
     *
     * Запись о валюте, против которой рассчитывается курс
     *
     * @return \common\models\sbbolxml\response\ExRateDetailType\CcyAType
     */
    public function getCcy()
    {
        return $this->ccy;
    }

    /**
     * Sets a new ccy
     *
     * Запись о валюте, против которой рассчитывается курс
     *
     * @param \common\models\sbbolxml\response\ExRateDetailType\CcyAType $ccy
     * @return static
     */
    public function setCcy(\common\models\sbbolxml\response\ExRateDetailType\CcyAType $ccy)
    {
        $this->ccy = $ccy;
        return $this;
    }

    /**
     * Adds as currencyRate
     *
     * @return static
     * @param \common\models\sbbolxml\response\CurrencyRateType $currencyRate
     */
    public function addToCurrencyRates(\common\models\sbbolxml\response\CurrencyRateType $currencyRate)
    {
        $this->currencyRates[] = $currencyRate;
        return $this;
    }

    /**
     * isset currencyRates
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetCurrencyRates($index)
    {
        return isset($this->currencyRates[$index]);
    }

    /**
     * unset currencyRates
     *
     * @param scalar $index
     * @return void
     */
    public function unsetCurrencyRates($index)
    {
        unset($this->currencyRates[$index]);
    }

    /**
     * Gets as currencyRates
     *
     * @return \common\models\sbbolxml\response\CurrencyRateType[]
     */
    public function getCurrencyRates()
    {
        return $this->currencyRates;
    }

    /**
     * Sets a new currencyRates
     *
     * @param \common\models\sbbolxml\response\CurrencyRateType[] $currencyRates
     * @return static
     */
    public function setCurrencyRates(array $currencyRates)
    {
        $this->currencyRates = $currencyRates;
        return $this;
    }


}


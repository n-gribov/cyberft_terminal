<?php

namespace common\models\sbbolxml\response\ExRateDetailType;

/**
 * Class representing CurrencyRatesAType
 *
 * Значение котировок валют для данного вида курса
 */
class CurrencyRatesAType
{

    /**
     * @property \common\models\sbbolxml\response\CurrencyRateType[] $currencyRate
     */
    private $currencyRate = array(
        
    );

    /**
     * Adds as currencyRate
     *
     * @return static
     * @param \common\models\sbbolxml\response\CurrencyRateType $currencyRate
     */
    public function addToCurrencyRate(\common\models\sbbolxml\response\CurrencyRateType $currencyRate)
    {
        $this->currencyRate[] = $currencyRate;
        return $this;
    }

    /**
     * isset currencyRate
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetCurrencyRate($index)
    {
        return isset($this->currencyRate[$index]);
    }

    /**
     * unset currencyRate
     *
     * @param scalar $index
     * @return void
     */
    public function unsetCurrencyRate($index)
    {
        unset($this->currencyRate[$index]);
    }

    /**
     * Gets as currencyRate
     *
     * @return \common\models\sbbolxml\response\CurrencyRateType[]
     */
    public function getCurrencyRate()
    {
        return $this->currencyRate;
    }

    /**
     * Sets a new currencyRate
     *
     * @param \common\models\sbbolxml\response\CurrencyRateType[] $currencyRate
     * @return static
     */
    public function setCurrencyRate(array $currencyRate)
    {
        $this->currencyRate = $currencyRate;
        return $this;
    }


}


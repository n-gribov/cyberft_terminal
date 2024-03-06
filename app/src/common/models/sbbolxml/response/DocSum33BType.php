<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing DocSum33BType
 *
 *
 * XSD Type: DocSum_33B
 */
class DocSum33BType
{

    /**
     * Валюта и сумма списания
     *
     * @property \common\models\sbbolxml\response\CurrAmountType $docSum
     */
    private $docSum = null;

    /**
     * Сумма и валюта перевода
     *
     * @property \common\models\sbbolxml\response\CurrAmountType $docSumTransfer
     */
    private $docSumTransfer = null;

    /**
     * Кросс-курс
     *
     * @property float $rate
     */
    private $rate = null;

    /**
     * С курсом проведения конверсионной опецраии согласны
     *  1 - чек выставлен
     *  0 - чек снят
     *
     * @property boolean $rateAgree
     */
    private $rateAgree = null;

    /**
     * Признак мультивалютности документа: 1 - мультивалютный
     *  0 - нет
     *
     * @property boolean $multiCurr
     */
    private $multiCurr = null;

    /**
     * Согласие с курсом проведения конверсионной операции: 1 - согл.
     *
     * @property boolean $acceptRate
     */
    private $acceptRate = null;

    /**
     * Валюта платежа отлична от валюты счета бенефициара: 1 - отлична
     *
     * @property boolean $differentCurrencies
     */
    private $differentCurrencies = null;

    /**
     * Gets as docSum
     *
     * Валюта и сумма списания
     *
     * @return \common\models\sbbolxml\response\CurrAmountType
     */
    public function getDocSum()
    {
        return $this->docSum;
    }

    /**
     * Sets a new docSum
     *
     * Валюта и сумма списания
     *
     * @param \common\models\sbbolxml\response\CurrAmountType $docSum
     * @return static
     */
    public function setDocSum(\common\models\sbbolxml\response\CurrAmountType $docSum)
    {
        $this->docSum = $docSum;
        return $this;
    }

    /**
     * Gets as docSumTransfer
     *
     * Сумма и валюта перевода
     *
     * @return \common\models\sbbolxml\response\CurrAmountType
     */
    public function getDocSumTransfer()
    {
        return $this->docSumTransfer;
    }

    /**
     * Sets a new docSumTransfer
     *
     * Сумма и валюта перевода
     *
     * @param \common\models\sbbolxml\response\CurrAmountType $docSumTransfer
     * @return static
     */
    public function setDocSumTransfer(\common\models\sbbolxml\response\CurrAmountType $docSumTransfer)
    {
        $this->docSumTransfer = $docSumTransfer;
        return $this;
    }

    /**
     * Gets as rate
     *
     * Кросс-курс
     *
     * @return float
     */
    public function getRate()
    {
        return $this->rate;
    }

    /**
     * Sets a new rate
     *
     * Кросс-курс
     *
     * @param float $rate
     * @return static
     */
    public function setRate($rate)
    {
        $this->rate = $rate;
        return $this;
    }

    /**
     * Gets as rateAgree
     *
     * С курсом проведения конверсионной опецраии согласны
     *  1 - чек выставлен
     *  0 - чек снят
     *
     * @return boolean
     */
    public function getRateAgree()
    {
        return $this->rateAgree;
    }

    /**
     * Sets a new rateAgree
     *
     * С курсом проведения конверсионной опецраии согласны
     *  1 - чек выставлен
     *  0 - чек снят
     *
     * @param boolean $rateAgree
     * @return static
     */
    public function setRateAgree($rateAgree)
    {
        $this->rateAgree = $rateAgree;
        return $this;
    }

    /**
     * Gets as multiCurr
     *
     * Признак мультивалютности документа: 1 - мультивалютный
     *  0 - нет
     *
     * @return boolean
     */
    public function getMultiCurr()
    {
        return $this->multiCurr;
    }

    /**
     * Sets a new multiCurr
     *
     * Признак мультивалютности документа: 1 - мультивалютный
     *  0 - нет
     *
     * @param boolean $multiCurr
     * @return static
     */
    public function setMultiCurr($multiCurr)
    {
        $this->multiCurr = $multiCurr;
        return $this;
    }

    /**
     * Gets as acceptRate
     *
     * Согласие с курсом проведения конверсионной операции: 1 - согл.
     *
     * @return boolean
     */
    public function getAcceptRate()
    {
        return $this->acceptRate;
    }

    /**
     * Sets a new acceptRate
     *
     * Согласие с курсом проведения конверсионной операции: 1 - согл.
     *
     * @param boolean $acceptRate
     * @return static
     */
    public function setAcceptRate($acceptRate)
    {
        $this->acceptRate = $acceptRate;
        return $this;
    }

    /**
     * Gets as differentCurrencies
     *
     * Валюта платежа отлична от валюты счета бенефициара: 1 - отлична
     *
     * @return boolean
     */
    public function getDifferentCurrencies()
    {
        return $this->differentCurrencies;
    }

    /**
     * Sets a new differentCurrencies
     *
     * Валюта платежа отлична от валюты счета бенефициара: 1 - отлична
     *
     * @param boolean $differentCurrencies
     * @return static
     */
    public function setDifferentCurrencies($differentCurrencies)
    {
        $this->differentCurrencies = $differentCurrencies;
        return $this;
    }


}


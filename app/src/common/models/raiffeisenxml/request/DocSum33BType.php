<?php

namespace common\models\raiffeisenxml\request;

/**
 * Class representing DocSum33BType
 *
 *
 * XSD Type: DocSum_33B
 */
class DocSum33BType
{

    /**
     * Валюта и сумма поручения
     *
     * @property \common\models\raiffeisenxml\request\CurrAmountType $docSum
     */
    private $docSum = null;

    /**
     * Кросс-курс
     *
     * @property float $rate
     */
    private $rate = null;

    /**
     * Gets as docSum
     *
     * Валюта и сумма поручения
     *
     * @return \common\models\raiffeisenxml\request\CurrAmountType
     */
    public function getDocSum()
    {
        return $this->docSum;
    }

    /**
     * Sets a new docSum
     *
     * Валюта и сумма поручения
     *
     * @param \common\models\raiffeisenxml\request\CurrAmountType $docSum
     * @return static
     */
    public function setDocSum(\common\models\raiffeisenxml\request\CurrAmountType $docSum)
    {
        $this->docSum = $docSum;
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


}


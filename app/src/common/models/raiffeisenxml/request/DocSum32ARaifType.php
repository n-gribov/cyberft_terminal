<?php

namespace common\models\raiffeisenxml\request;

/**
 * Class representing DocSum32ARaifType
 *
 *
 * XSD Type: DocSum_32ARaif
 */
class DocSum32ARaifType
{

    /**
     * @property bool $multiCurr
     */
    private $multiCurr = null;

    /**
     * Сумма перевода
     *
     * @property \common\models\raiffeisenxml\request\DocSum32ARaifType\TransSumAType $transSum
     */
    private $transSum = null;

    /**
     * Gets as multiCurr
     *
     * @return bool
     */
    public function getMultiCurr()
    {
        return $this->multiCurr;
    }

    /**
     * Sets a new multiCurr
     *
     * @param bool $multiCurr
     * @return static
     */
    public function setMultiCurr($multiCurr)
    {
        $this->multiCurr = $multiCurr;
        return $this;
    }

    /**
     * Gets as transSum
     *
     * Сумма перевода
     *
     * @return \common\models\raiffeisenxml\request\DocSum32ARaifType\TransSumAType
     */
    public function getTransSum()
    {
        return $this->transSum;
    }

    /**
     * Sets a new transSum
     *
     * Сумма перевода
     *
     * @param \common\models\raiffeisenxml\request\DocSum32ARaifType\TransSumAType $transSum
     * @return static
     */
    public function setTransSum(\common\models\raiffeisenxml\request\DocSum32ARaifType\TransSumAType $transSum)
    {
        $this->transSum = $transSum;
        return $this;
    }


}


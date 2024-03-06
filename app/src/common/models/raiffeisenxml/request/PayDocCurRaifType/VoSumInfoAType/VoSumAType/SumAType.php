<?php

namespace common\models\raiffeisenxml\request\PayDocCurRaifType\VoSumInfoAType\VoSumAType;

/**
 * Class representing SumAType
 */
class SumAType
{

    /**
     * Сумма
     *
     * @property float $sum
     */
    private $sum = null;

    /**
     * Цифровой код валюты
     *
     * @property string $code
     */
    private $code = null;

    /**
     * ISO код
     *
     * @property string $codeISO
     */
    private $codeISO = null;

    /**
     * Gets as sum
     *
     * Сумма
     *
     * @return float
     */
    public function getSum()
    {
        return $this->sum;
    }

    /**
     * Sets a new sum
     *
     * Сумма
     *
     * @param float $sum
     * @return static
     */
    public function setSum($sum)
    {
        $this->sum = $sum;
        return $this;
    }

    /**
     * Gets as code
     *
     * Цифровой код валюты
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Sets a new code
     *
     * Цифровой код валюты
     *
     * @param string $code
     * @return static
     */
    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    /**
     * Gets as codeISO
     *
     * ISO код
     *
     * @return string
     */
    public function getCodeISO()
    {
        return $this->codeISO;
    }

    /**
     * Sets a new codeISO
     *
     * ISO код
     *
     * @param string $codeISO
     * @return static
     */
    public function setCodeISO($codeISO)
    {
        $this->codeISO = $codeISO;
        return $this;
    }


}


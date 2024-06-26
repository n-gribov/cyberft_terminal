<?php

namespace common\models\raiffeisenxml\request\ConfDocCertificateDoc138IRaifType;

/**
 * Class representing ContrSumAType
 */
class ContrSumAType
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


}


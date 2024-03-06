<?php

namespace common\models\sbbolxml\request\MandatorySaleType;

/**
 * Class representing SubtractAType
 */
class SubtractAType
{

    /**
     * Сумма вычета из общей суммы валютной выручки
     *
     * @property \common\models\sbbolxml\request\CurrAmountType $sum
     */
    private $sum = null;

    /**
     * Обоснование вычета
     *
     * @property string $substantiation
     */
    private $substantiation = null;

    /**
     * Gets as sum
     *
     * Сумма вычета из общей суммы валютной выручки
     *
     * @return \common\models\sbbolxml\request\CurrAmountType
     */
    public function getSum()
    {
        return $this->sum;
    }

    /**
     * Sets a new sum
     *
     * Сумма вычета из общей суммы валютной выручки
     *
     * @param \common\models\sbbolxml\request\CurrAmountType $sum
     * @return static
     */
    public function setSum(\common\models\sbbolxml\request\CurrAmountType $sum)
    {
        $this->sum = $sum;
        return $this;
    }

    /**
     * Gets as substantiation
     *
     * Обоснование вычета
     *
     * @return string
     */
    public function getSubstantiation()
    {
        return $this->substantiation;
    }

    /**
     * Sets a new substantiation
     *
     * Обоснование вычета
     *
     * @param string $substantiation
     * @return static
     */
    public function setSubstantiation($substantiation)
    {
        $this->substantiation = $substantiation;
        return $this;
    }


}


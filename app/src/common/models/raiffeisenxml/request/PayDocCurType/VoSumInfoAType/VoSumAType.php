<?php

namespace common\models\raiffeisenxml\request\PayDocCurType\VoSumInfoAType;

/**
 * Class representing VoSumAType
 */
class VoSumAType
{

    /**
     * Код вида валютной операции
     *
     * @property string $vo
     */
    private $vo = null;

    /**
     * Валюта и сумма поручения
     *
     * @property \common\models\raiffeisenxml\request\CurrAmountType $sum
     */
    private $sum = null;

    /**
     * Gets as vo
     *
     * Код вида валютной операции
     *
     * @return string
     */
    public function getVo()
    {
        return $this->vo;
    }

    /**
     * Sets a new vo
     *
     * Код вида валютной операции
     *
     * @param string $vo
     * @return static
     */
    public function setVo($vo)
    {
        $this->vo = $vo;
        return $this;
    }

    /**
     * Gets as sum
     *
     * Валюта и сумма поручения
     *
     * @return \common\models\raiffeisenxml\request\CurrAmountType
     */
    public function getSum()
    {
        return $this->sum;
    }

    /**
     * Sets a new sum
     *
     * Валюта и сумма поручения
     *
     * @param \common\models\raiffeisenxml\request\CurrAmountType $sum
     * @return static
     */
    public function setSum(\common\models\raiffeisenxml\request\CurrAmountType $sum)
    {
        $this->sum = $sum;
        return $this;
    }


}


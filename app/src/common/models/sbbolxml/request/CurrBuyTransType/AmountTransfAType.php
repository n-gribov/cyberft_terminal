<?php

namespace common\models\sbbolxml\request\CurrBuyTransType;

/**
 * Class representing AmountTransfAType
 */
class AmountTransfAType
{

    /**
     * Сумма покупаемой валюты
     *
     * @property \common\models\sbbolxml\request\CurrAmountType $sumBuy
     */
    private $sumBuy = null;

    /**
     * за счет средств
     *
     * @property \common\models\sbbolxml\request\CurrAmountType $sumThrough
     */
    private $sumThrough = null;

    /**
     * Gets as sumBuy
     *
     * Сумма покупаемой валюты
     *
     * @return \common\models\sbbolxml\request\CurrAmountType
     */
    public function getSumBuy()
    {
        return $this->sumBuy;
    }

    /**
     * Sets a new sumBuy
     *
     * Сумма покупаемой валюты
     *
     * @param \common\models\sbbolxml\request\CurrAmountType $sumBuy
     * @return static
     */
    public function setSumBuy(\common\models\sbbolxml\request\CurrAmountType $sumBuy)
    {
        $this->sumBuy = $sumBuy;
        return $this;
    }

    /**
     * Gets as sumThrough
     *
     * за счет средств
     *
     * @return \common\models\sbbolxml\request\CurrAmountType
     */
    public function getSumThrough()
    {
        return $this->sumThrough;
    }

    /**
     * Sets a new sumThrough
     *
     * за счет средств
     *
     * @param \common\models\sbbolxml\request\CurrAmountType $sumThrough
     * @return static
     */
    public function setSumThrough(\common\models\sbbolxml\request\CurrAmountType $sumThrough)
    {
        $this->sumThrough = $sumThrough;
        return $this;
    }


}


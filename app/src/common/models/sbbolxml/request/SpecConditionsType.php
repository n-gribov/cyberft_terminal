<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing SpecConditionsType
 *
 * Особые условия кредитного договора
 * XSD Type: SpecConditions
 */
class SpecConditionsType
{

    /**
     * Сумма, подлежащая зачислению на счета за рубежом
     *
     * @property \common\models\sbbolxml\request\CurrAmountType $sumTransfer
     */
    private $sumTransfer = null;

    /**
     * Сумма, погашение которой предполагается за счет валютной выручки
     *
     * @property \common\models\sbbolxml\request\CurrAmountType $sumCurr
     */
    private $sumCurr = null;

    /**
     * Код срока привлечения
     *
     * @property string $tranche
     */
    private $tranche = null;

    /**
     * Gets as sumTransfer
     *
     * Сумма, подлежащая зачислению на счета за рубежом
     *
     * @return \common\models\sbbolxml\request\CurrAmountType
     */
    public function getSumTransfer()
    {
        return $this->sumTransfer;
    }

    /**
     * Sets a new sumTransfer
     *
     * Сумма, подлежащая зачислению на счета за рубежом
     *
     * @param \common\models\sbbolxml\request\CurrAmountType $sumTransfer
     * @return static
     */
    public function setSumTransfer(\common\models\sbbolxml\request\CurrAmountType $sumTransfer)
    {
        $this->sumTransfer = $sumTransfer;
        return $this;
    }

    /**
     * Gets as sumCurr
     *
     * Сумма, погашение которой предполагается за счет валютной выручки
     *
     * @return \common\models\sbbolxml\request\CurrAmountType
     */
    public function getSumCurr()
    {
        return $this->sumCurr;
    }

    /**
     * Sets a new sumCurr
     *
     * Сумма, погашение которой предполагается за счет валютной выручки
     *
     * @param \common\models\sbbolxml\request\CurrAmountType $sumCurr
     * @return static
     */
    public function setSumCurr(\common\models\sbbolxml\request\CurrAmountType $sumCurr)
    {
        $this->sumCurr = $sumCurr;
        return $this;
    }

    /**
     * Gets as tranche
     *
     * Код срока привлечения
     *
     * @return string
     */
    public function getTranche()
    {
        return $this->tranche;
    }

    /**
     * Sets a new tranche
     *
     * Код срока привлечения
     *
     * @param string $tranche
     * @return static
     */
    public function setTranche($tranche)
    {
        $this->tranche = $tranche;
        return $this;
    }


}


<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing SpecConditionsICSType
 *
 * Особые условия
 * XSD Type: SpecConditionsICS
 */
class SpecConditionsICSType
{

    /**
     * Сумма, подлежащая зачислению на счета за рубежом
     *
     * @property \common\models\sbbolxml\request\SpecConditionsICSType\SumTransferAType $sumTransfer
     */
    private $sumTransfer = null;

    /**
     * Сумма, подлежащая погашению за счет валютной выручки
     *
     * @property \common\models\sbbolxml\request\SpecConditionsICSType\SumCurrAType $sumCurr
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
     * @return \common\models\sbbolxml\request\SpecConditionsICSType\SumTransferAType
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
     * @param \common\models\sbbolxml\request\SpecConditionsICSType\SumTransferAType $sumTransfer
     * @return static
     */
    public function setSumTransfer(\common\models\sbbolxml\request\SpecConditionsICSType\SumTransferAType $sumTransfer)
    {
        $this->sumTransfer = $sumTransfer;
        return $this;
    }

    /**
     * Gets as sumCurr
     *
     * Сумма, подлежащая погашению за счет валютной выручки
     *
     * @return \common\models\sbbolxml\request\SpecConditionsICSType\SumCurrAType
     */
    public function getSumCurr()
    {
        return $this->sumCurr;
    }

    /**
     * Sets a new sumCurr
     *
     * Сумма, подлежащая погашению за счет валютной выручки
     *
     * @param \common\models\sbbolxml\request\SpecConditionsICSType\SumCurrAType $sumCurr
     * @return static
     */
    public function setSumCurr(\common\models\sbbolxml\request\SpecConditionsICSType\SumCurrAType $sumCurr)
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


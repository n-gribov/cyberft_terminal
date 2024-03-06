<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing ComDataType
 *
 * Общие сведения о контракте/кредитном договоре
 * XSD Type: ComData
 */
class ComDataType extends ComDataICSType
{

    /**
     * Валюта цены контракта/кредитного договора
     *
     * @property \common\models\sbbolxml\response\DPCurrType $curr
     */
    private $curr = null;

    /**
     * Gets as curr
     *
     * Валюта цены контракта/кредитного договора
     *
     * @return \common\models\sbbolxml\response\DPCurrType
     */
    public function getCurr()
    {
        return $this->curr;
    }

    /**
     * Sets a new curr
     *
     * Валюта цены контракта/кредитного договора
     *
     * @param \common\models\sbbolxml\response\DPCurrType $curr
     * @return static
     */
    public function setCurr(\common\models\sbbolxml\response\DPCurrType $curr)
    {
        $this->curr = $curr;
        return $this;
    }


}


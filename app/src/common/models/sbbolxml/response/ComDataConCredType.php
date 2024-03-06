<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing ComDataConCredType
 *
 * Общие сведения о кредитном договоре
 * XSD Type: ComDataConCred
 */
class ComDataConCredType extends ComDataICSType
{

    /**
     * Особые условия
     *
     * @property \common\models\sbbolxml\response\SpecConditionsType $specConditions
     */
    private $specConditions = null;

    /**
     * Валюта цены контракта/кредитного договора
     *
     * @property \common\models\sbbolxml\response\CurrVBKType $curr
     */
    private $curr = null;

    /**
     * Gets as specConditions
     *
     * Особые условия
     *
     * @return \common\models\sbbolxml\response\SpecConditionsType
     */
    public function getSpecConditions()
    {
        return $this->specConditions;
    }

    /**
     * Sets a new specConditions
     *
     * Особые условия
     *
     * @param \common\models\sbbolxml\response\SpecConditionsType $specConditions
     * @return static
     */
    public function setSpecConditions(\common\models\sbbolxml\response\SpecConditionsType $specConditions)
    {
        $this->specConditions = $specConditions;
        return $this;
    }

    /**
     * Gets as curr
     *
     * Валюта цены контракта/кредитного договора
     *
     * @return \common\models\sbbolxml\response\CurrVBKType
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
     * @param \common\models\sbbolxml\response\CurrVBKType $curr
     * @return static
     */
    public function setCurr(\common\models\sbbolxml\response\CurrVBKType $curr)
    {
        $this->curr = $curr;
        return $this;
    }


}


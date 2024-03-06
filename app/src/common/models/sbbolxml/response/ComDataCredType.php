<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing ComDataCredType
 *
 * Общие сведения о кредитном договоре
 * XSD Type: ComDataCred
 */
class ComDataCredType extends ComDataType
{

    /**
     * Особые условия
     *
     * @property \common\models\sbbolxml\response\SpecConditionsType $specConditions
     */
    private $specConditions = null;

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


}


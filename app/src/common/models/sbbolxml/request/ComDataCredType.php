<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing ComDataCredType
 *
 * Общие сведения о кредитном договоре
 * XSD Type: ComDataCred
 */
class ComDataCredType extends ComDataType
{

    /**
     * Особые условия кредитного договора
     *
     * @property \common\models\sbbolxml\request\SpecConditionsType $specConditions
     */
    private $specConditions = null;

    /**
     * Gets as specConditions
     *
     * Особые условия кредитного договора
     *
     * @return \common\models\sbbolxml\request\SpecConditionsType
     */
    public function getSpecConditions()
    {
        return $this->specConditions;
    }

    /**
     * Sets a new specConditions
     *
     * Особые условия кредитного договора
     *
     * @param \common\models\sbbolxml\request\SpecConditionsType $specConditions
     * @return static
     */
    public function setSpecConditions(\common\models\sbbolxml\request\SpecConditionsType $specConditions)
    {
        $this->specConditions = $specConditions;
        return $this;
    }


}


<?php

namespace common\models\sbbolxml\response\RZKSalaryDocType\RzkAType;

/**
 * Class representing RzkDocanalyticsAType
 */
class RzkDocanalyticsAType
{

    /**
     * Строка многострочной аналитики платежного поручения
     *
     * @property \common\models\sbbolxml\response\RZKSalaryDocType\RzkAType\RzkDocanalyticsAType\RzkDocanalyticAType[] $rzkDocanalytic
     */
    private $rzkDocanalytic = array(
        
    );

    /**
     * Adds as rzkDocanalytic
     *
     * Строка многострочной аналитики платежного поручения
     *
     * @return static
     * @param \common\models\sbbolxml\response\RZKSalaryDocType\RzkAType\RzkDocanalyticsAType\RzkDocanalyticAType $rzkDocanalytic
     */
    public function addToRzkDocanalytic(\common\models\sbbolxml\response\RZKSalaryDocType\RzkAType\RzkDocanalyticsAType\RzkDocanalyticAType $rzkDocanalytic)
    {
        $this->rzkDocanalytic[] = $rzkDocanalytic;
        return $this;
    }

    /**
     * isset rzkDocanalytic
     *
     * Строка многострочной аналитики платежного поручения
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetRzkDocanalytic($index)
    {
        return isset($this->rzkDocanalytic[$index]);
    }

    /**
     * unset rzkDocanalytic
     *
     * Строка многострочной аналитики платежного поручения
     *
     * @param scalar $index
     * @return void
     */
    public function unsetRzkDocanalytic($index)
    {
        unset($this->rzkDocanalytic[$index]);
    }

    /**
     * Gets as rzkDocanalytic
     *
     * Строка многострочной аналитики платежного поручения
     *
     * @return \common\models\sbbolxml\response\RZKSalaryDocType\RzkAType\RzkDocanalyticsAType\RzkDocanalyticAType[]
     */
    public function getRzkDocanalytic()
    {
        return $this->rzkDocanalytic;
    }

    /**
     * Sets a new rzkDocanalytic
     *
     * Строка многострочной аналитики платежного поручения
     *
     * @param \common\models\sbbolxml\response\RZKSalaryDocType\RzkAType\RzkDocanalyticsAType\RzkDocanalyticAType[] $rzkDocanalytic
     * @return static
     */
    public function setRzkDocanalytic(array $rzkDocanalytic)
    {
        $this->rzkDocanalytic = $rzkDocanalytic;
        return $this;
    }


}


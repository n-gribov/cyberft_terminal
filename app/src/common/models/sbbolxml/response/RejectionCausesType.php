<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing RejectionCausesType
 *
 *
 * XSD Type: RejectionCauses
 */
class RejectionCausesType
{

    /**
     * Причина отказа
     *
     * @property \common\models\sbbolxml\response\CaseType[] $case138I
     */
    private $case138I = array(
        
    );

    /**
     * Причина возврата
     *
     * @property \common\models\sbbolxml\response\CaseType[] $formalCase
     */
    private $formalCase = array(
        
    );

    /**
     * Adds as case138I
     *
     * Причина отказа
     *
     * @return static
     * @param \common\models\sbbolxml\response\CaseType $case138I
     */
    public function addToCase138I(\common\models\sbbolxml\response\CaseType $case138I)
    {
        $this->case138I[] = $case138I;
        return $this;
    }

    /**
     * isset case138I
     *
     * Причина отказа
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetCase138I($index)
    {
        return isset($this->case138I[$index]);
    }

    /**
     * unset case138I
     *
     * Причина отказа
     *
     * @param scalar $index
     * @return void
     */
    public function unsetCase138I($index)
    {
        unset($this->case138I[$index]);
    }

    /**
     * Gets as case138I
     *
     * Причина отказа
     *
     * @return \common\models\sbbolxml\response\CaseType[]
     */
    public function getCase138I()
    {
        return $this->case138I;
    }

    /**
     * Sets a new case138I
     *
     * Причина отказа
     *
     * @param \common\models\sbbolxml\response\CaseType[] $case138I
     * @return static
     */
    public function setCase138I(array $case138I)
    {
        $this->case138I = $case138I;
        return $this;
    }

    /**
     * Adds as formalCase
     *
     * Причина возврата
     *
     * @return static
     * @param \common\models\sbbolxml\response\CaseType $formalCase
     */
    public function addToFormalCase(\common\models\sbbolxml\response\CaseType $formalCase)
    {
        $this->formalCase[] = $formalCase;
        return $this;
    }

    /**
     * isset formalCase
     *
     * Причина возврата
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetFormalCase($index)
    {
        return isset($this->formalCase[$index]);
    }

    /**
     * unset formalCase
     *
     * Причина возврата
     *
     * @param scalar $index
     * @return void
     */
    public function unsetFormalCase($index)
    {
        unset($this->formalCase[$index]);
    }

    /**
     * Gets as formalCase
     *
     * Причина возврата
     *
     * @return \common\models\sbbolxml\response\CaseType[]
     */
    public function getFormalCase()
    {
        return $this->formalCase;
    }

    /**
     * Sets a new formalCase
     *
     * Причина возврата
     *
     * @param \common\models\sbbolxml\response\CaseType[] $formalCase
     * @return static
     */
    public function setFormalCase(array $formalCase)
    {
        $this->formalCase = $formalCase;
        return $this;
    }


}


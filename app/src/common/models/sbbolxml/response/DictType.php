<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing DictType
 *
 *
 * XSD Type: Dict
 */
class DictType
{

    /**
     * Идентификатор справочника
     *
     * @property string $dictId
     */
    private $dictId = null;

    /**
     * Текст ошибки, если что-то не так со справочником
     *
     * @property string $errorMessage
     */
    private $errorMessage = null;

    /**
     * @property \common\models\sbbolxml\response\StepType[] $step
     */
    private $step = array(
        
    );

    /**
     * Gets as dictId
     *
     * Идентификатор справочника
     *
     * @return string
     */
    public function getDictId()
    {
        return $this->dictId;
    }

    /**
     * Sets a new dictId
     *
     * Идентификатор справочника
     *
     * @param string $dictId
     * @return static
     */
    public function setDictId($dictId)
    {
        $this->dictId = $dictId;
        return $this;
    }

    /**
     * Gets as errorMessage
     *
     * Текст ошибки, если что-то не так со справочником
     *
     * @return string
     */
    public function getErrorMessage()
    {
        return $this->errorMessage;
    }

    /**
     * Sets a new errorMessage
     *
     * Текст ошибки, если что-то не так со справочником
     *
     * @param string $errorMessage
     * @return static
     */
    public function setErrorMessage($errorMessage)
    {
        $this->errorMessage = $errorMessage;
        return $this;
    }

    /**
     * Adds as step
     *
     * @return static
     * @param \common\models\sbbolxml\response\StepType $step
     */
    public function addToStep(\common\models\sbbolxml\response\StepType $step)
    {
        $this->step[] = $step;
        return $this;
    }

    /**
     * isset step
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetStep($index)
    {
        return isset($this->step[$index]);
    }

    /**
     * unset step
     *
     * @param scalar $index
     * @return void
     */
    public function unsetStep($index)
    {
        unset($this->step[$index]);
    }

    /**
     * Gets as step
     *
     * @return \common\models\sbbolxml\response\StepType[]
     */
    public function getStep()
    {
        return $this->step;
    }

    /**
     * Sets a new step
     *
     * @param \common\models\sbbolxml\response\StepType[] $step
     * @return static
     */
    public function setStep(array $step)
    {
        $this->step = $step;
        return $this;
    }


}


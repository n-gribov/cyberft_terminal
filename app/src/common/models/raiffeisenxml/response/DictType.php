<?php

namespace common\models\raiffeisenxml\response;

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
     * @property \common\models\raiffeisenxml\response\StepType[] $step
     */
    private $step = [
        
    ];

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
     * Adds as step
     *
     * @return static
     * @param \common\models\raiffeisenxml\response\StepType $step
     */
    public function addToStep(\common\models\raiffeisenxml\response\StepType $step)
    {
        $this->step[] = $step;
        return $this;
    }

    /**
     * isset step
     *
     * @param int|string $index
     * @return bool
     */
    public function issetStep($index)
    {
        return isset($this->step[$index]);
    }

    /**
     * unset step
     *
     * @param int|string $index
     * @return void
     */
    public function unsetStep($index)
    {
        unset($this->step[$index]);
    }

    /**
     * Gets as step
     *
     * @return \common\models\raiffeisenxml\response\StepType[]
     */
    public function getStep()
    {
        return $this->step;
    }

    /**
     * Sets a new step
     *
     * @param \common\models\raiffeisenxml\response\StepType[] $step
     * @return static
     */
    public function setStep(array $step)
    {
        $this->step = $step;
        return $this;
    }


}


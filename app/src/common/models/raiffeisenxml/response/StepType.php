<?php

namespace common\models\raiffeisenxml\response;

/**
 * Class representing StepType
 *
 *
 * XSD Type: Step
 */
class StepType
{

    /**
     * Ссылка на файл с пакетом
     *
     * @property string $postFix
     */
    private $postFix = null;

    /**
     * Иидентификатор обновления справочника
     *
     * @property string $stepId
     */
    private $stepId = null;

    /**
     * Иидентификатор обновления справочника
     *
     * @property int $order
     */
    private $order = null;

    /**
     * @property \common\models\raiffeisenxml\response\DigitalSignType $sign
     */
    private $sign = null;

    /**
     * Gets as postFix
     *
     * Ссылка на файл с пакетом
     *
     * @return string
     */
    public function getPostFix()
    {
        return $this->postFix;
    }

    /**
     * Sets a new postFix
     *
     * Ссылка на файл с пакетом
     *
     * @param string $postFix
     * @return static
     */
    public function setPostFix($postFix)
    {
        $this->postFix = $postFix;
        return $this;
    }

    /**
     * Gets as stepId
     *
     * Иидентификатор обновления справочника
     *
     * @return string
     */
    public function getStepId()
    {
        return $this->stepId;
    }

    /**
     * Sets a new stepId
     *
     * Иидентификатор обновления справочника
     *
     * @param string $stepId
     * @return static
     */
    public function setStepId($stepId)
    {
        $this->stepId = $stepId;
        return $this;
    }

    /**
     * Gets as order
     *
     * Иидентификатор обновления справочника
     *
     * @return int
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Sets a new order
     *
     * Иидентификатор обновления справочника
     *
     * @param int $order
     * @return static
     */
    public function setOrder($order)
    {
        $this->order = $order;
        return $this;
    }

    /**
     * Gets as sign
     *
     * @return \common\models\raiffeisenxml\response\DigitalSignType
     */
    public function getSign()
    {
        return $this->sign;
    }

    /**
     * Sets a new sign
     *
     * @param \common\models\raiffeisenxml\response\DigitalSignType $sign
     * @return static
     */
    public function setSign(\common\models\raiffeisenxml\response\DigitalSignType $sign)
    {
        $this->sign = $sign;
        return $this;
    }


}


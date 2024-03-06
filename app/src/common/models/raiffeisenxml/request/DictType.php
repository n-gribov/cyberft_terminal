<?php

namespace common\models\raiffeisenxml\request;

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
     * Идентификатор последнего обновления справочника или пустой идентификатор, если нужна
     *  полная репликация
     *  справочника
     *
     * @property string $stepId
     */
    private $stepId = null;

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
     * Gets as stepId
     *
     * Идентификатор последнего обновления справочника или пустой идентификатор, если нужна
     *  полная репликация
     *  справочника
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
     * Идентификатор последнего обновления справочника или пустой идентификатор, если нужна
     *  полная репликация
     *  справочника
     *
     * @param string $stepId
     * @return static
     */
    public function setStepId($stepId)
    {
        $this->stepId = $stepId;
        return $this;
    }


}


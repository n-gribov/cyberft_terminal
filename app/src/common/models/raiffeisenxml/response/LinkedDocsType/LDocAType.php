<?php

namespace common\models\raiffeisenxml\response\LinkedDocsType;

/**
 * Class representing LDocAType
 */
class LDocAType
{

    /**
     * Тип связанного документа
     *
     * @property string $type
     */
    private $type = null;

    /**
     * Идентификатор документа в УС
     *
     * @property string $docExtId
     */
    private $docExtId = null;

    /**
     * Дополнительные параметры связанного с поручением документа
     *
     * @property \common\models\raiffeisenxml\response\ParamsType\ParamAType[] $params
     */
    private $params = null;

    /**
     * Gets as type
     *
     * Тип связанного документа
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Sets a new type
     *
     * Тип связанного документа
     *
     * @param string $type
     * @return static
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * Gets as docExtId
     *
     * Идентификатор документа в УС
     *
     * @return string
     */
    public function getDocExtId()
    {
        return $this->docExtId;
    }

    /**
     * Sets a new docExtId
     *
     * Идентификатор документа в УС
     *
     * @param string $docExtId
     * @return static
     */
    public function setDocExtId($docExtId)
    {
        $this->docExtId = $docExtId;
        return $this;
    }

    /**
     * Adds as param
     *
     * Дополнительные параметры связанного с поручением документа
     *
     * @return static
     * @param \common\models\raiffeisenxml\response\ParamsType\ParamAType $param
     */
    public function addToParams(\common\models\raiffeisenxml\response\ParamsType\ParamAType $param)
    {
        $this->params[] = $param;
        return $this;
    }

    /**
     * isset params
     *
     * Дополнительные параметры связанного с поручением документа
     *
     * @param int|string $index
     * @return bool
     */
    public function issetParams($index)
    {
        return isset($this->params[$index]);
    }

    /**
     * unset params
     *
     * Дополнительные параметры связанного с поручением документа
     *
     * @param int|string $index
     * @return void
     */
    public function unsetParams($index)
    {
        unset($this->params[$index]);
    }

    /**
     * Gets as params
     *
     * Дополнительные параметры связанного с поручением документа
     *
     * @return \common\models\raiffeisenxml\response\ParamsType\ParamAType[]
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * Sets a new params
     *
     * Дополнительные параметры связанного с поручением документа
     *
     * @param \common\models\raiffeisenxml\response\ParamsType\ParamAType[] $params
     * @return static
     */
    public function setParams(array $params)
    {
        $this->params = $params;
        return $this;
    }


}


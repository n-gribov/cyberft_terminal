<?php

namespace common\models\sbbolxml\request\RevocationRequestType;

/**
 * Class representing LinkedDocAType
 */
class LinkedDocAType
{

    /**
     * Тип связанного документа
     *
     * @property string $type
     */
    private $type = null;

    /**
     * Идентификатор документа в Системе клиента
     *
     * @property string $docExtId
     */
    private $docExtId = null;

    /**
     * Дополнительные параметры связанного документа
     *
     * @property \common\models\sbbolxml\request\ParamType[] $params
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
     * Идентификатор документа в Системе клиента
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
     * Идентификатор документа в Системе клиента
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
     * Дополнительные параметры связанного документа
     *
     * @return static
     * @param \common\models\sbbolxml\request\ParamType $param
     */
    public function addToParams(\common\models\sbbolxml\request\ParamType $param)
    {
        $this->params[] = $param;
        return $this;
    }

    /**
     * isset params
     *
     * Дополнительные параметры связанного документа
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetParams($index)
    {
        return isset($this->params[$index]);
    }

    /**
     * unset params
     *
     * Дополнительные параметры связанного документа
     *
     * @param scalar $index
     * @return void
     */
    public function unsetParams($index)
    {
        unset($this->params[$index]);
    }

    /**
     * Gets as params
     *
     * Дополнительные параметры связанного документа
     *
     * @return \common\models\sbbolxml\request\ParamType[]
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * Sets a new params
     *
     * Дополнительные параметры связанного документа
     *
     * @param \common\models\sbbolxml\request\ParamType[] $params
     * @return static
     */
    public function setParams(array $params)
    {
        $this->params = $params;
        return $this;
    }


}


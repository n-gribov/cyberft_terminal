<?php

namespace common\models\sbbolxml\response\OrgInfoType\BranchesAType\BranchAType\ParamsAType;

/**
 * Class representing ParamAType
 */
class ParamAType
{

    /**
     * Идентификатор параметра
     *
     * @property string $paramId
     */
    private $paramId = null;

    /**
     * Название параметра
     *
     * @property string $name
     */
    private $name = null;

    /**
     * Значение параметра
     *
     * @property string $value
     */
    private $value = null;

    /**
     * Gets as paramId
     *
     * Идентификатор параметра
     *
     * @return string
     */
    public function getParamId()
    {
        return $this->paramId;
    }

    /**
     * Sets a new paramId
     *
     * Идентификатор параметра
     *
     * @param string $paramId
     * @return static
     */
    public function setParamId($paramId)
    {
        $this->paramId = $paramId;
        return $this;
    }

    /**
     * Gets as name
     *
     * Название параметра
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets a new name
     *
     * Название параметра
     *
     * @param string $name
     * @return static
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Gets as value
     *
     * Значение параметра
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Sets a new value
     *
     * Значение параметра
     *
     * @param string $value
     * @return static
     */
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }


}


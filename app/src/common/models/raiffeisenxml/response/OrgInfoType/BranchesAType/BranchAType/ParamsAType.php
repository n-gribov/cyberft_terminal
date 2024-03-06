<?php

namespace common\models\raiffeisenxml\response\OrgInfoType\BranchesAType\BranchAType;

/**
 * Class representing ParamsAType
 */
class ParamsAType
{

    /**
     * Параметр подразделения банка
     *
     * @property \common\models\raiffeisenxml\response\OrgInfoType\BranchesAType\BranchAType\ParamsAType\ParamAType[] $param
     */
    private $param = [
        
    ];

    /**
     * Adds as param
     *
     * Параметр подразделения банка
     *
     * @return static
     * @param \common\models\raiffeisenxml\response\OrgInfoType\BranchesAType\BranchAType\ParamsAType\ParamAType $param
     */
    public function addToParam(\common\models\raiffeisenxml\response\OrgInfoType\BranchesAType\BranchAType\ParamsAType\ParamAType $param)
    {
        $this->param[] = $param;
        return $this;
    }

    /**
     * isset param
     *
     * Параметр подразделения банка
     *
     * @param int|string $index
     * @return bool
     */
    public function issetParam($index)
    {
        return isset($this->param[$index]);
    }

    /**
     * unset param
     *
     * Параметр подразделения банка
     *
     * @param int|string $index
     * @return void
     */
    public function unsetParam($index)
    {
        unset($this->param[$index]);
    }

    /**
     * Gets as param
     *
     * Параметр подразделения банка
     *
     * @return \common\models\raiffeisenxml\response\OrgInfoType\BranchesAType\BranchAType\ParamsAType\ParamAType[]
     */
    public function getParam()
    {
        return $this->param;
    }

    /**
     * Sets a new param
     *
     * Параметр подразделения банка
     *
     * @param \common\models\raiffeisenxml\response\OrgInfoType\BranchesAType\BranchAType\ParamsAType\ParamAType[] $param
     * @return static
     */
    public function setParam(array $param)
    {
        $this->param = $param;
        return $this;
    }


}


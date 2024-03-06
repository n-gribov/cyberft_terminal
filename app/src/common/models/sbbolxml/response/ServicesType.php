<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing ServicesType
 *
 *
 * XSD Type: Services
 */
class ServicesType
{

    /**
     * Произвольные дополнительные параметры, описывающие продукты и услуги
     *
     * @property \common\models\sbbolxml\response\ParamsType\ParamAType[] $params
     */
    private $params = null;

    /**
     * Adds as param
     *
     * Произвольные дополнительные параметры, описывающие продукты и услуги
     *
     * @return static
     * @param \common\models\sbbolxml\response\ParamsType\ParamAType $param
     */
    public function addToParams(\common\models\sbbolxml\response\ParamsType\ParamAType $param)
    {
        $this->params[] = $param;
        return $this;
    }

    /**
     * isset params
     *
     * Произвольные дополнительные параметры, описывающие продукты и услуги
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
     * Произвольные дополнительные параметры, описывающие продукты и услуги
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
     * Произвольные дополнительные параметры, описывающие продукты и услуги
     *
     * @return \common\models\sbbolxml\response\ParamsType\ParamAType[]
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * Sets a new params
     *
     * Произвольные дополнительные параметры, описывающие продукты и услуги
     *
     * @param \common\models\sbbolxml\response\ParamsType\ParamAType[] $params
     * @return static
     */
    public function setParams(array $params)
    {
        $this->params = $params;
        return $this;
    }


}


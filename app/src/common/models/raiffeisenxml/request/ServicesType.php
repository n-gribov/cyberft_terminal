<?php

namespace common\models\raiffeisenxml\request;

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
     * @property \common\models\raiffeisenxml\request\ParamType[] $params
     */
    private $params = null;

    /**
     * Adds as param
     *
     * Произвольные дополнительные параметры, описывающие продукты и услуги
     *
     * @return static
     * @param \common\models\raiffeisenxml\request\ParamType $param
     */
    public function addToParams(\common\models\raiffeisenxml\request\ParamType $param)
    {
        $this->params[] = $param;
        return $this;
    }

    /**
     * isset params
     *
     * Произвольные дополнительные параметры, описывающие продукты и услуги
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
     * Произвольные дополнительные параметры, описывающие продукты и услуги
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
     * Произвольные дополнительные параметры, описывающие продукты и услуги
     *
     * @return \common\models\raiffeisenxml\request\ParamType[]
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
     * @param \common\models\raiffeisenxml\request\ParamType[] $params
     * @return static
     */
    public function setParams(array $params)
    {
        $this->params = $params;
        return $this;
    }


}


<?php

namespace common\models\sbbolxml\response\AdmCashierType;

/**
 * Class representing DeviceInfoAType
 */
class DeviceInfoAType
{

    /**
     * Устройство самообслуживания на территории Клиента (АДМ)
     *
     * @property boolean $typeADM
     */
    private $typeADM = null;

    /**
     * Устройство самообслуживания
     *
     * @property boolean $typeUS
     */
    private $typeUS = null;

    /**
     * Торговая точка для устройства самообслуживания
     *
     * @property string $tradePointName
     */
    private $tradePointName = null;

    /**
     * Gets as typeADM
     *
     * Устройство самообслуживания на территории Клиента (АДМ)
     *
     * @return boolean
     */
    public function getTypeADM()
    {
        return $this->typeADM;
    }

    /**
     * Sets a new typeADM
     *
     * Устройство самообслуживания на территории Клиента (АДМ)
     *
     * @param boolean $typeADM
     * @return static
     */
    public function setTypeADM($typeADM)
    {
        $this->typeADM = $typeADM;
        return $this;
    }

    /**
     * Gets as typeUS
     *
     * Устройство самообслуживания
     *
     * @return boolean
     */
    public function getTypeUS()
    {
        return $this->typeUS;
    }

    /**
     * Sets a new typeUS
     *
     * Устройство самообслуживания
     *
     * @param boolean $typeUS
     * @return static
     */
    public function setTypeUS($typeUS)
    {
        $this->typeUS = $typeUS;
        return $this;
    }

    /**
     * Gets as tradePointName
     *
     * Торговая точка для устройства самообслуживания
     *
     * @return string
     */
    public function getTradePointName()
    {
        return $this->tradePointName;
    }

    /**
     * Sets a new tradePointName
     *
     * Торговая точка для устройства самообслуживания
     *
     * @param string $tradePointName
     * @return static
     */
    public function setTradePointName($tradePointName)
    {
        $this->tradePointName = $tradePointName;
        return $this;
    }


}


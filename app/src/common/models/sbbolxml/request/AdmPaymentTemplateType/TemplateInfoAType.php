<?php

namespace common\models\sbbolxml\request\AdmPaymentTemplateType;

/**
 * Class representing TemplateInfoAType
 */
class TemplateInfoAType
{

    /**
     * Устройства самообслуживания: ADM - "Устройства самообслуживания на территории Клиента (АДМ)", US - "Устройство самообслуживания (УС)"
     *
     * @property string $deviceType
     */
    private $deviceType = null;

    /**
     * Наименование шаблона
     *
     * @property string $name
     */
    private $name = null;

    /**
     * Gets as deviceType
     *
     * Устройства самообслуживания: ADM - "Устройства самообслуживания на территории Клиента (АДМ)", US - "Устройство самообслуживания (УС)"
     *
     * @return string
     */
    public function getDeviceType()
    {
        return $this->deviceType;
    }

    /**
     * Sets a new deviceType
     *
     * Устройства самообслуживания: ADM - "Устройства самообслуживания на территории Клиента (АДМ)", US - "Устройство самообслуживания (УС)"
     *
     * @param string $deviceType
     * @return static
     */
    public function setDeviceType($deviceType)
    {
        $this->deviceType = $deviceType;
        return $this;
    }

    /**
     * Gets as name
     *
     * Наименование шаблона
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
     * Наименование шаблона
     *
     * @param string $name
     * @return static
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }


}


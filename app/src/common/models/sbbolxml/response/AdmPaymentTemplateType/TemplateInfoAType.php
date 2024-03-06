<?php

namespace common\models\sbbolxml\response\AdmPaymentTemplateType;

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
     * Блокировка. 1 - признак установлен, 0 - признак не установлен
     *
     * @property boolean $blocked
     */
    private $blocked = null;

    /**
     * Наличие подписи у шаблона. 1 - подписан, 0 - не подписан
     *
     * @property boolean $signed
     */
    private $signed = null;

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

    /**
     * Gets as blocked
     *
     * Блокировка. 1 - признак установлен, 0 - признак не установлен
     *
     * @return boolean
     */
    public function getBlocked()
    {
        return $this->blocked;
    }

    /**
     * Sets a new blocked
     *
     * Блокировка. 1 - признак установлен, 0 - признак не установлен
     *
     * @param boolean $blocked
     * @return static
     */
    public function setBlocked($blocked)
    {
        $this->blocked = $blocked;
        return $this;
    }

    /**
     * Gets as signed
     *
     * Наличие подписи у шаблона. 1 - подписан, 0 - не подписан
     *
     * @return boolean
     */
    public function getSigned()
    {
        return $this->signed;
    }

    /**
     * Sets a new signed
     *
     * Наличие подписи у шаблона. 1 - подписан, 0 - не подписан
     *
     * @param boolean $signed
     * @return static
     */
    public function setSigned($signed)
    {
        $this->signed = $signed;
        return $this;
    }


}


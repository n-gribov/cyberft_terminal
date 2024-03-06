<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing AuthPersICSRestructType
 *
 *
 * XSD Type: AuthPersICSRestruct
 */
class AuthPersICSRestructType
{

    /**
     * Имя уполномоченного сотрудника организации клиента
     *
     * @property string $name
     */
    private $name = null;

    /**
     * Номер телефона, факса уполномоченного сотрудника организации клиента
     *
     * @property string $telfax
     */
    private $telfax = null;

    /**
     * Должность
     *
     * @property string $position
     */
    private $position = null;

    /**
     * Gets as name
     *
     * Имя уполномоченного сотрудника организации клиента
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
     * Имя уполномоченного сотрудника организации клиента
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
     * Gets as telfax
     *
     * Номер телефона, факса уполномоченного сотрудника организации клиента
     *
     * @return string
     */
    public function getTelfax()
    {
        return $this->telfax;
    }

    /**
     * Sets a new telfax
     *
     * Номер телефона, факса уполномоченного сотрудника организации клиента
     *
     * @param string $telfax
     * @return static
     */
    public function setTelfax($telfax)
    {
        $this->telfax = $telfax;
        return $this;
    }

    /**
     * Gets as position
     *
     * Должность
     *
     * @return string
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Sets a new position
     *
     * Должность
     *
     * @param string $position
     * @return static
     */
    public function setPosition($position)
    {
        $this->position = $position;
        return $this;
    }


}


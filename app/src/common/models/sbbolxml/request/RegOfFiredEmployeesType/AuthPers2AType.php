<?php

namespace common\models\sbbolxml\request\RegOfFiredEmployeesType;

/**
 * Class representing AuthPers2AType
 */
class AuthPers2AType
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


}


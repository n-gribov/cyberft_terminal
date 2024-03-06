<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing AuthPersICSType
 *
 * Данные ответственного исполнителя
 * XSD Type: AuthPersICS
 */
class AuthPersICSType
{

    /**
     * ФИО
     *
     * @property string $name
     */
    private $name = null;

    /**
     * Номер телефона, факса
     *
     * @property string $phone
     */
    private $phone = null;

    /**
     * Должность (Подпись)
     *
     * @property string $position
     */
    private $position = null;

    /**
     * Gets as name
     *
     * ФИО
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
     * ФИО
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
     * Gets as phone
     *
     * Номер телефона, факса
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Sets a new phone
     *
     * Номер телефона, факса
     *
     * @param string $phone
     * @return static
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
        return $this;
    }

    /**
     * Gets as position
     *
     * Должность (Подпись)
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
     * Должность (Подпись)
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


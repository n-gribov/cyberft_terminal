<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing AuthorisedCapitalInfoType
 *
 * Сведения о величине уставного (складочного) капитала или величине уставного фонда, имущества
 * XSD Type: AuthorisedCapitalInfo
 */
class AuthorisedCapitalInfoType
{

    /**
     * Зарегистрировано
     *
     * @property string $registered
     */
    private $registered = null;

    /**
     * Оплачено
     *
     * @property string $paid
     */
    private $paid = null;

    /**
     * Gets as registered
     *
     * Зарегистрировано
     *
     * @return string
     */
    public function getRegistered()
    {
        return $this->registered;
    }

    /**
     * Sets a new registered
     *
     * Зарегистрировано
     *
     * @param string $registered
     * @return static
     */
    public function setRegistered($registered)
    {
        $this->registered = $registered;
        return $this;
    }

    /**
     * Gets as paid
     *
     * Оплачено
     *
     * @return string
     */
    public function getPaid()
    {
        return $this->paid;
    }

    /**
     * Sets a new paid
     *
     * Оплачено
     *
     * @param string $paid
     * @return static
     */
    public function setPaid($paid)
    {
        $this->paid = $paid;
        return $this;
    }


}


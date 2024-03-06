<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing RealAdressType
 *
 * Адрес местонахождения
 * XSD Type: RealAdress
 */
class RealAdressType
{

    /**
     * Настоящим подтверждаем присутствие постоянно действующего органа управления/иного органа или лица, которые имеют
     *  право действовать от его имени без доверенности, по указанному выше адресу местонахождения
     *
     * @property boolean $presenceConfirmation
     */
    private $presenceConfirmation = null;

    /**
     * @property string $address
     */
    private $address = null;

    /**
     * Gets as presenceConfirmation
     *
     * Настоящим подтверждаем присутствие постоянно действующего органа управления/иного органа или лица, которые имеют
     *  право действовать от его имени без доверенности, по указанному выше адресу местонахождения
     *
     * @return boolean
     */
    public function getPresenceConfirmation()
    {
        return $this->presenceConfirmation;
    }

    /**
     * Sets a new presenceConfirmation
     *
     * Настоящим подтверждаем присутствие постоянно действующего органа управления/иного органа или лица, которые имеют
     *  право действовать от его имени без доверенности, по указанному выше адресу местонахождения
     *
     * @param boolean $presenceConfirmation
     * @return static
     */
    public function setPresenceConfirmation($presenceConfirmation)
    {
        $this->presenceConfirmation = $presenceConfirmation;
        return $this;
    }

    /**
     * Gets as address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Sets a new address
     *
     * @param string $address
     * @return static
     */
    public function setAddress($address)
    {
        $this->address = $address;
        return $this;
    }


}


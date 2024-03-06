<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing AddressesIPType
 *
 * Адрес места жительства (регистрации) или места пребывания
 * XSD Type: AddressesIP
 */
class AddressesIPType
{

    /**
     * Адрес места жительства
     *
     * @property string $residentialAddress
     */
    private $residentialAddress = null;

    /**
     * Адрес места пребывания
     *
     * @property string $stayAddress
     */
    private $stayAddress = null;

    /**
     * Gets as residentialAddress
     *
     * Адрес места жительства
     *
     * @return string
     */
    public function getResidentialAddress()
    {
        return $this->residentialAddress;
    }

    /**
     * Sets a new residentialAddress
     *
     * Адрес места жительства
     *
     * @param string $residentialAddress
     * @return static
     */
    public function setResidentialAddress($residentialAddress)
    {
        $this->residentialAddress = $residentialAddress;
        return $this;
    }

    /**
     * Gets as stayAddress
     *
     * Адрес места пребывания
     *
     * @return string
     */
    public function getStayAddress()
    {
        return $this->stayAddress;
    }

    /**
     * Sets a new stayAddress
     *
     * Адрес места пребывания
     *
     * @param string $stayAddress
     * @return static
     */
    public function setStayAddress($stayAddress)
    {
        $this->stayAddress = $stayAddress;
        return $this;
    }


}


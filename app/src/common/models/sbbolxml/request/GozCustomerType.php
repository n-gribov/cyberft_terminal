<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing GozCustomerType
 *
 * Заказчик
 * XSD Type: GozCustomer
 */
class GozCustomerType
{

    /**
     * ИНН заказчика
     *
     * @property string $custINN
     */
    private $custINN = null;

    /**
     * КПП заказчика
     *
     * @property string $custKPP
     */
    private $custKPP = null;

    /**
     * № отдельного счета заказчика
     *
     * @property string $custAcc
     */
    private $custAcc = null;

    /**
     * Наименование заказчика
     *
     * @property string $custName
     */
    private $custName = null;

    /**
     * 1 - резидент, 0 - нерезидент
     *
     * @property boolean $resident
     */
    private $resident = null;

    /**
     * Gets as custINN
     *
     * ИНН заказчика
     *
     * @return string
     */
    public function getCustINN()
    {
        return $this->custINN;
    }

    /**
     * Sets a new custINN
     *
     * ИНН заказчика
     *
     * @param string $custINN
     * @return static
     */
    public function setCustINN($custINN)
    {
        $this->custINN = $custINN;
        return $this;
    }

    /**
     * Gets as custKPP
     *
     * КПП заказчика
     *
     * @return string
     */
    public function getCustKPP()
    {
        return $this->custKPP;
    }

    /**
     * Sets a new custKPP
     *
     * КПП заказчика
     *
     * @param string $custKPP
     * @return static
     */
    public function setCustKPP($custKPP)
    {
        $this->custKPP = $custKPP;
        return $this;
    }

    /**
     * Gets as custAcc
     *
     * № отдельного счета заказчика
     *
     * @return string
     */
    public function getCustAcc()
    {
        return $this->custAcc;
    }

    /**
     * Sets a new custAcc
     *
     * № отдельного счета заказчика
     *
     * @param string $custAcc
     * @return static
     */
    public function setCustAcc($custAcc)
    {
        $this->custAcc = $custAcc;
        return $this;
    }

    /**
     * Gets as custName
     *
     * Наименование заказчика
     *
     * @return string
     */
    public function getCustName()
    {
        return $this->custName;
    }

    /**
     * Sets a new custName
     *
     * Наименование заказчика
     *
     * @param string $custName
     * @return static
     */
    public function setCustName($custName)
    {
        $this->custName = $custName;
        return $this;
    }

    /**
     * Gets as resident
     *
     * 1 - резидент, 0 - нерезидент
     *
     * @return boolean
     */
    public function getResident()
    {
        return $this->resident;
    }

    /**
     * Sets a new resident
     *
     * 1 - резидент, 0 - нерезидент
     *
     * @param boolean $resident
     * @return static
     */
    public function setResident($resident)
    {
        $this->resident = $resident;
        return $this;
    }


}


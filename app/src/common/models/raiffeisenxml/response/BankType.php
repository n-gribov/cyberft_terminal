<?php

namespace common\models\raiffeisenxml\response;

/**
 * Class representing BankType
 *
 *
 * XSD Type: Bank
 */
class BankType
{

    /**
     * БИК
     *
     * @property string $bic
     */
    private $bic = null;

    /**
     * Номер корр. счёта банка
     *
     * @property string $correspAcc
     */
    private $correspAcc = null;

    /**
     * Наименование банка
     *
     * @property string $name
     */
    private $name = null;

    /**
     * Название населённого пункта банка
     *
     * @property string $bankCity
     */
    private $bankCity = null;

    /**
     * Тип населённого пункта банка
     *
     * @property string $settlementType
     */
    private $settlementType = null;

    /**
     * Gets as bic
     *
     * БИК
     *
     * @return string
     */
    public function getBic()
    {
        return $this->bic;
    }

    /**
     * Sets a new bic
     *
     * БИК
     *
     * @param string $bic
     * @return static
     */
    public function setBic($bic)
    {
        $this->bic = $bic;
        return $this;
    }

    /**
     * Gets as correspAcc
     *
     * Номер корр. счёта банка
     *
     * @return string
     */
    public function getCorrespAcc()
    {
        return $this->correspAcc;
    }

    /**
     * Sets a new correspAcc
     *
     * Номер корр. счёта банка
     *
     * @param string $correspAcc
     * @return static
     */
    public function setCorrespAcc($correspAcc)
    {
        $this->correspAcc = $correspAcc;
        return $this;
    }

    /**
     * Gets as name
     *
     * Наименование банка
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
     * Наименование банка
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
     * Gets as bankCity
     *
     * Название населённого пункта банка
     *
     * @return string
     */
    public function getBankCity()
    {
        return $this->bankCity;
    }

    /**
     * Sets a new bankCity
     *
     * Название населённого пункта банка
     *
     * @param string $bankCity
     * @return static
     */
    public function setBankCity($bankCity)
    {
        $this->bankCity = $bankCity;
        return $this;
    }

    /**
     * Gets as settlementType
     *
     * Тип населённого пункта банка
     *
     * @return string
     */
    public function getSettlementType()
    {
        return $this->settlementType;
    }

    /**
     * Sets a new settlementType
     *
     * Тип населённого пункта банка
     *
     * @param string $settlementType
     * @return static
     */
    public function setSettlementType($settlementType)
    {
        $this->settlementType = $settlementType;
        return $this;
    }


}


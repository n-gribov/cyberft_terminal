<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing RecBankTicketType
 *
 *
 * XSD Type: RecBankTicket
 */
class RecBankTicketType
{

    /**
     * @property string $bic
     */
    private $bic = null;

    /**
     * @property string $correspAcc
     */
    private $correspAcc = null;

    /**
     * наименование банка
     *
     * @property string $name
     */
    private $name = null;

    /**
     * Наименование города
     *
     * @property string $bankCity
     */
    private $bankCity = null;

    /**
     * Тип населенного пункта банка
     *
     * @property string $settlementType
     */
    private $settlementType = null;

    /**
     * Gets as bic
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
     * @return string
     */
    public function getCorrespAcc()
    {
        return $this->correspAcc;
    }

    /**
     * Sets a new correspAcc
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
     * наименование банка
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
     * наименование банка
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
     * Наименование города
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
     * Наименование города
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
     * Тип населенного пункта банка
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
     * Тип населенного пункта банка
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


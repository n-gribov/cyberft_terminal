<?php

namespace common\models\raiffeisenxml\response\AccountRubType;

/**
 * Class representing BankAType
 */
class BankAType
{

    /**
     * БИК банка зачисления рублей
     *
     * @property string $bic
     */
    private $bic = null;

    /**
     * Коррсчёт банка зачисления рублей
     *
     * @property string $correspAcc
     */
    private $correspAcc = null;

    /**
     * Наименование банка зачисления рублей (либо в соответствии с BNKSEEK,
     *  либо наименование отделения
     *  СБРФ)
     *
     * @property string $name
     */
    private $name = null;

    /**
     * Населённый пункт банка зачисления
     *
     * @property string $bankCity
     */
    private $bankCity = null;

    /**
     * Тип населённого пункта банка зачисления
     *
     * @property string $settlementType
     */
    private $settlementType = null;

    /**
     * Gets as bic
     *
     * БИК банка зачисления рублей
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
     * БИК банка зачисления рублей
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
     * Коррсчёт банка зачисления рублей
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
     * Коррсчёт банка зачисления рублей
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
     * Наименование банка зачисления рублей (либо в соответствии с BNKSEEK,
     *  либо наименование отделения
     *  СБРФ)
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
     * Наименование банка зачисления рублей (либо в соответствии с BNKSEEK,
     *  либо наименование отделения
     *  СБРФ)
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
     * Населённый пункт банка зачисления
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
     * Населённый пункт банка зачисления
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
     * Тип населённого пункта банка зачисления
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
     * Тип населённого пункта банка зачисления
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


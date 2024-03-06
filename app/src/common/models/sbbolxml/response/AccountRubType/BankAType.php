<?php

namespace common\models\sbbolxml\response\AccountRubType;

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
     * OBSNUM
     *
     * @property string $obsnum
     */
    private $obsnum = null;

    /**
     * Наименование банка (наименование отделения СБРФ)
     *
     * @property string $name
     */
    private $name = null;

    /**
     * Наименование банка по БИК РФ
     *
     * @property string $bankName
     */
    private $bankName = null;

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
     * Gets as obsnum
     *
     * OBSNUM
     *
     * @return string
     */
    public function getObsnum()
    {
        return $this->obsnum;
    }

    /**
     * Sets a new obsnum
     *
     * OBSNUM
     *
     * @param string $obsnum
     * @return static
     */
    public function setObsnum($obsnum)
    {
        $this->obsnum = $obsnum;
        return $this;
    }

    /**
     * Gets as name
     *
     * Наименование банка (наименование отделения СБРФ)
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
     * Наименование банка (наименование отделения СБРФ)
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
     * Gets as bankName
     *
     * Наименование банка по БИК РФ
     *
     * @return string
     */
    public function getBankName()
    {
        return $this->bankName;
    }

    /**
     * Sets a new bankName
     *
     * Наименование банка по БИК РФ
     *
     * @param string $bankName
     * @return static
     */
    public function setBankName($bankName)
    {
        $this->bankName = $bankName;
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


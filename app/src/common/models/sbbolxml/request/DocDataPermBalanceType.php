<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing DocDataPermBalanceType
 *
 * Реквизиты документа Заявление о присоединении к условиям размещения денежных средств в виде НСО
 * XSD Type: DocDataPermBalance
 */
class DocDataPermBalanceType extends ComDocData2Type
{

    /**
     * Системное наименование подразделения банка
     *
     * @property string $branchSystemName
     */
    private $branchSystemName = null;

    /**
     * Идентификатор карточки договора депозита. Заполняется при импорте договора, сформированного по заявлению
     *
     * @property string $actualCard
     */
    private $actualCard = null;

    /**
     * Номер договора из карточки депозита. Заполняется при импорте договора, сформированного по заявлению
     *
     * @property string $contractNum
     */
    private $contractNum = null;

    /**
     * Дата договора из карточки депозита. Заполняется при импорте договора, сформированного по заявлению
     *
     * @property \DateTime $contractDate
     */
    private $contractDate = null;

    /**
     * Признак подтверждения согласия с условиями
     *
     * @property boolean $confirming
     */
    private $confirming = null;

    /**
     * Gets as branchSystemName
     *
     * Системное наименование подразделения банка
     *
     * @return string
     */
    public function getBranchSystemName()
    {
        return $this->branchSystemName;
    }

    /**
     * Sets a new branchSystemName
     *
     * Системное наименование подразделения банка
     *
     * @param string $branchSystemName
     * @return static
     */
    public function setBranchSystemName($branchSystemName)
    {
        $this->branchSystemName = $branchSystemName;
        return $this;
    }

    /**
     * Gets as actualCard
     *
     * Идентификатор карточки договора депозита. Заполняется при импорте договора, сформированного по заявлению
     *
     * @return string
     */
    public function getActualCard()
    {
        return $this->actualCard;
    }

    /**
     * Sets a new actualCard
     *
     * Идентификатор карточки договора депозита. Заполняется при импорте договора, сформированного по заявлению
     *
     * @param string $actualCard
     * @return static
     */
    public function setActualCard($actualCard)
    {
        $this->actualCard = $actualCard;
        return $this;
    }

    /**
     * Gets as contractNum
     *
     * Номер договора из карточки депозита. Заполняется при импорте договора, сформированного по заявлению
     *
     * @return string
     */
    public function getContractNum()
    {
        return $this->contractNum;
    }

    /**
     * Sets a new contractNum
     *
     * Номер договора из карточки депозита. Заполняется при импорте договора, сформированного по заявлению
     *
     * @param string $contractNum
     * @return static
     */
    public function setContractNum($contractNum)
    {
        $this->contractNum = $contractNum;
        return $this;
    }

    /**
     * Gets as contractDate
     *
     * Дата договора из карточки депозита. Заполняется при импорте договора, сформированного по заявлению
     *
     * @return \DateTime
     */
    public function getContractDate()
    {
        return $this->contractDate;
    }

    /**
     * Sets a new contractDate
     *
     * Дата договора из карточки депозита. Заполняется при импорте договора, сформированного по заявлению
     *
     * @param \DateTime $contractDate
     * @return static
     */
    public function setContractDate(\DateTime $contractDate)
    {
        $this->contractDate = $contractDate;
        return $this;
    }

    /**
     * Gets as confirming
     *
     * Признак подтверждения согласия с условиями
     *
     * @return boolean
     */
    public function getConfirming()
    {
        return $this->confirming;
    }

    /**
     * Sets a new confirming
     *
     * Признак подтверждения согласия с условиями
     *
     * @param boolean $confirming
     * @return static
     */
    public function setConfirming($confirming)
    {
        $this->confirming = $confirming;
        return $this;
    }


}


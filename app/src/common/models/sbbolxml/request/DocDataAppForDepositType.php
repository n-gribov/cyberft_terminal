<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing DocDataAppForDepositType
 *
 * Реквизиты документа Заявление на пролонгацию вклада (депозита)
 * XSD Type: DocDataAppForDeposit
 */
class DocDataAppForDepositType extends ComDocData2Type
{

    /**
     * Системное наименование подразделения банка
     *
     * @property string $bankSystemName
     */
    private $bankSystemName = null;

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
     * Признак подтверждения согласия с условиями.
     *
     * @property boolean $confirming
     */
    private $confirming = null;

    /**
     * Способ перечисления денежных средств. Возможные значения: bank – клиент дает поручение банку на списание средств, client – клиент перечисляет средства самостоятельно.
     *
     * @property string $transferType
     */
    private $transferType = null;

    /**
     * Признак пролонгации, для заявления на пролонгацию . (для заявления на открытие не заполняется)
     *
     * @property boolean $prolongation
     */
    private $prolongation = null;

    /**
     * Номер договора из карточки депозита, для заявления на пролонгацию (для заявления на открытие не заполняется)
     *
     * @property string $prolongationNum
     */
    private $prolongationNum = null;

    /**
     * Дата договора из карточки депозита, для заявления на пролонгацию (для заявления на открытие не заполняется)
     *
     * @property \DateTime $prolongationDate
     */
    private $prolongationDate = null;

    /**
     * Gets as bankSystemName
     *
     * Системное наименование подразделения банка
     *
     * @return string
     */
    public function getBankSystemName()
    {
        return $this->bankSystemName;
    }

    /**
     * Sets a new bankSystemName
     *
     * Системное наименование подразделения банка
     *
     * @param string $bankSystemName
     * @return static
     */
    public function setBankSystemName($bankSystemName)
    {
        $this->bankSystemName = $bankSystemName;
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
     * Признак подтверждения согласия с условиями.
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
     * Признак подтверждения согласия с условиями.
     *
     * @param boolean $confirming
     * @return static
     */
    public function setConfirming($confirming)
    {
        $this->confirming = $confirming;
        return $this;
    }

    /**
     * Gets as transferType
     *
     * Способ перечисления денежных средств. Возможные значения: bank – клиент дает поручение банку на списание средств, client – клиент перечисляет средства самостоятельно.
     *
     * @return string
     */
    public function getTransferType()
    {
        return $this->transferType;
    }

    /**
     * Sets a new transferType
     *
     * Способ перечисления денежных средств. Возможные значения: bank – клиент дает поручение банку на списание средств, client – клиент перечисляет средства самостоятельно.
     *
     * @param string $transferType
     * @return static
     */
    public function setTransferType($transferType)
    {
        $this->transferType = $transferType;
        return $this;
    }

    /**
     * Gets as prolongation
     *
     * Признак пролонгации, для заявления на пролонгацию . (для заявления на открытие не заполняется)
     *
     * @return boolean
     */
    public function getProlongation()
    {
        return $this->prolongation;
    }

    /**
     * Sets a new prolongation
     *
     * Признак пролонгации, для заявления на пролонгацию . (для заявления на открытие не заполняется)
     *
     * @param boolean $prolongation
     * @return static
     */
    public function setProlongation($prolongation)
    {
        $this->prolongation = $prolongation;
        return $this;
    }

    /**
     * Gets as prolongationNum
     *
     * Номер договора из карточки депозита, для заявления на пролонгацию (для заявления на открытие не заполняется)
     *
     * @return string
     */
    public function getProlongationNum()
    {
        return $this->prolongationNum;
    }

    /**
     * Sets a new prolongationNum
     *
     * Номер договора из карточки депозита, для заявления на пролонгацию (для заявления на открытие не заполняется)
     *
     * @param string $prolongationNum
     * @return static
     */
    public function setProlongationNum($prolongationNum)
    {
        $this->prolongationNum = $prolongationNum;
        return $this;
    }

    /**
     * Gets as prolongationDate
     *
     * Дата договора из карточки депозита, для заявления на пролонгацию (для заявления на открытие не заполняется)
     *
     * @return \DateTime
     */
    public function getProlongationDate()
    {
        return $this->prolongationDate;
    }

    /**
     * Sets a new prolongationDate
     *
     * Дата договора из карточки депозита, для заявления на пролонгацию (для заявления на открытие не заполняется)
     *
     * @param \DateTime $prolongationDate
     * @return static
     */
    public function setProlongationDate(\DateTime $prolongationDate)
    {
        $this->prolongationDate = $prolongationDate;
        return $this;
    }


}


<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing DocDataChangeAccDetailsType
 *
 * Общие реквизиты заявления на изменение реквизитов расчетного счета
 * XSD Type: DocDataChangeAccDetails
 */
class DocDataChangeAccDetailsType
{

    /**
     * Номер документа
     *
     * @property string $docNum
     */
    private $docNum = null;

    /**
     * Дата составления документа
     *
     * @property \DateTime $docDate
     */
    private $docDate = null;

    /**
     * Системное наименование подразделения банка
     *
     * @property string $branchSystemName
     */
    private $branchSystemName = null;

    /**
     * Идентификатор договора депозита
     *
     * @property string $initialCard
     */
    private $initialCard = null;

    /**
     * Договор №, из карточки депозита
     *
     * @property string $contractNum
     */
    private $contractNum = null;

    /**
     * Дата договора (от), из карточки депозита
     *
     * @property \DateTime $contractDate
     */
    private $contractDate = null;

    /**
     * Номер депозитного счета
     *
     * @property string $depositAccNum
     */
    private $depositAccNum = null;

    /**
     * Тип карточки:
     *  AppForDepositNew - для заявлений на изменение реквизитов р/с возврата по депозиту
     *  PermBalanceNew - для заявлений на изменение реквизитов р/с возврата по НСО
     *
     * @property string $docToChangeType
     */
    private $docToChangeType = null;

    /**
     * @property \common\models\sbbolxml\request\OrgDataType $orgData
     */
    private $orgData = null;

    /**
     * Gets as docNum
     *
     * Номер документа
     *
     * @return string
     */
    public function getDocNum()
    {
        return $this->docNum;
    }

    /**
     * Sets a new docNum
     *
     * Номер документа
     *
     * @param string $docNum
     * @return static
     */
    public function setDocNum($docNum)
    {
        $this->docNum = $docNum;
        return $this;
    }

    /**
     * Gets as docDate
     *
     * Дата составления документа
     *
     * @return \DateTime
     */
    public function getDocDate()
    {
        return $this->docDate;
    }

    /**
     * Sets a new docDate
     *
     * Дата составления документа
     *
     * @param \DateTime $docDate
     * @return static
     */
    public function setDocDate(\DateTime $docDate)
    {
        $this->docDate = $docDate;
        return $this;
    }

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
     * Gets as initialCard
     *
     * Идентификатор договора депозита
     *
     * @return string
     */
    public function getInitialCard()
    {
        return $this->initialCard;
    }

    /**
     * Sets a new initialCard
     *
     * Идентификатор договора депозита
     *
     * @param string $initialCard
     * @return static
     */
    public function setInitialCard($initialCard)
    {
        $this->initialCard = $initialCard;
        return $this;
    }

    /**
     * Gets as contractNum
     *
     * Договор №, из карточки депозита
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
     * Договор №, из карточки депозита
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
     * Дата договора (от), из карточки депозита
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
     * Дата договора (от), из карточки депозита
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
     * Gets as depositAccNum
     *
     * Номер депозитного счета
     *
     * @return string
     */
    public function getDepositAccNum()
    {
        return $this->depositAccNum;
    }

    /**
     * Sets a new depositAccNum
     *
     * Номер депозитного счета
     *
     * @param string $depositAccNum
     * @return static
     */
    public function setDepositAccNum($depositAccNum)
    {
        $this->depositAccNum = $depositAccNum;
        return $this;
    }

    /**
     * Gets as docToChangeType
     *
     * Тип карточки:
     *  AppForDepositNew - для заявлений на изменение реквизитов р/с возврата по депозиту
     *  PermBalanceNew - для заявлений на изменение реквизитов р/с возврата по НСО
     *
     * @return string
     */
    public function getDocToChangeType()
    {
        return $this->docToChangeType;
    }

    /**
     * Sets a new docToChangeType
     *
     * Тип карточки:
     *  AppForDepositNew - для заявлений на изменение реквизитов р/с возврата по депозиту
     *  PermBalanceNew - для заявлений на изменение реквизитов р/с возврата по НСО
     *
     * @param string $docToChangeType
     * @return static
     */
    public function setDocToChangeType($docToChangeType)
    {
        $this->docToChangeType = $docToChangeType;
        return $this;
    }

    /**
     * Gets as orgData
     *
     * @return \common\models\sbbolxml\request\OrgDataType
     */
    public function getOrgData()
    {
        return $this->orgData;
    }

    /**
     * Sets a new orgData
     *
     * @param \common\models\sbbolxml\request\OrgDataType $orgData
     * @return static
     */
    public function setOrgData(\common\models\sbbolxml\request\OrgDataType $orgData)
    {
        $this->orgData = $orgData;
        return $this;
    }


}


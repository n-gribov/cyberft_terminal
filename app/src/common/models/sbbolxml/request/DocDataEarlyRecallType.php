<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing DocDataEarlyRecallType
 *
 * Общие реквизиты заявления на отзыв вклада (депозита)
 * XSD Type: DocDataEarlyRecall
 */
class DocDataEarlyRecallType
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
     * @property string $bankSystemName
     */
    private $bankSystemName = null;

    /**
     * Идентификатор карточки депозита
     *
     * @property string $depositID
     */
    private $depositID = null;

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
     * Номер счета
     *
     * @property string $accNum
     */
    private $accNum = null;

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
     * Gets as depositID
     *
     * Идентификатор карточки депозита
     *
     * @return string
     */
    public function getDepositID()
    {
        return $this->depositID;
    }

    /**
     * Sets a new depositID
     *
     * Идентификатор карточки депозита
     *
     * @param string $depositID
     * @return static
     */
    public function setDepositID($depositID)
    {
        $this->depositID = $depositID;
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
     * Gets as accNum
     *
     * Номер счета
     *
     * @return string
     */
    public function getAccNum()
    {
        return $this->accNum;
    }

    /**
     * Sets a new accNum
     *
     * Номер счета
     *
     * @param string $accNum
     * @return static
     */
    public function setAccNum($accNum)
    {
        $this->accNum = $accNum;
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


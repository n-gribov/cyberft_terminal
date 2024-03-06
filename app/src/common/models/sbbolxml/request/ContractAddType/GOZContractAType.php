<?php

namespace common\models\sbbolxml\request\ContractAddType;

/**
 * Class representing GOZContractAType
 */
class GOZContractAType
{

    /**
     * № контракта
     *
     * @property string $contractNum
     */
    private $contractNum = null;

    /**
     * Дата заключения контракта
     *
     * @property \DateTime $createDate
     */
    private $createDate = null;

    /**
     * Дата предоставления контракта
     *
     * @property \DateTime $sendDate
     */
    private $sendDate = null;

    /**
     * Дата последнего изменения
     *
     * @property \DateTime $changeDate
     */
    private $changeDate = null;

    /**
     * Дата завершения обязательств по контракту
     *
     * @property \DateTime $endDate
     */
    private $endDate = null;

    /**
     * Код типа предмета договора, заполняется кодом из справочника Типов предметов договоров UpgRplDictionary/ContractSubjectTypeEntry/@code
     *
     * @property integer $contractSubjectType
     */
    private $contractSubjectType = null;

    /**
     * Gets as contractNum
     *
     * № контракта
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
     * № контракта
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
     * Gets as createDate
     *
     * Дата заключения контракта
     *
     * @return \DateTime
     */
    public function getCreateDate()
    {
        return $this->createDate;
    }

    /**
     * Sets a new createDate
     *
     * Дата заключения контракта
     *
     * @param \DateTime $createDate
     * @return static
     */
    public function setCreateDate(\DateTime $createDate)
    {
        $this->createDate = $createDate;
        return $this;
    }

    /**
     * Gets as sendDate
     *
     * Дата предоставления контракта
     *
     * @return \DateTime
     */
    public function getSendDate()
    {
        return $this->sendDate;
    }

    /**
     * Sets a new sendDate
     *
     * Дата предоставления контракта
     *
     * @param \DateTime $sendDate
     * @return static
     */
    public function setSendDate(\DateTime $sendDate)
    {
        $this->sendDate = $sendDate;
        return $this;
    }

    /**
     * Gets as changeDate
     *
     * Дата последнего изменения
     *
     * @return \DateTime
     */
    public function getChangeDate()
    {
        return $this->changeDate;
    }

    /**
     * Sets a new changeDate
     *
     * Дата последнего изменения
     *
     * @param \DateTime $changeDate
     * @return static
     */
    public function setChangeDate(\DateTime $changeDate)
    {
        $this->changeDate = $changeDate;
        return $this;
    }

    /**
     * Gets as endDate
     *
     * Дата завершения обязательств по контракту
     *
     * @return \DateTime
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * Sets a new endDate
     *
     * Дата завершения обязательств по контракту
     *
     * @param \DateTime $endDate
     * @return static
     */
    public function setEndDate(\DateTime $endDate)
    {
        $this->endDate = $endDate;
        return $this;
    }

    /**
     * Gets as contractSubjectType
     *
     * Код типа предмета договора, заполняется кодом из справочника Типов предметов договоров UpgRplDictionary/ContractSubjectTypeEntry/@code
     *
     * @return integer
     */
    public function getContractSubjectType()
    {
        return $this->contractSubjectType;
    }

    /**
     * Sets a new contractSubjectType
     *
     * Код типа предмета договора, заполняется кодом из справочника Типов предметов договоров UpgRplDictionary/ContractSubjectTypeEntry/@code
     *
     * @param integer $contractSubjectType
     * @return static
     */
    public function setContractSubjectType($contractSubjectType)
    {
        $this->contractSubjectType = $contractSubjectType;
        return $this;
    }


}


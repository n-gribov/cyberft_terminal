<?php

namespace common\models\sbbolxml\request\ActAddType;

/**
 * Class representing GOZActAType
 */
class GOZActAType
{

    /**
     * № акта
     *
     * @property string $actNum
     */
    private $actNum = null;

    /**
     * Дата акта
     *
     * @property \DateTime $actDate
     */
    private $actDate = null;

    /**
     * Тип акта, заполняется кодом из справочника Типов предметов договоров UpgRplDictionary/ConTypeEntry/@code
     *
     * @property string $actType
     */
    private $actType = null;

    /**
     * Дата предоставления акта
     *
     * @property \DateTime $sendDate
     */
    private $sendDate = null;

    /**
     * Содержание акта
     *
     * @property string $actContent
     */
    private $actContent = null;

    /**
     * № контракта
     *
     * @property string $contractEssenceId
     */
    private $contractEssenceId = null;

    /**
     * Сумма акта
     *
     * @property \common\models\sbbolxml\request\ActAddType\GOZActAType\ActSummAType $actSumm
     */
    private $actSumm = null;

    /**
     * Gets as actNum
     *
     * № акта
     *
     * @return string
     */
    public function getActNum()
    {
        return $this->actNum;
    }

    /**
     * Sets a new actNum
     *
     * № акта
     *
     * @param string $actNum
     * @return static
     */
    public function setActNum($actNum)
    {
        $this->actNum = $actNum;
        return $this;
    }

    /**
     * Gets as actDate
     *
     * Дата акта
     *
     * @return \DateTime
     */
    public function getActDate()
    {
        return $this->actDate;
    }

    /**
     * Sets a new actDate
     *
     * Дата акта
     *
     * @param \DateTime $actDate
     * @return static
     */
    public function setActDate(\DateTime $actDate)
    {
        $this->actDate = $actDate;
        return $this;
    }

    /**
     * Gets as actType
     *
     * Тип акта, заполняется кодом из справочника Типов предметов договоров UpgRplDictionary/ConTypeEntry/@code
     *
     * @return string
     */
    public function getActType()
    {
        return $this->actType;
    }

    /**
     * Sets a new actType
     *
     * Тип акта, заполняется кодом из справочника Типов предметов договоров UpgRplDictionary/ConTypeEntry/@code
     *
     * @param string $actType
     * @return static
     */
    public function setActType($actType)
    {
        $this->actType = $actType;
        return $this;
    }

    /**
     * Gets as sendDate
     *
     * Дата предоставления акта
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
     * Дата предоставления акта
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
     * Gets as actContent
     *
     * Содержание акта
     *
     * @return string
     */
    public function getActContent()
    {
        return $this->actContent;
    }

    /**
     * Sets a new actContent
     *
     * Содержание акта
     *
     * @param string $actContent
     * @return static
     */
    public function setActContent($actContent)
    {
        $this->actContent = $actContent;
        return $this;
    }

    /**
     * Gets as contractEssenceId
     *
     * № контракта
     *
     * @return string
     */
    public function getContractEssenceId()
    {
        return $this->contractEssenceId;
    }

    /**
     * Sets a new contractEssenceId
     *
     * № контракта
     *
     * @param string $contractEssenceId
     * @return static
     */
    public function setContractEssenceId($contractEssenceId)
    {
        $this->contractEssenceId = $contractEssenceId;
        return $this;
    }

    /**
     * Gets as actSumm
     *
     * Сумма акта
     *
     * @return \common\models\sbbolxml\request\ActAddType\GOZActAType\ActSummAType
     */
    public function getActSumm()
    {
        return $this->actSumm;
    }

    /**
     * Sets a new actSumm
     *
     * Сумма акта
     *
     * @param \common\models\sbbolxml\request\ActAddType\GOZActAType\ActSummAType $actSumm
     * @return static
     */
    public function setActSumm(\common\models\sbbolxml\request\ActAddType\GOZActAType\ActSummAType $actSumm)
    {
        $this->actSumm = $actSumm;
        return $this;
    }


}


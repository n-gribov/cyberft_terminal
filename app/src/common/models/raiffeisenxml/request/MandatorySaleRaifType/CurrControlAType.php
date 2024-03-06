<?php

namespace common\models\raiffeisenxml\request\MandatorySaleRaifType;

/**
 * Class representing CurrControlAType
 */
class CurrControlAType
{

    /**
     * Номер паспорта сделки
     *
     * @property string $dealPassNum
     */
    private $dealPassNum = null;

    /**
     * Дата паспорта сделки
     *
     * @property \DateTime $dealPassDate
     */
    private $dealPassDate = null;

    /**
     * Номер контракта
     *
     * @property string $contractNum
     */
    private $contractNum = null;

    /**
     * Дата контракта
     *
     * @property \DateTime $contractDate
     */
    private $contractDate = null;

    /**
     * Код вида ВО
     *
     * @property string $vo
     */
    private $vo = null;

    /**
     * Код вида услуг
     *
     * @property string $servTypeCode
     */
    private $servTypeCode = null;

    /**
     * Номер уведомления
     *
     * @property string $noticeNum
     */
    private $noticeNum = null;

    /**
     * Дата уведомления
     *
     * @property \DateTime $noticeDate
     */
    private $noticeDate = null;

    /**
     * Реквизиты справки о валютных операциях
     *
     * @property \common\models\raiffeisenxml\request\MandatorySaleRaifType\CurrControlAType\CurrDealInquiryAType $currDealInquiry
     */
    private $currDealInquiry = null;

    /**
     * @property \common\models\raiffeisenxml\request\MandatorySaleRaifType\CurrControlAType\VoSumInfoAType\VoSumAType[] $voSumInfo
     */
    private $voSumInfo = null;

    /**
     * Gets as dealPassNum
     *
     * Номер паспорта сделки
     *
     * @return string
     */
    public function getDealPassNum()
    {
        return $this->dealPassNum;
    }

    /**
     * Sets a new dealPassNum
     *
     * Номер паспорта сделки
     *
     * @param string $dealPassNum
     * @return static
     */
    public function setDealPassNum($dealPassNum)
    {
        $this->dealPassNum = $dealPassNum;
        return $this;
    }

    /**
     * Gets as dealPassDate
     *
     * Дата паспорта сделки
     *
     * @return \DateTime
     */
    public function getDealPassDate()
    {
        return $this->dealPassDate;
    }

    /**
     * Sets a new dealPassDate
     *
     * Дата паспорта сделки
     *
     * @param \DateTime $dealPassDate
     * @return static
     */
    public function setDealPassDate(\DateTime $dealPassDate)
    {
        $this->dealPassDate = $dealPassDate;
        return $this;
    }

    /**
     * Gets as contractNum
     *
     * Номер контракта
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
     * Номер контракта
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
     * Дата контракта
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
     * Дата контракта
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
     * Gets as vo
     *
     * Код вида ВО
     *
     * @return string
     */
    public function getVo()
    {
        return $this->vo;
    }

    /**
     * Sets a new vo
     *
     * Код вида ВО
     *
     * @param string $vo
     * @return static
     */
    public function setVo($vo)
    {
        $this->vo = $vo;
        return $this;
    }

    /**
     * Gets as servTypeCode
     *
     * Код вида услуг
     *
     * @return string
     */
    public function getServTypeCode()
    {
        return $this->servTypeCode;
    }

    /**
     * Sets a new servTypeCode
     *
     * Код вида услуг
     *
     * @param string $servTypeCode
     * @return static
     */
    public function setServTypeCode($servTypeCode)
    {
        $this->servTypeCode = $servTypeCode;
        return $this;
    }

    /**
     * Gets as noticeNum
     *
     * Номер уведомления
     *
     * @return string
     */
    public function getNoticeNum()
    {
        return $this->noticeNum;
    }

    /**
     * Sets a new noticeNum
     *
     * Номер уведомления
     *
     * @param string $noticeNum
     * @return static
     */
    public function setNoticeNum($noticeNum)
    {
        $this->noticeNum = $noticeNum;
        return $this;
    }

    /**
     * Gets as noticeDate
     *
     * Дата уведомления
     *
     * @return \DateTime
     */
    public function getNoticeDate()
    {
        return $this->noticeDate;
    }

    /**
     * Sets a new noticeDate
     *
     * Дата уведомления
     *
     * @param \DateTime $noticeDate
     * @return static
     */
    public function setNoticeDate(\DateTime $noticeDate)
    {
        $this->noticeDate = $noticeDate;
        return $this;
    }

    /**
     * Gets as currDealInquiry
     *
     * Реквизиты справки о валютных операциях
     *
     * @return \common\models\raiffeisenxml\request\MandatorySaleRaifType\CurrControlAType\CurrDealInquiryAType
     */
    public function getCurrDealInquiry()
    {
        return $this->currDealInquiry;
    }

    /**
     * Sets a new currDealInquiry
     *
     * Реквизиты справки о валютных операциях
     *
     * @param \common\models\raiffeisenxml\request\MandatorySaleRaifType\CurrControlAType\CurrDealInquiryAType $currDealInquiry
     * @return static
     */
    public function setCurrDealInquiry(\common\models\raiffeisenxml\request\MandatorySaleRaifType\CurrControlAType\CurrDealInquiryAType $currDealInquiry)
    {
        $this->currDealInquiry = $currDealInquiry;
        return $this;
    }

    /**
     * Adds as voSum
     *
     * @return static
     * @param \common\models\raiffeisenxml\request\MandatorySaleRaifType\CurrControlAType\VoSumInfoAType\VoSumAType $voSum
     */
    public function addToVoSumInfo(\common\models\raiffeisenxml\request\MandatorySaleRaifType\CurrControlAType\VoSumInfoAType\VoSumAType $voSum)
    {
        $this->voSumInfo[] = $voSum;
        return $this;
    }

    /**
     * isset voSumInfo
     *
     * @param int|string $index
     * @return bool
     */
    public function issetVoSumInfo($index)
    {
        return isset($this->voSumInfo[$index]);
    }

    /**
     * unset voSumInfo
     *
     * @param int|string $index
     * @return void
     */
    public function unsetVoSumInfo($index)
    {
        unset($this->voSumInfo[$index]);
    }

    /**
     * Gets as voSumInfo
     *
     * @return \common\models\raiffeisenxml\request\MandatorySaleRaifType\CurrControlAType\VoSumInfoAType\VoSumAType[]
     */
    public function getVoSumInfo()
    {
        return $this->voSumInfo;
    }

    /**
     * Sets a new voSumInfo
     *
     * @param \common\models\raiffeisenxml\request\MandatorySaleRaifType\CurrControlAType\VoSumInfoAType\VoSumAType[] $voSumInfo
     * @return static
     */
    public function setVoSumInfo(array $voSumInfo)
    {
        $this->voSumInfo = $voSumInfo;
        return $this;
    }


}


<?php

namespace common\models\sbbolxml\response\OrganizationInfoType\ElectionAccountsAType;

/**
 * Class representing ElectionAccountAType
 */
class ElectionAccountAType
{

    /**
     * Номер счета
     *
     * @property string $accountNumber
     */
    private $accountNumber = null;

    /**
     * БИК
     *
     * @property string $bik
     */
    private $bik = null;

    /**
     * Дата открытия счета
     *
     * @property \DateTime $registerDate
     */
    private $registerDate = null;

    /**
     * Дата закрытия счета
     *
     * @property \DateTime $closeDate
     */
    private $closeDate = null;

    /**
     * ISO-код валюты договора
     *
     * @property string $cur
     */
    private $cur = null;

    /**
     * Статус счета
     *
     * @property string $status
     */
    private $status = null;

    /**
     * Организация (наименование кандидата)
     *
     * @property string $candidateName
     */
    private $candidateName = null;

    /**
     * Тип кандидата
     *
     * @property string $candidateType
     */
    private $candidateType = null;

    /**
     * Избирательная кампания
     *
     * @property string $campaignName
     */
    private $campaignName = null;

    /**
     * Округ
     *
     * @property string $district
     */
    private $district = null;

    /**
     * Кредитная организация
     *
     * @property string $bankReq
     */
    private $bankReq = null;

    /**
     * Тербанк
     *
     * @property string $terBankNum
     */
    private $terBankNum = null;

    /**
     * Gets as accountNumber
     *
     * Номер счета
     *
     * @return string
     */
    public function getAccountNumber()
    {
        return $this->accountNumber;
    }

    /**
     * Sets a new accountNumber
     *
     * Номер счета
     *
     * @param string $accountNumber
     * @return static
     */
    public function setAccountNumber($accountNumber)
    {
        $this->accountNumber = $accountNumber;
        return $this;
    }

    /**
     * Gets as bik
     *
     * БИК
     *
     * @return string
     */
    public function getBik()
    {
        return $this->bik;
    }

    /**
     * Sets a new bik
     *
     * БИК
     *
     * @param string $bik
     * @return static
     */
    public function setBik($bik)
    {
        $this->bik = $bik;
        return $this;
    }

    /**
     * Gets as registerDate
     *
     * Дата открытия счета
     *
     * @return \DateTime
     */
    public function getRegisterDate()
    {
        return $this->registerDate;
    }

    /**
     * Sets a new registerDate
     *
     * Дата открытия счета
     *
     * @param \DateTime $registerDate
     * @return static
     */
    public function setRegisterDate(\DateTime $registerDate)
    {
        $this->registerDate = $registerDate;
        return $this;
    }

    /**
     * Gets as closeDate
     *
     * Дата закрытия счета
     *
     * @return \DateTime
     */
    public function getCloseDate()
    {
        return $this->closeDate;
    }

    /**
     * Sets a new closeDate
     *
     * Дата закрытия счета
     *
     * @param \DateTime $closeDate
     * @return static
     */
    public function setCloseDate(\DateTime $closeDate)
    {
        $this->closeDate = $closeDate;
        return $this;
    }

    /**
     * Gets as cur
     *
     * ISO-код валюты договора
     *
     * @return string
     */
    public function getCur()
    {
        return $this->cur;
    }

    /**
     * Sets a new cur
     *
     * ISO-код валюты договора
     *
     * @param string $cur
     * @return static
     */
    public function setCur($cur)
    {
        $this->cur = $cur;
        return $this;
    }

    /**
     * Gets as status
     *
     * Статус счета
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Sets a new status
     *
     * Статус счета
     *
     * @param string $status
     * @return static
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * Gets as candidateName
     *
     * Организация (наименование кандидата)
     *
     * @return string
     */
    public function getCandidateName()
    {
        return $this->candidateName;
    }

    /**
     * Sets a new candidateName
     *
     * Организация (наименование кандидата)
     *
     * @param string $candidateName
     * @return static
     */
    public function setCandidateName($candidateName)
    {
        $this->candidateName = $candidateName;
        return $this;
    }

    /**
     * Gets as candidateType
     *
     * Тип кандидата
     *
     * @return string
     */
    public function getCandidateType()
    {
        return $this->candidateType;
    }

    /**
     * Sets a new candidateType
     *
     * Тип кандидата
     *
     * @param string $candidateType
     * @return static
     */
    public function setCandidateType($candidateType)
    {
        $this->candidateType = $candidateType;
        return $this;
    }

    /**
     * Gets as campaignName
     *
     * Избирательная кампания
     *
     * @return string
     */
    public function getCampaignName()
    {
        return $this->campaignName;
    }

    /**
     * Sets a new campaignName
     *
     * Избирательная кампания
     *
     * @param string $campaignName
     * @return static
     */
    public function setCampaignName($campaignName)
    {
        $this->campaignName = $campaignName;
        return $this;
    }

    /**
     * Gets as district
     *
     * Округ
     *
     * @return string
     */
    public function getDistrict()
    {
        return $this->district;
    }

    /**
     * Sets a new district
     *
     * Округ
     *
     * @param string $district
     * @return static
     */
    public function setDistrict($district)
    {
        $this->district = $district;
        return $this;
    }

    /**
     * Gets as bankReq
     *
     * Кредитная организация
     *
     * @return string
     */
    public function getBankReq()
    {
        return $this->bankReq;
    }

    /**
     * Sets a new bankReq
     *
     * Кредитная организация
     *
     * @param string $bankReq
     * @return static
     */
    public function setBankReq($bankReq)
    {
        $this->bankReq = $bankReq;
        return $this;
    }

    /**
     * Gets as terBankNum
     *
     * Тербанк
     *
     * @return string
     */
    public function getTerBankNum()
    {
        return $this->terBankNum;
    }

    /**
     * Sets a new terBankNum
     *
     * Тербанк
     *
     * @param string $terBankNum
     * @return static
     */
    public function setTerBankNum($terBankNum)
    {
        $this->terBankNum = $terBankNum;
        return $this;
    }


}


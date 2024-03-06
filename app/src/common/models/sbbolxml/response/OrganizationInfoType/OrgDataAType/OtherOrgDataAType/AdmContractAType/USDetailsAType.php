<?php

namespace common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\OtherOrgDataAType\AdmContractAType;

/**
 * Class representing USDetailsAType
 */
class USDetailsAType
{

    /**
     * Номер договора
     *
     * @property string $selfContractNumber
     */
    private $selfContractNumber = null;

    /**
     * дата окончания
     *
     * @property \DateTime $selfEndDate
     */
    private $selfEndDate = null;

    /**
     * Дневной лимит
     *
     * @property float $selfLimit
     */
    private $selfLimit = null;

    /**
     * Идентификатор организации
     *  admcashier.ORGID
     *
     * @property string $selfOrgId
     */
    private $selfOrgId = null;

    /**
     * причина приостановки
     *
     * @property string $selfPurpose
     */
    private $selfPurpose = null;

    /**
     * Дата начала действия
     *
     * @property \DateTime $selfStartDate
     */
    private $selfStartDate = null;

    /**
     * Статус:
     *  OPEN
     *  SUSPENDED
     *  CLOSED
     *
     * @property string $selfStatus
     */
    private $selfStatus = null;

    /**
     * Gets as selfContractNumber
     *
     * Номер договора
     *
     * @return string
     */
    public function getSelfContractNumber()
    {
        return $this->selfContractNumber;
    }

    /**
     * Sets a new selfContractNumber
     *
     * Номер договора
     *
     * @param string $selfContractNumber
     * @return static
     */
    public function setSelfContractNumber($selfContractNumber)
    {
        $this->selfContractNumber = $selfContractNumber;
        return $this;
    }

    /**
     * Gets as selfEndDate
     *
     * дата окончания
     *
     * @return \DateTime
     */
    public function getSelfEndDate()
    {
        return $this->selfEndDate;
    }

    /**
     * Sets a new selfEndDate
     *
     * дата окончания
     *
     * @param \DateTime $selfEndDate
     * @return static
     */
    public function setSelfEndDate(\DateTime $selfEndDate)
    {
        $this->selfEndDate = $selfEndDate;
        return $this;
    }

    /**
     * Gets as selfLimit
     *
     * Дневной лимит
     *
     * @return float
     */
    public function getSelfLimit()
    {
        return $this->selfLimit;
    }

    /**
     * Sets a new selfLimit
     *
     * Дневной лимит
     *
     * @param float $selfLimit
     * @return static
     */
    public function setSelfLimit($selfLimit)
    {
        $this->selfLimit = $selfLimit;
        return $this;
    }

    /**
     * Gets as selfOrgId
     *
     * Идентификатор организации
     *  admcashier.ORGID
     *
     * @return string
     */
    public function getSelfOrgId()
    {
        return $this->selfOrgId;
    }

    /**
     * Sets a new selfOrgId
     *
     * Идентификатор организации
     *  admcashier.ORGID
     *
     * @param string $selfOrgId
     * @return static
     */
    public function setSelfOrgId($selfOrgId)
    {
        $this->selfOrgId = $selfOrgId;
        return $this;
    }

    /**
     * Gets as selfPurpose
     *
     * причина приостановки
     *
     * @return string
     */
    public function getSelfPurpose()
    {
        return $this->selfPurpose;
    }

    /**
     * Sets a new selfPurpose
     *
     * причина приостановки
     *
     * @param string $selfPurpose
     * @return static
     */
    public function setSelfPurpose($selfPurpose)
    {
        $this->selfPurpose = $selfPurpose;
        return $this;
    }

    /**
     * Gets as selfStartDate
     *
     * Дата начала действия
     *
     * @return \DateTime
     */
    public function getSelfStartDate()
    {
        return $this->selfStartDate;
    }

    /**
     * Sets a new selfStartDate
     *
     * Дата начала действия
     *
     * @param \DateTime $selfStartDate
     * @return static
     */
    public function setSelfStartDate(\DateTime $selfStartDate)
    {
        $this->selfStartDate = $selfStartDate;
        return $this;
    }

    /**
     * Gets as selfStatus
     *
     * Статус:
     *  OPEN
     *  SUSPENDED
     *  CLOSED
     *
     * @return string
     */
    public function getSelfStatus()
    {
        return $this->selfStatus;
    }

    /**
     * Sets a new selfStatus
     *
     * Статус:
     *  OPEN
     *  SUSPENDED
     *  CLOSED
     *
     * @param string $selfStatus
     * @return static
     */
    public function setSelfStatus($selfStatus)
    {
        $this->selfStatus = $selfStatus;
        return $this;
    }


}


<?php

namespace common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\OtherOrgDataAType\AdmContractAType;

/**
 * Class representing ADMDetailsAType
 */
class ADMDetailsAType
{

    /**
     * Идентификатор организации
     *  admcashier.ORGID
     *
     * @property string $orgId
     */
    private $orgId = null;

    /**
     * номер контракта АДМ
     *
     * @property string $contractNumber
     */
    private $contractNumber = null;

    /**
     * Дата начала действия договора
     *
     * @property \DateTime $activeFrom
     */
    private $activeFrom = null;

    /**
     * Статус:
     *  OPEN
     *  SUSPENDED
     *  CLOSED
     *
     * @property string $status
     */
    private $status = null;

    /**
     * обоснование
     *
     * @property string $suspendReason
     */
    private $suspendReason = null;

    /**
     * Подтверждение СМС. 1 - подтверждение
     *  подключено, 0 - отключено
     *
     * @property boolean $acceptBySMS
     */
    private $acceptBySMS = null;

    /**
     * Gets as orgId
     *
     * Идентификатор организации
     *  admcashier.ORGID
     *
     * @return string
     */
    public function getOrgId()
    {
        return $this->orgId;
    }

    /**
     * Sets a new orgId
     *
     * Идентификатор организации
     *  admcashier.ORGID
     *
     * @param string $orgId
     * @return static
     */
    public function setOrgId($orgId)
    {
        $this->orgId = $orgId;
        return $this;
    }

    /**
     * Gets as contractNumber
     *
     * номер контракта АДМ
     *
     * @return string
     */
    public function getContractNumber()
    {
        return $this->contractNumber;
    }

    /**
     * Sets a new contractNumber
     *
     * номер контракта АДМ
     *
     * @param string $contractNumber
     * @return static
     */
    public function setContractNumber($contractNumber)
    {
        $this->contractNumber = $contractNumber;
        return $this;
    }

    /**
     * Gets as activeFrom
     *
     * Дата начала действия договора
     *
     * @return \DateTime
     */
    public function getActiveFrom()
    {
        return $this->activeFrom;
    }

    /**
     * Sets a new activeFrom
     *
     * Дата начала действия договора
     *
     * @param \DateTime $activeFrom
     * @return static
     */
    public function setActiveFrom(\DateTime $activeFrom)
    {
        $this->activeFrom = $activeFrom;
        return $this;
    }

    /**
     * Gets as status
     *
     * Статус:
     *  OPEN
     *  SUSPENDED
     *  CLOSED
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
     * Статус:
     *  OPEN
     *  SUSPENDED
     *  CLOSED
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
     * Gets as suspendReason
     *
     * обоснование
     *
     * @return string
     */
    public function getSuspendReason()
    {
        return $this->suspendReason;
    }

    /**
     * Sets a new suspendReason
     *
     * обоснование
     *
     * @param string $suspendReason
     * @return static
     */
    public function setSuspendReason($suspendReason)
    {
        $this->suspendReason = $suspendReason;
        return $this;
    }

    /**
     * Gets as acceptBySMS
     *
     * Подтверждение СМС. 1 - подтверждение
     *  подключено, 0 - отключено
     *
     * @return boolean
     */
    public function getAcceptBySMS()
    {
        return $this->acceptBySMS;
    }

    /**
     * Sets a new acceptBySMS
     *
     * Подтверждение СМС. 1 - подтверждение
     *  подключено, 0 - отключено
     *
     * @param boolean $acceptBySMS
     * @return static
     */
    public function setAcceptBySMS($acceptBySMS)
    {
        $this->acceptBySMS = $acceptBySMS;
        return $this;
    }


}


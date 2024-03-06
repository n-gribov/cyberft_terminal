<?php

namespace common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\OrgBranchesAType\OrgBranchAType;

/**
 * Class representing BlockInfoAType
 */
class BlockInfoAType
{

    /**
     * Приостановление оказания услуг:
     *  1- установлено
     *  0- не установлено
     *
     * @property boolean $stopedService
     */
    private $stopedService = null;

    /**
     * Причина приостановления услуг
     *
     * @property string $stopInfo
     */
    private $stopInfo = null;

    /**
     * Финансовая блокировка:
     *  1- установлена
     *  0- не установлена
     *
     * @property boolean $finBlock
     */
    private $finBlock = null;

    /**
     * Причина финансовой блокировки
     *
     * @property string $finBlockInfo
     */
    private $finBlockInfo = null;

    /**
     * Расторжение контракта (договора) с
     *  банком:
     *  1- установлено
     *  0- не установлено
     *
     * @property boolean $contractRescission
     */
    private $contractRescission = null;

    /**
     * Причина расторжения договора
     *
     * @property string $contractRescissionInfo
     */
    private $contractRescissionInfo = null;

    /**
     * Дата расторжения контракта
     *
     * @property \DateTime $dateRescission
     */
    private $dateRescission = null;

    /**
     * Причина расторжения контракта
     *  (договора)
     *
     * @property string $reason
     */
    private $reason = null;

    /**
     * Gets as stopedService
     *
     * Приостановление оказания услуг:
     *  1- установлено
     *  0- не установлено
     *
     * @return boolean
     */
    public function getStopedService()
    {
        return $this->stopedService;
    }

    /**
     * Sets a new stopedService
     *
     * Приостановление оказания услуг:
     *  1- установлено
     *  0- не установлено
     *
     * @param boolean $stopedService
     * @return static
     */
    public function setStopedService($stopedService)
    {
        $this->stopedService = $stopedService;
        return $this;
    }

    /**
     * Gets as stopInfo
     *
     * Причина приостановления услуг
     *
     * @return string
     */
    public function getStopInfo()
    {
        return $this->stopInfo;
    }

    /**
     * Sets a new stopInfo
     *
     * Причина приостановления услуг
     *
     * @param string $stopInfo
     * @return static
     */
    public function setStopInfo($stopInfo)
    {
        $this->stopInfo = $stopInfo;
        return $this;
    }

    /**
     * Gets as finBlock
     *
     * Финансовая блокировка:
     *  1- установлена
     *  0- не установлена
     *
     * @return boolean
     */
    public function getFinBlock()
    {
        return $this->finBlock;
    }

    /**
     * Sets a new finBlock
     *
     * Финансовая блокировка:
     *  1- установлена
     *  0- не установлена
     *
     * @param boolean $finBlock
     * @return static
     */
    public function setFinBlock($finBlock)
    {
        $this->finBlock = $finBlock;
        return $this;
    }

    /**
     * Gets as finBlockInfo
     *
     * Причина финансовой блокировки
     *
     * @return string
     */
    public function getFinBlockInfo()
    {
        return $this->finBlockInfo;
    }

    /**
     * Sets a new finBlockInfo
     *
     * Причина финансовой блокировки
     *
     * @param string $finBlockInfo
     * @return static
     */
    public function setFinBlockInfo($finBlockInfo)
    {
        $this->finBlockInfo = $finBlockInfo;
        return $this;
    }

    /**
     * Gets as contractRescission
     *
     * Расторжение контракта (договора) с
     *  банком:
     *  1- установлено
     *  0- не установлено
     *
     * @return boolean
     */
    public function getContractRescission()
    {
        return $this->contractRescission;
    }

    /**
     * Sets a new contractRescission
     *
     * Расторжение контракта (договора) с
     *  банком:
     *  1- установлено
     *  0- не установлено
     *
     * @param boolean $contractRescission
     * @return static
     */
    public function setContractRescission($contractRescission)
    {
        $this->contractRescission = $contractRescission;
        return $this;
    }

    /**
     * Gets as contractRescissionInfo
     *
     * Причина расторжения договора
     *
     * @return string
     */
    public function getContractRescissionInfo()
    {
        return $this->contractRescissionInfo;
    }

    /**
     * Sets a new contractRescissionInfo
     *
     * Причина расторжения договора
     *
     * @param string $contractRescissionInfo
     * @return static
     */
    public function setContractRescissionInfo($contractRescissionInfo)
    {
        $this->contractRescissionInfo = $contractRescissionInfo;
        return $this;
    }

    /**
     * Gets as dateRescission
     *
     * Дата расторжения контракта
     *
     * @return \DateTime
     */
    public function getDateRescission()
    {
        return $this->dateRescission;
    }

    /**
     * Sets a new dateRescission
     *
     * Дата расторжения контракта
     *
     * @param \DateTime $dateRescission
     * @return static
     */
    public function setDateRescission(\DateTime $dateRescission)
    {
        $this->dateRescission = $dateRescission;
        return $this;
    }

    /**
     * Gets as reason
     *
     * Причина расторжения контракта
     *  (договора)
     *
     * @return string
     */
    public function getReason()
    {
        return $this->reason;
    }

    /**
     * Sets a new reason
     *
     * Причина расторжения контракта
     *  (договора)
     *
     * @param string $reason
     * @return static
     */
    public function setReason($reason)
    {
        $this->reason = $reason;
        return $this;
    }


}


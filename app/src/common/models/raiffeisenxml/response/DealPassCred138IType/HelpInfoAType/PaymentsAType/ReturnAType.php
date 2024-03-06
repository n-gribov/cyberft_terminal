<?php

namespace common\models\raiffeisenxml\response\DealPassCred138IType\HelpInfoAType\PaymentsAType;

/**
 * Class representing ReturnAType
 */
class ReturnAType
{

    /**
     * Номер п/п
     *
     * @property int $strNum
     */
    private $strNum = null;

    /**
     * Суммы платежей по датам их
     *  осуществления (В счет процентных
     *  платежей)
     *
     * @property \common\models\raiffeisenxml\response\DealPassCred138IType\HelpInfoAType\PaymentsAType\ReturnAType\PercentInfoAType $percentInfo
     */
    private $percentInfo = null;

    /**
     * Суммы платежей по датам их
     *  осуществления (В счет основного
     *  долга)
     *
     * @property \common\models\raiffeisenxml\response\DealPassCred138IType\HelpInfoAType\PaymentsAType\ReturnAType\DebtInfoAType $debtInfo
     */
    private $debtInfo = null;

    /**
     * Описание особых условий
     *
     * @property string $specConditions
     */
    private $specConditions = null;

    /**
     * Gets as strNum
     *
     * Номер п/п
     *
     * @return int
     */
    public function getStrNum()
    {
        return $this->strNum;
    }

    /**
     * Sets a new strNum
     *
     * Номер п/п
     *
     * @param int $strNum
     * @return static
     */
    public function setStrNum($strNum)
    {
        $this->strNum = $strNum;
        return $this;
    }

    /**
     * Gets as percentInfo
     *
     * Суммы платежей по датам их
     *  осуществления (В счет процентных
     *  платежей)
     *
     * @return \common\models\raiffeisenxml\response\DealPassCred138IType\HelpInfoAType\PaymentsAType\ReturnAType\PercentInfoAType
     */
    public function getPercentInfo()
    {
        return $this->percentInfo;
    }

    /**
     * Sets a new percentInfo
     *
     * Суммы платежей по датам их
     *  осуществления (В счет процентных
     *  платежей)
     *
     * @param \common\models\raiffeisenxml\response\DealPassCred138IType\HelpInfoAType\PaymentsAType\ReturnAType\PercentInfoAType $percentInfo
     * @return static
     */
    public function setPercentInfo(\common\models\raiffeisenxml\response\DealPassCred138IType\HelpInfoAType\PaymentsAType\ReturnAType\PercentInfoAType $percentInfo)
    {
        $this->percentInfo = $percentInfo;
        return $this;
    }

    /**
     * Gets as debtInfo
     *
     * Суммы платежей по датам их
     *  осуществления (В счет основного
     *  долга)
     *
     * @return \common\models\raiffeisenxml\response\DealPassCred138IType\HelpInfoAType\PaymentsAType\ReturnAType\DebtInfoAType
     */
    public function getDebtInfo()
    {
        return $this->debtInfo;
    }

    /**
     * Sets a new debtInfo
     *
     * Суммы платежей по датам их
     *  осуществления (В счет основного
     *  долга)
     *
     * @param \common\models\raiffeisenxml\response\DealPassCred138IType\HelpInfoAType\PaymentsAType\ReturnAType\DebtInfoAType $debtInfo
     * @return static
     */
    public function setDebtInfo(\common\models\raiffeisenxml\response\DealPassCred138IType\HelpInfoAType\PaymentsAType\ReturnAType\DebtInfoAType $debtInfo)
    {
        $this->debtInfo = $debtInfo;
        return $this;
    }

    /**
     * Gets as specConditions
     *
     * Описание особых условий
     *
     * @return string
     */
    public function getSpecConditions()
    {
        return $this->specConditions;
    }

    /**
     * Sets a new specConditions
     *
     * Описание особых условий
     *
     * @param string $specConditions
     * @return static
     */
    public function setSpecConditions($specConditions)
    {
        $this->specConditions = $specConditions;
        return $this;
    }


}


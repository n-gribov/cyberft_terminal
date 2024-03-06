<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing AdmOperationType
 *
 * Операция внесения средств
 * XSD Type: AdmOperation
 */
class AdmOperationType
{

    /**
     * Информация об организации
     *
     * @property \common\models\sbbolxml\response\AdmOperationType\OrgInfoAType $orgInfo
     */
    private $orgInfo = null;

    /**
     * Информация о вносителе средств
     *
     * @property \common\models\sbbolxml\response\AdmOperationType\CashierInfoAType $cashierInfo
     */
    private $cashierInfo = null;

    /**
     * Информация об операции
     *
     * @property \common\models\sbbolxml\response\AdmOperationType\OperationInfoAType $operationInfo
     */
    private $operationInfo = null;

    /**
     * Информация об устройстве
     *
     * @property \common\models\sbbolxml\response\AdmOperationType\DeviceInfoAType $deviceInfo
     */
    private $deviceInfo = null;

    /**
     * Gets as orgInfo
     *
     * Информация об организации
     *
     * @return \common\models\sbbolxml\response\AdmOperationType\OrgInfoAType
     */
    public function getOrgInfo()
    {
        return $this->orgInfo;
    }

    /**
     * Sets a new orgInfo
     *
     * Информация об организации
     *
     * @param \common\models\sbbolxml\response\AdmOperationType\OrgInfoAType $orgInfo
     * @return static
     */
    public function setOrgInfo(\common\models\sbbolxml\response\AdmOperationType\OrgInfoAType $orgInfo)
    {
        $this->orgInfo = $orgInfo;
        return $this;
    }

    /**
     * Gets as cashierInfo
     *
     * Информация о вносителе средств
     *
     * @return \common\models\sbbolxml\response\AdmOperationType\CashierInfoAType
     */
    public function getCashierInfo()
    {
        return $this->cashierInfo;
    }

    /**
     * Sets a new cashierInfo
     *
     * Информация о вносителе средств
     *
     * @param \common\models\sbbolxml\response\AdmOperationType\CashierInfoAType $cashierInfo
     * @return static
     */
    public function setCashierInfo(\common\models\sbbolxml\response\AdmOperationType\CashierInfoAType $cashierInfo)
    {
        $this->cashierInfo = $cashierInfo;
        return $this;
    }

    /**
     * Gets as operationInfo
     *
     * Информация об операции
     *
     * @return \common\models\sbbolxml\response\AdmOperationType\OperationInfoAType
     */
    public function getOperationInfo()
    {
        return $this->operationInfo;
    }

    /**
     * Sets a new operationInfo
     *
     * Информация об операции
     *
     * @param \common\models\sbbolxml\response\AdmOperationType\OperationInfoAType $operationInfo
     * @return static
     */
    public function setOperationInfo(\common\models\sbbolxml\response\AdmOperationType\OperationInfoAType $operationInfo)
    {
        $this->operationInfo = $operationInfo;
        return $this;
    }

    /**
     * Gets as deviceInfo
     *
     * Информация об устройстве
     *
     * @return \common\models\sbbolxml\response\AdmOperationType\DeviceInfoAType
     */
    public function getDeviceInfo()
    {
        return $this->deviceInfo;
    }

    /**
     * Sets a new deviceInfo
     *
     * Информация об устройстве
     *
     * @param \common\models\sbbolxml\response\AdmOperationType\DeviceInfoAType $deviceInfo
     * @return static
     */
    public function setDeviceInfo(\common\models\sbbolxml\response\AdmOperationType\DeviceInfoAType $deviceInfo)
    {
        $this->deviceInfo = $deviceInfo;
        return $this;
    }


}


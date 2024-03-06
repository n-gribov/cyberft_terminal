<?php

namespace common\models\sbbolxml\response\ListOfEmployeesType;

/**
 * Class representing EmployeesInfoAType
 *
 * Список сотрудников
 */
class EmployeesInfoAType
{

    /**
     * @property \common\models\sbbolxml\response\EmployeeInfoType[] $employeeInfo
     */
    private $employeeInfo = array(
        
    );

    /**
     * Adds as employeeInfo
     *
     * @return static
     * @param \common\models\sbbolxml\response\EmployeeInfoType $employeeInfo
     */
    public function addToEmployeeInfo(\common\models\sbbolxml\response\EmployeeInfoType $employeeInfo)
    {
        $this->employeeInfo[] = $employeeInfo;
        return $this;
    }

    /**
     * isset employeeInfo
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetEmployeeInfo($index)
    {
        return isset($this->employeeInfo[$index]);
    }

    /**
     * unset employeeInfo
     *
     * @param scalar $index
     * @return void
     */
    public function unsetEmployeeInfo($index)
    {
        unset($this->employeeInfo[$index]);
    }

    /**
     * Gets as employeeInfo
     *
     * @return \common\models\sbbolxml\response\EmployeeInfoType[]
     */
    public function getEmployeeInfo()
    {
        return $this->employeeInfo;
    }

    /**
     * Sets a new employeeInfo
     *
     * @param \common\models\sbbolxml\response\EmployeeInfoType[] $employeeInfo
     * @return static
     */
    public function setEmployeeInfo(array $employeeInfo)
    {
        $this->employeeInfo = $employeeInfo;
        return $this;
    }


}


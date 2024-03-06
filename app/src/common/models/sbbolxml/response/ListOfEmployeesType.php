<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing ListOfEmployeesType
 *
 * Cпискок сотрудников из СББОЛ
 * XSD Type: ListOfEmployees
 */
class ListOfEmployeesType
{

    /**
     * @property \common\models\sbbolxml\response\EmployeeInfoType[] $employeesInfo
     */
    private $employeesInfo = null;

    /**
     * @property string[] $requestIdList
     */
    private $requestIdList = null;

    /**
     * Список тикетов для запроса следующих частей списка сотрудников
     *
     * @property integer $total
     */
    private $total = null;

    /**
     * @property \common\models\sbbolxml\response\DigitalSignType $sign
     */
    private $sign = null;

    /**
     * Adds as employeeInfo
     *
     * @return static
     * @param \common\models\sbbolxml\response\EmployeeInfoType $employeeInfo
     */
    public function addToEmployeesInfo(\common\models\sbbolxml\response\EmployeeInfoType $employeeInfo)
    {
        $this->employeesInfo[] = $employeeInfo;
        return $this;
    }

    /**
     * isset employeesInfo
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetEmployeesInfo($index)
    {
        return isset($this->employeesInfo[$index]);
    }

    /**
     * unset employeesInfo
     *
     * @param scalar $index
     * @return void
     */
    public function unsetEmployeesInfo($index)
    {
        unset($this->employeesInfo[$index]);
    }

    /**
     * Gets as employeesInfo
     *
     * @return \common\models\sbbolxml\response\EmployeeInfoType[]
     */
    public function getEmployeesInfo()
    {
        return $this->employeesInfo;
    }

    /**
     * Sets a new employeesInfo
     *
     * @param \common\models\sbbolxml\response\EmployeeInfoType[] $employeesInfo
     * @return static
     */
    public function setEmployeesInfo(array $employeesInfo)
    {
        $this->employeesInfo = $employeesInfo;
        return $this;
    }

    /**
     * Adds as requestId
     *
     * @return static
     * @param string $requestId
     */
    public function addToRequestIdList($requestId)
    {
        $this->requestIdList[] = $requestId;
        return $this;
    }

    /**
     * isset requestIdList
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetRequestIdList($index)
    {
        return isset($this->requestIdList[$index]);
    }

    /**
     * unset requestIdList
     *
     * @param scalar $index
     * @return void
     */
    public function unsetRequestIdList($index)
    {
        unset($this->requestIdList[$index]);
    }

    /**
     * Gets as requestIdList
     *
     * @return string[]
     */
    public function getRequestIdList()
    {
        return $this->requestIdList;
    }

    /**
     * Sets a new requestIdList
     *
     * @param string $requestIdList
     * @return static
     */
    public function setRequestIdList(array $requestIdList)
    {
        $this->requestIdList = $requestIdList;
        return $this;
    }

    /**
     * Gets as total
     *
     * Список тикетов для запроса следующих частей списка сотрудников
     *
     * @return integer
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * Sets a new total
     *
     * Список тикетов для запроса следующих частей списка сотрудников
     *
     * @param integer $total
     * @return static
     */
    public function setTotal($total)
    {
        $this->total = $total;
        return $this;
    }

    /**
     * Gets as sign
     *
     * @return \common\models\sbbolxml\response\DigitalSignType
     */
    public function getSign()
    {
        return $this->sign;
    }

    /**
     * Sets a new sign
     *
     * @param \common\models\sbbolxml\response\DigitalSignType $sign
     * @return static
     */
    public function setSign(\common\models\sbbolxml\response\DigitalSignType $sign)
    {
        $this->sign = $sign;
        return $this;
    }


}


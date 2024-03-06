<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing RegOfFiredEmployeesType
 *
 *
 * XSD Type: RegOfFiredEmployees
 */
class RegOfFiredEmployeesType extends DocBaseType
{

    /**
     * Общие реквизиты документа
     *
     * @property \common\models\sbbolxml\request\RegOfFiredEmployeesType\DocDataAType $docData
     */
    private $docData = null;

    /**
     * Уполномоченный сотрудник организации клиента
     *
     * @property \common\models\sbbolxml\request\RegOfFiredEmployeesType\AuthPers2AType $authPers2
     */
    private $authPers2 = null;

    /**
     * @property \common\models\sbbolxml\request\RegOfFiredEmployeesType\OrgDataAType $orgData
     */
    private $orgData = null;

    /**
     * Список содержит общую информацию по сотрудникам, статус которых требуется изменить
     *  на "уволен"
     *
     * @property \common\models\sbbolxml\request\RegOfFiredEmployeesType\ListOFiredEmployeesAType\FiredEmployeesInfoAType[] $listOFiredEmployees
     */
    private $listOFiredEmployees = null;

    /**
     * Итоговое количество сотрудниокв
     *
     * @property integer $total
     */
    private $total = null;

    /**
     * Gets as docData
     *
     * Общие реквизиты документа
     *
     * @return \common\models\sbbolxml\request\RegOfFiredEmployeesType\DocDataAType
     */
    public function getDocData()
    {
        return $this->docData;
    }

    /**
     * Sets a new docData
     *
     * Общие реквизиты документа
     *
     * @param \common\models\sbbolxml\request\RegOfFiredEmployeesType\DocDataAType $docData
     * @return static
     */
    public function setDocData(\common\models\sbbolxml\request\RegOfFiredEmployeesType\DocDataAType $docData)
    {
        $this->docData = $docData;
        return $this;
    }

    /**
     * Gets as authPers2
     *
     * Уполномоченный сотрудник организации клиента
     *
     * @return \common\models\sbbolxml\request\RegOfFiredEmployeesType\AuthPers2AType
     */
    public function getAuthPers2()
    {
        return $this->authPers2;
    }

    /**
     * Sets a new authPers2
     *
     * Уполномоченный сотрудник организации клиента
     *
     * @param \common\models\sbbolxml\request\RegOfFiredEmployeesType\AuthPers2AType $authPers2
     * @return static
     */
    public function setAuthPers2(\common\models\sbbolxml\request\RegOfFiredEmployeesType\AuthPers2AType $authPers2)
    {
        $this->authPers2 = $authPers2;
        return $this;
    }

    /**
     * Gets as orgData
     *
     * @return \common\models\sbbolxml\request\RegOfFiredEmployeesType\OrgDataAType
     */
    public function getOrgData()
    {
        return $this->orgData;
    }

    /**
     * Sets a new orgData
     *
     * @param \common\models\sbbolxml\request\RegOfFiredEmployeesType\OrgDataAType $orgData
     * @return static
     */
    public function setOrgData(\common\models\sbbolxml\request\RegOfFiredEmployeesType\OrgDataAType $orgData)
    {
        $this->orgData = $orgData;
        return $this;
    }

    /**
     * Adds as firedEmployeesInfo
     *
     * Список содержит общую информацию по сотрудникам, статус которых требуется изменить
     *  на "уволен"
     *
     * @return static
     * @param \common\models\sbbolxml\request\RegOfFiredEmployeesType\ListOFiredEmployeesAType\FiredEmployeesInfoAType $firedEmployeesInfo
     */
    public function addToListOFiredEmployees(\common\models\sbbolxml\request\RegOfFiredEmployeesType\ListOFiredEmployeesAType\FiredEmployeesInfoAType $firedEmployeesInfo)
    {
        $this->listOFiredEmployees[] = $firedEmployeesInfo;
        return $this;
    }

    /**
     * isset listOFiredEmployees
     *
     * Список содержит общую информацию по сотрудникам, статус которых требуется изменить
     *  на "уволен"
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetListOFiredEmployees($index)
    {
        return isset($this->listOFiredEmployees[$index]);
    }

    /**
     * unset listOFiredEmployees
     *
     * Список содержит общую информацию по сотрудникам, статус которых требуется изменить
     *  на "уволен"
     *
     * @param scalar $index
     * @return void
     */
    public function unsetListOFiredEmployees($index)
    {
        unset($this->listOFiredEmployees[$index]);
    }

    /**
     * Gets as listOFiredEmployees
     *
     * Список содержит общую информацию по сотрудникам, статус которых требуется изменить
     *  на "уволен"
     *
     * @return \common\models\sbbolxml\request\RegOfFiredEmployeesType\ListOFiredEmployeesAType\FiredEmployeesInfoAType[]
     */
    public function getListOFiredEmployees()
    {
        return $this->listOFiredEmployees;
    }

    /**
     * Sets a new listOFiredEmployees
     *
     * Список содержит общую информацию по сотрудникам, статус которых требуется изменить
     *  на "уволен"
     *
     * @param \common\models\sbbolxml\request\RegOfFiredEmployeesType\ListOFiredEmployeesAType\FiredEmployeesInfoAType[] $listOFiredEmployees
     * @return static
     */
    public function setListOFiredEmployees(array $listOFiredEmployees)
    {
        $this->listOFiredEmployees = $listOFiredEmployees;
        return $this;
    }

    /**
     * Gets as total
     *
     * Итоговое количество сотрудниокв
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
     * Итоговое количество сотрудниокв
     *
     * @param integer $total
     * @return static
     */
    public function setTotal($total)
    {
        $this->total = $total;
        return $this;
    }


}


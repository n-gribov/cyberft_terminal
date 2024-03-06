<?php

namespace common\models\sbbolxml\request\RegOfFiredEmployeesType;

/**
 * Class representing OrgDataAType
 */
class OrgDataAType
{

    /**
     * КПП клиента
     *
     * @property string $kpp
     */
    private $kpp = null;

    /**
     * ОКАТО клиента
     *
     * @property string $okato
     */
    private $okato = null;

    /**
     * ИНН клиента
     *
     * @property string $inn
     */
    private $inn = null;

    /**
     * Наименование организации клиента (сокращенное наименование - как в платежных руб. документах)
     *
     * @property string $orgName
     */
    private $orgName = null;

    /**
     * ОКПО клиента
     *
     * @property string $okpo
     */
    private $okpo = null;

    /**
     * ОГРН клиента
     *
     * @property string $orgOGRN
     */
    private $orgOGRN = null;

    /**
     * Реквизиты расчётного счёта клиента
     *
     * @property \common\models\sbbolxml\request\AccNumBicType $account
     */
    private $account = null;

    /**
     * Реквизиты зарплатного договра
     *
     * @property \common\models\sbbolxml\request\RegOfFiredEmployeesType\OrgDataAType\SalaryContractAType $salaryContract
     */
    private $salaryContract = null;

    /**
     * Gets as kpp
     *
     * КПП клиента
     *
     * @return string
     */
    public function getKpp()
    {
        return $this->kpp;
    }

    /**
     * Sets a new kpp
     *
     * КПП клиента
     *
     * @param string $kpp
     * @return static
     */
    public function setKpp($kpp)
    {
        $this->kpp = $kpp;
        return $this;
    }

    /**
     * Gets as okato
     *
     * ОКАТО клиента
     *
     * @return string
     */
    public function getOkato()
    {
        return $this->okato;
    }

    /**
     * Sets a new okato
     *
     * ОКАТО клиента
     *
     * @param string $okato
     * @return static
     */
    public function setOkato($okato)
    {
        $this->okato = $okato;
        return $this;
    }

    /**
     * Gets as inn
     *
     * ИНН клиента
     *
     * @return string
     */
    public function getInn()
    {
        return $this->inn;
    }

    /**
     * Sets a new inn
     *
     * ИНН клиента
     *
     * @param string $inn
     * @return static
     */
    public function setInn($inn)
    {
        $this->inn = $inn;
        return $this;
    }

    /**
     * Gets as orgName
     *
     * Наименование организации клиента (сокращенное наименование - как в платежных руб. документах)
     *
     * @return string
     */
    public function getOrgName()
    {
        return $this->orgName;
    }

    /**
     * Sets a new orgName
     *
     * Наименование организации клиента (сокращенное наименование - как в платежных руб. документах)
     *
     * @param string $orgName
     * @return static
     */
    public function setOrgName($orgName)
    {
        $this->orgName = $orgName;
        return $this;
    }

    /**
     * Gets as okpo
     *
     * ОКПО клиента
     *
     * @return string
     */
    public function getOkpo()
    {
        return $this->okpo;
    }

    /**
     * Sets a new okpo
     *
     * ОКПО клиента
     *
     * @param string $okpo
     * @return static
     */
    public function setOkpo($okpo)
    {
        $this->okpo = $okpo;
        return $this;
    }

    /**
     * Gets as orgOGRN
     *
     * ОГРН клиента
     *
     * @return string
     */
    public function getOrgOGRN()
    {
        return $this->orgOGRN;
    }

    /**
     * Sets a new orgOGRN
     *
     * ОГРН клиента
     *
     * @param string $orgOGRN
     * @return static
     */
    public function setOrgOGRN($orgOGRN)
    {
        $this->orgOGRN = $orgOGRN;
        return $this;
    }

    /**
     * Gets as account
     *
     * Реквизиты расчётного счёта клиента
     *
     * @return \common\models\sbbolxml\request\AccNumBicType
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * Sets a new account
     *
     * Реквизиты расчётного счёта клиента
     *
     * @param \common\models\sbbolxml\request\AccNumBicType $account
     * @return static
     */
    public function setAccount(\common\models\sbbolxml\request\AccNumBicType $account)
    {
        $this->account = $account;
        return $this;
    }

    /**
     * Gets as salaryContract
     *
     * Реквизиты зарплатного договра
     *
     * @return \common\models\sbbolxml\request\RegOfFiredEmployeesType\OrgDataAType\SalaryContractAType
     */
    public function getSalaryContract()
    {
        return $this->salaryContract;
    }

    /**
     * Sets a new salaryContract
     *
     * Реквизиты зарплатного договра
     *
     * @param \common\models\sbbolxml\request\RegOfFiredEmployeesType\OrgDataAType\SalaryContractAType $salaryContract
     * @return static
     */
    public function setSalaryContract(\common\models\sbbolxml\request\RegOfFiredEmployeesType\OrgDataAType\SalaryContractAType $salaryContract)
    {
        $this->salaryContract = $salaryContract;
        return $this;
    }


}


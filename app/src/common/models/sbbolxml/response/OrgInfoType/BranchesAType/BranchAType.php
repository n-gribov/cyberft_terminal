<?php

namespace common\models\sbbolxml\response\OrgInfoType\BranchesAType;

/**
 * Class representing BranchAType
 */
class BranchAType
{

    /**
     * БИК банка
     *
     * @property string $bic
     */
    private $bic = null;

    /**
     * Идентификатор подразделения банка
     *
     * @property string $branchId
     */
    private $branchId = null;

    /**
     * Наименование подразделения банка, в котором обслуживается
     *  организация
     *
     * @property string $bankName
     */
    private $bankName = null;

    /**
     * Идентификатор вышестоящего (родительского) подразделения
     *
     * @property string $parentId
     */
    private $parentId = null;

    /**
     * Адреса подразделения банка
     *
     * @property \common\models\sbbolxml\response\OrgInfoType\BranchesAType\BranchAType\AddressesAType\AddressAType[] $addresses
     */
    private $addresses = null;

    /**
     * Параметры подразделения банка
     *
     * @property \common\models\sbbolxml\response\OrgInfoType\BranchesAType\BranchAType\ParamsAType\ParamAType[] $params
     */
    private $params = null;

    /**
     * Gets as bic
     *
     * БИК банка
     *
     * @return string
     */
    public function getBic()
    {
        return $this->bic;
    }

    /**
     * Sets a new bic
     *
     * БИК банка
     *
     * @param string $bic
     * @return static
     */
    public function setBic($bic)
    {
        $this->bic = $bic;
        return $this;
    }

    /**
     * Gets as branchId
     *
     * Идентификатор подразделения банка
     *
     * @return string
     */
    public function getBranchId()
    {
        return $this->branchId;
    }

    /**
     * Sets a new branchId
     *
     * Идентификатор подразделения банка
     *
     * @param string $branchId
     * @return static
     */
    public function setBranchId($branchId)
    {
        $this->branchId = $branchId;
        return $this;
    }

    /**
     * Gets as bankName
     *
     * Наименование подразделения банка, в котором обслуживается
     *  организация
     *
     * @return string
     */
    public function getBankName()
    {
        return $this->bankName;
    }

    /**
     * Sets a new bankName
     *
     * Наименование подразделения банка, в котором обслуживается
     *  организация
     *
     * @param string $bankName
     * @return static
     */
    public function setBankName($bankName)
    {
        $this->bankName = $bankName;
        return $this;
    }

    /**
     * Gets as parentId
     *
     * Идентификатор вышестоящего (родительского) подразделения
     *
     * @return string
     */
    public function getParentId()
    {
        return $this->parentId;
    }

    /**
     * Sets a new parentId
     *
     * Идентификатор вышестоящего (родительского) подразделения
     *
     * @param string $parentId
     * @return static
     */
    public function setParentId($parentId)
    {
        $this->parentId = $parentId;
        return $this;
    }

    /**
     * Adds as address
     *
     * Адреса подразделения банка
     *
     * @return static
     * @param \common\models\sbbolxml\response\OrgInfoType\BranchesAType\BranchAType\AddressesAType\AddressAType $address
     */
    public function addToAddresses(\common\models\sbbolxml\response\OrgInfoType\BranchesAType\BranchAType\AddressesAType\AddressAType $address)
    {
        $this->addresses[] = $address;
        return $this;
    }

    /**
     * isset addresses
     *
     * Адреса подразделения банка
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetAddresses($index)
    {
        return isset($this->addresses[$index]);
    }

    /**
     * unset addresses
     *
     * Адреса подразделения банка
     *
     * @param scalar $index
     * @return void
     */
    public function unsetAddresses($index)
    {
        unset($this->addresses[$index]);
    }

    /**
     * Gets as addresses
     *
     * Адреса подразделения банка
     *
     * @return \common\models\sbbolxml\response\OrgInfoType\BranchesAType\BranchAType\AddressesAType\AddressAType[]
     */
    public function getAddresses()
    {
        return $this->addresses;
    }

    /**
     * Sets a new addresses
     *
     * Адреса подразделения банка
     *
     * @param \common\models\sbbolxml\response\OrgInfoType\BranchesAType\BranchAType\AddressesAType\AddressAType[] $addresses
     * @return static
     */
    public function setAddresses(array $addresses)
    {
        $this->addresses = $addresses;
        return $this;
    }

    /**
     * Adds as param
     *
     * Параметры подразделения банка
     *
     * @return static
     * @param \common\models\sbbolxml\response\OrgInfoType\BranchesAType\BranchAType\ParamsAType\ParamAType $param
     */
    public function addToParams(\common\models\sbbolxml\response\OrgInfoType\BranchesAType\BranchAType\ParamsAType\ParamAType $param)
    {
        $this->params[] = $param;
        return $this;
    }

    /**
     * isset params
     *
     * Параметры подразделения банка
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetParams($index)
    {
        return isset($this->params[$index]);
    }

    /**
     * unset params
     *
     * Параметры подразделения банка
     *
     * @param scalar $index
     * @return void
     */
    public function unsetParams($index)
    {
        unset($this->params[$index]);
    }

    /**
     * Gets as params
     *
     * Параметры подразделения банка
     *
     * @return \common\models\sbbolxml\response\OrgInfoType\BranchesAType\BranchAType\ParamsAType\ParamAType[]
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * Sets a new params
     *
     * Параметры подразделения банка
     *
     * @param \common\models\sbbolxml\response\OrgInfoType\BranchesAType\BranchAType\ParamsAType\ParamAType[] $params
     * @return static
     */
    public function setParams(array $params)
    {
        $this->params = $params;
        return $this;
    }


}


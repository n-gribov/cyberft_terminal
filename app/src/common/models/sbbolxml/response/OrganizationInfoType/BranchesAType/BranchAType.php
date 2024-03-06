<?php

namespace common\models\sbbolxml\response\OrganizationInfoType\BranchesAType;

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
     * ОГРН
     *
     * @property string $ogrn
     */
    private $ogrn = null;

    /**
     * Дата регистрации
     *
     * @property \DateTime $regDate
     */
    private $regDate = null;

    /**
     * ИНН
     *
     * @property string $inn
     */
    private $inn = null;

    /**
     * КПП
     *
     * @property string $kpp
     */
    private $kpp = null;

    /**
     * Регистрационный номер кредитной организации
     *
     * @property string $regNum
     */
    private $regNum = null;

    /**
     * Регистрационный номер филиала кредитной организации
     *
     * @property string $regFilialNum
     */
    private $regFilialNum = null;

    /**
     * Признак блокировки Подразделения банка
     *  1 - заблокировано
     *  0 - не заблокировано
     *
     * @property boolean $blocked
     */
    private $blocked = null;

    /**
     * Идентификатор подразделения банка
     *
     * @property string $branchId
     */
    private $branchId = null;

    /**
     * Системное имя подразделения банка
     *
     * @property string $systemName
     */
    private $systemName = null;

    /**
     * Краткое наименование подразделения банка
     *
     * @property string $shortName
     */
    private $shortName = null;

    /**
     * Наименование подразделения банка, в котором обслуживается
     *  организация
     *  (сокращенное наименование)
     *
     * @property string $bankName
     */
    private $bankName = null;

    /**
     * Тип подразделения банка
     *
     * @property string $branchType
     */
    private $branchType = null;

    /**
     * Адрес сервера обновления версий
     *
     * @property string $updateServerAdress
     */
    private $updateServerAdress = null;

    /**
     * Описание адреса сервера обновления версий
     *
     * @property string $updateServerAdressDesc
     */
    private $updateServerAdressDesc = null;

    /**
     * Идентификатор вышестоящего (родительского) подразделения
     *
     * @property string $parentId
     */
    private $parentId = null;

    /**
     * Адреса подразделения банка
     *
     * @property \common\models\sbbolxml\response\OrganizationInfoType\BranchesAType\BranchAType\AddressesAType\AddressAType[] $addresses
     */
    private $addresses = null;

    /**
     * Реквизиты подразделения банка
     *
     * @property \common\models\sbbolxml\response\OrganizationInfoType\BranchesAType\BranchAType\BranchDataAType $branchData
     */
    private $branchData = null;

    /**
     * @property \common\models\sbbolxml\response\OrganizationInfoType\BranchesAType\BranchAType\ContactsAType $contacts
     */
    private $contacts = null;

    /**
     * Параметры подразделения банка
     *
     * @property \common\models\sbbolxml\response\OrganizationInfoType\BranchesAType\BranchAType\ParamsAType\ParamAType[] $params
     */
    private $params = null;

    /**
     * Услуги
     *
     * @property \common\models\sbbolxml\response\OrganizationInfoType\BranchesAType\BranchAType\ServicePacksAType\ServicePackAType[] $servicePacks
     */
    private $servicePacks = null;

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
     * Gets as ogrn
     *
     * ОГРН
     *
     * @return string
     */
    public function getOgrn()
    {
        return $this->ogrn;
    }

    /**
     * Sets a new ogrn
     *
     * ОГРН
     *
     * @param string $ogrn
     * @return static
     */
    public function setOgrn($ogrn)
    {
        $this->ogrn = $ogrn;
        return $this;
    }

    /**
     * Gets as regDate
     *
     * Дата регистрации
     *
     * @return \DateTime
     */
    public function getRegDate()
    {
        return $this->regDate;
    }

    /**
     * Sets a new regDate
     *
     * Дата регистрации
     *
     * @param \DateTime $regDate
     * @return static
     */
    public function setRegDate(\DateTime $regDate)
    {
        $this->regDate = $regDate;
        return $this;
    }

    /**
     * Gets as inn
     *
     * ИНН
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
     * ИНН
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
     * Gets as kpp
     *
     * КПП
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
     * КПП
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
     * Gets as regNum
     *
     * Регистрационный номер кредитной организации
     *
     * @return string
     */
    public function getRegNum()
    {
        return $this->regNum;
    }

    /**
     * Sets a new regNum
     *
     * Регистрационный номер кредитной организации
     *
     * @param string $regNum
     * @return static
     */
    public function setRegNum($regNum)
    {
        $this->regNum = $regNum;
        return $this;
    }

    /**
     * Gets as regFilialNum
     *
     * Регистрационный номер филиала кредитной организации
     *
     * @return string
     */
    public function getRegFilialNum()
    {
        return $this->regFilialNum;
    }

    /**
     * Sets a new regFilialNum
     *
     * Регистрационный номер филиала кредитной организации
     *
     * @param string $regFilialNum
     * @return static
     */
    public function setRegFilialNum($regFilialNum)
    {
        $this->regFilialNum = $regFilialNum;
        return $this;
    }

    /**
     * Gets as blocked
     *
     * Признак блокировки Подразделения банка
     *  1 - заблокировано
     *  0 - не заблокировано
     *
     * @return boolean
     */
    public function getBlocked()
    {
        return $this->blocked;
    }

    /**
     * Sets a new blocked
     *
     * Признак блокировки Подразделения банка
     *  1 - заблокировано
     *  0 - не заблокировано
     *
     * @param boolean $blocked
     * @return static
     */
    public function setBlocked($blocked)
    {
        $this->blocked = $blocked;
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
     * Gets as systemName
     *
     * Системное имя подразделения банка
     *
     * @return string
     */
    public function getSystemName()
    {
        return $this->systemName;
    }

    /**
     * Sets a new systemName
     *
     * Системное имя подразделения банка
     *
     * @param string $systemName
     * @return static
     */
    public function setSystemName($systemName)
    {
        $this->systemName = $systemName;
        return $this;
    }

    /**
     * Gets as shortName
     *
     * Краткое наименование подразделения банка
     *
     * @return string
     */
    public function getShortName()
    {
        return $this->shortName;
    }

    /**
     * Sets a new shortName
     *
     * Краткое наименование подразделения банка
     *
     * @param string $shortName
     * @return static
     */
    public function setShortName($shortName)
    {
        $this->shortName = $shortName;
        return $this;
    }

    /**
     * Gets as bankName
     *
     * Наименование подразделения банка, в котором обслуживается
     *  организация
     *  (сокращенное наименование)
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
     *  (сокращенное наименование)
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
     * Gets as branchType
     *
     * Тип подразделения банка
     *
     * @return string
     */
    public function getBranchType()
    {
        return $this->branchType;
    }

    /**
     * Sets a new branchType
     *
     * Тип подразделения банка
     *
     * @param string $branchType
     * @return static
     */
    public function setBranchType($branchType)
    {
        $this->branchType = $branchType;
        return $this;
    }

    /**
     * Gets as updateServerAdress
     *
     * Адрес сервера обновления версий
     *
     * @return string
     */
    public function getUpdateServerAdress()
    {
        return $this->updateServerAdress;
    }

    /**
     * Sets a new updateServerAdress
     *
     * Адрес сервера обновления версий
     *
     * @param string $updateServerAdress
     * @return static
     */
    public function setUpdateServerAdress($updateServerAdress)
    {
        $this->updateServerAdress = $updateServerAdress;
        return $this;
    }

    /**
     * Gets as updateServerAdressDesc
     *
     * Описание адреса сервера обновления версий
     *
     * @return string
     */
    public function getUpdateServerAdressDesc()
    {
        return $this->updateServerAdressDesc;
    }

    /**
     * Sets a new updateServerAdressDesc
     *
     * Описание адреса сервера обновления версий
     *
     * @param string $updateServerAdressDesc
     * @return static
     */
    public function setUpdateServerAdressDesc($updateServerAdressDesc)
    {
        $this->updateServerAdressDesc = $updateServerAdressDesc;
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
     * @param \common\models\sbbolxml\response\OrganizationInfoType\BranchesAType\BranchAType\AddressesAType\AddressAType $address
     */
    public function addToAddresses(\common\models\sbbolxml\response\OrganizationInfoType\BranchesAType\BranchAType\AddressesAType\AddressAType $address)
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
     * @return \common\models\sbbolxml\response\OrganizationInfoType\BranchesAType\BranchAType\AddressesAType\AddressAType[]
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
     * @param \common\models\sbbolxml\response\OrganizationInfoType\BranchesAType\BranchAType\AddressesAType\AddressAType[] $addresses
     * @return static
     */
    public function setAddresses(array $addresses)
    {
        $this->addresses = $addresses;
        return $this;
    }

    /**
     * Gets as branchData
     *
     * Реквизиты подразделения банка
     *
     * @return \common\models\sbbolxml\response\OrganizationInfoType\BranchesAType\BranchAType\BranchDataAType
     */
    public function getBranchData()
    {
        return $this->branchData;
    }

    /**
     * Sets a new branchData
     *
     * Реквизиты подразделения банка
     *
     * @param \common\models\sbbolxml\response\OrganizationInfoType\BranchesAType\BranchAType\BranchDataAType $branchData
     * @return static
     */
    public function setBranchData(\common\models\sbbolxml\response\OrganizationInfoType\BranchesAType\BranchAType\BranchDataAType $branchData)
    {
        $this->branchData = $branchData;
        return $this;
    }

    /**
     * Gets as contacts
     *
     * @return \common\models\sbbolxml\response\OrganizationInfoType\BranchesAType\BranchAType\ContactsAType
     */
    public function getContacts()
    {
        return $this->contacts;
    }

    /**
     * Sets a new contacts
     *
     * @param \common\models\sbbolxml\response\OrganizationInfoType\BranchesAType\BranchAType\ContactsAType $contacts
     * @return static
     */
    public function setContacts(\common\models\sbbolxml\response\OrganizationInfoType\BranchesAType\BranchAType\ContactsAType $contacts)
    {
        $this->contacts = $contacts;
        return $this;
    }

    /**
     * Adds as param
     *
     * Параметры подразделения банка
     *
     * @return static
     * @param \common\models\sbbolxml\response\OrganizationInfoType\BranchesAType\BranchAType\ParamsAType\ParamAType $param
     */
    public function addToParams(\common\models\sbbolxml\response\OrganizationInfoType\BranchesAType\BranchAType\ParamsAType\ParamAType $param)
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
     * @return \common\models\sbbolxml\response\OrganizationInfoType\BranchesAType\BranchAType\ParamsAType\ParamAType[]
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
     * @param \common\models\sbbolxml\response\OrganizationInfoType\BranchesAType\BranchAType\ParamsAType\ParamAType[] $params
     * @return static
     */
    public function setParams(array $params)
    {
        $this->params = $params;
        return $this;
    }

    /**
     * Adds as servicePack
     *
     * Услуги
     *
     * @return static
     * @param \common\models\sbbolxml\response\OrganizationInfoType\BranchesAType\BranchAType\ServicePacksAType\ServicePackAType $servicePack
     */
    public function addToServicePacks(\common\models\sbbolxml\response\OrganizationInfoType\BranchesAType\BranchAType\ServicePacksAType\ServicePackAType $servicePack)
    {
        $this->servicePacks[] = $servicePack;
        return $this;
    }

    /**
     * isset servicePacks
     *
     * Услуги
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetServicePacks($index)
    {
        return isset($this->servicePacks[$index]);
    }

    /**
     * unset servicePacks
     *
     * Услуги
     *
     * @param scalar $index
     * @return void
     */
    public function unsetServicePacks($index)
    {
        unset($this->servicePacks[$index]);
    }

    /**
     * Gets as servicePacks
     *
     * Услуги
     *
     * @return \common\models\sbbolxml\response\OrganizationInfoType\BranchesAType\BranchAType\ServicePacksAType\ServicePackAType[]
     */
    public function getServicePacks()
    {
        return $this->servicePacks;
    }

    /**
     * Sets a new servicePacks
     *
     * Услуги
     *
     * @param \common\models\sbbolxml\response\OrganizationInfoType\BranchesAType\BranchAType\ServicePacksAType\ServicePackAType[] $servicePacks
     * @return static
     */
    public function setServicePacks(array $servicePacks)
    {
        $this->servicePacks = $servicePacks;
        return $this;
    }


}


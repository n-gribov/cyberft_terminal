<?php

namespace common\models\sbbolxml\response\OrgInfoType;

/**
 * Class representing OrgDataAType
 */
class OrgDataAType
{

    /**
     * Сокращенное наименование организации
     *
     * @property string $shortName
     */
    private $shortName = null;

    /**
     * Полное наименование для ВК
     *
     * @property string $vkFullName
     */
    private $vkFullName = null;

    /**
     * Идентификатор организации в СББОЛ
     *
     * @property string $orgId
     */
    private $orgId = null;

    /**
     * Счета организации доступные пользователю
     *
     * @property \common\models\sbbolxml\response\AccountRubType[] $accounts
     */
    private $accounts = null;

    /**
     * Новое ИНН
     *
     * @property string $iNN
     */
    private $iNN = null;

    /**
     * Новое
     *  Полное наименование
     *
     * @property string $fullName
     */
    private $fullName = null;

    /**
     * Новое
     *  Орг-правовую форму (текст, например, ООО)
     *
     * @property string $orgForm
     */
    private $orgForm = null;

    /**
     * Новое
     *  Тип (рез/нерезидент) (текст, например, резидент –
     *  физ.лицо)
     *
     * @property string $stateCode
     */
    private $stateCode = null;

    /**
     * Признак рез/нерезидент
     *
     * @property boolean $stateType
     */
    private $stateType = null;

    /**
     * ОГРН
     *
     * @property string $oGRN
     */
    private $oGRN = null;

    /**
     * Дата регистрации
     *
     * @property \DateTime $dateOGRN
     */
    private $dateOGRN = null;

    /**
     * ОКПО
     *
     * @property string $oKPO
     */
    private $oKPO = null;

    /**
     * ОКАТО
     *
     * @property string $oKATO
     */
    private $oKATO = null;

    /**
     * Новое Адреса (все заведенные для организации)
     *
     * @property \common\models\sbbolxml\response\OrgInfoType\OrgDataAType\AddressesAType\AddressAType[] $addresses
     */
    private $addresses = null;

    /**
     * Список подтвержденных корреспондентов
     *
     * @property \common\models\sbbolxml\response\OrgInfoType\OrgDataAType\CorrespondentsAType\CorrespondentAType[] $correspondents
     */
    private $correspondents = null;

    /**
     * Прочие данные по организации
     *
     * @property \common\models\sbbolxml\response\OrgInfoType\OrgDataAType\OtherOrgDataAType $otherOrgData
     */
    private $otherOrgData = null;

    /**
     * Gets as shortName
     *
     * Сокращенное наименование организации
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
     * Сокращенное наименование организации
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
     * Gets as vkFullName
     *
     * Полное наименование для ВК
     *
     * @return string
     */
    public function getVkFullName()
    {
        return $this->vkFullName;
    }

    /**
     * Sets a new vkFullName
     *
     * Полное наименование для ВК
     *
     * @param string $vkFullName
     * @return static
     */
    public function setVkFullName($vkFullName)
    {
        $this->vkFullName = $vkFullName;
        return $this;
    }

    /**
     * Gets as orgId
     *
     * Идентификатор организации в СББОЛ
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
     * Идентификатор организации в СББОЛ
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
     * Adds as account
     *
     * Счета организации доступные пользователю
     *
     * @return static
     * @param \common\models\sbbolxml\response\AccountRubType $account
     */
    public function addToAccounts(\common\models\sbbolxml\response\AccountRubType $account)
    {
        $this->accounts[] = $account;
        return $this;
    }

    /**
     * isset accounts
     *
     * Счета организации доступные пользователю
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetAccounts($index)
    {
        return isset($this->accounts[$index]);
    }

    /**
     * unset accounts
     *
     * Счета организации доступные пользователю
     *
     * @param scalar $index
     * @return void
     */
    public function unsetAccounts($index)
    {
        unset($this->accounts[$index]);
    }

    /**
     * Gets as accounts
     *
     * Счета организации доступные пользователю
     *
     * @return \common\models\sbbolxml\response\AccountRubType[]
     */
    public function getAccounts()
    {
        return $this->accounts;
    }

    /**
     * Sets a new accounts
     *
     * Счета организации доступные пользователю
     *
     * @param \common\models\sbbolxml\response\AccountRubType[] $accounts
     * @return static
     */
    public function setAccounts(array $accounts)
    {
        $this->accounts = $accounts;
        return $this;
    }

    /**
     * Gets as iNN
     *
     * Новое ИНН
     *
     * @return string
     */
    public function getINN()
    {
        return $this->iNN;
    }

    /**
     * Sets a new iNN
     *
     * Новое ИНН
     *
     * @param string $iNN
     * @return static
     */
    public function setINN($iNN)
    {
        $this->iNN = $iNN;
        return $this;
    }

    /**
     * Gets as fullName
     *
     * Новое
     *  Полное наименование
     *
     * @return string
     */
    public function getFullName()
    {
        return $this->fullName;
    }

    /**
     * Sets a new fullName
     *
     * Новое
     *  Полное наименование
     *
     * @param string $fullName
     * @return static
     */
    public function setFullName($fullName)
    {
        $this->fullName = $fullName;
        return $this;
    }

    /**
     * Gets as orgForm
     *
     * Новое
     *  Орг-правовую форму (текст, например, ООО)
     *
     * @return string
     */
    public function getOrgForm()
    {
        return $this->orgForm;
    }

    /**
     * Sets a new orgForm
     *
     * Новое
     *  Орг-правовую форму (текст, например, ООО)
     *
     * @param string $orgForm
     * @return static
     */
    public function setOrgForm($orgForm)
    {
        $this->orgForm = $orgForm;
        return $this;
    }

    /**
     * Gets as stateCode
     *
     * Новое
     *  Тип (рез/нерезидент) (текст, например, резидент –
     *  физ.лицо)
     *
     * @return string
     */
    public function getStateCode()
    {
        return $this->stateCode;
    }

    /**
     * Sets a new stateCode
     *
     * Новое
     *  Тип (рез/нерезидент) (текст, например, резидент –
     *  физ.лицо)
     *
     * @param string $stateCode
     * @return static
     */
    public function setStateCode($stateCode)
    {
        $this->stateCode = $stateCode;
        return $this;
    }

    /**
     * Gets as stateType
     *
     * Признак рез/нерезидент
     *
     * @return boolean
     */
    public function getStateType()
    {
        return $this->stateType;
    }

    /**
     * Sets a new stateType
     *
     * Признак рез/нерезидент
     *
     * @param boolean $stateType
     * @return static
     */
    public function setStateType($stateType)
    {
        $this->stateType = $stateType;
        return $this;
    }

    /**
     * Gets as oGRN
     *
     * ОГРН
     *
     * @return string
     */
    public function getOGRN()
    {
        return $this->oGRN;
    }

    /**
     * Sets a new oGRN
     *
     * ОГРН
     *
     * @param string $oGRN
     * @return static
     */
    public function setOGRN($oGRN)
    {
        $this->oGRN = $oGRN;
        return $this;
    }

    /**
     * Gets as dateOGRN
     *
     * Дата регистрации
     *
     * @return \DateTime
     */
    public function getDateOGRN()
    {
        return $this->dateOGRN;
    }

    /**
     * Sets a new dateOGRN
     *
     * Дата регистрации
     *
     * @param \DateTime $dateOGRN
     * @return static
     */
    public function setDateOGRN(\DateTime $dateOGRN)
    {
        $this->dateOGRN = $dateOGRN;
        return $this;
    }

    /**
     * Gets as oKPO
     *
     * ОКПО
     *
     * @return string
     */
    public function getOKPO()
    {
        return $this->oKPO;
    }

    /**
     * Sets a new oKPO
     *
     * ОКПО
     *
     * @param string $oKPO
     * @return static
     */
    public function setOKPO($oKPO)
    {
        $this->oKPO = $oKPO;
        return $this;
    }

    /**
     * Gets as oKATO
     *
     * ОКАТО
     *
     * @return string
     */
    public function getOKATO()
    {
        return $this->oKATO;
    }

    /**
     * Sets a new oKATO
     *
     * ОКАТО
     *
     * @param string $oKATO
     * @return static
     */
    public function setOKATO($oKATO)
    {
        $this->oKATO = $oKATO;
        return $this;
    }

    /**
     * Adds as address
     *
     * Новое Адреса (все заведенные для организации)
     *
     * @return static
     * @param \common\models\sbbolxml\response\OrgInfoType\OrgDataAType\AddressesAType\AddressAType $address
     */
    public function addToAddresses(\common\models\sbbolxml\response\OrgInfoType\OrgDataAType\AddressesAType\AddressAType $address)
    {
        $this->addresses[] = $address;
        return $this;
    }

    /**
     * isset addresses
     *
     * Новое Адреса (все заведенные для организации)
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
     * Новое Адреса (все заведенные для организации)
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
     * Новое Адреса (все заведенные для организации)
     *
     * @return \common\models\sbbolxml\response\OrgInfoType\OrgDataAType\AddressesAType\AddressAType[]
     */
    public function getAddresses()
    {
        return $this->addresses;
    }

    /**
     * Sets a new addresses
     *
     * Новое Адреса (все заведенные для организации)
     *
     * @param \common\models\sbbolxml\response\OrgInfoType\OrgDataAType\AddressesAType\AddressAType[] $addresses
     * @return static
     */
    public function setAddresses(array $addresses)
    {
        $this->addresses = $addresses;
        return $this;
    }

    /**
     * Adds as correspondent
     *
     * Список подтвержденных корреспондентов
     *
     * @return static
     * @param \common\models\sbbolxml\response\OrgInfoType\OrgDataAType\CorrespondentsAType\CorrespondentAType $correspondent
     */
    public function addToCorrespondents(\common\models\sbbolxml\response\OrgInfoType\OrgDataAType\CorrespondentsAType\CorrespondentAType $correspondent)
    {
        $this->correspondents[] = $correspondent;
        return $this;
    }

    /**
     * isset correspondents
     *
     * Список подтвержденных корреспондентов
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetCorrespondents($index)
    {
        return isset($this->correspondents[$index]);
    }

    /**
     * unset correspondents
     *
     * Список подтвержденных корреспондентов
     *
     * @param scalar $index
     * @return void
     */
    public function unsetCorrespondents($index)
    {
        unset($this->correspondents[$index]);
    }

    /**
     * Gets as correspondents
     *
     * Список подтвержденных корреспондентов
     *
     * @return \common\models\sbbolxml\response\OrgInfoType\OrgDataAType\CorrespondentsAType\CorrespondentAType[]
     */
    public function getCorrespondents()
    {
        return $this->correspondents;
    }

    /**
     * Sets a new correspondents
     *
     * Список подтвержденных корреспондентов
     *
     * @param \common\models\sbbolxml\response\OrgInfoType\OrgDataAType\CorrespondentsAType\CorrespondentAType[] $correspondents
     * @return static
     */
    public function setCorrespondents(array $correspondents)
    {
        $this->correspondents = $correspondents;
        return $this;
    }

    /**
     * Gets as otherOrgData
     *
     * Прочие данные по организации
     *
     * @return \common\models\sbbolxml\response\OrgInfoType\OrgDataAType\OtherOrgDataAType
     */
    public function getOtherOrgData()
    {
        return $this->otherOrgData;
    }

    /**
     * Sets a new otherOrgData
     *
     * Прочие данные по организации
     *
     * @param \common\models\sbbolxml\response\OrgInfoType\OrgDataAType\OtherOrgDataAType $otherOrgData
     * @return static
     */
    public function setOtherOrgData(\common\models\sbbolxml\response\OrgInfoType\OrgDataAType\OtherOrgDataAType $otherOrgData)
    {
        $this->otherOrgData = $otherOrgData;
        return $this;
    }


}


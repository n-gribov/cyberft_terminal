<?php

namespace common\models\sbbolxml\response\OrganizationInfoType;

/**
 * Class representing OrgDataAType
 */
class OrgDataAType
{

    /**
     * Идентификатор организации в СББОЛ
     *
     * @property string $orgId
     */
    private $orgId = null;

    /**
     * Организационно-правовая форма (текст, например, ООО)
     *
     * @property string $orgForm
     */
    private $orgForm = null;

    /**
     * Сокращенное наименование организации
     *
     * @property string $shortName
     */
    private $shortName = null;

    /**
     * Финансовое наименование организации
     *
     * @property string $financialName
     */
    private $financialName = null;

    /**
     * Полное наименование
     *
     * @property string $fullName
     */
    private $fullName = null;

    /**
     * Полное наименование для ВК
     *
     * @property string $vkFullName
     */
    private $vkFullName = null;

    /**
     * Счета организации доступные пользователю
     *
     * @property \common\models\sbbolxml\response\AccountRubType[] $accounts
     */
    private $accounts = null;

    /**
     * Признак рез/нерезидент
     *
     * @property boolean $stateType
     */
    private $stateType = null;

    /**
     * Тип (рез/нерезидент) (текст, например, резидент – физ.лицо)
     *
     * @property string $stateCode
     */
    private $stateCode = null;

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
     * ИНН/КИО
     *
     * @property string $iNN
     */
    private $iNN = null;

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
     * Подразделение Банка, к которому приписана организация
     *
     * @property string $branchId
     */
    private $branchId = null;

    /**
     * Признак принадлежности организации УФК: 0 – (false), 1 –(true).
     *
     * @property boolean $exportImportUFEBS
     */
    private $exportImportUFEBS = null;

    /**
     * Адреса (все заведенные для организации)
     *
     * @property \common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\AddressesAType\AddressAType[] $addresses
     */
    private $addresses = null;

    /**
     * Подразделения банка, в которых обслуживается организация
     *
     * @property \common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\OrgBranchesAType\OrgBranchAType[] $orgBranches
     */
    private $orgBranches = null;

    /**
     * Прочие данные по организации
     *
     * @property \common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\OtherOrgDataAType $otherOrgData
     */
    private $otherOrgData = null;

    /**
     * Предназначение криптопрофиля
     *  (на что может быть наложена подпись)
     *
     * @property \common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\AuthSignsAType\AuthSignAType[] $authSigns
     */
    private $authSigns = null;

    /**
     * @property \common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\BeneficiarsAType $beneficiars
     */
    private $beneficiars = null;

    /**
     * @property \common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\CorrespondentsAType $correspondents
     */
    private $correspondents = null;

    /**
     * @property string[] $holdingOrgs
     */
    private $holdingOrgs = null;

    /**
     * Верcия данных об организации
     *
     * @property integer $orgDataVersion
     */
    private $orgDataVersion = null;

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
     * Gets as orgForm
     *
     * Организационно-правовая форма (текст, например, ООО)
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
     * Организационно-правовая форма (текст, например, ООО)
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
     * Gets as financialName
     *
     * Финансовое наименование организации
     *
     * @return string
     */
    public function getFinancialName()
    {
        return $this->financialName;
    }

    /**
     * Sets a new financialName
     *
     * Финансовое наименование организации
     *
     * @param string $financialName
     * @return static
     */
    public function setFinancialName($financialName)
    {
        $this->financialName = $financialName;
        return $this;
    }

    /**
     * Gets as fullName
     *
     * Полное наименование
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
     * Полное наименование
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
     * Gets as stateCode
     *
     * Тип (рез/нерезидент) (текст, например, резидент – физ.лицо)
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
     * Тип (рез/нерезидент) (текст, например, резидент – физ.лицо)
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
     * Gets as iNN
     *
     * ИНН/КИО
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
     * ИНН/КИО
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
     * Gets as branchId
     *
     * Подразделение Банка, к которому приписана организация
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
     * Подразделение Банка, к которому приписана организация
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
     * Gets as exportImportUFEBS
     *
     * Признак принадлежности организации УФК: 0 – (false), 1 –(true).
     *
     * @return boolean
     */
    public function getExportImportUFEBS()
    {
        return $this->exportImportUFEBS;
    }

    /**
     * Sets a new exportImportUFEBS
     *
     * Признак принадлежности организации УФК: 0 – (false), 1 –(true).
     *
     * @param boolean $exportImportUFEBS
     * @return static
     */
    public function setExportImportUFEBS($exportImportUFEBS)
    {
        $this->exportImportUFEBS = $exportImportUFEBS;
        return $this;
    }

    /**
     * Adds as address
     *
     * Адреса (все заведенные для организации)
     *
     * @return static
     * @param \common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\AddressesAType\AddressAType $address
     */
    public function addToAddresses(\common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\AddressesAType\AddressAType $address)
    {
        $this->addresses[] = $address;
        return $this;
    }

    /**
     * isset addresses
     *
     * Адреса (все заведенные для организации)
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
     * Адреса (все заведенные для организации)
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
     * Адреса (все заведенные для организации)
     *
     * @return \common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\AddressesAType\AddressAType[]
     */
    public function getAddresses()
    {
        return $this->addresses;
    }

    /**
     * Sets a new addresses
     *
     * Адреса (все заведенные для организации)
     *
     * @param \common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\AddressesAType\AddressAType[] $addresses
     * @return static
     */
    public function setAddresses(array $addresses)
    {
        $this->addresses = $addresses;
        return $this;
    }

    /**
     * Adds as orgBranch
     *
     * Подразделения банка, в которых обслуживается организация
     *
     * @return static
     * @param \common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\OrgBranchesAType\OrgBranchAType $orgBranch
     */
    public function addToOrgBranches(\common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\OrgBranchesAType\OrgBranchAType $orgBranch)
    {
        $this->orgBranches[] = $orgBranch;
        return $this;
    }

    /**
     * isset orgBranches
     *
     * Подразделения банка, в которых обслуживается организация
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetOrgBranches($index)
    {
        return isset($this->orgBranches[$index]);
    }

    /**
     * unset orgBranches
     *
     * Подразделения банка, в которых обслуживается организация
     *
     * @param scalar $index
     * @return void
     */
    public function unsetOrgBranches($index)
    {
        unset($this->orgBranches[$index]);
    }

    /**
     * Gets as orgBranches
     *
     * Подразделения банка, в которых обслуживается организация
     *
     * @return \common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\OrgBranchesAType\OrgBranchAType[]
     */
    public function getOrgBranches()
    {
        return $this->orgBranches;
    }

    /**
     * Sets a new orgBranches
     *
     * Подразделения банка, в которых обслуживается организация
     *
     * @param \common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\OrgBranchesAType\OrgBranchAType[] $orgBranches
     * @return static
     */
    public function setOrgBranches(array $orgBranches)
    {
        $this->orgBranches = $orgBranches;
        return $this;
    }

    /**
     * Gets as otherOrgData
     *
     * Прочие данные по организации
     *
     * @return \common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\OtherOrgDataAType
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
     * @param \common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\OtherOrgDataAType $otherOrgData
     * @return static
     */
    public function setOtherOrgData(\common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\OtherOrgDataAType $otherOrgData)
    {
        $this->otherOrgData = $otherOrgData;
        return $this;
    }

    /**
     * Adds as authSign
     *
     * Предназначение криптопрофиля
     *  (на что может быть наложена подпись)
     *
     * @return static
     * @param \common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\AuthSignsAType\AuthSignAType $authSign
     */
    public function addToAuthSigns(\common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\AuthSignsAType\AuthSignAType $authSign)
    {
        $this->authSigns[] = $authSign;
        return $this;
    }

    /**
     * isset authSigns
     *
     * Предназначение криптопрофиля
     *  (на что может быть наложена подпись)
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetAuthSigns($index)
    {
        return isset($this->authSigns[$index]);
    }

    /**
     * unset authSigns
     *
     * Предназначение криптопрофиля
     *  (на что может быть наложена подпись)
     *
     * @param scalar $index
     * @return void
     */
    public function unsetAuthSigns($index)
    {
        unset($this->authSigns[$index]);
    }

    /**
     * Gets as authSigns
     *
     * Предназначение криптопрофиля
     *  (на что может быть наложена подпись)
     *
     * @return \common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\AuthSignsAType\AuthSignAType[]
     */
    public function getAuthSigns()
    {
        return $this->authSigns;
    }

    /**
     * Sets a new authSigns
     *
     * Предназначение криптопрофиля
     *  (на что может быть наложена подпись)
     *
     * @param \common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\AuthSignsAType\AuthSignAType[] $authSigns
     * @return static
     */
    public function setAuthSigns(array $authSigns)
    {
        $this->authSigns = $authSigns;
        return $this;
    }

    /**
     * Gets as beneficiars
     *
     * @return \common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\BeneficiarsAType
     */
    public function getBeneficiars()
    {
        return $this->beneficiars;
    }

    /**
     * Sets a new beneficiars
     *
     * @param \common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\BeneficiarsAType $beneficiars
     * @return static
     */
    public function setBeneficiars(\common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\BeneficiarsAType $beneficiars)
    {
        $this->beneficiars = $beneficiars;
        return $this;
    }

    /**
     * Gets as correspondents
     *
     * @return \common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\CorrespondentsAType
     */
    public function getCorrespondents()
    {
        return $this->correspondents;
    }

    /**
     * Sets a new correspondents
     *
     * @param \common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\CorrespondentsAType $correspondents
     * @return static
     */
    public function setCorrespondents(\common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\CorrespondentsAType $correspondents)
    {
        $this->correspondents = $correspondents;
        return $this;
    }

    /**
     * Adds as org
     *
     * @return static
     * @param string $org
     */
    public function addToHoldingOrgs($org)
    {
        $this->holdingOrgs[] = $org;
        return $this;
    }

    /**
     * isset holdingOrgs
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetHoldingOrgs($index)
    {
        return isset($this->holdingOrgs[$index]);
    }

    /**
     * unset holdingOrgs
     *
     * @param scalar $index
     * @return void
     */
    public function unsetHoldingOrgs($index)
    {
        unset($this->holdingOrgs[$index]);
    }

    /**
     * Gets as holdingOrgs
     *
     * @return string[]
     */
    public function getHoldingOrgs()
    {
        return $this->holdingOrgs;
    }

    /**
     * Sets a new holdingOrgs
     *
     * @param string $holdingOrgs
     * @return static
     */
    public function setHoldingOrgs(array $holdingOrgs)
    {
        $this->holdingOrgs = $holdingOrgs;
        return $this;
    }

    /**
     * Gets as orgDataVersion
     *
     * Верcия данных об организации
     *
     * @return integer
     */
    public function getOrgDataVersion()
    {
        return $this->orgDataVersion;
    }

    /**
     * Sets a new orgDataVersion
     *
     * Верcия данных об организации
     *
     * @param integer $orgDataVersion
     * @return static
     */
    public function setOrgDataVersion($orgDataVersion)
    {
        $this->orgDataVersion = $orgDataVersion;
        return $this;
    }


}


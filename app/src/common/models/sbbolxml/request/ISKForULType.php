<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing ISKForULType
 *
 * Информационные сведения юридического лица (филиала)
 * XSD Type: ISKForUL
 */
class ISKForULType extends DocBaseType
{

    /**
     * @property \common\models\sbbolxml\request\OrgDataType $orgData
     */
    private $orgData = null;

    /**
     * @property \common\models\sbbolxml\request\RegistrationDataType $registrationData
     */
    private $registrationData = null;

    /**
     * Корневой элемент для Адреса местонахождения
     *
     * @property \common\models\sbbolxml\request\RealAdressType $addressInformation
     */
    private $addressInformation = null;

    /**
     * Адрес электронной почты/сайт
     *
     * @property string $emailOrSite
     */
    private $emailOrSite = null;

    /**
     * Номера контактных телефонов и факсов (с указанием кода города)
     *
     * @property string $telfax
     */
    private $telfax = null;

    /**
     * @property \common\models\sbbolxml\request\AuthorisedCapitalInfoType $authorisedCapitalInfo
     */
    private $authorisedCapitalInfo = null;

    /**
     * Сфера деятельности/отрасль производства
     *
     * @property \common\models\sbbolxml\request\BranchOfProductionType $branchOfProduction
     */
    private $branchOfProduction = null;

    /**
     * Сведения о лицензиях на право осуществления деятельности, подлежащей лицензированию
     *
     * @property \common\models\sbbolxml\request\LicensesInformationType $licensesInformation
     */
    private $licensesInformation = null;

    /**
     * Планируется ли осуществление операции(й) по переводу денежных средств на счета контрагентов –
     *  нерезидентов, не
     *  являющихся резидентами Республики Беларусь или Республики Казахстан, и действующих в своих интересах или по поручению
     *  третьих
     *  лиц, по заключенным с ними внешнеторговым договорам (контрактам), по которым будет осуществляться ввоз товаров,
     *  приобретенных
     *  у резидентов Республики Беларусь или Республики Казахстан соответственно, с территории Республики Беларусь или
     *  Республики
     *  Казахстан, по товарно-транспортным накладным (товарно-сопроводительным документам), оформленным грузоотправителями
     *  Республики
     *  Беларусь или Республики Казахстан
     *
     * @property boolean $nonResidentOperations
     */
    private $nonResidentOperations = null;

    /**
     * Сведения о деловой репутации
     *
     * @property \common\models\sbbolxml\request\BusinessStandingType $businessStanding
     */
    private $businessStanding = null;

    /**
     * Сведения о целях установления и предполагаемом характере деловых отношений с Банком
     *
     * @property \common\models\sbbolxml\request\BusinessRelationsInformationType $businessRelationsInformation
     */
    private $businessRelationsInformation = null;

    /**
     * Виды договоров (контрактов), расчеты по которым планируется осуществлять через Банк (нужное отметить)
     *
     * @property \common\models\sbbolxml\request\TypesOfContractsType $typesOfContracts
     */
    private $typesOfContracts = null;

    /**
     * Сведения о планируемых операциях по счету в месяц
     *
     * @property \common\models\sbbolxml\request\PlannedOperationsInfoType $plannedOperationsInfo
     */
    private $plannedOperationsInfo = null;

    /**
     * @property \common\models\sbbolxml\request\KeyContractorsType $keyContractors
     */
    private $keyContractors = null;

    /**
     * Сведения (документы) о финансовом положении
     *
     * @property \common\models\sbbolxml\request\FinancialSituationInfoAndDocsType $financialSituationInfoAndDocs
     */
    private $financialSituationInfoAndDocs = null;

    /**
     * Выгодоприобретатели
     *  True, если выбрано «Есть», False, если выбрано «Нет»
     *
     * @property \common\models\sbbolxml\request\ProfitAcquirersType $profitAcquirers
     */
    private $profitAcquirers = null;

    /**
     * @property \common\models\sbbolxml\request\OrgManagementType $orgManagement
     */
    private $orgManagement = null;

    /**
     * @property \common\models\sbbolxml\request\BeneficiarType $beneficiar
     */
    private $beneficiar = null;

    /**
     * Gets as orgData
     *
     * @return \common\models\sbbolxml\request\OrgDataType
     */
    public function getOrgData()
    {
        return $this->orgData;
    }

    /**
     * Sets a new orgData
     *
     * @param \common\models\sbbolxml\request\OrgDataType $orgData
     * @return static
     */
    public function setOrgData(\common\models\sbbolxml\request\OrgDataType $orgData)
    {
        $this->orgData = $orgData;
        return $this;
    }

    /**
     * Gets as registrationData
     *
     * @return \common\models\sbbolxml\request\RegistrationDataType
     */
    public function getRegistrationData()
    {
        return $this->registrationData;
    }

    /**
     * Sets a new registrationData
     *
     * @param \common\models\sbbolxml\request\RegistrationDataType $registrationData
     * @return static
     */
    public function setRegistrationData(\common\models\sbbolxml\request\RegistrationDataType $registrationData)
    {
        $this->registrationData = $registrationData;
        return $this;
    }

    /**
     * Gets as addressInformation
     *
     * Корневой элемент для Адреса местонахождения
     *
     * @return \common\models\sbbolxml\request\RealAdressType
     */
    public function getAddressInformation()
    {
        return $this->addressInformation;
    }

    /**
     * Sets a new addressInformation
     *
     * Корневой элемент для Адреса местонахождения
     *
     * @param \common\models\sbbolxml\request\RealAdressType $addressInformation
     * @return static
     */
    public function setAddressInformation(\common\models\sbbolxml\request\RealAdressType $addressInformation)
    {
        $this->addressInformation = $addressInformation;
        return $this;
    }

    /**
     * Gets as emailOrSite
     *
     * Адрес электронной почты/сайт
     *
     * @return string
     */
    public function getEmailOrSite()
    {
        return $this->emailOrSite;
    }

    /**
     * Sets a new emailOrSite
     *
     * Адрес электронной почты/сайт
     *
     * @param string $emailOrSite
     * @return static
     */
    public function setEmailOrSite($emailOrSite)
    {
        $this->emailOrSite = $emailOrSite;
        return $this;
    }

    /**
     * Gets as telfax
     *
     * Номера контактных телефонов и факсов (с указанием кода города)
     *
     * @return string
     */
    public function getTelfax()
    {
        return $this->telfax;
    }

    /**
     * Sets a new telfax
     *
     * Номера контактных телефонов и факсов (с указанием кода города)
     *
     * @param string $telfax
     * @return static
     */
    public function setTelfax($telfax)
    {
        $this->telfax = $telfax;
        return $this;
    }

    /**
     * Gets as authorisedCapitalInfo
     *
     * @return \common\models\sbbolxml\request\AuthorisedCapitalInfoType
     */
    public function getAuthorisedCapitalInfo()
    {
        return $this->authorisedCapitalInfo;
    }

    /**
     * Sets a new authorisedCapitalInfo
     *
     * @param \common\models\sbbolxml\request\AuthorisedCapitalInfoType $authorisedCapitalInfo
     * @return static
     */
    public function setAuthorisedCapitalInfo(\common\models\sbbolxml\request\AuthorisedCapitalInfoType $authorisedCapitalInfo)
    {
        $this->authorisedCapitalInfo = $authorisedCapitalInfo;
        return $this;
    }

    /**
     * Gets as branchOfProduction
     *
     * Сфера деятельности/отрасль производства
     *
     * @return \common\models\sbbolxml\request\BranchOfProductionType
     */
    public function getBranchOfProduction()
    {
        return $this->branchOfProduction;
    }

    /**
     * Sets a new branchOfProduction
     *
     * Сфера деятельности/отрасль производства
     *
     * @param \common\models\sbbolxml\request\BranchOfProductionType $branchOfProduction
     * @return static
     */
    public function setBranchOfProduction(\common\models\sbbolxml\request\BranchOfProductionType $branchOfProduction)
    {
        $this->branchOfProduction = $branchOfProduction;
        return $this;
    }

    /**
     * Gets as licensesInformation
     *
     * Сведения о лицензиях на право осуществления деятельности, подлежащей лицензированию
     *
     * @return \common\models\sbbolxml\request\LicensesInformationType
     */
    public function getLicensesInformation()
    {
        return $this->licensesInformation;
    }

    /**
     * Sets a new licensesInformation
     *
     * Сведения о лицензиях на право осуществления деятельности, подлежащей лицензированию
     *
     * @param \common\models\sbbolxml\request\LicensesInformationType $licensesInformation
     * @return static
     */
    public function setLicensesInformation(\common\models\sbbolxml\request\LicensesInformationType $licensesInformation)
    {
        $this->licensesInformation = $licensesInformation;
        return $this;
    }

    /**
     * Gets as nonResidentOperations
     *
     * Планируется ли осуществление операции(й) по переводу денежных средств на счета контрагентов –
     *  нерезидентов, не
     *  являющихся резидентами Республики Беларусь или Республики Казахстан, и действующих в своих интересах или по поручению
     *  третьих
     *  лиц, по заключенным с ними внешнеторговым договорам (контрактам), по которым будет осуществляться ввоз товаров,
     *  приобретенных
     *  у резидентов Республики Беларусь или Республики Казахстан соответственно, с территории Республики Беларусь или
     *  Республики
     *  Казахстан, по товарно-транспортным накладным (товарно-сопроводительным документам), оформленным грузоотправителями
     *  Республики
     *  Беларусь или Республики Казахстан
     *
     * @return boolean
     */
    public function getNonResidentOperations()
    {
        return $this->nonResidentOperations;
    }

    /**
     * Sets a new nonResidentOperations
     *
     * Планируется ли осуществление операции(й) по переводу денежных средств на счета контрагентов –
     *  нерезидентов, не
     *  являющихся резидентами Республики Беларусь или Республики Казахстан, и действующих в своих интересах или по поручению
     *  третьих
     *  лиц, по заключенным с ними внешнеторговым договорам (контрактам), по которым будет осуществляться ввоз товаров,
     *  приобретенных
     *  у резидентов Республики Беларусь или Республики Казахстан соответственно, с территории Республики Беларусь или
     *  Республики
     *  Казахстан, по товарно-транспортным накладным (товарно-сопроводительным документам), оформленным грузоотправителями
     *  Республики
     *  Беларусь или Республики Казахстан
     *
     * @param boolean $nonResidentOperations
     * @return static
     */
    public function setNonResidentOperations($nonResidentOperations)
    {
        $this->nonResidentOperations = $nonResidentOperations;
        return $this;
    }

    /**
     * Gets as businessStanding
     *
     * Сведения о деловой репутации
     *
     * @return \common\models\sbbolxml\request\BusinessStandingType
     */
    public function getBusinessStanding()
    {
        return $this->businessStanding;
    }

    /**
     * Sets a new businessStanding
     *
     * Сведения о деловой репутации
     *
     * @param \common\models\sbbolxml\request\BusinessStandingType $businessStanding
     * @return static
     */
    public function setBusinessStanding(\common\models\sbbolxml\request\BusinessStandingType $businessStanding)
    {
        $this->businessStanding = $businessStanding;
        return $this;
    }

    /**
     * Gets as businessRelationsInformation
     *
     * Сведения о целях установления и предполагаемом характере деловых отношений с Банком
     *
     * @return \common\models\sbbolxml\request\BusinessRelationsInformationType
     */
    public function getBusinessRelationsInformation()
    {
        return $this->businessRelationsInformation;
    }

    /**
     * Sets a new businessRelationsInformation
     *
     * Сведения о целях установления и предполагаемом характере деловых отношений с Банком
     *
     * @param \common\models\sbbolxml\request\BusinessRelationsInformationType $businessRelationsInformation
     * @return static
     */
    public function setBusinessRelationsInformation(\common\models\sbbolxml\request\BusinessRelationsInformationType $businessRelationsInformation)
    {
        $this->businessRelationsInformation = $businessRelationsInformation;
        return $this;
    }

    /**
     * Gets as typesOfContracts
     *
     * Виды договоров (контрактов), расчеты по которым планируется осуществлять через Банк (нужное отметить)
     *
     * @return \common\models\sbbolxml\request\TypesOfContractsType
     */
    public function getTypesOfContracts()
    {
        return $this->typesOfContracts;
    }

    /**
     * Sets a new typesOfContracts
     *
     * Виды договоров (контрактов), расчеты по которым планируется осуществлять через Банк (нужное отметить)
     *
     * @param \common\models\sbbolxml\request\TypesOfContractsType $typesOfContracts
     * @return static
     */
    public function setTypesOfContracts(\common\models\sbbolxml\request\TypesOfContractsType $typesOfContracts)
    {
        $this->typesOfContracts = $typesOfContracts;
        return $this;
    }

    /**
     * Gets as plannedOperationsInfo
     *
     * Сведения о планируемых операциях по счету в месяц
     *
     * @return \common\models\sbbolxml\request\PlannedOperationsInfoType
     */
    public function getPlannedOperationsInfo()
    {
        return $this->plannedOperationsInfo;
    }

    /**
     * Sets a new plannedOperationsInfo
     *
     * Сведения о планируемых операциях по счету в месяц
     *
     * @param \common\models\sbbolxml\request\PlannedOperationsInfoType $plannedOperationsInfo
     * @return static
     */
    public function setPlannedOperationsInfo(\common\models\sbbolxml\request\PlannedOperationsInfoType $plannedOperationsInfo)
    {
        $this->plannedOperationsInfo = $plannedOperationsInfo;
        return $this;
    }

    /**
     * Gets as keyContractors
     *
     * @return \common\models\sbbolxml\request\KeyContractorsType
     */
    public function getKeyContractors()
    {
        return $this->keyContractors;
    }

    /**
     * Sets a new keyContractors
     *
     * @param \common\models\sbbolxml\request\KeyContractorsType $keyContractors
     * @return static
     */
    public function setKeyContractors(\common\models\sbbolxml\request\KeyContractorsType $keyContractors)
    {
        $this->keyContractors = $keyContractors;
        return $this;
    }

    /**
     * Gets as financialSituationInfoAndDocs
     *
     * Сведения (документы) о финансовом положении
     *
     * @return \common\models\sbbolxml\request\FinancialSituationInfoAndDocsType
     */
    public function getFinancialSituationInfoAndDocs()
    {
        return $this->financialSituationInfoAndDocs;
    }

    /**
     * Sets a new financialSituationInfoAndDocs
     *
     * Сведения (документы) о финансовом положении
     *
     * @param \common\models\sbbolxml\request\FinancialSituationInfoAndDocsType $financialSituationInfoAndDocs
     * @return static
     */
    public function setFinancialSituationInfoAndDocs(\common\models\sbbolxml\request\FinancialSituationInfoAndDocsType $financialSituationInfoAndDocs)
    {
        $this->financialSituationInfoAndDocs = $financialSituationInfoAndDocs;
        return $this;
    }

    /**
     * Gets as profitAcquirers
     *
     * Выгодоприобретатели
     *  True, если выбрано «Есть», False, если выбрано «Нет»
     *
     * @return \common\models\sbbolxml\request\ProfitAcquirersType
     */
    public function getProfitAcquirers()
    {
        return $this->profitAcquirers;
    }

    /**
     * Sets a new profitAcquirers
     *
     * Выгодоприобретатели
     *  True, если выбрано «Есть», False, если выбрано «Нет»
     *
     * @param \common\models\sbbolxml\request\ProfitAcquirersType $profitAcquirers
     * @return static
     */
    public function setProfitAcquirers(\common\models\sbbolxml\request\ProfitAcquirersType $profitAcquirers)
    {
        $this->profitAcquirers = $profitAcquirers;
        return $this;
    }

    /**
     * Gets as orgManagement
     *
     * @return \common\models\sbbolxml\request\OrgManagementType
     */
    public function getOrgManagement()
    {
        return $this->orgManagement;
    }

    /**
     * Sets a new orgManagement
     *
     * @param \common\models\sbbolxml\request\OrgManagementType $orgManagement
     * @return static
     */
    public function setOrgManagement(\common\models\sbbolxml\request\OrgManagementType $orgManagement)
    {
        $this->orgManagement = $orgManagement;
        return $this;
    }

    /**
     * Gets as beneficiar
     *
     * @return \common\models\sbbolxml\request\BeneficiarType
     */
    public function getBeneficiar()
    {
        return $this->beneficiar;
    }

    /**
     * Sets a new beneficiar
     *
     * @param \common\models\sbbolxml\request\BeneficiarType $beneficiar
     * @return static
     */
    public function setBeneficiar(\common\models\sbbolxml\request\BeneficiarType $beneficiar)
    {
        $this->beneficiar = $beneficiar;
        return $this;
    }


}


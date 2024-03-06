<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing ISKForIPType
 *
 * Информационные сведения индивидуального предпринимателя
 * XSD Type: ISKForIP
 */
class ISKForIPType extends DocBaseType
{

    /**
     * Содержит основные реквизиты организации
     *
     * @property \common\models\sbbolxml\request\OrgDataIPType $orgData
     */
    private $orgData = null;

    /**
     * IdentificationDocumentInfo
     *
     * @property \common\models\sbbolxml\request\IdentificationDocumentInfoType $identificationDocumentInfo
     */
    private $identificationDocumentInfo = null;

    /**
     * Данные миграционной карты
     *
     * @property \common\models\sbbolxml\request\MigrationCardInfoType $migrationCardInfo
     */
    private $migrationCardInfo = null;

    /**
     * Данные документа, подтверждающего право иностранного гражданина или лица без гражданства на пребывание
     *  (проживание) в РФ
     *
     * @property \common\models\sbbolxml\request\RightToStayDocumentInfoType $rightToStayDocumentInfo
     */
    private $rightToStayDocumentInfo = null;

    /**
     * Адрес места жительства (регистрации) или места пребывания
     *
     * @property \common\models\sbbolxml\request\AddressesIPType $addresses
     */
    private $addresses = null;

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
     * Почтовый адрес
     *
     * @property string $postalAddress
     */
    private $postalAddress = null;

    /**
     * ИНН
     *
     * @property string $iNN
     */
    private $iNN = null;

    /**
     * Сведения о регистрации в качестве индивидуального предпринимателя
     *
     * @property \common\models\sbbolxml\request\IndividualEnterpriseRegInfoType $individualEnterpriseRegInfo
     */
    private $individualEnterpriseRegInfo = null;

    /**
     * Сведения о лицензиях на право осуществления деятельности, подлежащей лицензированию
     *
     * @property \common\models\sbbolxml\request\LicensesInformationType $licensesInformation
     */
    private $licensesInformation = null;

    /**
     * Сфера деятельности/отрасль производства
     *
     * @property \common\models\sbbolxml\request\BranchOfProductionType $branchOfProduction
     */
    private $branchOfProduction = null;

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
     * Информация об основных контрагентах, планируемых плательщиках и получателях по операциям с денежными
     *  средствами,
     *  находящимися на счете
     *
     * @property \common\models\sbbolxml\request\KeyContractorsType $keyContractors
     */
    private $keyContractors = null;

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
     * Наличие бенефициарных владельцев
     *
     * @property \common\models\sbbolxml\request\BeneficiarType $beneficiar
     */
    private $beneficiar = null;

    /**
     * Gets as orgData
     *
     * Содержит основные реквизиты организации
     *
     * @return \common\models\sbbolxml\request\OrgDataIPType
     */
    public function getOrgData()
    {
        return $this->orgData;
    }

    /**
     * Sets a new orgData
     *
     * Содержит основные реквизиты организации
     *
     * @param \common\models\sbbolxml\request\OrgDataIPType $orgData
     * @return static
     */
    public function setOrgData(\common\models\sbbolxml\request\OrgDataIPType $orgData)
    {
        $this->orgData = $orgData;
        return $this;
    }

    /**
     * Gets as identificationDocumentInfo
     *
     * IdentificationDocumentInfo
     *
     * @return \common\models\sbbolxml\request\IdentificationDocumentInfoType
     */
    public function getIdentificationDocumentInfo()
    {
        return $this->identificationDocumentInfo;
    }

    /**
     * Sets a new identificationDocumentInfo
     *
     * IdentificationDocumentInfo
     *
     * @param \common\models\sbbolxml\request\IdentificationDocumentInfoType $identificationDocumentInfo
     * @return static
     */
    public function setIdentificationDocumentInfo(\common\models\sbbolxml\request\IdentificationDocumentInfoType $identificationDocumentInfo)
    {
        $this->identificationDocumentInfo = $identificationDocumentInfo;
        return $this;
    }

    /**
     * Gets as migrationCardInfo
     *
     * Данные миграционной карты
     *
     * @return \common\models\sbbolxml\request\MigrationCardInfoType
     */
    public function getMigrationCardInfo()
    {
        return $this->migrationCardInfo;
    }

    /**
     * Sets a new migrationCardInfo
     *
     * Данные миграционной карты
     *
     * @param \common\models\sbbolxml\request\MigrationCardInfoType $migrationCardInfo
     * @return static
     */
    public function setMigrationCardInfo(\common\models\sbbolxml\request\MigrationCardInfoType $migrationCardInfo)
    {
        $this->migrationCardInfo = $migrationCardInfo;
        return $this;
    }

    /**
     * Gets as rightToStayDocumentInfo
     *
     * Данные документа, подтверждающего право иностранного гражданина или лица без гражданства на пребывание
     *  (проживание) в РФ
     *
     * @return \common\models\sbbolxml\request\RightToStayDocumentInfoType
     */
    public function getRightToStayDocumentInfo()
    {
        return $this->rightToStayDocumentInfo;
    }

    /**
     * Sets a new rightToStayDocumentInfo
     *
     * Данные документа, подтверждающего право иностранного гражданина или лица без гражданства на пребывание
     *  (проживание) в РФ
     *
     * @param \common\models\sbbolxml\request\RightToStayDocumentInfoType $rightToStayDocumentInfo
     * @return static
     */
    public function setRightToStayDocumentInfo(\common\models\sbbolxml\request\RightToStayDocumentInfoType $rightToStayDocumentInfo)
    {
        $this->rightToStayDocumentInfo = $rightToStayDocumentInfo;
        return $this;
    }

    /**
     * Gets as addresses
     *
     * Адрес места жительства (регистрации) или места пребывания
     *
     * @return \common\models\sbbolxml\request\AddressesIPType
     */
    public function getAddresses()
    {
        return $this->addresses;
    }

    /**
     * Sets a new addresses
     *
     * Адрес места жительства (регистрации) или места пребывания
     *
     * @param \common\models\sbbolxml\request\AddressesIPType $addresses
     * @return static
     */
    public function setAddresses(\common\models\sbbolxml\request\AddressesIPType $addresses)
    {
        $this->addresses = $addresses;
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
     * Gets as postalAddress
     *
     * Почтовый адрес
     *
     * @return string
     */
    public function getPostalAddress()
    {
        return $this->postalAddress;
    }

    /**
     * Sets a new postalAddress
     *
     * Почтовый адрес
     *
     * @param string $postalAddress
     * @return static
     */
    public function setPostalAddress($postalAddress)
    {
        $this->postalAddress = $postalAddress;
        return $this;
    }

    /**
     * Gets as iNN
     *
     * ИНН
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
     * ИНН
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
     * Gets as individualEnterpriseRegInfo
     *
     * Сведения о регистрации в качестве индивидуального предпринимателя
     *
     * @return \common\models\sbbolxml\request\IndividualEnterpriseRegInfoType
     */
    public function getIndividualEnterpriseRegInfo()
    {
        return $this->individualEnterpriseRegInfo;
    }

    /**
     * Sets a new individualEnterpriseRegInfo
     *
     * Сведения о регистрации в качестве индивидуального предпринимателя
     *
     * @param \common\models\sbbolxml\request\IndividualEnterpriseRegInfoType $individualEnterpriseRegInfo
     * @return static
     */
    public function setIndividualEnterpriseRegInfo(\common\models\sbbolxml\request\IndividualEnterpriseRegInfoType $individualEnterpriseRegInfo)
    {
        $this->individualEnterpriseRegInfo = $individualEnterpriseRegInfo;
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
     * Информация об основных контрагентах, планируемых плательщиках и получателях по операциям с денежными
     *  средствами,
     *  находящимися на счете
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
     * Информация об основных контрагентах, планируемых плательщиках и получателях по операциям с денежными
     *  средствами,
     *  находящимися на счете
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
     * Gets as beneficiar
     *
     * Наличие бенефициарных владельцев
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
     * Наличие бенефициарных владельцев
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


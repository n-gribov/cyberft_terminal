<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing ProfitAcquirerULType
 *
 * Информационные сведения юридического лица (филиала)
 * XSD Type: ProfitAcquirerUL
 */
class ProfitAcquirerULType
{

    /**
     * Содержит основные реквизиты организации
     *
     * @property \common\models\sbbolxml\request\OrgDataType $orgData
     */
    private $orgData = null;

    /**
     * Содержит информацию о регистрации
     *
     * @property \common\models\sbbolxml\request\RegistrationDataType $registrationData
     */
    private $registrationData = null;

    /**
     * Адрес местонахождения
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
     * Сведения о величине уставного (складочного) капитала или величине уставного фонда, имущества
     *
     * @property \common\models\sbbolxml\request\AuthorisedCapitalInfoType $authorisedCapitalInfo
     */
    private $authorisedCapitalInfo = null;

    /**
     * Сведения о лицензиях на право осуществления деятельности, подлежащей лицензированию
     *
     * @property \common\models\sbbolxml\request\LicensesInformationType $licensesInformation
     */
    private $licensesInformation = null;

    /**
     * @property \common\models\sbbolxml\request\OrgManagementType $orgManagement
     */
    private $orgManagement = null;

    /**
     * Сведения об основаниях, свидетельствующих о том, что Клиент действует к выгоде другого лица
     *
     * @property \common\models\sbbolxml\request\ProfitReasonInfoType $profitReasonInfo
     */
    private $profitReasonInfo = null;

    /**
     * Gets as orgData
     *
     * Содержит основные реквизиты организации
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
     * Содержит основные реквизиты организации
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
     * Содержит информацию о регистрации
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
     * Содержит информацию о регистрации
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
     * Адрес местонахождения
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
     * Адрес местонахождения
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
     * Сведения о величине уставного (складочного) капитала или величине уставного фонда, имущества
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
     * Сведения о величине уставного (складочного) капитала или величине уставного фонда, имущества
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
     * Gets as profitReasonInfo
     *
     * Сведения об основаниях, свидетельствующих о том, что Клиент действует к выгоде другого лица
     *
     * @return \common\models\sbbolxml\request\ProfitReasonInfoType
     */
    public function getProfitReasonInfo()
    {
        return $this->profitReasonInfo;
    }

    /**
     * Sets a new profitReasonInfo
     *
     * Сведения об основаниях, свидетельствующих о том, что Клиент действует к выгоде другого лица
     *
     * @param \common\models\sbbolxml\request\ProfitReasonInfoType $profitReasonInfo
     * @return static
     */
    public function setProfitReasonInfo(\common\models\sbbolxml\request\ProfitReasonInfoType $profitReasonInfo)
    {
        $this->profitReasonInfo = $profitReasonInfo;
        return $this;
    }


}


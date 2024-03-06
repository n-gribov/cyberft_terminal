<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing ProfitAcquirerIPType
 *
 * Информационные сведения юридического лица (филиала)
 * XSD Type: ProfitAcquirerIP
 */
class ProfitAcquirerIPType
{

    /**
     * Содержит основные реквизиты организации
     *
     * @property \common\models\sbbolxml\request\OrgDataIPType $orgData
     */
    private $orgData = null;

    /**
     * Реквизиты документа, удостоверяющего личность
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
     * @property string $inn
     */
    private $inn = null;

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
     * Реквизиты документа, удостоверяющего личность
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
     * Реквизиты документа, удостоверяющего личность
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
     * @return \common\models\sbbolxml\request\RightToStayDocumentInfoType
     */
    public function getRightToStayDocumentInfo()
    {
        return $this->rightToStayDocumentInfo;
    }

    /**
     * Sets a new rightToStayDocumentInfo
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


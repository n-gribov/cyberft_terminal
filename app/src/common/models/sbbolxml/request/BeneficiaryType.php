<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing BeneficiaryType
 *
 * Сведения о бенефициарном владельце
 * XSD Type: Beneficiary
 */
class BeneficiaryType
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
     * ИНН
     *
     * @property string $inn
     */
    private $inn = null;

    /**
     * Размер доли участия в уставном капитале организации (%)
     *
     * @property string $authorizedCapitalShareSize
     */
    private $authorizedCapitalShareSize = null;

    /**
     * Основание для признания физического лица бенефициарным владельцем Клиента (нужное отметить
     *
     * @property \common\models\sbbolxml\request\BeneficiaryReasonInfoType $beneficiaryReasonInfo
     */
    private $beneficiaryReasonInfo = null;

    /**
     * @property \common\models\sbbolxml\request\BeneficiaryType\BeneficiaryIsNotForeignOfficerConfirmationAType $beneficiaryIsNotForeignOfficerConfirmation
     */
    private $beneficiaryIsNotForeignOfficerConfirmation = null;

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
     * Gets as authorizedCapitalShareSize
     *
     * Размер доли участия в уставном капитале организации (%)
     *
     * @return string
     */
    public function getAuthorizedCapitalShareSize()
    {
        return $this->authorizedCapitalShareSize;
    }

    /**
     * Sets a new authorizedCapitalShareSize
     *
     * Размер доли участия в уставном капитале организации (%)
     *
     * @param string $authorizedCapitalShareSize
     * @return static
     */
    public function setAuthorizedCapitalShareSize($authorizedCapitalShareSize)
    {
        $this->authorizedCapitalShareSize = $authorizedCapitalShareSize;
        return $this;
    }

    /**
     * Gets as beneficiaryReasonInfo
     *
     * Основание для признания физического лица бенефициарным владельцем Клиента (нужное отметить
     *
     * @return \common\models\sbbolxml\request\BeneficiaryReasonInfoType
     */
    public function getBeneficiaryReasonInfo()
    {
        return $this->beneficiaryReasonInfo;
    }

    /**
     * Sets a new beneficiaryReasonInfo
     *
     * Основание для признания физического лица бенефициарным владельцем Клиента (нужное отметить
     *
     * @param \common\models\sbbolxml\request\BeneficiaryReasonInfoType $beneficiaryReasonInfo
     * @return static
     */
    public function setBeneficiaryReasonInfo(\common\models\sbbolxml\request\BeneficiaryReasonInfoType $beneficiaryReasonInfo)
    {
        $this->beneficiaryReasonInfo = $beneficiaryReasonInfo;
        return $this;
    }

    /**
     * Gets as beneficiaryIsNotForeignOfficerConfirmation
     *
     * @return \common\models\sbbolxml\request\BeneficiaryType\BeneficiaryIsNotForeignOfficerConfirmationAType
     */
    public function getBeneficiaryIsNotForeignOfficerConfirmation()
    {
        return $this->beneficiaryIsNotForeignOfficerConfirmation;
    }

    /**
     * Sets a new beneficiaryIsNotForeignOfficerConfirmation
     *
     * @param \common\models\sbbolxml\request\BeneficiaryType\BeneficiaryIsNotForeignOfficerConfirmationAType $beneficiaryIsNotForeignOfficerConfirmation
     * @return static
     */
    public function setBeneficiaryIsNotForeignOfficerConfirmation(\common\models\sbbolxml\request\BeneficiaryType\BeneficiaryIsNotForeignOfficerConfirmationAType $beneficiaryIsNotForeignOfficerConfirmation)
    {
        $this->beneficiaryIsNotForeignOfficerConfirmation = $beneficiaryIsNotForeignOfficerConfirmation;
        return $this;
    }


}


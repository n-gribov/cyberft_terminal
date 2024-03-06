<?php

namespace common\models\sbbolxml\request\CertifRequestQualifiedType;

/**
 * Class representing SignHolderAType
 */
class SignHolderAType
{

    /**
     * Идентификатор криптопрофиля. Соответствует значению атрибута из
     *  ответа Response/OrganizationsInfo/OrganizationInfo/SignDevices/SignDevice/Alias
     *
     * @property string $cryptoProfileName
     */
    private $cryptoProfileName = null;

    /**
     * Уникальный идентификатор средства подписи. Соответствует значению
     *  атрибута из
     *  ответа
     *  Responce/OrgsInfo/SignDevices/SignDevice/SignDeviceId
     *
     * @property string $idCrypto
     */
    private $idCrypto = null;

    /**
     * ФИО уполномоченного представителя ЮЛ/ИП/сотрудника ИП.
     *  Например: «Иванов
     *  Иван Иванович».
     *
     * @property string $commonName
     */
    private $commonName = null;

    /**
     * Имя уполномоченного представителя Юл/ИП/сотрудника ИП.
     *  Например: «Иван».
     *
     * @property string $firstName
     */
    private $firstName = null;

    /**
     * Фамилия уполномоченного представителя ЮЛ/ИП/сотрудника ИП.
     *  Например:
     *  «Иванов».
     *
     * @property string $secondName
     */
    private $secondName = null;

    /**
     * Отчество уполномоченного представителя ЮЛ/ИП/сотрудника ИП.
     *  Например:
     *  «Иванович».
     *
     * @property string $middleName
     */
    private $middleName = null;

    /**
     * Тип документа
     *
     * @property string $docType
     */
    private $docType = null;

    /**
     * Код типа документа
     *
     * @property string $docTypeCode
     */
    private $docTypeCode = null;

    /**
     * Серия паспорта
     *
     * @property string $series
     */
    private $series = null;

    /**
     * Номер паспорта
     *
     * @property string $number
     */
    private $number = null;

    /**
     * Кем выдан
     *
     * @property string $issuer
     */
    private $issuer = null;

    /**
     * Дата выдачи в формате ДД.ММ.ГГГГ
     *
     * @property \DateTime $date
     */
    private $date = null;

    /**
     * Код подразделения в формате ХХХ-ХХХ
     *
     * @property string $branchCode
     */
    private $branchCode = null;

    /**
     * Дата рождения в формате ДД.ММ.ГГГГ
     *
     * @property \DateTime $dateOfBirth
     */
    private $dateOfBirth = null;

    /**
     * Место рождения
     *
     * @property string $birthPlace
     */
    private $birthPlace = null;

    /**
     * Пол
     *
     * @property integer $sex
     */
    private $sex = null;

    /**
     * Гражданство
     *
     * @property \common\models\sbbolxml\request\CertifRequestQualifiedType\SignHolderAType\CitizenshipAType $citizenship
     */
    private $citizenship = null;

    /**
     * Подразделение (если имеется) уполномоченного представителя
     *  юридического
     *  лица. Например: «Бухгалтерия».
     *
     * @property string $subUnitName
     */
    private $subUnitName = null;

    /**
     * Должность уполномоченного представителя ЮЛ. Например: «Главный
     *  бухгалтер».
     *
     * @property string $position
     */
    private $position = null;

    /**
     * Срок полномочий в формате ДД.ММ.ГГГГ
     *
     * @property \DateTime $termOfOffice
     */
    private $termOfOffice = null;

    /**
     * Документ, подтверждающий полномочия
     *
     *  (Пример: Доверенность 123 от ДД.ММ.ГГГГ)
     *
     *  ПОКА НЕ ЗАПОЛНЯЕТСЯ
     *
     * @property string $authDocument
     */
    private $authDocument = null;

    /**
     * Телефон
     *
     * @property string $tel
     */
    private $tel = null;

    /**
     * СНИЛС представителя ЮЛ/ИП/сотрудника ИП. Для нерезидента РФ
     *  поле
     *  заполняется одиннадцатью нулями.
     *
     * @property string $snils
     */
    private $snils = null;

    /**
     * ИНН сотрудника, например: «772233665544».
     *
     * @property string $iNN
     */
    private $iNN = null;

    /**
     * Адрес электронной почты
     *
     * @property string $email
     */
    private $email = null;

    /**
     * Gets as cryptoProfileName
     *
     * Идентификатор криптопрофиля. Соответствует значению атрибута из
     *  ответа Response/OrganizationsInfo/OrganizationInfo/SignDevices/SignDevice/Alias
     *
     * @return string
     */
    public function getCryptoProfileName()
    {
        return $this->cryptoProfileName;
    }

    /**
     * Sets a new cryptoProfileName
     *
     * Идентификатор криптопрофиля. Соответствует значению атрибута из
     *  ответа Response/OrganizationsInfo/OrganizationInfo/SignDevices/SignDevice/Alias
     *
     * @param string $cryptoProfileName
     * @return static
     */
    public function setCryptoProfileName($cryptoProfileName)
    {
        $this->cryptoProfileName = $cryptoProfileName;
        return $this;
    }

    /**
     * Gets as idCrypto
     *
     * Уникальный идентификатор средства подписи. Соответствует значению
     *  атрибута из
     *  ответа
     *  Responce/OrgsInfo/SignDevices/SignDevice/SignDeviceId
     *
     * @return string
     */
    public function getIdCrypto()
    {
        return $this->idCrypto;
    }

    /**
     * Sets a new idCrypto
     *
     * Уникальный идентификатор средства подписи. Соответствует значению
     *  атрибута из
     *  ответа
     *  Responce/OrgsInfo/SignDevices/SignDevice/SignDeviceId
     *
     * @param string $idCrypto
     * @return static
     */
    public function setIdCrypto($idCrypto)
    {
        $this->idCrypto = $idCrypto;
        return $this;
    }

    /**
     * Gets as commonName
     *
     * ФИО уполномоченного представителя ЮЛ/ИП/сотрудника ИП.
     *  Например: «Иванов
     *  Иван Иванович».
     *
     * @return string
     */
    public function getCommonName()
    {
        return $this->commonName;
    }

    /**
     * Sets a new commonName
     *
     * ФИО уполномоченного представителя ЮЛ/ИП/сотрудника ИП.
     *  Например: «Иванов
     *  Иван Иванович».
     *
     * @param string $commonName
     * @return static
     */
    public function setCommonName($commonName)
    {
        $this->commonName = $commonName;
        return $this;
    }

    /**
     * Gets as firstName
     *
     * Имя уполномоченного представителя Юл/ИП/сотрудника ИП.
     *  Например: «Иван».
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Sets a new firstName
     *
     * Имя уполномоченного представителя Юл/ИП/сотрудника ИП.
     *  Например: «Иван».
     *
     * @param string $firstName
     * @return static
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
        return $this;
    }

    /**
     * Gets as secondName
     *
     * Фамилия уполномоченного представителя ЮЛ/ИП/сотрудника ИП.
     *  Например:
     *  «Иванов».
     *
     * @return string
     */
    public function getSecondName()
    {
        return $this->secondName;
    }

    /**
     * Sets a new secondName
     *
     * Фамилия уполномоченного представителя ЮЛ/ИП/сотрудника ИП.
     *  Например:
     *  «Иванов».
     *
     * @param string $secondName
     * @return static
     */
    public function setSecondName($secondName)
    {
        $this->secondName = $secondName;
        return $this;
    }

    /**
     * Gets as middleName
     *
     * Отчество уполномоченного представителя ЮЛ/ИП/сотрудника ИП.
     *  Например:
     *  «Иванович».
     *
     * @return string
     */
    public function getMiddleName()
    {
        return $this->middleName;
    }

    /**
     * Sets a new middleName
     *
     * Отчество уполномоченного представителя ЮЛ/ИП/сотрудника ИП.
     *  Например:
     *  «Иванович».
     *
     * @param string $middleName
     * @return static
     */
    public function setMiddleName($middleName)
    {
        $this->middleName = $middleName;
        return $this;
    }

    /**
     * Gets as docType
     *
     * Тип документа
     *
     * @return string
     */
    public function getDocType()
    {
        return $this->docType;
    }

    /**
     * Sets a new docType
     *
     * Тип документа
     *
     * @param string $docType
     * @return static
     */
    public function setDocType($docType)
    {
        $this->docType = $docType;
        return $this;
    }

    /**
     * Gets as docTypeCode
     *
     * Код типа документа
     *
     * @return string
     */
    public function getDocTypeCode()
    {
        return $this->docTypeCode;
    }

    /**
     * Sets a new docTypeCode
     *
     * Код типа документа
     *
     * @param string $docTypeCode
     * @return static
     */
    public function setDocTypeCode($docTypeCode)
    {
        $this->docTypeCode = $docTypeCode;
        return $this;
    }

    /**
     * Gets as series
     *
     * Серия паспорта
     *
     * @return string
     */
    public function getSeries()
    {
        return $this->series;
    }

    /**
     * Sets a new series
     *
     * Серия паспорта
     *
     * @param string $series
     * @return static
     */
    public function setSeries($series)
    {
        $this->series = $series;
        return $this;
    }

    /**
     * Gets as number
     *
     * Номер паспорта
     *
     * @return string
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Sets a new number
     *
     * Номер паспорта
     *
     * @param string $number
     * @return static
     */
    public function setNumber($number)
    {
        $this->number = $number;
        return $this;
    }

    /**
     * Gets as issuer
     *
     * Кем выдан
     *
     * @return string
     */
    public function getIssuer()
    {
        return $this->issuer;
    }

    /**
     * Sets a new issuer
     *
     * Кем выдан
     *
     * @param string $issuer
     * @return static
     */
    public function setIssuer($issuer)
    {
        $this->issuer = $issuer;
        return $this;
    }

    /**
     * Gets as date
     *
     * Дата выдачи в формате ДД.ММ.ГГГГ
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Sets a new date
     *
     * Дата выдачи в формате ДД.ММ.ГГГГ
     *
     * @param \DateTime $date
     * @return static
     */
    public function setDate(\DateTime $date)
    {
        $this->date = $date;
        return $this;
    }

    /**
     * Gets as branchCode
     *
     * Код подразделения в формате ХХХ-ХХХ
     *
     * @return string
     */
    public function getBranchCode()
    {
        return $this->branchCode;
    }

    /**
     * Sets a new branchCode
     *
     * Код подразделения в формате ХХХ-ХХХ
     *
     * @param string $branchCode
     * @return static
     */
    public function setBranchCode($branchCode)
    {
        $this->branchCode = $branchCode;
        return $this;
    }

    /**
     * Gets as dateOfBirth
     *
     * Дата рождения в формате ДД.ММ.ГГГГ
     *
     * @return \DateTime
     */
    public function getDateOfBirth()
    {
        return $this->dateOfBirth;
    }

    /**
     * Sets a new dateOfBirth
     *
     * Дата рождения в формате ДД.ММ.ГГГГ
     *
     * @param \DateTime $dateOfBirth
     * @return static
     */
    public function setDateOfBirth(\DateTime $dateOfBirth)
    {
        $this->dateOfBirth = $dateOfBirth;
        return $this;
    }

    /**
     * Gets as birthPlace
     *
     * Место рождения
     *
     * @return string
     */
    public function getBirthPlace()
    {
        return $this->birthPlace;
    }

    /**
     * Sets a new birthPlace
     *
     * Место рождения
     *
     * @param string $birthPlace
     * @return static
     */
    public function setBirthPlace($birthPlace)
    {
        $this->birthPlace = $birthPlace;
        return $this;
    }

    /**
     * Gets as sex
     *
     * Пол
     *
     * @return integer
     */
    public function getSex()
    {
        return $this->sex;
    }

    /**
     * Sets a new sex
     *
     * Пол
     *
     * @param integer $sex
     * @return static
     */
    public function setSex($sex)
    {
        $this->sex = $sex;
        return $this;
    }

    /**
     * Gets as citizenship
     *
     * Гражданство
     *
     * @return \common\models\sbbolxml\request\CertifRequestQualifiedType\SignHolderAType\CitizenshipAType
     */
    public function getCitizenship()
    {
        return $this->citizenship;
    }

    /**
     * Sets a new citizenship
     *
     * Гражданство
     *
     * @param \common\models\sbbolxml\request\CertifRequestQualifiedType\SignHolderAType\CitizenshipAType $citizenship
     * @return static
     */
    public function setCitizenship(\common\models\sbbolxml\request\CertifRequestQualifiedType\SignHolderAType\CitizenshipAType $citizenship)
    {
        $this->citizenship = $citizenship;
        return $this;
    }

    /**
     * Gets as subUnitName
     *
     * Подразделение (если имеется) уполномоченного представителя
     *  юридического
     *  лица. Например: «Бухгалтерия».
     *
     * @return string
     */
    public function getSubUnitName()
    {
        return $this->subUnitName;
    }

    /**
     * Sets a new subUnitName
     *
     * Подразделение (если имеется) уполномоченного представителя
     *  юридического
     *  лица. Например: «Бухгалтерия».
     *
     * @param string $subUnitName
     * @return static
     */
    public function setSubUnitName($subUnitName)
    {
        $this->subUnitName = $subUnitName;
        return $this;
    }

    /**
     * Gets as position
     *
     * Должность уполномоченного представителя ЮЛ. Например: «Главный
     *  бухгалтер».
     *
     * @return string
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Sets a new position
     *
     * Должность уполномоченного представителя ЮЛ. Например: «Главный
     *  бухгалтер».
     *
     * @param string $position
     * @return static
     */
    public function setPosition($position)
    {
        $this->position = $position;
        return $this;
    }

    /**
     * Gets as termOfOffice
     *
     * Срок полномочий в формате ДД.ММ.ГГГГ
     *
     * @return \DateTime
     */
    public function getTermOfOffice()
    {
        return $this->termOfOffice;
    }

    /**
     * Sets a new termOfOffice
     *
     * Срок полномочий в формате ДД.ММ.ГГГГ
     *
     * @param \DateTime $termOfOffice
     * @return static
     */
    public function setTermOfOffice(\DateTime $termOfOffice)
    {
        $this->termOfOffice = $termOfOffice;
        return $this;
    }

    /**
     * Gets as authDocument
     *
     * Документ, подтверждающий полномочия
     *
     *  (Пример: Доверенность 123 от ДД.ММ.ГГГГ)
     *
     *  ПОКА НЕ ЗАПОЛНЯЕТСЯ
     *
     * @return string
     */
    public function getAuthDocument()
    {
        return $this->authDocument;
    }

    /**
     * Sets a new authDocument
     *
     * Документ, подтверждающий полномочия
     *
     *  (Пример: Доверенность 123 от ДД.ММ.ГГГГ)
     *
     *  ПОКА НЕ ЗАПОЛНЯЕТСЯ
     *
     * @param string $authDocument
     * @return static
     */
    public function setAuthDocument($authDocument)
    {
        $this->authDocument = $authDocument;
        return $this;
    }

    /**
     * Gets as tel
     *
     * Телефон
     *
     * @return string
     */
    public function getTel()
    {
        return $this->tel;
    }

    /**
     * Sets a new tel
     *
     * Телефон
     *
     * @param string $tel
     * @return static
     */
    public function setTel($tel)
    {
        $this->tel = $tel;
        return $this;
    }

    /**
     * Gets as snils
     *
     * СНИЛС представителя ЮЛ/ИП/сотрудника ИП. Для нерезидента РФ
     *  поле
     *  заполняется одиннадцатью нулями.
     *
     * @return string
     */
    public function getSnils()
    {
        return $this->snils;
    }

    /**
     * Sets a new snils
     *
     * СНИЛС представителя ЮЛ/ИП/сотрудника ИП. Для нерезидента РФ
     *  поле
     *  заполняется одиннадцатью нулями.
     *
     * @param string $snils
     * @return static
     */
    public function setSnils($snils)
    {
        $this->snils = $snils;
        return $this;
    }

    /**
     * Gets as iNN
     *
     * ИНН сотрудника, например: «772233665544».
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
     * ИНН сотрудника, например: «772233665544».
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
     * Gets as email
     *
     * Адрес электронной почты
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Sets a new email
     *
     * Адрес электронной почты
     *
     * @param string $email
     * @return static
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }


}


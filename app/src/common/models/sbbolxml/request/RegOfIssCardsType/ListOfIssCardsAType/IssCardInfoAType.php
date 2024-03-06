<?php

namespace common\models\sbbolxml\request\RegOfIssCardsType\ListOfIssCardsAType;

/**
 * Class representing IssCardInfoAType
 */
class IssCardInfoAType
{

    /**
     * Номер п/п (может быть использован при подписи, обязательно
     *  д.б. возвращен в тикете )
     *
     * @property string $numSt
     */
    private $numSt = null;

    /**
     * Фамилия физического лица
     *
     * @property string $sName
     */
    private $sName = null;

    /**
     * Имя физического лица
     *
     * @property string $name
     */
    private $name = null;

    /**
     * Отчество физического лица
     *  (у некоторых иностранцев нет отчества)
     *
     * @property string $middleName
     */
    private $middleName = null;

    /**
     * Гражданство сотрудника
     *
     * @property \common\models\sbbolxml\request\RegOfIssCardsType\ListOfIssCardsAType\IssCardInfoAType\CitizenshipAType $citizenship
     */
    private $citizenship = null;

    /**
     * 1 - для резидентов. Иначе 0.
     *
     * @property boolean $resident
     */
    private $resident = null;

    /**
     * Дата рождения
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
     * 0-М
     *  1-Ж
     *
     * @property boolean $sex
     */
    private $sex = null;

    /**
     * Адрес регистрации
     *
     * @property \common\models\sbbolxml\request\AddressType $registrAddress
     */
    private $registrAddress = null;

    /**
     * 1 - адрес регистрации совпадает с адресом проживания, 0- адрес регистрации отличается от
     *  ардеса
     *  проживания
     *
     * @property boolean $sameAddress
     */
    private $sameAddress = null;

    /**
     * Адрес проживания
     *
     * @property \common\models\sbbolxml\request\AddressType $placeOfResidence
     */
    private $placeOfResidence = null;

    /**
     * @property string $iNN
     */
    private $iNN = null;

    /**
     * Текст эмбоссированный на ПК. Например: TATIANA M/IVANOVA
     *  или TANIA/IVANOVA/MRS
     *
     * @property \common\models\sbbolxml\request\RegOfIssCardsType\ListOfIssCardsAType\IssCardInfoAType\TextCardAType $textCard
     */
    private $textCard = null;

    /**
     * Документ, удостоверяющий личность
     *
     * @property \common\models\sbbolxml\request\IdentityDocType $identityDoc
     */
    private $identityDoc = null;

    /**
     * Должность
     *
     * @property string $position
     */
    private $position = null;

    /**
     * Категория населения. Возможные значения:
     *  207,0,212,217,218.
     *  207 – лица, перечисляющие зарплату на счета;
     *  0 – пенсионеры;
     *  212 – зарплата с разрешенным овердрафтом для сотрудников банка;
     *  217 - зарплата с разрешенным овердрафтом для сотрудников организации;
     *  218 – студенческая (договор с учебным заведением).
     *
     *  Можно заполнить автоматически
     *
     * @property string $category
     */
    private $category = null;

    /**
     * Контактные данные
     *
     * @property \common\models\sbbolxml\request\RegOfIssCardsType\ListOfIssCardsAType\IssCardInfoAType\ContactInfoAType $contactInfo
     */
    private $contactInfo = null;

    /**
     * Информация о картах
     *
     * @property \common\models\sbbolxml\request\RegOfIssCardsType\ListOfIssCardsAType\IssCardInfoAType\CardInfoAType $cardInfo
     */
    private $cardInfo = null;

    /**
     * Номер отделения Сбербанка для места обслуживания физ.лица (для АС Прометей, 4 символа)
     *
     * @property string $oSBNum
     */
    private $oSBNum = null;

    /**
     * Номер внутреннего структурного подразделения (филиала)
     *  для места обслуживания физ.лица (для АС Прометей, 4 символа)
     *
     * @property string $branchOSBNum
     */
    private $branchOSBNum = null;

    /**
     * Код Территорильного банка СБРФ для места
     *  обслуживания физ.лиц (для АС Прометей, 2 символа)
     *
     * @property string $tBNum
     */
    private $tBNum = null;

    /**
     * Код подразделения места обслуживания физ. лица
     *
     * @property string $serviceBranchCode
     */
    private $serviceBranchCode = null;

    /**
     * Название подразделения места обслуживания физ. лица
     *
     * @property string $serviceBranchName
     */
    private $serviceBranchName = null;

    /**
     * Если отмечен флаг в поле «Рассылка отчета по Internet» (З.2) экранной формы «Добавление записи ФЛ», то указывать значение «1», иначе пусто
     *
     * @property boolean $sendReport
     */
    private $sendReport = null;

    /**
     * Значение поля «Код индивидуального дизайна» (З.3) экранной формы «Добавление записи ФЛ»
     *
     * @property string $designID
     */
    private $designID = null;

    /**
     * Gets as numSt
     *
     * Номер п/п (может быть использован при подписи, обязательно
     *  д.б. возвращен в тикете )
     *
     * @return string
     */
    public function getNumSt()
    {
        return $this->numSt;
    }

    /**
     * Sets a new numSt
     *
     * Номер п/п (может быть использован при подписи, обязательно
     *  д.б. возвращен в тикете )
     *
     * @param string $numSt
     * @return static
     */
    public function setNumSt($numSt)
    {
        $this->numSt = $numSt;
        return $this;
    }

    /**
     * Gets as sName
     *
     * Фамилия физического лица
     *
     * @return string
     */
    public function getSName()
    {
        return $this->sName;
    }

    /**
     * Sets a new sName
     *
     * Фамилия физического лица
     *
     * @param string $sName
     * @return static
     */
    public function setSName($sName)
    {
        $this->sName = $sName;
        return $this;
    }

    /**
     * Gets as name
     *
     * Имя физического лица
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets a new name
     *
     * Имя физического лица
     *
     * @param string $name
     * @return static
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Gets as middleName
     *
     * Отчество физического лица
     *  (у некоторых иностранцев нет отчества)
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
     * Отчество физического лица
     *  (у некоторых иностранцев нет отчества)
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
     * Gets as citizenship
     *
     * Гражданство сотрудника
     *
     * @return \common\models\sbbolxml\request\RegOfIssCardsType\ListOfIssCardsAType\IssCardInfoAType\CitizenshipAType
     */
    public function getCitizenship()
    {
        return $this->citizenship;
    }

    /**
     * Sets a new citizenship
     *
     * Гражданство сотрудника
     *
     * @param \common\models\sbbolxml\request\RegOfIssCardsType\ListOfIssCardsAType\IssCardInfoAType\CitizenshipAType $citizenship
     * @return static
     */
    public function setCitizenship(\common\models\sbbolxml\request\RegOfIssCardsType\ListOfIssCardsAType\IssCardInfoAType\CitizenshipAType $citizenship)
    {
        $this->citizenship = $citizenship;
        return $this;
    }

    /**
     * Gets as resident
     *
     * 1 - для резидентов. Иначе 0.
     *
     * @return boolean
     */
    public function getResident()
    {
        return $this->resident;
    }

    /**
     * Sets a new resident
     *
     * 1 - для резидентов. Иначе 0.
     *
     * @param boolean $resident
     * @return static
     */
    public function setResident($resident)
    {
        $this->resident = $resident;
        return $this;
    }

    /**
     * Gets as dateOfBirth
     *
     * Дата рождения
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
     * Дата рождения
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
     * 0-М
     *  1-Ж
     *
     * @return boolean
     */
    public function getSex()
    {
        return $this->sex;
    }

    /**
     * Sets a new sex
     *
     * 0-М
     *  1-Ж
     *
     * @param boolean $sex
     * @return static
     */
    public function setSex($sex)
    {
        $this->sex = $sex;
        return $this;
    }

    /**
     * Gets as registrAddress
     *
     * Адрес регистрации
     *
     * @return \common\models\sbbolxml\request\AddressType
     */
    public function getRegistrAddress()
    {
        return $this->registrAddress;
    }

    /**
     * Sets a new registrAddress
     *
     * Адрес регистрации
     *
     * @param \common\models\sbbolxml\request\AddressType $registrAddress
     * @return static
     */
    public function setRegistrAddress(\common\models\sbbolxml\request\AddressType $registrAddress)
    {
        $this->registrAddress = $registrAddress;
        return $this;
    }

    /**
     * Gets as sameAddress
     *
     * 1 - адрес регистрации совпадает с адресом проживания, 0- адрес регистрации отличается от
     *  ардеса
     *  проживания
     *
     * @return boolean
     */
    public function getSameAddress()
    {
        return $this->sameAddress;
    }

    /**
     * Sets a new sameAddress
     *
     * 1 - адрес регистрации совпадает с адресом проживания, 0- адрес регистрации отличается от
     *  ардеса
     *  проживания
     *
     * @param boolean $sameAddress
     * @return static
     */
    public function setSameAddress($sameAddress)
    {
        $this->sameAddress = $sameAddress;
        return $this;
    }

    /**
     * Gets as placeOfResidence
     *
     * Адрес проживания
     *
     * @return \common\models\sbbolxml\request\AddressType
     */
    public function getPlaceOfResidence()
    {
        return $this->placeOfResidence;
    }

    /**
     * Sets a new placeOfResidence
     *
     * Адрес проживания
     *
     * @param \common\models\sbbolxml\request\AddressType $placeOfResidence
     * @return static
     */
    public function setPlaceOfResidence(\common\models\sbbolxml\request\AddressType $placeOfResidence)
    {
        $this->placeOfResidence = $placeOfResidence;
        return $this;
    }

    /**
     * Gets as iNN
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
     * @param string $iNN
     * @return static
     */
    public function setINN($iNN)
    {
        $this->iNN = $iNN;
        return $this;
    }

    /**
     * Gets as textCard
     *
     * Текст эмбоссированный на ПК. Например: TATIANA M/IVANOVA
     *  или TANIA/IVANOVA/MRS
     *
     * @return \common\models\sbbolxml\request\RegOfIssCardsType\ListOfIssCardsAType\IssCardInfoAType\TextCardAType
     */
    public function getTextCard()
    {
        return $this->textCard;
    }

    /**
     * Sets a new textCard
     *
     * Текст эмбоссированный на ПК. Например: TATIANA M/IVANOVA
     *  или TANIA/IVANOVA/MRS
     *
     * @param \common\models\sbbolxml\request\RegOfIssCardsType\ListOfIssCardsAType\IssCardInfoAType\TextCardAType $textCard
     * @return static
     */
    public function setTextCard(\common\models\sbbolxml\request\RegOfIssCardsType\ListOfIssCardsAType\IssCardInfoAType\TextCardAType $textCard)
    {
        $this->textCard = $textCard;
        return $this;
    }

    /**
     * Gets as identityDoc
     *
     * Документ, удостоверяющий личность
     *
     * @return \common\models\sbbolxml\request\IdentityDocType
     */
    public function getIdentityDoc()
    {
        return $this->identityDoc;
    }

    /**
     * Sets a new identityDoc
     *
     * Документ, удостоверяющий личность
     *
     * @param \common\models\sbbolxml\request\IdentityDocType $identityDoc
     * @return static
     */
    public function setIdentityDoc(\common\models\sbbolxml\request\IdentityDocType $identityDoc)
    {
        $this->identityDoc = $identityDoc;
        return $this;
    }

    /**
     * Gets as position
     *
     * Должность
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
     * Должность
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
     * Gets as category
     *
     * Категория населения. Возможные значения:
     *  207,0,212,217,218.
     *  207 – лица, перечисляющие зарплату на счета;
     *  0 – пенсионеры;
     *  212 – зарплата с разрешенным овердрафтом для сотрудников банка;
     *  217 - зарплата с разрешенным овердрафтом для сотрудников организации;
     *  218 – студенческая (договор с учебным заведением).
     *
     *  Можно заполнить автоматически
     *
     * @return string
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Sets a new category
     *
     * Категория населения. Возможные значения:
     *  207,0,212,217,218.
     *  207 – лица, перечисляющие зарплату на счета;
     *  0 – пенсионеры;
     *  212 – зарплата с разрешенным овердрафтом для сотрудников банка;
     *  217 - зарплата с разрешенным овердрафтом для сотрудников организации;
     *  218 – студенческая (договор с учебным заведением).
     *
     *  Можно заполнить автоматически
     *
     * @param string $category
     * @return static
     */
    public function setCategory($category)
    {
        $this->category = $category;
        return $this;
    }

    /**
     * Gets as contactInfo
     *
     * Контактные данные
     *
     * @return \common\models\sbbolxml\request\RegOfIssCardsType\ListOfIssCardsAType\IssCardInfoAType\ContactInfoAType
     */
    public function getContactInfo()
    {
        return $this->contactInfo;
    }

    /**
     * Sets a new contactInfo
     *
     * Контактные данные
     *
     * @param \common\models\sbbolxml\request\RegOfIssCardsType\ListOfIssCardsAType\IssCardInfoAType\ContactInfoAType $contactInfo
     * @return static
     */
    public function setContactInfo(\common\models\sbbolxml\request\RegOfIssCardsType\ListOfIssCardsAType\IssCardInfoAType\ContactInfoAType $contactInfo)
    {
        $this->contactInfo = $contactInfo;
        return $this;
    }

    /**
     * Gets as cardInfo
     *
     * Информация о картах
     *
     * @return \common\models\sbbolxml\request\RegOfIssCardsType\ListOfIssCardsAType\IssCardInfoAType\CardInfoAType
     */
    public function getCardInfo()
    {
        return $this->cardInfo;
    }

    /**
     * Sets a new cardInfo
     *
     * Информация о картах
     *
     * @param \common\models\sbbolxml\request\RegOfIssCardsType\ListOfIssCardsAType\IssCardInfoAType\CardInfoAType $cardInfo
     * @return static
     */
    public function setCardInfo(\common\models\sbbolxml\request\RegOfIssCardsType\ListOfIssCardsAType\IssCardInfoAType\CardInfoAType $cardInfo)
    {
        $this->cardInfo = $cardInfo;
        return $this;
    }

    /**
     * Gets as oSBNum
     *
     * Номер отделения Сбербанка для места обслуживания физ.лица (для АС Прометей, 4 символа)
     *
     * @return string
     */
    public function getOSBNum()
    {
        return $this->oSBNum;
    }

    /**
     * Sets a new oSBNum
     *
     * Номер отделения Сбербанка для места обслуживания физ.лица (для АС Прометей, 4 символа)
     *
     * @param string $oSBNum
     * @return static
     */
    public function setOSBNum($oSBNum)
    {
        $this->oSBNum = $oSBNum;
        return $this;
    }

    /**
     * Gets as branchOSBNum
     *
     * Номер внутреннего структурного подразделения (филиала)
     *  для места обслуживания физ.лица (для АС Прометей, 4 символа)
     *
     * @return string
     */
    public function getBranchOSBNum()
    {
        return $this->branchOSBNum;
    }

    /**
     * Sets a new branchOSBNum
     *
     * Номер внутреннего структурного подразделения (филиала)
     *  для места обслуживания физ.лица (для АС Прометей, 4 символа)
     *
     * @param string $branchOSBNum
     * @return static
     */
    public function setBranchOSBNum($branchOSBNum)
    {
        $this->branchOSBNum = $branchOSBNum;
        return $this;
    }

    /**
     * Gets as tBNum
     *
     * Код Территорильного банка СБРФ для места
     *  обслуживания физ.лиц (для АС Прометей, 2 символа)
     *
     * @return string
     */
    public function getTBNum()
    {
        return $this->tBNum;
    }

    /**
     * Sets a new tBNum
     *
     * Код Территорильного банка СБРФ для места
     *  обслуживания физ.лиц (для АС Прометей, 2 символа)
     *
     * @param string $tBNum
     * @return static
     */
    public function setTBNum($tBNum)
    {
        $this->tBNum = $tBNum;
        return $this;
    }

    /**
     * Gets as serviceBranchCode
     *
     * Код подразделения места обслуживания физ. лица
     *
     * @return string
     */
    public function getServiceBranchCode()
    {
        return $this->serviceBranchCode;
    }

    /**
     * Sets a new serviceBranchCode
     *
     * Код подразделения места обслуживания физ. лица
     *
     * @param string $serviceBranchCode
     * @return static
     */
    public function setServiceBranchCode($serviceBranchCode)
    {
        $this->serviceBranchCode = $serviceBranchCode;
        return $this;
    }

    /**
     * Gets as serviceBranchName
     *
     * Название подразделения места обслуживания физ. лица
     *
     * @return string
     */
    public function getServiceBranchName()
    {
        return $this->serviceBranchName;
    }

    /**
     * Sets a new serviceBranchName
     *
     * Название подразделения места обслуживания физ. лица
     *
     * @param string $serviceBranchName
     * @return static
     */
    public function setServiceBranchName($serviceBranchName)
    {
        $this->serviceBranchName = $serviceBranchName;
        return $this;
    }

    /**
     * Gets as sendReport
     *
     * Если отмечен флаг в поле «Рассылка отчета по Internet» (З.2) экранной формы «Добавление записи ФЛ», то указывать значение «1», иначе пусто
     *
     * @return boolean
     */
    public function getSendReport()
    {
        return $this->sendReport;
    }

    /**
     * Sets a new sendReport
     *
     * Если отмечен флаг в поле «Рассылка отчета по Internet» (З.2) экранной формы «Добавление записи ФЛ», то указывать значение «1», иначе пусто
     *
     * @param boolean $sendReport
     * @return static
     */
    public function setSendReport($sendReport)
    {
        $this->sendReport = $sendReport;
        return $this;
    }

    /**
     * Gets as designID
     *
     * Значение поля «Код индивидуального дизайна» (З.3) экранной формы «Добавление записи ФЛ»
     *
     * @return string
     */
    public function getDesignID()
    {
        return $this->designID;
    }

    /**
     * Sets a new designID
     *
     * Значение поля «Код индивидуального дизайна» (З.3) экранной формы «Добавление записи ФЛ»
     *
     * @param string $designID
     * @return static
     */
    public function setDesignID($designID)
    {
        $this->designID = $designID;
        return $this;
    }


}


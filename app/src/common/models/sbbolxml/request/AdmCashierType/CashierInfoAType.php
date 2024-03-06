<?php

namespace common\models\sbbolxml\request\AdmCashierType;

/**
 * Class representing CashierInfoAType
 *
 * Данные вносителя
 */
class CashierInfoAType
{

    /**
     * Фамилия
     *  ОБЯЗАТЕЛЬНО ДЛЯ ЗАПОЛНЕНИЯ
     *  Версия 1
     *
     * @property string $lastName
     */
    private $lastName = null;

    /**
     * Имя
     *  ОБЯЗАТЕЛЬНО ДЛЯ ЗАПОЛНЕНИЯ
     *  Версия 1
     *
     * @property string $firstName
     */
    private $firstName = null;

    /**
     * Отчество
     *  ОБЯЗАТЕЛЬНО ДЛЯ ЗАПОЛНЕНИЯ
     *  Версия 1
     *
     * @property string $middleName
     */
    private $middleName = null;

    /**
     * ФИО вносителя
     *  ОБЯЗАТЕЛЬНО ДЛЯ ЗАПОЛНЕНИЯ(В версии 1)
     *
     * @property string $fullName
     */
    private $fullName = null;

    /**
     * ФИО в верхнем регистре
     *  ОБЯЗАТЕЛЬНО ДЛЯ ЗАПОЛНЕНИЯ
     *  Версия 1
     *
     * @property string $fullNameUC
     */
    private $fullNameUC = null;

    /**
     * Дата рождения
     *
     * @property \DateTime $dateOfBirth
     */
    private $dateOfBirth = null;

    /**
     * Место рождения
     *  ОБЯЗАТЕЛЬНО ДЛЯ ЗАПОЛНЕНИЯ
     *  Версия 1
     *
     * @property string $birthPlace
     */
    private $birthPlace = null;

    /**
     * Номер телефона
     *  Доступно для редактирования, после первого подписания документа(правило актуально для версии 1)
     *
     * @property string $phone
     */
    private $phone = null;

    /**
     * Блокировка вносителя, 1 - признак установлен, 0 - признак не установлен.
     *  Доступно для редактирования, после первого подписания документа(правило актуально для версии 1)
     *
     * @property boolean $blocked
     */
    private $blocked = null;

    /**
     * Согласие с условиями приёма денежной наличности через устройства самообслуживания.
     *  ОБЯЗАТЕЛЬНО ДЛЯ ЗАПОЛНЕНИЯ
     *  Версия 1
     *
     * @property boolean $agreedAdmOperation
     */
    private $agreedAdmOperation = null;

    /**
     * Согласие на обработку персональных данных
     *
     * @property boolean $agreeSavePD
     */
    private $agreeSavePD = null;

    /**
     * Признак «Наделение полномочий» обязателен для заполнения для новой версии
     *
     * @property boolean $agreedEmpowerment
     */
    private $agreedEmpowerment = null;

    /**
     * Реквизиты вносителя для УС
     *
     * @property \common\models\sbbolxml\request\AdmCashierType\CashierInfoAType\DocInfoAType $docInfo
     */
    private $docInfo = null;

    /**
     * Адрес регистрации
     *  Группа полей доступна для редактирования, после первого подписания документа
     *  ОБЯЗАТЕЛЬНО ДЛЯ ЗАПОЛНЕНИЯ
     *  Версия 1
     *
     * @property \common\models\sbbolxml\request\AdmCashierType\CashierInfoAType\RegAddrAType $regAddr
     */
    private $regAddr = null;

    /**
     * Gets as lastName
     *
     * Фамилия
     *  ОБЯЗАТЕЛЬНО ДЛЯ ЗАПОЛНЕНИЯ
     *  Версия 1
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Sets a new lastName
     *
     * Фамилия
     *  ОБЯЗАТЕЛЬНО ДЛЯ ЗАПОЛНЕНИЯ
     *  Версия 1
     *
     * @param string $lastName
     * @return static
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
        return $this;
    }

    /**
     * Gets as firstName
     *
     * Имя
     *  ОБЯЗАТЕЛЬНО ДЛЯ ЗАПОЛНЕНИЯ
     *  Версия 1
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
     * Имя
     *  ОБЯЗАТЕЛЬНО ДЛЯ ЗАПОЛНЕНИЯ
     *  Версия 1
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
     * Gets as middleName
     *
     * Отчество
     *  ОБЯЗАТЕЛЬНО ДЛЯ ЗАПОЛНЕНИЯ
     *  Версия 1
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
     * Отчество
     *  ОБЯЗАТЕЛЬНО ДЛЯ ЗАПОЛНЕНИЯ
     *  Версия 1
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
     * Gets as fullName
     *
     * ФИО вносителя
     *  ОБЯЗАТЕЛЬНО ДЛЯ ЗАПОЛНЕНИЯ(В версии 1)
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
     * ФИО вносителя
     *  ОБЯЗАТЕЛЬНО ДЛЯ ЗАПОЛНЕНИЯ(В версии 1)
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
     * Gets as fullNameUC
     *
     * ФИО в верхнем регистре
     *  ОБЯЗАТЕЛЬНО ДЛЯ ЗАПОЛНЕНИЯ
     *  Версия 1
     *
     * @return string
     */
    public function getFullNameUC()
    {
        return $this->fullNameUC;
    }

    /**
     * Sets a new fullNameUC
     *
     * ФИО в верхнем регистре
     *  ОБЯЗАТЕЛЬНО ДЛЯ ЗАПОЛНЕНИЯ
     *  Версия 1
     *
     * @param string $fullNameUC
     * @return static
     */
    public function setFullNameUC($fullNameUC)
    {
        $this->fullNameUC = $fullNameUC;
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
     *  ОБЯЗАТЕЛЬНО ДЛЯ ЗАПОЛНЕНИЯ
     *  Версия 1
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
     *  ОБЯЗАТЕЛЬНО ДЛЯ ЗАПОЛНЕНИЯ
     *  Версия 1
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
     * Gets as phone
     *
     * Номер телефона
     *  Доступно для редактирования, после первого подписания документа(правило актуально для версии 1)
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Sets a new phone
     *
     * Номер телефона
     *  Доступно для редактирования, после первого подписания документа(правило актуально для версии 1)
     *
     * @param string $phone
     * @return static
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
        return $this;
    }

    /**
     * Gets as blocked
     *
     * Блокировка вносителя, 1 - признак установлен, 0 - признак не установлен.
     *  Доступно для редактирования, после первого подписания документа(правило актуально для версии 1)
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
     * Блокировка вносителя, 1 - признак установлен, 0 - признак не установлен.
     *  Доступно для редактирования, после первого подписания документа(правило актуально для версии 1)
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
     * Gets as agreedAdmOperation
     *
     * Согласие с условиями приёма денежной наличности через устройства самообслуживания.
     *  ОБЯЗАТЕЛЬНО ДЛЯ ЗАПОЛНЕНИЯ
     *  Версия 1
     *
     * @return boolean
     */
    public function getAgreedAdmOperation()
    {
        return $this->agreedAdmOperation;
    }

    /**
     * Sets a new agreedAdmOperation
     *
     * Согласие с условиями приёма денежной наличности через устройства самообслуживания.
     *  ОБЯЗАТЕЛЬНО ДЛЯ ЗАПОЛНЕНИЯ
     *  Версия 1
     *
     * @param boolean $agreedAdmOperation
     * @return static
     */
    public function setAgreedAdmOperation($agreedAdmOperation)
    {
        $this->agreedAdmOperation = $agreedAdmOperation;
        return $this;
    }

    /**
     * Gets as agreeSavePD
     *
     * Согласие на обработку персональных данных
     *
     * @return boolean
     */
    public function getAgreeSavePD()
    {
        return $this->agreeSavePD;
    }

    /**
     * Sets a new agreeSavePD
     *
     * Согласие на обработку персональных данных
     *
     * @param boolean $agreeSavePD
     * @return static
     */
    public function setAgreeSavePD($agreeSavePD)
    {
        $this->agreeSavePD = $agreeSavePD;
        return $this;
    }

    /**
     * Gets as agreedEmpowerment
     *
     * Признак «Наделение полномочий» обязателен для заполнения для новой версии
     *
     * @return boolean
     */
    public function getAgreedEmpowerment()
    {
        return $this->agreedEmpowerment;
    }

    /**
     * Sets a new agreedEmpowerment
     *
     * Признак «Наделение полномочий» обязателен для заполнения для новой версии
     *
     * @param boolean $agreedEmpowerment
     * @return static
     */
    public function setAgreedEmpowerment($agreedEmpowerment)
    {
        $this->agreedEmpowerment = $agreedEmpowerment;
        return $this;
    }

    /**
     * Gets as docInfo
     *
     * Реквизиты вносителя для УС
     *
     * @return \common\models\sbbolxml\request\AdmCashierType\CashierInfoAType\DocInfoAType
     */
    public function getDocInfo()
    {
        return $this->docInfo;
    }

    /**
     * Sets a new docInfo
     *
     * Реквизиты вносителя для УС
     *
     * @param \common\models\sbbolxml\request\AdmCashierType\CashierInfoAType\DocInfoAType $docInfo
     * @return static
     */
    public function setDocInfo(\common\models\sbbolxml\request\AdmCashierType\CashierInfoAType\DocInfoAType $docInfo)
    {
        $this->docInfo = $docInfo;
        return $this;
    }

    /**
     * Gets as regAddr
     *
     * Адрес регистрации
     *  Группа полей доступна для редактирования, после первого подписания документа
     *  ОБЯЗАТЕЛЬНО ДЛЯ ЗАПОЛНЕНИЯ
     *  Версия 1
     *
     * @return \common\models\sbbolxml\request\AdmCashierType\CashierInfoAType\RegAddrAType
     */
    public function getRegAddr()
    {
        return $this->regAddr;
    }

    /**
     * Sets a new regAddr
     *
     * Адрес регистрации
     *  Группа полей доступна для редактирования, после первого подписания документа
     *  ОБЯЗАТЕЛЬНО ДЛЯ ЗАПОЛНЕНИЯ
     *  Версия 1
     *
     * @param \common\models\sbbolxml\request\AdmCashierType\CashierInfoAType\RegAddrAType $regAddr
     * @return static
     */
    public function setRegAddr(\common\models\sbbolxml\request\AdmCashierType\CashierInfoAType\RegAddrAType $regAddr)
    {
        $this->regAddr = $regAddr;
        return $this;
    }


}


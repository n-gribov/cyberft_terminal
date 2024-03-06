<?php

namespace common\models\sbbolxml\response\AdmCashierType;

/**
 * Class representing CashierInfoAType
 *
 * Данные вносителя
 */
class CashierInfoAType
{

    /**
     * Фамилия
     *
     * @property string $lastName
     */
    private $lastName = null;

    /**
     * Имя
     *
     * @property string $firstName
     */
    private $firstName = null;

    /**
     * Отчество
     *
     * @property string $middleName
     */
    private $middleName = null;

    /**
     * ФИО вносителя
     *
     * @property string $fullName
     */
    private $fullName = null;

    /**
     * ФИО в верхнем регистре
     *
     * @property string $fullNameUC
     */
    private $fullNameUC = null;

    /**
     * Логин вносителя
     *
     * @property string $login
     */
    private $login = null;

    /**
     * Дата рождения
     *
     * @property \DateTime $dateOfBirth
     */
    private $dateOfBirth = null;

    /**
     * Место рожденияе
     *
     * @property string $birthPlace
     */
    private $birthPlace = null;

    /**
     * Номер телефона
     *
     * @property string $phone
     */
    private $phone = null;

    /**
     * Блокировка вносителя, 1 - признак установлен, 0 - признак не установлен.
     *
     * @property boolean $blocked
     */
    private $blocked = null;

    /**
     * Согласие с условиями приёма денежной наличности через устройства самообслуживания.
     *
     * @property boolean $agreedAdmOperation
     */
    private $agreedAdmOperation = null;

    /**
     * Согласие с наделением полномочиями на внесение наличных денежных средств через устройства самообслуживания на р/с Клиента.
     *  1 – согласие
     *  0 – несогласие
     *
     * @property boolean $agreedEmpowerment
     */
    private $agreedEmpowerment = null;

    /**
     * Согласие на обработку персональных данных
     *
     * @property boolean $agreeSavePD
     */
    private $agreeSavePD = null;

    /**
     * Реквизиты вносителя для УС
     *
     * @property \common\models\sbbolxml\response\AdmCashierType\CashierInfoAType\DocInfoAType $docInfo
     */
    private $docInfo = null;

    /**
     * Адрес регистрации
     *
     * @property \common\models\sbbolxml\response\AdmCashierType\CashierInfoAType\RegAddrAType $regAddr
     */
    private $regAddr = null;

    /**
     * Gets as lastName
     *
     * Фамилия
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
     * Gets as login
     *
     * Логин вносителя
     *
     * @return string
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * Sets a new login
     *
     * Логин вносителя
     *
     * @param string $login
     * @return static
     */
    public function setLogin($login)
    {
        $this->login = $login;
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
     * Место рожденияе
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
     * Место рожденияе
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
     * Gets as agreedEmpowerment
     *
     * Согласие с наделением полномочиями на внесение наличных денежных средств через устройства самообслуживания на р/с Клиента.
     *  1 – согласие
     *  0 – несогласие
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
     * Согласие с наделением полномочиями на внесение наличных денежных средств через устройства самообслуживания на р/с Клиента.
     *  1 – согласие
     *  0 – несогласие
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
     * Gets as docInfo
     *
     * Реквизиты вносителя для УС
     *
     * @return \common\models\sbbolxml\response\AdmCashierType\CashierInfoAType\DocInfoAType
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
     * @param \common\models\sbbolxml\response\AdmCashierType\CashierInfoAType\DocInfoAType $docInfo
     * @return static
     */
    public function setDocInfo(\common\models\sbbolxml\response\AdmCashierType\CashierInfoAType\DocInfoAType $docInfo)
    {
        $this->docInfo = $docInfo;
        return $this;
    }

    /**
     * Gets as regAddr
     *
     * Адрес регистрации
     *
     * @return \common\models\sbbolxml\response\AdmCashierType\CashierInfoAType\RegAddrAType
     */
    public function getRegAddr()
    {
        return $this->regAddr;
    }

    /**
     * Sets a new regAddr
     *
     * Адрес регистрации
     *
     * @param \common\models\sbbolxml\response\AdmCashierType\CashierInfoAType\RegAddrAType $regAddr
     * @return static
     */
    public function setRegAddr(\common\models\sbbolxml\response\AdmCashierType\CashierInfoAType\RegAddrAType $regAddr)
    {
        $this->regAddr = $regAddr;
        return $this;
    }


}


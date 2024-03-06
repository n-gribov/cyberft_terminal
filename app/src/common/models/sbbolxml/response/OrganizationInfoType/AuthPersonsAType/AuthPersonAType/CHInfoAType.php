<?php

namespace common\models\sbbolxml\response\OrganizationInfoType\AuthPersonsAType\AuthPersonAType;

/**
 * Class representing CHInfoAType
 */
class CHInfoAType
{

    /**
     * Код физического лица в СН
     *
     * @property string $cHId
     */
    private $cHId = null;

    /**
     * Оператор отправки
     *  (Допустимые значения Intervale SMS, SMS, EMAIL, XML, MFMS
     *  SMS, mms)
     *
     * @property string $operator
     */
    private $operator = null;

    /**
     * ФИО
     *
     * @property string $fIO
     */
    private $fIO = null;

    /**
     * Дата рождения
     *
     * @property \DateTime $birthDate
     */
    private $birthDate = null;

    /**
     * Номер мобильного телефона
     *
     * @property string $mobilTel
     */
    private $mobilTel = null;

    /**
     * Адрес электронной почты
     *
     * @property string $email
     */
    private $email = null;

    /**
     * Внешняя система
     *
     * @property string $intSystem
     */
    private $intSystem = null;

    /**
     * Gets as cHId
     *
     * Код физического лица в СН
     *
     * @return string
     */
    public function getCHId()
    {
        return $this->cHId;
    }

    /**
     * Sets a new cHId
     *
     * Код физического лица в СН
     *
     * @param string $cHId
     * @return static
     */
    public function setCHId($cHId)
    {
        $this->cHId = $cHId;
        return $this;
    }

    /**
     * Gets as operator
     *
     * Оператор отправки
     *  (Допустимые значения Intervale SMS, SMS, EMAIL, XML, MFMS
     *  SMS, mms)
     *
     * @return string
     */
    public function getOperator()
    {
        return $this->operator;
    }

    /**
     * Sets a new operator
     *
     * Оператор отправки
     *  (Допустимые значения Intervale SMS, SMS, EMAIL, XML, MFMS
     *  SMS, mms)
     *
     * @param string $operator
     * @return static
     */
    public function setOperator($operator)
    {
        $this->operator = $operator;
        return $this;
    }

    /**
     * Gets as fIO
     *
     * ФИО
     *
     * @return string
     */
    public function getFIO()
    {
        return $this->fIO;
    }

    /**
     * Sets a new fIO
     *
     * ФИО
     *
     * @param string $fIO
     * @return static
     */
    public function setFIO($fIO)
    {
        $this->fIO = $fIO;
        return $this;
    }

    /**
     * Gets as birthDate
     *
     * Дата рождения
     *
     * @return \DateTime
     */
    public function getBirthDate()
    {
        return $this->birthDate;
    }

    /**
     * Sets a new birthDate
     *
     * Дата рождения
     *
     * @param \DateTime $birthDate
     * @return static
     */
    public function setBirthDate(\DateTime $birthDate)
    {
        $this->birthDate = $birthDate;
        return $this;
    }

    /**
     * Gets as mobilTel
     *
     * Номер мобильного телефона
     *
     * @return string
     */
    public function getMobilTel()
    {
        return $this->mobilTel;
    }

    /**
     * Sets a new mobilTel
     *
     * Номер мобильного телефона
     *
     * @param string $mobilTel
     * @return static
     */
    public function setMobilTel($mobilTel)
    {
        $this->mobilTel = $mobilTel;
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

    /**
     * Gets as intSystem
     *
     * Внешняя система
     *
     * @return string
     */
    public function getIntSystem()
    {
        return $this->intSystem;
    }

    /**
     * Sets a new intSystem
     *
     * Внешняя система
     *
     * @param string $intSystem
     * @return static
     */
    public function setIntSystem($intSystem)
    {
        $this->intSystem = $intSystem;
        return $this;
    }


}


<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing UserDataType
 *
 * Информация об отправителе ПСФ
 * XSD Type: UserData
 */
class UserDataType
{

    /**
     * Фамилия пользователя, отправившего ПСФ
     *
     * @property string $lastName
     */
    private $lastName = null;

    /**
     * Имя пользователя, отправившего ПСФ
     *
     * @property string $firstName
     */
    private $firstName = null;

    /**
     * Отчество пользователя, отправившего ПСФ
     *
     * @property string $middleName
     */
    private $middleName = null;

    /**
     * Дата рождения пользователя, отправившего ПСФ
     *
     * @property \DateTime $birthDate
     */
    private $birthDate = null;

    /**
     * Мобильный телефон пользователя, создавшего ПСФ
     *
     * @property string $mobilePhone
     */
    private $mobilePhone = null;

    /**
     * Номер рабочего телефона пользователя клиента, создавшего ПСФ
     *
     * @property string $workPhone
     */
    private $workPhone = null;

    /**
     * E-mail пользователя, создавшего ПСФ
     *
     * @property string $email
     */
    private $email = null;

    /**
     * Gets as lastName
     *
     * Фамилия пользователя, отправившего ПСФ
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
     * Фамилия пользователя, отправившего ПСФ
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
     * Имя пользователя, отправившего ПСФ
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
     * Имя пользователя, отправившего ПСФ
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
     * Отчество пользователя, отправившего ПСФ
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
     * Отчество пользователя, отправившего ПСФ
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
     * Gets as birthDate
     *
     * Дата рождения пользователя, отправившего ПСФ
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
     * Дата рождения пользователя, отправившего ПСФ
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
     * Gets as mobilePhone
     *
     * Мобильный телефон пользователя, создавшего ПСФ
     *
     * @return string
     */
    public function getMobilePhone()
    {
        return $this->mobilePhone;
    }

    /**
     * Sets a new mobilePhone
     *
     * Мобильный телефон пользователя, создавшего ПСФ
     *
     * @param string $mobilePhone
     * @return static
     */
    public function setMobilePhone($mobilePhone)
    {
        $this->mobilePhone = $mobilePhone;
        return $this;
    }

    /**
     * Gets as workPhone
     *
     * Номер рабочего телефона пользователя клиента, создавшего ПСФ
     *
     * @return string
     */
    public function getWorkPhone()
    {
        return $this->workPhone;
    }

    /**
     * Sets a new workPhone
     *
     * Номер рабочего телефона пользователя клиента, создавшего ПСФ
     *
     * @param string $workPhone
     * @return static
     */
    public function setWorkPhone($workPhone)
    {
        $this->workPhone = $workPhone;
        return $this;
    }

    /**
     * Gets as email
     *
     * E-mail пользователя, создавшего ПСФ
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
     * E-mail пользователя, создавшего ПСФ
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


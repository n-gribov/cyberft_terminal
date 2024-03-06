<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing RegistrationDataType
 *
 * Содержит информацию о регистрации
 * XSD Type: RegistrationData
 */
class RegistrationDataType
{

    /**
     * Наименование регистрирующего органа
     *
     * @property string $registeredBy
     */
    private $registeredBy = null;

    /**
     * Место регистрации (город, страна)
     *
     * @property string $registrationPlace
     */
    private $registrationPlace = null;

    /**
     * Gets as registeredBy
     *
     * Наименование регистрирующего органа
     *
     * @return string
     */
    public function getRegisteredBy()
    {
        return $this->registeredBy;
    }

    /**
     * Sets a new registeredBy
     *
     * Наименование регистрирующего органа
     *
     * @param string $registeredBy
     * @return static
     */
    public function setRegisteredBy($registeredBy)
    {
        $this->registeredBy = $registeredBy;
        return $this;
    }

    /**
     * Gets as registrationPlace
     *
     * Место регистрации (город, страна)
     *
     * @return string
     */
    public function getRegistrationPlace()
    {
        return $this->registrationPlace;
    }

    /**
     * Sets a new registrationPlace
     *
     * Место регистрации (город, страна)
     *
     * @param string $registrationPlace
     * @return static
     */
    public function setRegistrationPlace($registrationPlace)
    {
        $this->registrationPlace = $registrationPlace;
        return $this;
    }


}


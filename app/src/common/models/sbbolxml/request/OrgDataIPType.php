<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing OrgDataIPType
 *
 *
 * XSD Type: OrgDataIP
 */
class OrgDataIPType
{

    /**
     * ФИО
     *
     * @property string $clientNameIntheGenitive
     */
    private $clientNameIntheGenitive = null;

    /**
     * ФИО
     *
     * @property string $fio
     */
    private $fio = null;

    /**
     * Дата и место рождения
     *
     * @property string $birthDayAndPlace
     */
    private $birthDayAndPlace = null;

    /**
     * Дата и место рождения
     *
     * @property string $citizenship
     */
    private $citizenship = null;

    /**
     * Gets as clientNameIntheGenitive
     *
     * ФИО
     *
     * @return string
     */
    public function getClientNameIntheGenitive()
    {
        return $this->clientNameIntheGenitive;
    }

    /**
     * Sets a new clientNameIntheGenitive
     *
     * ФИО
     *
     * @param string $clientNameIntheGenitive
     * @return static
     */
    public function setClientNameIntheGenitive($clientNameIntheGenitive)
    {
        $this->clientNameIntheGenitive = $clientNameIntheGenitive;
        return $this;
    }

    /**
     * Gets as fio
     *
     * ФИО
     *
     * @return string
     */
    public function getFio()
    {
        return $this->fio;
    }

    /**
     * Sets a new fio
     *
     * ФИО
     *
     * @param string $fio
     * @return static
     */
    public function setFio($fio)
    {
        $this->fio = $fio;
        return $this;
    }

    /**
     * Gets as birthDayAndPlace
     *
     * Дата и место рождения
     *
     * @return string
     */
    public function getBirthDayAndPlace()
    {
        return $this->birthDayAndPlace;
    }

    /**
     * Sets a new birthDayAndPlace
     *
     * Дата и место рождения
     *
     * @param string $birthDayAndPlace
     * @return static
     */
    public function setBirthDayAndPlace($birthDayAndPlace)
    {
        $this->birthDayAndPlace = $birthDayAndPlace;
        return $this;
    }

    /**
     * Gets as citizenship
     *
     * Дата и место рождения
     *
     * @return string
     */
    public function getCitizenship()
    {
        return $this->citizenship;
    }

    /**
     * Sets a new citizenship
     *
     * Дата и место рождения
     *
     * @param string $citizenship
     * @return static
     */
    public function setCitizenship($citizenship)
    {
        $this->citizenship = $citizenship;
        return $this;
    }


}


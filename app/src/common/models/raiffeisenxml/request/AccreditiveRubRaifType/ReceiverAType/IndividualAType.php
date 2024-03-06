<?php

namespace common\models\raiffeisenxml\request\AccreditiveRubRaifType\ReceiverAType;

/**
 * Class representing IndividualAType
 */
class IndividualAType
{

    /**
     * ИНН
     *
     * @property string $inn
     */
    private $inn = null;

    /**
     * Дата рождения
     *
     * @property \DateTime $birthDate
     */
    private $birthDate = null;

    /**
     * ФИО
     *
     * @property string $fio
     */
    private $fio = null;

    /**
     * Адрес
     *
     * @property string $address
     */
    private $address = null;

    /**
     * Удостоверяющий документ
     *
     * @property \common\models\raiffeisenxml\request\AccreditiveRubRaifType\ReceiverAType\IndividualAType\DocAType $doc
     */
    private $doc = null;

    /**
     * Дополнительная информация
     *
     * @property string $addInfo
     */
    private $addInfo = null;

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
     * Gets as address
     *
     * Адрес
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Sets a new address
     *
     * Адрес
     *
     * @param string $address
     * @return static
     */
    public function setAddress($address)
    {
        $this->address = $address;
        return $this;
    }

    /**
     * Gets as doc
     *
     * Удостоверяющий документ
     *
     * @return \common\models\raiffeisenxml\request\AccreditiveRubRaifType\ReceiverAType\IndividualAType\DocAType
     */
    public function getDoc()
    {
        return $this->doc;
    }

    /**
     * Sets a new doc
     *
     * Удостоверяющий документ
     *
     * @param \common\models\raiffeisenxml\request\AccreditiveRubRaifType\ReceiverAType\IndividualAType\DocAType $doc
     * @return static
     */
    public function setDoc(\common\models\raiffeisenxml\request\AccreditiveRubRaifType\ReceiverAType\IndividualAType\DocAType $doc)
    {
        $this->doc = $doc;
        return $this;
    }

    /**
     * Gets as addInfo
     *
     * Дополнительная информация
     *
     * @return string
     */
    public function getAddInfo()
    {
        return $this->addInfo;
    }

    /**
     * Sets a new addInfo
     *
     * Дополнительная информация
     *
     * @param string $addInfo
     * @return static
     */
    public function setAddInfo($addInfo)
    {
        $this->addInfo = $addInfo;
        return $this;
    }


}


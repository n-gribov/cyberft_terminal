<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing CptyType
 *
 * Информация о контрагенте
 * XSD Type: CptyType
 */
class CptyType
{

    /**
     * Уникальный идентификатор организации в АС СББОЛ
     *
     * @property string $uID
     */
    private $uID = null;

    /**
     * Наименование организации в АС СББОЛ
     *
     * @property string $name
     */
    private $name = null;

    /**
     * Адрес организации в АС СББОЛ
     *
     * @property string $address
     */
    private $address = null;

    /**
     * Признак возможности ввода для данного клиента новых ПИ
     *
     * @property boolean $newSIInd
     */
    private $newSIInd = null;

    /**
     * Номер генерального соглашения
     *
     * @property string $genAgrNum
     */
    private $genAgrNum = null;

    /**
     * Дата генерального соглашения
     *
     * @property \DateTime $genAgrDate
     */
    private $genAgrDate = null;

    /**
     * Gets as uID
     *
     * Уникальный идентификатор организации в АС СББОЛ
     *
     * @return string
     */
    public function getUID()
    {
        return $this->uID;
    }

    /**
     * Sets a new uID
     *
     * Уникальный идентификатор организации в АС СББОЛ
     *
     * @param string $uID
     * @return static
     */
    public function setUID($uID)
    {
        $this->uID = $uID;
        return $this;
    }

    /**
     * Gets as name
     *
     * Наименование организации в АС СББОЛ
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
     * Наименование организации в АС СББОЛ
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
     * Gets as address
     *
     * Адрес организации в АС СББОЛ
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
     * Адрес организации в АС СББОЛ
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
     * Gets as newSIInd
     *
     * Признак возможности ввода для данного клиента новых ПИ
     *
     * @return boolean
     */
    public function getNewSIInd()
    {
        return $this->newSIInd;
    }

    /**
     * Sets a new newSIInd
     *
     * Признак возможности ввода для данного клиента новых ПИ
     *
     * @param boolean $newSIInd
     * @return static
     */
    public function setNewSIInd($newSIInd)
    {
        $this->newSIInd = $newSIInd;
        return $this;
    }

    /**
     * Gets as genAgrNum
     *
     * Номер генерального соглашения
     *
     * @return string
     */
    public function getGenAgrNum()
    {
        return $this->genAgrNum;
    }

    /**
     * Sets a new genAgrNum
     *
     * Номер генерального соглашения
     *
     * @param string $genAgrNum
     * @return static
     */
    public function setGenAgrNum($genAgrNum)
    {
        $this->genAgrNum = $genAgrNum;
        return $this;
    }

    /**
     * Gets as genAgrDate
     *
     * Дата генерального соглашения
     *
     * @return \DateTime
     */
    public function getGenAgrDate()
    {
        return $this->genAgrDate;
    }

    /**
     * Sets a new genAgrDate
     *
     * Дата генерального соглашения
     *
     * @param \DateTime $genAgrDate
     * @return static
     */
    public function setGenAgrDate(\DateTime $genAgrDate)
    {
        $this->genAgrDate = $genAgrDate;
        return $this;
    }


}


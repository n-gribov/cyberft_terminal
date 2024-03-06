<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing ElectionComissionInfoType
 *
 * Информация для избирательной комиссии
 * XSD Type: ElectionComissionInfo
 */
class ElectionComissionInfoType
{

    /**
     * Номер первичного документа
     *
     * @property string $documentCLNT
     */
    private $documentCLNT = null;

    /**
     * Дата первичного документа
     *
     * @property \DateTime $dateCLNT
     */
    private $dateCLNT = null;

    /**
     * Название вида операции
     *
     * @property string $operationTypeText
     */
    private $operationTypeText = null;

    /**
     * Вид движения
     *
     * @property string $typeGround
     */
    private $typeGround = null;

    /**
     * Основание платежа
     *
     * @property string $dogovor
     */
    private $dogovor = null;

    /**
     * Признак физ. лица
     *
     * @property boolean $isCorrPhys
     */
    private $isCorrPhys = null;

    /**
     * Дата рождения (регистрации фирмы) корреспондента
     *
     * @property \DateTime $datePers
     */
    private $datePers = null;

    /**
     * Тип документа, удостоверяющего личность (буквенный код)
     *
     * @property string $dType
     */
    private $dType = null;

    /**
     * Название типа документа, удостоверяющего личность.
     *
     * @property string $dTypeCaption
     */
    private $dTypeCaption = null;

    /**
     * Полное наименование типа документа, удостоверяющего личность
     *
     * @property string $dTypeFull
     */
    private $dTypeFull = null;

    /**
     * Серия
     *
     * @property string $dSer
     */
    private $dSer = null;

    /**
     * Номер
     *
     * @property string $dNum
     */
    private $dNum = null;

    /**
     * Выдан, когда
     *
     * @property \DateTime $dDate
     */
    private $dDate = null;

    /**
     * Выдан, кем
     *
     * @property string $dWho
     */
    private $dWho = null;

    /**
     * Регион проживания
     *
     * @property string $codeRegion
     */
    private $codeRegion = null;

    /**
     * Страна (Гражданство)
     *
     * @property string $codeAlpha3
     */
    private $codeAlpha3 = null;

    /**
     * Адрес жительства корреспондента
     *
     * @property string $address
     */
    private $address = null;

    /**
     * Наименование Плательщик / Получатель
     *
     * @property string $klPlat
     */
    private $klPlat = null;

    /**
     * Gets as documentCLNT
     *
     * Номер первичного документа
     *
     * @return string
     */
    public function getDocumentCLNT()
    {
        return $this->documentCLNT;
    }

    /**
     * Sets a new documentCLNT
     *
     * Номер первичного документа
     *
     * @param string $documentCLNT
     * @return static
     */
    public function setDocumentCLNT($documentCLNT)
    {
        $this->documentCLNT = $documentCLNT;
        return $this;
    }

    /**
     * Gets as dateCLNT
     *
     * Дата первичного документа
     *
     * @return \DateTime
     */
    public function getDateCLNT()
    {
        return $this->dateCLNT;
    }

    /**
     * Sets a new dateCLNT
     *
     * Дата первичного документа
     *
     * @param \DateTime $dateCLNT
     * @return static
     */
    public function setDateCLNT(\DateTime $dateCLNT)
    {
        $this->dateCLNT = $dateCLNT;
        return $this;
    }

    /**
     * Gets as operationTypeText
     *
     * Название вида операции
     *
     * @return string
     */
    public function getOperationTypeText()
    {
        return $this->operationTypeText;
    }

    /**
     * Sets a new operationTypeText
     *
     * Название вида операции
     *
     * @param string $operationTypeText
     * @return static
     */
    public function setOperationTypeText($operationTypeText)
    {
        $this->operationTypeText = $operationTypeText;
        return $this;
    }

    /**
     * Gets as typeGround
     *
     * Вид движения
     *
     * @return string
     */
    public function getTypeGround()
    {
        return $this->typeGround;
    }

    /**
     * Sets a new typeGround
     *
     * Вид движения
     *
     * @param string $typeGround
     * @return static
     */
    public function setTypeGround($typeGround)
    {
        $this->typeGround = $typeGround;
        return $this;
    }

    /**
     * Gets as dogovor
     *
     * Основание платежа
     *
     * @return string
     */
    public function getDogovor()
    {
        return $this->dogovor;
    }

    /**
     * Sets a new dogovor
     *
     * Основание платежа
     *
     * @param string $dogovor
     * @return static
     */
    public function setDogovor($dogovor)
    {
        $this->dogovor = $dogovor;
        return $this;
    }

    /**
     * Gets as isCorrPhys
     *
     * Признак физ. лица
     *
     * @return boolean
     */
    public function getIsCorrPhys()
    {
        return $this->isCorrPhys;
    }

    /**
     * Sets a new isCorrPhys
     *
     * Признак физ. лица
     *
     * @param boolean $isCorrPhys
     * @return static
     */
    public function setIsCorrPhys($isCorrPhys)
    {
        $this->isCorrPhys = $isCorrPhys;
        return $this;
    }

    /**
     * Gets as datePers
     *
     * Дата рождения (регистрации фирмы) корреспондента
     *
     * @return \DateTime
     */
    public function getDatePers()
    {
        return $this->datePers;
    }

    /**
     * Sets a new datePers
     *
     * Дата рождения (регистрации фирмы) корреспондента
     *
     * @param \DateTime $datePers
     * @return static
     */
    public function setDatePers(\DateTime $datePers)
    {
        $this->datePers = $datePers;
        return $this;
    }

    /**
     * Gets as dType
     *
     * Тип документа, удостоверяющего личность (буквенный код)
     *
     * @return string
     */
    public function getDType()
    {
        return $this->dType;
    }

    /**
     * Sets a new dType
     *
     * Тип документа, удостоверяющего личность (буквенный код)
     *
     * @param string $dType
     * @return static
     */
    public function setDType($dType)
    {
        $this->dType = $dType;
        return $this;
    }

    /**
     * Gets as dTypeCaption
     *
     * Название типа документа, удостоверяющего личность.
     *
     * @return string
     */
    public function getDTypeCaption()
    {
        return $this->dTypeCaption;
    }

    /**
     * Sets a new dTypeCaption
     *
     * Название типа документа, удостоверяющего личность.
     *
     * @param string $dTypeCaption
     * @return static
     */
    public function setDTypeCaption($dTypeCaption)
    {
        $this->dTypeCaption = $dTypeCaption;
        return $this;
    }

    /**
     * Gets as dTypeFull
     *
     * Полное наименование типа документа, удостоверяющего личность
     *
     * @return string
     */
    public function getDTypeFull()
    {
        return $this->dTypeFull;
    }

    /**
     * Sets a new dTypeFull
     *
     * Полное наименование типа документа, удостоверяющего личность
     *
     * @param string $dTypeFull
     * @return static
     */
    public function setDTypeFull($dTypeFull)
    {
        $this->dTypeFull = $dTypeFull;
        return $this;
    }

    /**
     * Gets as dSer
     *
     * Серия
     *
     * @return string
     */
    public function getDSer()
    {
        return $this->dSer;
    }

    /**
     * Sets a new dSer
     *
     * Серия
     *
     * @param string $dSer
     * @return static
     */
    public function setDSer($dSer)
    {
        $this->dSer = $dSer;
        return $this;
    }

    /**
     * Gets as dNum
     *
     * Номер
     *
     * @return string
     */
    public function getDNum()
    {
        return $this->dNum;
    }

    /**
     * Sets a new dNum
     *
     * Номер
     *
     * @param string $dNum
     * @return static
     */
    public function setDNum($dNum)
    {
        $this->dNum = $dNum;
        return $this;
    }

    /**
     * Gets as dDate
     *
     * Выдан, когда
     *
     * @return \DateTime
     */
    public function getDDate()
    {
        return $this->dDate;
    }

    /**
     * Sets a new dDate
     *
     * Выдан, когда
     *
     * @param \DateTime $dDate
     * @return static
     */
    public function setDDate(\DateTime $dDate)
    {
        $this->dDate = $dDate;
        return $this;
    }

    /**
     * Gets as dWho
     *
     * Выдан, кем
     *
     * @return string
     */
    public function getDWho()
    {
        return $this->dWho;
    }

    /**
     * Sets a new dWho
     *
     * Выдан, кем
     *
     * @param string $dWho
     * @return static
     */
    public function setDWho($dWho)
    {
        $this->dWho = $dWho;
        return $this;
    }

    /**
     * Gets as codeRegion
     *
     * Регион проживания
     *
     * @return string
     */
    public function getCodeRegion()
    {
        return $this->codeRegion;
    }

    /**
     * Sets a new codeRegion
     *
     * Регион проживания
     *
     * @param string $codeRegion
     * @return static
     */
    public function setCodeRegion($codeRegion)
    {
        $this->codeRegion = $codeRegion;
        return $this;
    }

    /**
     * Gets as codeAlpha3
     *
     * Страна (Гражданство)
     *
     * @return string
     */
    public function getCodeAlpha3()
    {
        return $this->codeAlpha3;
    }

    /**
     * Sets a new codeAlpha3
     *
     * Страна (Гражданство)
     *
     * @param string $codeAlpha3
     * @return static
     */
    public function setCodeAlpha3($codeAlpha3)
    {
        $this->codeAlpha3 = $codeAlpha3;
        return $this;
    }

    /**
     * Gets as address
     *
     * Адрес жительства корреспондента
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
     * Адрес жительства корреспондента
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
     * Gets as klPlat
     *
     * Наименование Плательщик / Получатель
     *
     * @return string
     */
    public function getKlPlat()
    {
        return $this->klPlat;
    }

    /**
     * Sets a new klPlat
     *
     * Наименование Плательщик / Получатель
     *
     * @param string $klPlat
     * @return static
     */
    public function setKlPlat($klPlat)
    {
        $this->klPlat = $klPlat;
        return $this;
    }


}


<?php

namespace common\models\raiffeisenxml\request\ChanDPConRaifType;

/**
 * Class representing ChanInfoAType
 */
class ChanInfoAType
{

    /**
     * Признак наличия номера документа 0- с номером, 1- без номера
     *
     * @property bool $checkNum
     */
    private $checkNum = null;

    /**
     * Номер строки по порядку
     *
     * @property string $strNum
     */
    private $strNum = null;

    /**
     * Наименование документа
     *
     * @property string $docName
     */
    private $docName = null;

    /**
     * Номер документа
     *
     * @property string $docNum
     */
    private $docNum = null;

    /**
     * Дата документа
     *
     * @property \DateTime $docDate
     */
    private $docDate = null;

    /**
     * Gets as checkNum
     *
     * Признак наличия номера документа 0- с номером, 1- без номера
     *
     * @return bool
     */
    public function getCheckNum()
    {
        return $this->checkNum;
    }

    /**
     * Sets a new checkNum
     *
     * Признак наличия номера документа 0- с номером, 1- без номера
     *
     * @param bool $checkNum
     * @return static
     */
    public function setCheckNum($checkNum)
    {
        $this->checkNum = $checkNum;
        return $this;
    }

    /**
     * Gets as strNum
     *
     * Номер строки по порядку
     *
     * @return string
     */
    public function getStrNum()
    {
        return $this->strNum;
    }

    /**
     * Sets a new strNum
     *
     * Номер строки по порядку
     *
     * @param string $strNum
     * @return static
     */
    public function setStrNum($strNum)
    {
        $this->strNum = $strNum;
        return $this;
    }

    /**
     * Gets as docName
     *
     * Наименование документа
     *
     * @return string
     */
    public function getDocName()
    {
        return $this->docName;
    }

    /**
     * Sets a new docName
     *
     * Наименование документа
     *
     * @param string $docName
     * @return static
     */
    public function setDocName($docName)
    {
        $this->docName = $docName;
        return $this;
    }

    /**
     * Gets as docNum
     *
     * Номер документа
     *
     * @return string
     */
    public function getDocNum()
    {
        return $this->docNum;
    }

    /**
     * Sets a new docNum
     *
     * Номер документа
     *
     * @param string $docNum
     * @return static
     */
    public function setDocNum($docNum)
    {
        $this->docNum = $docNum;
        return $this;
    }

    /**
     * Gets as docDate
     *
     * Дата документа
     *
     * @return \DateTime
     */
    public function getDocDate()
    {
        return $this->docDate;
    }

    /**
     * Sets a new docDate
     *
     * Дата документа
     *
     * @param \DateTime $docDate
     * @return static
     */
    public function setDocDate(\DateTime $docDate)
    {
        $this->docDate = $docDate;
        return $this;
    }


}


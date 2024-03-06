<?php

namespace common\models\raiffeisenxml\request\ClDPType\DpInfoAType;

/**
 * Class representing OperAType
 */
class OperAType
{

    /**
     * Номер строки по порядку
     *
     * @property string $strNum
     */
    private $strNum = null;

    /**
     * Номер ПС
     *
     * @property string $dpNum
     */
    private $dpNum = null;

    /**
     * Дата ПС
     *
     * @property \DateTime $dpDate
     */
    private $dpDate = null;

    /**
     * Пункт инструкции
     *
     * @property string $chapter
     */
    private $chapter = null;

    /**
     * Основание для закрытия ПС
     *
     * @property string $addInfo
     */
    private $addInfo = null;

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
     * Gets as dpNum
     *
     * Номер ПС
     *
     * @return string
     */
    public function getDpNum()
    {
        return $this->dpNum;
    }

    /**
     * Sets a new dpNum
     *
     * Номер ПС
     *
     * @param string $dpNum
     * @return static
     */
    public function setDpNum($dpNum)
    {
        $this->dpNum = $dpNum;
        return $this;
    }

    /**
     * Gets as dpDate
     *
     * Дата ПС
     *
     * @return \DateTime
     */
    public function getDpDate()
    {
        return $this->dpDate;
    }

    /**
     * Sets a new dpDate
     *
     * Дата ПС
     *
     * @param \DateTime $dpDate
     * @return static
     */
    public function setDpDate(\DateTime $dpDate)
    {
        $this->dpDate = $dpDate;
        return $this;
    }

    /**
     * Gets as chapter
     *
     * Пункт инструкции
     *
     * @return string
     */
    public function getChapter()
    {
        return $this->chapter;
    }

    /**
     * Sets a new chapter
     *
     * Пункт инструкции
     *
     * @param string $chapter
     * @return static
     */
    public function setChapter($chapter)
    {
        $this->chapter = $chapter;
        return $this;
    }

    /**
     * Gets as addInfo
     *
     * Основание для закрытия ПС
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
     * Основание для закрытия ПС
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


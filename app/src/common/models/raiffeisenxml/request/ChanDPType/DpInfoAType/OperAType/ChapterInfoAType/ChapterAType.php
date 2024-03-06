<?php

namespace common\models\raiffeisenxml\request\ChanDPType\DpInfoAType\OperAType\ChapterInfoAType;

/**
 * Class representing ChapterAType
 */
class ChapterAType
{

    /**
     * Номер строки по порядку
     *
     * @property string $strNum
     */
    private $strNum = null;

    /**
     * № раздела
     *
     * @property string $chapter
     */
    private $chapter = null;

    /**
     * Новое значение графы раздела ПС
     *
     * @property string $addInfo
     */
    private $addInfo = null;

    /**
     * № графы
     *
     * @property string $columns
     */
    private $columns = null;

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
     * Gets as chapter
     *
     * № раздела
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
     * № раздела
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
     * Новое значение графы раздела ПС
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
     * Новое значение графы раздела ПС
     *
     * @param string $addInfo
     * @return static
     */
    public function setAddInfo($addInfo)
    {
        $this->addInfo = $addInfo;
        return $this;
    }

    /**
     * Gets as columns
     *
     * № графы
     *
     * @return string
     */
    public function getColumns()
    {
        return $this->columns;
    }

    /**
     * Sets a new columns
     *
     * № графы
     *
     * @param string $columns
     * @return static
     */
    public function setColumns($columns)
    {
        $this->columns = $columns;
        return $this;
    }


}


<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing ChapterType
 *
 * Сведения о переоформляемом разделе/подразделе
 * XSD Type: Chapter
 */
class ChapterType
{

    /**
     * Номер строки по порядку
     *
     * @property integer $strNum
     */
    private $strNum = null;

    /**
     * № раздела
     *
     * @property string $dpChapter
     */
    private $dpChapter = null;

    /**
     * № подраздела
     *
     * @property string $dpSubchapter
     */
    private $dpSubchapter = null;

    /**
     * № пункта
     *
     * @property string $dpParagraph
     */
    private $dpParagraph = null;

    /**
     * № подпункта
     *
     * @property string $dpSubparagraph
     */
    private $dpSubparagraph = null;

    /**
     * № графы
     *
     * @property string $dpColumns
     */
    private $dpColumns = null;

    /**
     * № строки
     *
     * @property string $dpString
     */
    private $dpString = null;

    /**
     * Новое значение графы раздела ПС
     *
     * @property string $dpAddInfo
     */
    private $dpAddInfo = null;

    /**
     * Gets as strNum
     *
     * Номер строки по порядку
     *
     * @return integer
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
     * @param integer $strNum
     * @return static
     */
    public function setStrNum($strNum)
    {
        $this->strNum = $strNum;
        return $this;
    }

    /**
     * Gets as dpChapter
     *
     * № раздела
     *
     * @return string
     */
    public function getDpChapter()
    {
        return $this->dpChapter;
    }

    /**
     * Sets a new dpChapter
     *
     * № раздела
     *
     * @param string $dpChapter
     * @return static
     */
    public function setDpChapter($dpChapter)
    {
        $this->dpChapter = $dpChapter;
        return $this;
    }

    /**
     * Gets as dpSubchapter
     *
     * № подраздела
     *
     * @return string
     */
    public function getDpSubchapter()
    {
        return $this->dpSubchapter;
    }

    /**
     * Sets a new dpSubchapter
     *
     * № подраздела
     *
     * @param string $dpSubchapter
     * @return static
     */
    public function setDpSubchapter($dpSubchapter)
    {
        $this->dpSubchapter = $dpSubchapter;
        return $this;
    }

    /**
     * Gets as dpParagraph
     *
     * № пункта
     *
     * @return string
     */
    public function getDpParagraph()
    {
        return $this->dpParagraph;
    }

    /**
     * Sets a new dpParagraph
     *
     * № пункта
     *
     * @param string $dpParagraph
     * @return static
     */
    public function setDpParagraph($dpParagraph)
    {
        $this->dpParagraph = $dpParagraph;
        return $this;
    }

    /**
     * Gets as dpSubparagraph
     *
     * № подпункта
     *
     * @return string
     */
    public function getDpSubparagraph()
    {
        return $this->dpSubparagraph;
    }

    /**
     * Sets a new dpSubparagraph
     *
     * № подпункта
     *
     * @param string $dpSubparagraph
     * @return static
     */
    public function setDpSubparagraph($dpSubparagraph)
    {
        $this->dpSubparagraph = $dpSubparagraph;
        return $this;
    }

    /**
     * Gets as dpColumns
     *
     * № графы
     *
     * @return string
     */
    public function getDpColumns()
    {
        return $this->dpColumns;
    }

    /**
     * Sets a new dpColumns
     *
     * № графы
     *
     * @param string $dpColumns
     * @return static
     */
    public function setDpColumns($dpColumns)
    {
        $this->dpColumns = $dpColumns;
        return $this;
    }

    /**
     * Gets as dpString
     *
     * № строки
     *
     * @return string
     */
    public function getDpString()
    {
        return $this->dpString;
    }

    /**
     * Sets a new dpString
     *
     * № строки
     *
     * @param string $dpString
     * @return static
     */
    public function setDpString($dpString)
    {
        $this->dpString = $dpString;
        return $this;
    }

    /**
     * Gets as dpAddInfo
     *
     * Новое значение графы раздела ПС
     *
     * @return string
     */
    public function getDpAddInfo()
    {
        return $this->dpAddInfo;
    }

    /**
     * Sets a new dpAddInfo
     *
     * Новое значение графы раздела ПС
     *
     * @param string $dpAddInfo
     * @return static
     */
    public function setDpAddInfo($dpAddInfo)
    {
        $this->dpAddInfo = $dpAddInfo;
        return $this;
    }


}


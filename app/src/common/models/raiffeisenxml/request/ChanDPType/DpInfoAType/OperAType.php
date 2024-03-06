<?php

namespace common\models\raiffeisenxml\request\ChanDPType\DpInfoAType;

/**
 * Class representing OperAType
 */
class OperAType
{

    /**
     * Реквизиты переоформляемого ПС
     *
     * @property \common\models\raiffeisenxml\request\ChanDPType\DpInfoAType\OperAType\DpDataAType $dpData
     */
    private $dpData = null;

    /**
     * Инф. о переоф. разделах
     *
     * @property \common\models\raiffeisenxml\request\ChanDPType\DpInfoAType\OperAType\ChapterInfoAType\ChapterAType[] $chapterInfo
     */
    private $chapterInfo = null;

    /**
     * Инф. о документах, явл. основанием для переоф. ПС
     *
     * @property \common\models\raiffeisenxml\request\ChanDPType\DpInfoAType\OperAType\GroundInfoAType\GroundAType[] $groundInfo
     */
    private $groundInfo = null;

    /**
     * Gets as dpData
     *
     * Реквизиты переоформляемого ПС
     *
     * @return \common\models\raiffeisenxml\request\ChanDPType\DpInfoAType\OperAType\DpDataAType
     */
    public function getDpData()
    {
        return $this->dpData;
    }

    /**
     * Sets a new dpData
     *
     * Реквизиты переоформляемого ПС
     *
     * @param \common\models\raiffeisenxml\request\ChanDPType\DpInfoAType\OperAType\DpDataAType $dpData
     * @return static
     */
    public function setDpData(\common\models\raiffeisenxml\request\ChanDPType\DpInfoAType\OperAType\DpDataAType $dpData)
    {
        $this->dpData = $dpData;
        return $this;
    }

    /**
     * Adds as chapter
     *
     * Инф. о переоф. разделах
     *
     * @return static
     * @param \common\models\raiffeisenxml\request\ChanDPType\DpInfoAType\OperAType\ChapterInfoAType\ChapterAType $chapter
     */
    public function addToChapterInfo(\common\models\raiffeisenxml\request\ChanDPType\DpInfoAType\OperAType\ChapterInfoAType\ChapterAType $chapter)
    {
        $this->chapterInfo[] = $chapter;
        return $this;
    }

    /**
     * isset chapterInfo
     *
     * Инф. о переоф. разделах
     *
     * @param int|string $index
     * @return bool
     */
    public function issetChapterInfo($index)
    {
        return isset($this->chapterInfo[$index]);
    }

    /**
     * unset chapterInfo
     *
     * Инф. о переоф. разделах
     *
     * @param int|string $index
     * @return void
     */
    public function unsetChapterInfo($index)
    {
        unset($this->chapterInfo[$index]);
    }

    /**
     * Gets as chapterInfo
     *
     * Инф. о переоф. разделах
     *
     * @return \common\models\raiffeisenxml\request\ChanDPType\DpInfoAType\OperAType\ChapterInfoAType\ChapterAType[]
     */
    public function getChapterInfo()
    {
        return $this->chapterInfo;
    }

    /**
     * Sets a new chapterInfo
     *
     * Инф. о переоф. разделах
     *
     * @param \common\models\raiffeisenxml\request\ChanDPType\DpInfoAType\OperAType\ChapterInfoAType\ChapterAType[] $chapterInfo
     * @return static
     */
    public function setChapterInfo(array $chapterInfo)
    {
        $this->chapterInfo = $chapterInfo;
        return $this;
    }

    /**
     * Adds as ground
     *
     * Инф. о документах, явл. основанием для переоф. ПС
     *
     * @return static
     * @param \common\models\raiffeisenxml\request\ChanDPType\DpInfoAType\OperAType\GroundInfoAType\GroundAType $ground
     */
    public function addToGroundInfo(\common\models\raiffeisenxml\request\ChanDPType\DpInfoAType\OperAType\GroundInfoAType\GroundAType $ground)
    {
        $this->groundInfo[] = $ground;
        return $this;
    }

    /**
     * isset groundInfo
     *
     * Инф. о документах, явл. основанием для переоф. ПС
     *
     * @param int|string $index
     * @return bool
     */
    public function issetGroundInfo($index)
    {
        return isset($this->groundInfo[$index]);
    }

    /**
     * unset groundInfo
     *
     * Инф. о документах, явл. основанием для переоф. ПС
     *
     * @param int|string $index
     * @return void
     */
    public function unsetGroundInfo($index)
    {
        unset($this->groundInfo[$index]);
    }

    /**
     * Gets as groundInfo
     *
     * Инф. о документах, явл. основанием для переоф. ПС
     *
     * @return \common\models\raiffeisenxml\request\ChanDPType\DpInfoAType\OperAType\GroundInfoAType\GroundAType[]
     */
    public function getGroundInfo()
    {
        return $this->groundInfo;
    }

    /**
     * Sets a new groundInfo
     *
     * Инф. о документах, явл. основанием для переоф. ПС
     *
     * @param \common\models\raiffeisenxml\request\ChanDPType\DpInfoAType\OperAType\GroundInfoAType\GroundAType[] $groundInfo
     * @return static
     */
    public function setGroundInfo(array $groundInfo)
    {
        $this->groundInfo = $groundInfo;
        return $this;
    }


}


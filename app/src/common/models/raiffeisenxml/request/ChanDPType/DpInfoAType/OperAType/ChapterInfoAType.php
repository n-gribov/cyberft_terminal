<?php

namespace common\models\raiffeisenxml\request\ChanDPType\DpInfoAType\OperAType;

/**
 * Class representing ChapterInfoAType
 */
class ChapterInfoAType
{

    /**
     * Инф. о переоф. разделе
     *
     * @property \common\models\raiffeisenxml\request\ChanDPType\DpInfoAType\OperAType\ChapterInfoAType\ChapterAType[] $chapter
     */
    private $chapter = [
        
    ];

    /**
     * Adds as chapter
     *
     * Инф. о переоф. разделе
     *
     * @return static
     * @param \common\models\raiffeisenxml\request\ChanDPType\DpInfoAType\OperAType\ChapterInfoAType\ChapterAType $chapter
     */
    public function addToChapter(\common\models\raiffeisenxml\request\ChanDPType\DpInfoAType\OperAType\ChapterInfoAType\ChapterAType $chapter)
    {
        $this->chapter[] = $chapter;
        return $this;
    }

    /**
     * isset chapter
     *
     * Инф. о переоф. разделе
     *
     * @param int|string $index
     * @return bool
     */
    public function issetChapter($index)
    {
        return isset($this->chapter[$index]);
    }

    /**
     * unset chapter
     *
     * Инф. о переоф. разделе
     *
     * @param int|string $index
     * @return void
     */
    public function unsetChapter($index)
    {
        unset($this->chapter[$index]);
    }

    /**
     * Gets as chapter
     *
     * Инф. о переоф. разделе
     *
     * @return \common\models\raiffeisenxml\request\ChanDPType\DpInfoAType\OperAType\ChapterInfoAType\ChapterAType[]
     */
    public function getChapter()
    {
        return $this->chapter;
    }

    /**
     * Sets a new chapter
     *
     * Инф. о переоф. разделе
     *
     * @param \common\models\raiffeisenxml\request\ChanDPType\DpInfoAType\OperAType\ChapterInfoAType\ChapterAType[] $chapter
     * @return static
     */
    public function setChapter(array $chapter)
    {
        $this->chapter = $chapter;
        return $this;
    }


}


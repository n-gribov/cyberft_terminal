<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing TableDealPassIcsType
 *
 * >Сведения о переоформляемых разделах/подразделах
 * XSD Type: TableDealPassIcs
 */
class TableDealPassIcsType
{

    /**
     * Сведения о переоформляемых разделах/подразделах
     *
     * @property \common\models\sbbolxml\request\ICSChapterInfoType $chapterInfo
     */
    private $chapterInfo = null;

    /**
     * Gets as chapterInfo
     *
     * Сведения о переоформляемых разделах/подразделах
     *
     * @return \common\models\sbbolxml\request\ICSChapterInfoType
     */
    public function getChapterInfo()
    {
        return $this->chapterInfo;
    }

    /**
     * Sets a new chapterInfo
     *
     * Сведения о переоформляемых разделах/подразделах
     *
     * @param \common\models\sbbolxml\request\ICSChapterInfoType $chapterInfo
     * @return static
     */
    public function setChapterInfo(\common\models\sbbolxml\request\ICSChapterInfoType $chapterInfo)
    {
        $this->chapterInfo = $chapterInfo;
        return $this;
    }


}


<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing DpDataType
 *
 * Сведения о переоформляемом ПС
 * XSD Type: DpData
 */
class DpDataType
{

    /**
     * Номер строки по порядку
     *
     * @property integer $strNum
     */
    private $strNum = null;

    /**
     * Номер ПС
     *
     * @property string $dpNum
     */
    private $dpNum = null;

    /**
     * Дата ПС в формате ДД.ММ.ГГГГ
     *
     * @property \DateTime $dpDate
     */
    private $dpDate = null;

    /**
     * Информация о контракте (кредитном договоре)
     *
     * @property \common\models\sbbolxml\request\ContractType $contract
     */
    private $contract = null;

    /**
     * Сведения о документах, на основании которых должны быть внесены изменения ПС
     *
     * @property \common\models\sbbolxml\request\GroundType[] $groundInfo
     */
    private $groundInfo = null;

    /**
     * Сведения о переоформляемых разделах/подразделах
     *
     * @property \common\models\sbbolxml\request\ChapterType[] $chapterInfo
     */
    private $chapterInfo = null;

    /**
     * Сведения о приложении
     *
     * @property string $attachInfo
     */
    private $attachInfo = null;

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
     * Дата ПС в формате ДД.ММ.ГГГГ
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
     * Дата ПС в формате ДД.ММ.ГГГГ
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
     * Gets as contract
     *
     * Информация о контракте (кредитном договоре)
     *
     * @return \common\models\sbbolxml\request\ContractType
     */
    public function getContract()
    {
        return $this->contract;
    }

    /**
     * Sets a new contract
     *
     * Информация о контракте (кредитном договоре)
     *
     * @param \common\models\sbbolxml\request\ContractType $contract
     * @return static
     */
    public function setContract(\common\models\sbbolxml\request\ContractType $contract)
    {
        $this->contract = $contract;
        return $this;
    }

    /**
     * Adds as ground
     *
     * Сведения о документах, на основании которых должны быть внесены изменения ПС
     *
     * @return static
     * @param \common\models\sbbolxml\request\GroundType $ground
     */
    public function addToGroundInfo(\common\models\sbbolxml\request\GroundType $ground)
    {
        $this->groundInfo[] = $ground;
        return $this;
    }

    /**
     * isset groundInfo
     *
     * Сведения о документах, на основании которых должны быть внесены изменения ПС
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetGroundInfo($index)
    {
        return isset($this->groundInfo[$index]);
    }

    /**
     * unset groundInfo
     *
     * Сведения о документах, на основании которых должны быть внесены изменения ПС
     *
     * @param scalar $index
     * @return void
     */
    public function unsetGroundInfo($index)
    {
        unset($this->groundInfo[$index]);
    }

    /**
     * Gets as groundInfo
     *
     * Сведения о документах, на основании которых должны быть внесены изменения ПС
     *
     * @return \common\models\sbbolxml\request\GroundType[]
     */
    public function getGroundInfo()
    {
        return $this->groundInfo;
    }

    /**
     * Sets a new groundInfo
     *
     * Сведения о документах, на основании которых должны быть внесены изменения ПС
     *
     * @param \common\models\sbbolxml\request\GroundType[] $groundInfo
     * @return static
     */
    public function setGroundInfo(array $groundInfo)
    {
        $this->groundInfo = $groundInfo;
        return $this;
    }

    /**
     * Adds as chapter
     *
     * Сведения о переоформляемых разделах/подразделах
     *
     * @return static
     * @param \common\models\sbbolxml\request\ChapterType $chapter
     */
    public function addToChapterInfo(\common\models\sbbolxml\request\ChapterType $chapter)
    {
        $this->chapterInfo[] = $chapter;
        return $this;
    }

    /**
     * isset chapterInfo
     *
     * Сведения о переоформляемых разделах/подразделах
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetChapterInfo($index)
    {
        return isset($this->chapterInfo[$index]);
    }

    /**
     * unset chapterInfo
     *
     * Сведения о переоформляемых разделах/подразделах
     *
     * @param scalar $index
     * @return void
     */
    public function unsetChapterInfo($index)
    {
        unset($this->chapterInfo[$index]);
    }

    /**
     * Gets as chapterInfo
     *
     * Сведения о переоформляемых разделах/подразделах
     *
     * @return \common\models\sbbolxml\request\ChapterType[]
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
     * @param \common\models\sbbolxml\request\ChapterType[] $chapterInfo
     * @return static
     */
    public function setChapterInfo(array $chapterInfo)
    {
        $this->chapterInfo = $chapterInfo;
        return $this;
    }

    /**
     * Gets as attachInfo
     *
     * Сведения о приложении
     *
     * @return string
     */
    public function getAttachInfo()
    {
        return $this->attachInfo;
    }

    /**
     * Sets a new attachInfo
     *
     * Сведения о приложении
     *
     * @param string $attachInfo
     * @return static
     */
    public function setAttachInfo($attachInfo)
    {
        $this->attachInfo = $attachInfo;
        return $this;
    }


}


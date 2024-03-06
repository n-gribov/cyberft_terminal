<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing GroundDocDataICSType
 *
 *
 * XSD Type: GroundDocDataICS
 */
class GroundDocDataICSType
{

    /**
     * Документ: 0 - с номером, 1 - без номера
     *
     * @property boolean $numCheck
     */
    private $numCheck = null;

    /**
     * Номер документа, являющегося основанием для переоформления ПС
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
     * Название документа, на основании которого переоформляется ПС
     *
     * @property string $docName
     */
    private $docName = null;

    /**
     * Gets as numCheck
     *
     * Документ: 0 - с номером, 1 - без номера
     *
     * @return boolean
     */
    public function getNumCheck()
    {
        return $this->numCheck;
    }

    /**
     * Sets a new numCheck
     *
     * Документ: 0 - с номером, 1 - без номера
     *
     * @param boolean $numCheck
     * @return static
     */
    public function setNumCheck($numCheck)
    {
        $this->numCheck = $numCheck;
        return $this;
    }

    /**
     * Gets as docNum
     *
     * Номер документа, являющегося основанием для переоформления ПС
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
     * Номер документа, являющегося основанием для переоформления ПС
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

    /**
     * Gets as docName
     *
     * Название документа, на основании которого переоформляется ПС
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
     * Название документа, на основании которого переоформляется ПС
     *
     * @param string $docName
     * @return static
     */
    public function setDocName($docName)
    {
        $this->docName = $docName;
        return $this;
    }


}


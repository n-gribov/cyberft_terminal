<?php

namespace common\models\raiffeisenxml\request\ChanDPType\DpInfoAType\OperAType\GroundInfoAType\GroundAType;

/**
 * Class representing DocDataAType
 */
class DocDataAType
{

    /**
     * Номер сформированного
     *  документа
     *  если нет номера - null
     *
     * @property string $docNum
     */
    private $docNum = null;

    /**
     * Дата создания документа по
     *  местному времени
     *
     * @property \DateTime $docDate
     */
    private $docDate = null;

    /**
     * @property string $docName
     */
    private $docName = null;

    /**
     * @property string $addInfo
     */
    private $addInfo = null;

    /**
     * Gets as docNum
     *
     * Номер сформированного
     *  документа
     *  если нет номера - null
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
     * Номер сформированного
     *  документа
     *  если нет номера - null
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
     * Дата создания документа по
     *  местному времени
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
     * Дата создания документа по
     *  местному времени
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
     * @return string
     */
    public function getDocName()
    {
        return $this->docName;
    }

    /**
     * Sets a new docName
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
     * Gets as addInfo
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
     * @param string $addInfo
     * @return static
     */
    public function setAddInfo($addInfo)
    {
        $this->addInfo = $addInfo;
        return $this;
    }


}


<?php

namespace common\models\raiffeisenxml\request\MandatorySaleRaifType\CurrControlAType;

/**
 * Class representing CurrDealInquiryAType
 */
class CurrDealInquiryAType
{

    /**
     * Номер сформированного документа
     *  если нет номера - null
     *
     * @property string $docNum
     */
    private $docNum = null;

    /**
     * Дата создания документа по местному времени
     *
     * @property \DateTime $docDate
     */
    private $docDate = null;

    /**
     * Gets as docNum
     *
     * Номер сформированного документа
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
     * Номер сформированного документа
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
     * Дата создания документа по местному времени
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
     * Дата создания документа по местному времени
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


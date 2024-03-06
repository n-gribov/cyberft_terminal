<?php

namespace common\models\raiffeisenxml\request\PayDocCurRaifType;

/**
 * Class representing CurrDealInquiryAType
 */
class CurrDealInquiryAType
{

    /**
     * Наименование подразделения - получателя документа
     *
     * @property string $bankName
     */
    private $bankName = null;

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
     * Gets as bankName
     *
     * Наименование подразделения - получателя документа
     *
     * @return string
     */
    public function getBankName()
    {
        return $this->bankName;
    }

    /**
     * Sets a new bankName
     *
     * Наименование подразделения - получателя документа
     *
     * @param string $bankName
     * @return static
     */
    public function setBankName($bankName)
    {
        $this->bankName = $bankName;
        return $this;
    }

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


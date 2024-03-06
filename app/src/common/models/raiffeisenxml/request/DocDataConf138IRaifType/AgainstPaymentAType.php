<?php

namespace common\models\raiffeisenxml\request\DocDataConf138IRaifType;

/**
 * Class representing AgainstPaymentAType
 */
class AgainstPaymentAType
{

    /**
     * Номер расчетного документа.
     *
     * @property string $docNum
     */
    private $docNum = null;

    /**
     * Дата расчетного документа.
     *
     * @property \DateTime $docDate
     */
    private $docDate = null;

    /**
     * Gets as docNum
     *
     * Номер расчетного документа.
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
     * Номер расчетного документа.
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
     * Дата расчетного документа.
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
     * Дата расчетного документа.
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


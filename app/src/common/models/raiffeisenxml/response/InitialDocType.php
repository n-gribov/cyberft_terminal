<?php

namespace common\models\raiffeisenxml\response;

/**
 * Class representing InitialDocType
 *
 *
 * XSD Type: InitialDoc
 */
class InitialDocType
{

    /**
     * @property string $docNum
     */
    private $docNum = null;

    /**
     * @property \DateTime $docDate
     */
    private $docDate = null;

    /**
     * @property string $docRef
     */
    private $docRef = null;

    /**
     * Дата текущего операционного дня запроса выписки
     *
     * @property \DateTime $operDayReq
     */
    private $operDayReq = null;

    /**
     * Gets as docNum
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
     * @return \DateTime
     */
    public function getDocDate()
    {
        return $this->docDate;
    }

    /**
     * Sets a new docDate
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
     * Gets as docRef
     *
     * @return string
     */
    public function getDocRef()
    {
        return $this->docRef;
    }

    /**
     * Sets a new docRef
     *
     * @param string $docRef
     * @return static
     */
    public function setDocRef($docRef)
    {
        $this->docRef = $docRef;
        return $this;
    }

    /**
     * Gets as operDayReq
     *
     * Дата текущего операционного дня запроса выписки
     *
     * @return \DateTime
     */
    public function getOperDayReq()
    {
        return $this->operDayReq;
    }

    /**
     * Sets a new operDayReq
     *
     * Дата текущего операционного дня запроса выписки
     *
     * @param \DateTime $operDayReq
     * @return static
     */
    public function setOperDayReq(\DateTime $operDayReq)
    {
        $this->operDayReq = $operDayReq;
        return $this;
    }


}


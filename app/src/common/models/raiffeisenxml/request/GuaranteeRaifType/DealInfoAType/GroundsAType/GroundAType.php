<?php

namespace common\models\raiffeisenxml\request\GuaranteeRaifType\DealInfoAType\GroundsAType;

/**
 * Class representing GroundAType
 */
class GroundAType
{

    /**
     * Номер документа
     *
     * @property string $num
     */
    private $num = null;

    /**
     * Дата документа
     *
     * @property \DateTime $date
     */
    private $date = null;

    /**
     * Срок исполнения
     *
     * @property \DateTime $term
     */
    private $term = null;

    /**
     * Дата налоговой декларации
     *
     * @property \DateTime $taxReturnDate
     */
    private $taxReturnDate = null;

    /**
     * Тип документа
     *
     * @property string $docType
     */
    private $docType = null;

    /**
     * Наименование заявления
     *
     * @property string $orderName
     */
    private $orderName = null;

    /**
     * Период
     *
     * @property string $period
     */
    private $period = null;

    /**
     * Иной документ
     *
     * @property string $otherDoc
     */
    private $otherDoc = null;

    /**
     * Gets as num
     *
     * Номер документа
     *
     * @return string
     */
    public function getNum()
    {
        return $this->num;
    }

    /**
     * Sets a new num
     *
     * Номер документа
     *
     * @param string $num
     * @return static
     */
    public function setNum($num)
    {
        $this->num = $num;
        return $this;
    }

    /**
     * Gets as date
     *
     * Дата документа
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Sets a new date
     *
     * Дата документа
     *
     * @param \DateTime $date
     * @return static
     */
    public function setDate(\DateTime $date)
    {
        $this->date = $date;
        return $this;
    }

    /**
     * Gets as term
     *
     * Срок исполнения
     *
     * @return \DateTime
     */
    public function getTerm()
    {
        return $this->term;
    }

    /**
     * Sets a new term
     *
     * Срок исполнения
     *
     * @param \DateTime $term
     * @return static
     */
    public function setTerm(\DateTime $term)
    {
        $this->term = $term;
        return $this;
    }

    /**
     * Gets as taxReturnDate
     *
     * Дата налоговой декларации
     *
     * @return \DateTime
     */
    public function getTaxReturnDate()
    {
        return $this->taxReturnDate;
    }

    /**
     * Sets a new taxReturnDate
     *
     * Дата налоговой декларации
     *
     * @param \DateTime $taxReturnDate
     * @return static
     */
    public function setTaxReturnDate(\DateTime $taxReturnDate)
    {
        $this->taxReturnDate = $taxReturnDate;
        return $this;
    }

    /**
     * Gets as docType
     *
     * Тип документа
     *
     * @return string
     */
    public function getDocType()
    {
        return $this->docType;
    }

    /**
     * Sets a new docType
     *
     * Тип документа
     *
     * @param string $docType
     * @return static
     */
    public function setDocType($docType)
    {
        $this->docType = $docType;
        return $this;
    }

    /**
     * Gets as orderName
     *
     * Наименование заявления
     *
     * @return string
     */
    public function getOrderName()
    {
        return $this->orderName;
    }

    /**
     * Sets a new orderName
     *
     * Наименование заявления
     *
     * @param string $orderName
     * @return static
     */
    public function setOrderName($orderName)
    {
        $this->orderName = $orderName;
        return $this;
    }

    /**
     * Gets as period
     *
     * Период
     *
     * @return string
     */
    public function getPeriod()
    {
        return $this->period;
    }

    /**
     * Sets a new period
     *
     * Период
     *
     * @param string $period
     * @return static
     */
    public function setPeriod($period)
    {
        $this->period = $period;
        return $this;
    }

    /**
     * Gets as otherDoc
     *
     * Иной документ
     *
     * @return string
     */
    public function getOtherDoc()
    {
        return $this->otherDoc;
    }

    /**
     * Sets a new otherDoc
     *
     * Иной документ
     *
     * @param string $otherDoc
     * @return static
     */
    public function setOtherDoc($otherDoc)
    {
        $this->otherDoc = $otherDoc;
        return $this;
    }


}


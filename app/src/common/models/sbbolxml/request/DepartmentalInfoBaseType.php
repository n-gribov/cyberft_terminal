<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing DepartmentalInfoBaseType
 *
 *
 * XSD Type: DepartmentalInfoBase
 */
class DepartmentalInfoBaseType
{

    /**
     * Показатель статуса налогоплательщика (101)
     *
     * @property string $drawerStatus
     */
    private $drawerStatus = null;

    /**
     * Код ОКАТО (105)
     *
     * @property string $okato
     */
    private $okato = null;

    /**
     * Показатель основания платежа (106)
     *
     * @property string $paytReason
     */
    private $paytReason = null;

    /**
     * Налоговый период (107)
     *
     * @property string $taxPeriod
     */
    private $taxPeriod = null;

    /**
     * Номер налогового документа (108)
     *
     * @property string $docNo
     */
    private $docNo = null;

    /**
     * Дата налогового документа (109) DD.MM.YYYY или 0
     *
     * @property string $docDate
     */
    private $docDate = null;

    /**
     * Тип налогового платежа (110)
     *
     * @property string $taxPaytKind
     */
    private $taxPaytKind = null;

    /**
     * Gets as drawerStatus
     *
     * Показатель статуса налогоплательщика (101)
     *
     * @return string
     */
    public function getDrawerStatus()
    {
        return $this->drawerStatus;
    }

    /**
     * Sets a new drawerStatus
     *
     * Показатель статуса налогоплательщика (101)
     *
     * @param string $drawerStatus
     * @return static
     */
    public function setDrawerStatus($drawerStatus)
    {
        $this->drawerStatus = $drawerStatus;
        return $this;
    }

    /**
     * Gets as okato
     *
     * Код ОКАТО (105)
     *
     * @return string
     */
    public function getOkato()
    {
        return $this->okato;
    }

    /**
     * Sets a new okato
     *
     * Код ОКАТО (105)
     *
     * @param string $okato
     * @return static
     */
    public function setOkato($okato)
    {
        $this->okato = $okato;
        return $this;
    }

    /**
     * Gets as paytReason
     *
     * Показатель основания платежа (106)
     *
     * @return string
     */
    public function getPaytReason()
    {
        return $this->paytReason;
    }

    /**
     * Sets a new paytReason
     *
     * Показатель основания платежа (106)
     *
     * @param string $paytReason
     * @return static
     */
    public function setPaytReason($paytReason)
    {
        $this->paytReason = $paytReason;
        return $this;
    }

    /**
     * Gets as taxPeriod
     *
     * Налоговый период (107)
     *
     * @return string
     */
    public function getTaxPeriod()
    {
        return $this->taxPeriod;
    }

    /**
     * Sets a new taxPeriod
     *
     * Налоговый период (107)
     *
     * @param string $taxPeriod
     * @return static
     */
    public function setTaxPeriod($taxPeriod)
    {
        $this->taxPeriod = $taxPeriod;
        return $this;
    }

    /**
     * Gets as docNo
     *
     * Номер налогового документа (108)
     *
     * @return string
     */
    public function getDocNo()
    {
        return $this->docNo;
    }

    /**
     * Sets a new docNo
     *
     * Номер налогового документа (108)
     *
     * @param string $docNo
     * @return static
     */
    public function setDocNo($docNo)
    {
        $this->docNo = $docNo;
        return $this;
    }

    /**
     * Gets as docDate
     *
     * Дата налогового документа (109) DD.MM.YYYY или 0
     *
     * @return string
     */
    public function getDocDate()
    {
        return $this->docDate;
    }

    /**
     * Sets a new docDate
     *
     * Дата налогового документа (109) DD.MM.YYYY или 0
     *
     * @param string $docDate
     * @return static
     */
    public function setDocDate($docDate)
    {
        $this->docDate = $docDate;
        return $this;
    }

    /**
     * Gets as taxPaytKind
     *
     * Тип налогового платежа (110)
     *
     * @return string
     */
    public function getTaxPaytKind()
    {
        return $this->taxPaytKind;
    }

    /**
     * Sets a new taxPaytKind
     *
     * Тип налогового платежа (110)
     *
     * @param string $taxPaytKind
     * @return static
     */
    public function setTaxPaytKind($taxPaytKind)
    {
        $this->taxPaytKind = $taxPaytKind;
        return $this;
    }


}


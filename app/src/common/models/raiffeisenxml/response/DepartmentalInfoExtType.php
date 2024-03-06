<?php

namespace common\models\raiffeisenxml\response;

/**
 * Class representing DepartmentalInfoExtType
 *
 *
 * XSD Type: DepartmentalInfoExt
 */
class DepartmentalInfoExtType
{

    /**
     * Показатель статуса налогоплательщика (101)
     *
     * @property string $drawerStatus
     */
    private $drawerStatus = null;

    /**
     * Код бюджетной классификации (104)
     *
     * @property string $cbc
     */
    private $cbc = null;

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
     * Дата налогового документа (109) DD.MM.YYYY
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
     * КПП (до 9)
     *
     * @property string $kpp103
     */
    private $kpp103 = null;

    /**
     * КПП (до 9)
     *
     * @property string $kpp102
     */
    private $kpp102 = null;

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
     * Gets as cbc
     *
     * Код бюджетной классификации (104)
     *
     * @return string
     */
    public function getCbc()
    {
        return $this->cbc;
    }

    /**
     * Sets a new cbc
     *
     * Код бюджетной классификации (104)
     *
     * @param string $cbc
     * @return static
     */
    public function setCbc($cbc)
    {
        $this->cbc = $cbc;
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
     * Дата налогового документа (109) DD.MM.YYYY
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
     * Дата налогового документа (109) DD.MM.YYYY
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

    /**
     * Gets as kpp103
     *
     * КПП (до 9)
     *
     * @return string
     */
    public function getKpp103()
    {
        return $this->kpp103;
    }

    /**
     * Sets a new kpp103
     *
     * КПП (до 9)
     *
     * @param string $kpp103
     * @return static
     */
    public function setKpp103($kpp103)
    {
        $this->kpp103 = $kpp103;
        return $this;
    }

    /**
     * Gets as kpp102
     *
     * КПП (до 9)
     *
     * @return string
     */
    public function getKpp102()
    {
        return $this->kpp102;
    }

    /**
     * Sets a new kpp102
     *
     * КПП (до 9)
     *
     * @param string $kpp102
     * @return static
     */
    public function setKpp102($kpp102)
    {
        $this->kpp102 = $kpp102;
        return $this;
    }


}


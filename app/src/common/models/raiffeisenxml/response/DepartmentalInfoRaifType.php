<?php

namespace common\models\raiffeisenxml\response;

/**
 * Class representing DepartmentalInfoRaifType
 *
 *
 * XSD Type: DepartmentalInfoRaif
 */
class DepartmentalInfoRaifType
{

    /**
     * КБК
     *
     * @property string $cbc
     */
    private $cbc = null;

    /**
     * Дата документа
     *
     * @property string $docDate
     */
    private $docDate = null;

    /**
     * Номер налогового документа
     *
     * @property string $docNo
     */
    private $docNo = null;

    /**
     * Статус составителя документа
     *
     * @property string $drawerStatus
     */
    private $drawerStatus = null;

    /**
     * ОКАТО
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
     * Тип налогового платежа (110)
     *
     * @property string $taxPaytKind
     */
    private $taxPaytKind = null;

    /**
     * Налоговый период/ Код таможенного органа
     *
     * @property string $taxPeriod
     */
    private $taxPeriod = null;

    /**
     * Gets as cbc
     *
     * КБК
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
     * КБК
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
     * Gets as docDate
     *
     * Дата документа
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
     * Дата документа
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
     * Gets as docNo
     *
     * Номер налогового документа
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
     * Номер налогового документа
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
     * Gets as drawerStatus
     *
     * Статус составителя документа
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
     * Статус составителя документа
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
     * ОКАТО
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
     * ОКАТО
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
     * Gets as taxPeriod
     *
     * Налоговый период/ Код таможенного органа
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
     * Налоговый период/ Код таможенного органа
     *
     * @param string $taxPeriod
     * @return static
     */
    public function setTaxPeriod($taxPeriod)
    {
        $this->taxPeriod = $taxPeriod;
        return $this;
    }


}


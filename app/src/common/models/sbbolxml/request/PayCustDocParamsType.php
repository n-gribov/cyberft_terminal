<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing PayCustDocParamsType
 *
 * Параметры таможенного платежа
 * XSD Type: PayCustDocParams
 */
class PayCustDocParamsType
{

    /**
     * Номер документа основания платежа
     *
     * @property string $docNo
     */
    private $docNo = null;

    /**
     * Дата документа основания платежа
     *
     * @property \DateTime $docDate
     */
    private $docDate = null;

    /**
     * Код (идентификатор) таможенной процедуры
     *
     * @property string $procCode
     */
    private $procCode = null;

    /**
     * Код таможенного органа
     *
     * @property string $depCode
     */
    private $depCode = null;

    /**
     * Код вида платежа
     *
     * @property string $payingType
     */
    private $payingType = null;

    /**
     * Gets as docNo
     *
     * Номер документа основания платежа
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
     * Номер документа основания платежа
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
     * Дата документа основания платежа
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
     * Дата документа основания платежа
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
     * Gets as procCode
     *
     * Код (идентификатор) таможенной процедуры
     *
     * @return string
     */
    public function getProcCode()
    {
        return $this->procCode;
    }

    /**
     * Sets a new procCode
     *
     * Код (идентификатор) таможенной процедуры
     *
     * @param string $procCode
     * @return static
     */
    public function setProcCode($procCode)
    {
        $this->procCode = $procCode;
        return $this;
    }

    /**
     * Gets as depCode
     *
     * Код таможенного органа
     *
     * @return string
     */
    public function getDepCode()
    {
        return $this->depCode;
    }

    /**
     * Sets a new depCode
     *
     * Код таможенного органа
     *
     * @param string $depCode
     * @return static
     */
    public function setDepCode($depCode)
    {
        $this->depCode = $depCode;
        return $this;
    }

    /**
     * Gets as payingType
     *
     * Код вида платежа
     *
     * @return string
     */
    public function getPayingType()
    {
        return $this->payingType;
    }

    /**
     * Sets a new payingType
     *
     * Код вида платежа
     *
     * @param string $payingType
     * @return static
     */
    public function setPayingType($payingType)
    {
        $this->payingType = $payingType;
        return $this;
    }


}


<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing DiffDocType
 *
 *
 * XSD Type: DiffDoc
 */
class DiffDocType
{

    /**
     * Условие оплаты (для 02)
     *
     * @property string $payingCondition
     */
    private $payingCondition = null;

    /**
     * Вид аккредитива (для 08)
     *
     * @property string $letterOfCreditType
     */
    private $letterOfCreditType = null;

    /**
     * Содержание операции (для 16)
     *
     * @property string $operContent
     */
    private $operContent = null;

    /**
     * Условие оплаты аккредитива (для 08)
     *
     * @property string $letterOfCreditPaymCond
     */
    private $letterOfCreditPaymCond = null;

    /**
     * Требуемые документы (для 08)
     *
     * @property string $letterOfCreditDemandDocs
     */
    private $letterOfCreditDemandDocs = null;

    /**
     * Дополнительные условия аккредитива (для 08)
     *
     * @property string $letterOfCreditAddCond
     */
    private $letterOfCreditAddCond = null;

    /**
     * Срок действия аккредитива (для 08)
     *
     * @property \DateTime $letterOfCreditPeriodVal
     */
    private $letterOfCreditPeriodVal = null;

    /**
     * Номер счета поставщика (для 08)
     *
     * @property string $letterOfCreditPayAcc
     */
    private $letterOfCreditPayAcc = null;

    /**
     * Шифр документа (картотека) (для 16)
     *
     * @property string $docShifr
     */
    private $docShifr = null;

    /**
     * Дата документа (картотека) (для 16)
     *
     * @property \DateTime $docDateCard
     */
    private $docDateCard = null;

    /**
     * Номер документа (картотека) (для 16)
     *
     * @property string $docNumberCard
     */
    private $docNumberCard = null;

    /**
     * Сумма остатка платежа (картотека) (для 16)
     *
     * @property float $sumRestCard
     */
    private $sumRestCard = null;

    /**
     * Номер платежа (картотека) (для 16)
     *
     * @property string $numPaymentCard
     */
    private $numPaymentCard = null;

    /**
     * Срок акцепта, количество дней (для 08)
     *
     * @property integer $letterOfCreditAcceptDate
     */
    private $letterOfCreditAcceptDate = null;

    /**
     * Окончание срока акцепта
     *
     * @property \DateTime $endAcceptDate
     */
    private $endAcceptDate = null;

    /**
     * Gets as payingCondition
     *
     * Условие оплаты (для 02)
     *
     * @return string
     */
    public function getPayingCondition()
    {
        return $this->payingCondition;
    }

    /**
     * Sets a new payingCondition
     *
     * Условие оплаты (для 02)
     *
     * @param string $payingCondition
     * @return static
     */
    public function setPayingCondition($payingCondition)
    {
        $this->payingCondition = $payingCondition;
        return $this;
    }

    /**
     * Gets as letterOfCreditType
     *
     * Вид аккредитива (для 08)
     *
     * @return string
     */
    public function getLetterOfCreditType()
    {
        return $this->letterOfCreditType;
    }

    /**
     * Sets a new letterOfCreditType
     *
     * Вид аккредитива (для 08)
     *
     * @param string $letterOfCreditType
     * @return static
     */
    public function setLetterOfCreditType($letterOfCreditType)
    {
        $this->letterOfCreditType = $letterOfCreditType;
        return $this;
    }

    /**
     * Gets as operContent
     *
     * Содержание операции (для 16)
     *
     * @return string
     */
    public function getOperContent()
    {
        return $this->operContent;
    }

    /**
     * Sets a new operContent
     *
     * Содержание операции (для 16)
     *
     * @param string $operContent
     * @return static
     */
    public function setOperContent($operContent)
    {
        $this->operContent = $operContent;
        return $this;
    }

    /**
     * Gets as letterOfCreditPaymCond
     *
     * Условие оплаты аккредитива (для 08)
     *
     * @return string
     */
    public function getLetterOfCreditPaymCond()
    {
        return $this->letterOfCreditPaymCond;
    }

    /**
     * Sets a new letterOfCreditPaymCond
     *
     * Условие оплаты аккредитива (для 08)
     *
     * @param string $letterOfCreditPaymCond
     * @return static
     */
    public function setLetterOfCreditPaymCond($letterOfCreditPaymCond)
    {
        $this->letterOfCreditPaymCond = $letterOfCreditPaymCond;
        return $this;
    }

    /**
     * Gets as letterOfCreditDemandDocs
     *
     * Требуемые документы (для 08)
     *
     * @return string
     */
    public function getLetterOfCreditDemandDocs()
    {
        return $this->letterOfCreditDemandDocs;
    }

    /**
     * Sets a new letterOfCreditDemandDocs
     *
     * Требуемые документы (для 08)
     *
     * @param string $letterOfCreditDemandDocs
     * @return static
     */
    public function setLetterOfCreditDemandDocs($letterOfCreditDemandDocs)
    {
        $this->letterOfCreditDemandDocs = $letterOfCreditDemandDocs;
        return $this;
    }

    /**
     * Gets as letterOfCreditAddCond
     *
     * Дополнительные условия аккредитива (для 08)
     *
     * @return string
     */
    public function getLetterOfCreditAddCond()
    {
        return $this->letterOfCreditAddCond;
    }

    /**
     * Sets a new letterOfCreditAddCond
     *
     * Дополнительные условия аккредитива (для 08)
     *
     * @param string $letterOfCreditAddCond
     * @return static
     */
    public function setLetterOfCreditAddCond($letterOfCreditAddCond)
    {
        $this->letterOfCreditAddCond = $letterOfCreditAddCond;
        return $this;
    }

    /**
     * Gets as letterOfCreditPeriodVal
     *
     * Срок действия аккредитива (для 08)
     *
     * @return \DateTime
     */
    public function getLetterOfCreditPeriodVal()
    {
        return $this->letterOfCreditPeriodVal;
    }

    /**
     * Sets a new letterOfCreditPeriodVal
     *
     * Срок действия аккредитива (для 08)
     *
     * @param \DateTime $letterOfCreditPeriodVal
     * @return static
     */
    public function setLetterOfCreditPeriodVal(\DateTime $letterOfCreditPeriodVal)
    {
        $this->letterOfCreditPeriodVal = $letterOfCreditPeriodVal;
        return $this;
    }

    /**
     * Gets as letterOfCreditPayAcc
     *
     * Номер счета поставщика (для 08)
     *
     * @return string
     */
    public function getLetterOfCreditPayAcc()
    {
        return $this->letterOfCreditPayAcc;
    }

    /**
     * Sets a new letterOfCreditPayAcc
     *
     * Номер счета поставщика (для 08)
     *
     * @param string $letterOfCreditPayAcc
     * @return static
     */
    public function setLetterOfCreditPayAcc($letterOfCreditPayAcc)
    {
        $this->letterOfCreditPayAcc = $letterOfCreditPayAcc;
        return $this;
    }

    /**
     * Gets as docShifr
     *
     * Шифр документа (картотека) (для 16)
     *
     * @return string
     */
    public function getDocShifr()
    {
        return $this->docShifr;
    }

    /**
     * Sets a new docShifr
     *
     * Шифр документа (картотека) (для 16)
     *
     * @param string $docShifr
     * @return static
     */
    public function setDocShifr($docShifr)
    {
        $this->docShifr = $docShifr;
        return $this;
    }

    /**
     * Gets as docDateCard
     *
     * Дата документа (картотека) (для 16)
     *
     * @return \DateTime
     */
    public function getDocDateCard()
    {
        return $this->docDateCard;
    }

    /**
     * Sets a new docDateCard
     *
     * Дата документа (картотека) (для 16)
     *
     * @param \DateTime $docDateCard
     * @return static
     */
    public function setDocDateCard(\DateTime $docDateCard)
    {
        $this->docDateCard = $docDateCard;
        return $this;
    }

    /**
     * Gets as docNumberCard
     *
     * Номер документа (картотека) (для 16)
     *
     * @return string
     */
    public function getDocNumberCard()
    {
        return $this->docNumberCard;
    }

    /**
     * Sets a new docNumberCard
     *
     * Номер документа (картотека) (для 16)
     *
     * @param string $docNumberCard
     * @return static
     */
    public function setDocNumberCard($docNumberCard)
    {
        $this->docNumberCard = $docNumberCard;
        return $this;
    }

    /**
     * Gets as sumRestCard
     *
     * Сумма остатка платежа (картотека) (для 16)
     *
     * @return float
     */
    public function getSumRestCard()
    {
        return $this->sumRestCard;
    }

    /**
     * Sets a new sumRestCard
     *
     * Сумма остатка платежа (картотека) (для 16)
     *
     * @param float $sumRestCard
     * @return static
     */
    public function setSumRestCard($sumRestCard)
    {
        $this->sumRestCard = $sumRestCard;
        return $this;
    }

    /**
     * Gets as numPaymentCard
     *
     * Номер платежа (картотека) (для 16)
     *
     * @return string
     */
    public function getNumPaymentCard()
    {
        return $this->numPaymentCard;
    }

    /**
     * Sets a new numPaymentCard
     *
     * Номер платежа (картотека) (для 16)
     *
     * @param string $numPaymentCard
     * @return static
     */
    public function setNumPaymentCard($numPaymentCard)
    {
        $this->numPaymentCard = $numPaymentCard;
        return $this;
    }

    /**
     * Gets as letterOfCreditAcceptDate
     *
     * Срок акцепта, количество дней (для 08)
     *
     * @return integer
     */
    public function getLetterOfCreditAcceptDate()
    {
        return $this->letterOfCreditAcceptDate;
    }

    /**
     * Sets a new letterOfCreditAcceptDate
     *
     * Срок акцепта, количество дней (для 08)
     *
     * @param integer $letterOfCreditAcceptDate
     * @return static
     */
    public function setLetterOfCreditAcceptDate($letterOfCreditAcceptDate)
    {
        $this->letterOfCreditAcceptDate = $letterOfCreditAcceptDate;
        return $this;
    }

    /**
     * Gets as endAcceptDate
     *
     * Окончание срока акцепта
     *
     * @return \DateTime
     */
    public function getEndAcceptDate()
    {
        return $this->endAcceptDate;
    }

    /**
     * Sets a new endAcceptDate
     *
     * Окончание срока акцепта
     *
     * @param \DateTime $endAcceptDate
     * @return static
     */
    public function setEndAcceptDate(\DateTime $endAcceptDate)
    {
        $this->endAcceptDate = $endAcceptDate;
        return $this;
    }


}


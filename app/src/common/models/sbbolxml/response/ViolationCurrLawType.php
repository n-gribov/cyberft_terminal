<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing ViolationCurrLawType
 *
 * Информация о зафиксированных банком нарушениях валютного законодательства
 * XSD Type: ViolationCurrLaw
 */
class ViolationCurrLawType
{

    /**
     * Цифровой код валюты
     *
     * @property string $currCode
     */
    private $currCode = null;

    /**
     * ISO-код валюты
     *
     * @property string $currIsoCode
     */
    private $currIsoCode = null;

    /**
     * Номер строки по порядку
     *
     * @property integer $lineNumber
     */
    private $lineNumber = null;

    /**
     * Дата нарушения
     *
     * @property \DateTime $violationDate
     */
    private $violationDate = null;

    /**
     * Сумма нарушения
     *
     * @property integer $violationSum
     */
    private $violationSum = null;

    /**
     * Нормативный правовой акт
     *
     * @property string $normLegalAct
     */
    private $normLegalAct = null;

    /**
     * Нарушенная норма
     *
     * @property string $violationNorm
     */
    private $violationNorm = null;

    /**
     * Описание нарушения
     *
     * @property string $comment
     */
    private $comment = null;

    /**
     * Номер ПС
     *
     * @property string $dealPassNum
     */
    private $dealPassNum = null;

    /**
     * Информация о контраке/ кредитном договоре
     *
     * @property \common\models\sbbolxml\response\VBKInfoContractType $contract
     */
    private $contract = null;

    /**
     * Gets as currCode
     *
     * Цифровой код валюты
     *
     * @return string
     */
    public function getCurrCode()
    {
        return $this->currCode;
    }

    /**
     * Sets a new currCode
     *
     * Цифровой код валюты
     *
     * @param string $currCode
     * @return static
     */
    public function setCurrCode($currCode)
    {
        $this->currCode = $currCode;
        return $this;
    }

    /**
     * Gets as currIsoCode
     *
     * ISO-код валюты
     *
     * @return string
     */
    public function getCurrIsoCode()
    {
        return $this->currIsoCode;
    }

    /**
     * Sets a new currIsoCode
     *
     * ISO-код валюты
     *
     * @param string $currIsoCode
     * @return static
     */
    public function setCurrIsoCode($currIsoCode)
    {
        $this->currIsoCode = $currIsoCode;
        return $this;
    }

    /**
     * Gets as lineNumber
     *
     * Номер строки по порядку
     *
     * @return integer
     */
    public function getLineNumber()
    {
        return $this->lineNumber;
    }

    /**
     * Sets a new lineNumber
     *
     * Номер строки по порядку
     *
     * @param integer $lineNumber
     * @return static
     */
    public function setLineNumber($lineNumber)
    {
        $this->lineNumber = $lineNumber;
        return $this;
    }

    /**
     * Gets as violationDate
     *
     * Дата нарушения
     *
     * @return \DateTime
     */
    public function getViolationDate()
    {
        return $this->violationDate;
    }

    /**
     * Sets a new violationDate
     *
     * Дата нарушения
     *
     * @param \DateTime $violationDate
     * @return static
     */
    public function setViolationDate(\DateTime $violationDate)
    {
        $this->violationDate = $violationDate;
        return $this;
    }

    /**
     * Gets as violationSum
     *
     * Сумма нарушения
     *
     * @return integer
     */
    public function getViolationSum()
    {
        return $this->violationSum;
    }

    /**
     * Sets a new violationSum
     *
     * Сумма нарушения
     *
     * @param integer $violationSum
     * @return static
     */
    public function setViolationSum($violationSum)
    {
        $this->violationSum = $violationSum;
        return $this;
    }

    /**
     * Gets as normLegalAct
     *
     * Нормативный правовой акт
     *
     * @return string
     */
    public function getNormLegalAct()
    {
        return $this->normLegalAct;
    }

    /**
     * Sets a new normLegalAct
     *
     * Нормативный правовой акт
     *
     * @param string $normLegalAct
     * @return static
     */
    public function setNormLegalAct($normLegalAct)
    {
        $this->normLegalAct = $normLegalAct;
        return $this;
    }

    /**
     * Gets as violationNorm
     *
     * Нарушенная норма
     *
     * @return string
     */
    public function getViolationNorm()
    {
        return $this->violationNorm;
    }

    /**
     * Sets a new violationNorm
     *
     * Нарушенная норма
     *
     * @param string $violationNorm
     * @return static
     */
    public function setViolationNorm($violationNorm)
    {
        $this->violationNorm = $violationNorm;
        return $this;
    }

    /**
     * Gets as comment
     *
     * Описание нарушения
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Sets a new comment
     *
     * Описание нарушения
     *
     * @param string $comment
     * @return static
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
        return $this;
    }

    /**
     * Gets as dealPassNum
     *
     * Номер ПС
     *
     * @return string
     */
    public function getDealPassNum()
    {
        return $this->dealPassNum;
    }

    /**
     * Sets a new dealPassNum
     *
     * Номер ПС
     *
     * @param string $dealPassNum
     * @return static
     */
    public function setDealPassNum($dealPassNum)
    {
        $this->dealPassNum = $dealPassNum;
        return $this;
    }

    /**
     * Gets as contract
     *
     * Информация о контраке/ кредитном договоре
     *
     * @return \common\models\sbbolxml\response\VBKInfoContractType
     */
    public function getContract()
    {
        return $this->contract;
    }

    /**
     * Sets a new contract
     *
     * Информация о контраке/ кредитном договоре
     *
     * @param \common\models\sbbolxml\response\VBKInfoContractType $contract
     * @return static
     */
    public function setContract(\common\models\sbbolxml\response\VBKInfoContractType $contract)
    {
        $this->contract = $contract;
        return $this;
    }


}


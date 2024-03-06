<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing SalaryPayDocRuType
 *
 *
 * XSD Type: SalaryPayDocRu
 */
class SalaryPayDocRuType
{

    /**
     * Номер расчетного документа
     *
     * @property string $docNum
     */
    private $docNum = null;

    /**
     * Дата расчетного документа
     *  (по местному времени обслуживающего подразделения банка)
     *
     * @property \DateTime $docDate
     */
    private $docDate = null;

    /**
     * Счет списания (cчет плательщика)
     *
     * @property string $payerAcc
     */
    private $payerAcc = null;

    /**
     * БИК банка плательщика
     *
     * @property string $payerBic
     */
    private $payerBic = null;

    /**
     * Счет зачисления (cчет получателя)
     *
     * @property string $payeeAcc
     */
    private $payeeAcc = null;

    /**
     * БИК банка зачисления
     *
     * @property string $payeeBic
     */
    private $payeeBic = null;

    /**
     * Назначение платежа
     *
     * @property string $pur
     */
    private $pur = null;

    /**
     * Номер в списке по порядку
     *
     * @property integer $numSt
     */
    private $numSt = null;

    /**
     * Сумма платежа
     *
     * @property \common\models\sbbolxml\request\SalaryCurrAmountType $sum
     */
    private $sum = null;

    /**
     * Gets as docNum
     *
     * Номер расчетного документа
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
     * Номер расчетного документа
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
     * Дата расчетного документа
     *  (по местному времени обслуживающего подразделения банка)
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
     * Дата расчетного документа
     *  (по местному времени обслуживающего подразделения банка)
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
     * Gets as payerAcc
     *
     * Счет списания (cчет плательщика)
     *
     * @return string
     */
    public function getPayerAcc()
    {
        return $this->payerAcc;
    }

    /**
     * Sets a new payerAcc
     *
     * Счет списания (cчет плательщика)
     *
     * @param string $payerAcc
     * @return static
     */
    public function setPayerAcc($payerAcc)
    {
        $this->payerAcc = $payerAcc;
        return $this;
    }

    /**
     * Gets as payerBic
     *
     * БИК банка плательщика
     *
     * @return string
     */
    public function getPayerBic()
    {
        return $this->payerBic;
    }

    /**
     * Sets a new payerBic
     *
     * БИК банка плательщика
     *
     * @param string $payerBic
     * @return static
     */
    public function setPayerBic($payerBic)
    {
        $this->payerBic = $payerBic;
        return $this;
    }

    /**
     * Gets as payeeAcc
     *
     * Счет зачисления (cчет получателя)
     *
     * @return string
     */
    public function getPayeeAcc()
    {
        return $this->payeeAcc;
    }

    /**
     * Sets a new payeeAcc
     *
     * Счет зачисления (cчет получателя)
     *
     * @param string $payeeAcc
     * @return static
     */
    public function setPayeeAcc($payeeAcc)
    {
        $this->payeeAcc = $payeeAcc;
        return $this;
    }

    /**
     * Gets as payeeBic
     *
     * БИК банка зачисления
     *
     * @return string
     */
    public function getPayeeBic()
    {
        return $this->payeeBic;
    }

    /**
     * Sets a new payeeBic
     *
     * БИК банка зачисления
     *
     * @param string $payeeBic
     * @return static
     */
    public function setPayeeBic($payeeBic)
    {
        $this->payeeBic = $payeeBic;
        return $this;
    }

    /**
     * Gets as pur
     *
     * Назначение платежа
     *
     * @return string
     */
    public function getPur()
    {
        return $this->pur;
    }

    /**
     * Sets a new pur
     *
     * Назначение платежа
     *
     * @param string $pur
     * @return static
     */
    public function setPur($pur)
    {
        $this->pur = $pur;
        return $this;
    }

    /**
     * Gets as numSt
     *
     * Номер в списке по порядку
     *
     * @return integer
     */
    public function getNumSt()
    {
        return $this->numSt;
    }

    /**
     * Sets a new numSt
     *
     * Номер в списке по порядку
     *
     * @param integer $numSt
     * @return static
     */
    public function setNumSt($numSt)
    {
        $this->numSt = $numSt;
        return $this;
    }

    /**
     * Gets as sum
     *
     * Сумма платежа
     *
     * @return \common\models\sbbolxml\request\SalaryCurrAmountType
     */
    public function getSum()
    {
        return $this->sum;
    }

    /**
     * Sets a new sum
     *
     * Сумма платежа
     *
     * @param \common\models\sbbolxml\request\SalaryCurrAmountType $sum
     * @return static
     */
    public function setSum(\common\models\sbbolxml\request\SalaryCurrAmountType $sum)
    {
        $this->sum = $sum;
        return $this;
    }


}


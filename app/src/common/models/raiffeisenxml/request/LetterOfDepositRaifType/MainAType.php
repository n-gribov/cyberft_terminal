<?php

namespace common\models\raiffeisenxml\request\LetterOfDepositRaifType;

/**
 * Class representing MainAType
 */
class MainAType
{

    /**
     * Дата размещения депозита.
     *
     * @property \DateTime $depositDate
     */
    private $depositDate = null;

    /**
     * Дата возврата
     *
     * @property \DateTime $returnDate
     */
    private $returnDate = null;

    /**
     * Срок депозита
     *
     * @property float $term
     */
    private $term = null;

    /**
     * Процентная ставка
     *
     * @property float $interestRate
     */
    private $interestRate = null;

    /**
     * Периодичность выплаты процентов. Возможные значения: "Ежемесячно", "Ежеквартально", "Раз в полгода", "Ежегодно", "В конце Срока Депозита".
     *
     * @property string $payFrequency
     */
    private $payFrequency = null;

    /**
     * Действуя в соответствии с. Возможные значения: "Генеральное соглашение об общих условиях размещения срочных банковских вкладов юридическим лицом", "Генеральное соглашение об общих условиях размещения срочных банковских вкладов юридическим лицом без открытия расчетного счета в ЗАО "Райффайзенбанк"", "Договор об общих условиях размещения срочных банко".
     *
     * @property string $agreement
     */
    private $agreement = null;

    /**
     * Сумма депозита.
     *
     * @property \common\models\raiffeisenxml\request\CurrAmountType $sum
     */
    private $sum = null;

    /**
     * просим Банк списать денежные средства в размере Суммы Депозита со счета
     *
     * @property \common\models\raiffeisenxml\request\WriteOfAccAccountType $writeOffAcc
     */
    private $writeOffAcc = null;

    /**
     * подтверждаем перечисление денежных средств в размере Суммы Депозита
     *
     * @property \common\models\raiffeisenxml\request\TransferAccAccountType $transferAcc
     */
    private $transferAcc = null;

    /**
     * Сумму Депозита просим зачислить на Депозитный счет в Банке
     *
     * @property string $enrollmentAcc
     */
    private $enrollmentAcc = null;

    /**
     * Gets as depositDate
     *
     * Дата размещения депозита.
     *
     * @return \DateTime
     */
    public function getDepositDate()
    {
        return $this->depositDate;
    }

    /**
     * Sets a new depositDate
     *
     * Дата размещения депозита.
     *
     * @param \DateTime $depositDate
     * @return static
     */
    public function setDepositDate(\DateTime $depositDate)
    {
        $this->depositDate = $depositDate;
        return $this;
    }

    /**
     * Gets as returnDate
     *
     * Дата возврата
     *
     * @return \DateTime
     */
    public function getReturnDate()
    {
        return $this->returnDate;
    }

    /**
     * Sets a new returnDate
     *
     * Дата возврата
     *
     * @param \DateTime $returnDate
     * @return static
     */
    public function setReturnDate(\DateTime $returnDate)
    {
        $this->returnDate = $returnDate;
        return $this;
    }

    /**
     * Gets as term
     *
     * Срок депозита
     *
     * @return float
     */
    public function getTerm()
    {
        return $this->term;
    }

    /**
     * Sets a new term
     *
     * Срок депозита
     *
     * @param float $term
     * @return static
     */
    public function setTerm($term)
    {
        $this->term = $term;
        return $this;
    }

    /**
     * Gets as interestRate
     *
     * Процентная ставка
     *
     * @return float
     */
    public function getInterestRate()
    {
        return $this->interestRate;
    }

    /**
     * Sets a new interestRate
     *
     * Процентная ставка
     *
     * @param float $interestRate
     * @return static
     */
    public function setInterestRate($interestRate)
    {
        $this->interestRate = $interestRate;
        return $this;
    }

    /**
     * Gets as payFrequency
     *
     * Периодичность выплаты процентов. Возможные значения: "Ежемесячно", "Ежеквартально", "Раз в полгода", "Ежегодно", "В конце Срока Депозита".
     *
     * @return string
     */
    public function getPayFrequency()
    {
        return $this->payFrequency;
    }

    /**
     * Sets a new payFrequency
     *
     * Периодичность выплаты процентов. Возможные значения: "Ежемесячно", "Ежеквартально", "Раз в полгода", "Ежегодно", "В конце Срока Депозита".
     *
     * @param string $payFrequency
     * @return static
     */
    public function setPayFrequency($payFrequency)
    {
        $this->payFrequency = $payFrequency;
        return $this;
    }

    /**
     * Gets as agreement
     *
     * Действуя в соответствии с. Возможные значения: "Генеральное соглашение об общих условиях размещения срочных банковских вкладов юридическим лицом", "Генеральное соглашение об общих условиях размещения срочных банковских вкладов юридическим лицом без открытия расчетного счета в ЗАО "Райффайзенбанк"", "Договор об общих условиях размещения срочных банко".
     *
     * @return string
     */
    public function getAgreement()
    {
        return $this->agreement;
    }

    /**
     * Sets a new agreement
     *
     * Действуя в соответствии с. Возможные значения: "Генеральное соглашение об общих условиях размещения срочных банковских вкладов юридическим лицом", "Генеральное соглашение об общих условиях размещения срочных банковских вкладов юридическим лицом без открытия расчетного счета в ЗАО "Райффайзенбанк"", "Договор об общих условиях размещения срочных банко".
     *
     * @param string $agreement
     * @return static
     */
    public function setAgreement($agreement)
    {
        $this->agreement = $agreement;
        return $this;
    }

    /**
     * Gets as sum
     *
     * Сумма депозита.
     *
     * @return \common\models\raiffeisenxml\request\CurrAmountType
     */
    public function getSum()
    {
        return $this->sum;
    }

    /**
     * Sets a new sum
     *
     * Сумма депозита.
     *
     * @param \common\models\raiffeisenxml\request\CurrAmountType $sum
     * @return static
     */
    public function setSum(\common\models\raiffeisenxml\request\CurrAmountType $sum)
    {
        $this->sum = $sum;
        return $this;
    }

    /**
     * Gets as writeOffAcc
     *
     * просим Банк списать денежные средства в размере Суммы Депозита со счета
     *
     * @return \common\models\raiffeisenxml\request\WriteOfAccAccountType
     */
    public function getWriteOffAcc()
    {
        return $this->writeOffAcc;
    }

    /**
     * Sets a new writeOffAcc
     *
     * просим Банк списать денежные средства в размере Суммы Депозита со счета
     *
     * @param \common\models\raiffeisenxml\request\WriteOfAccAccountType $writeOffAcc
     * @return static
     */
    public function setWriteOffAcc(\common\models\raiffeisenxml\request\WriteOfAccAccountType $writeOffAcc)
    {
        $this->writeOffAcc = $writeOffAcc;
        return $this;
    }

    /**
     * Gets as transferAcc
     *
     * подтверждаем перечисление денежных средств в размере Суммы Депозита
     *
     * @return \common\models\raiffeisenxml\request\TransferAccAccountType
     */
    public function getTransferAcc()
    {
        return $this->transferAcc;
    }

    /**
     * Sets a new transferAcc
     *
     * подтверждаем перечисление денежных средств в размере Суммы Депозита
     *
     * @param \common\models\raiffeisenxml\request\TransferAccAccountType $transferAcc
     * @return static
     */
    public function setTransferAcc(\common\models\raiffeisenxml\request\TransferAccAccountType $transferAcc)
    {
        $this->transferAcc = $transferAcc;
        return $this;
    }

    /**
     * Gets as enrollmentAcc
     *
     * Сумму Депозита просим зачислить на Депозитный счет в Банке
     *
     * @return string
     */
    public function getEnrollmentAcc()
    {
        return $this->enrollmentAcc;
    }

    /**
     * Sets a new enrollmentAcc
     *
     * Сумму Депозита просим зачислить на Депозитный счет в Банке
     *
     * @param string $enrollmentAcc
     * @return static
     */
    public function setEnrollmentAcc($enrollmentAcc)
    {
        $this->enrollmentAcc = $enrollmentAcc;
        return $this;
    }


}


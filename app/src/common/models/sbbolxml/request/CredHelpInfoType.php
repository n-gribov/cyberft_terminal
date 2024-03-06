<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing CredHelpInfoType
 *
 * Справочная информация о кредитном договоре
 * XSD Type: CredHelpInfo
 */
class CredHelpInfoType
{

    /**
     * Основания заполнения графика платежей по возврату основного долга и процентных платежей
     *
     * @property \common\models\sbbolxml\request\CredBasePaymentsType $basePay
     */
    private $basePay = null;

    /**
     * Описание графика платежей по возврату основного долга и процентных платежей
     *
     * @property \common\models\sbbolxml\request\CredDebtPaymentLoanType[] $paymentLoan
     */
    private $paymentLoan = null;

    /**
     * Наличие прямого инвестирования (0 – признак не установлен; 1 – признак установлен)
     *
     * @property boolean $directInvesting
     */
    private $directInvesting = null;

    /**
     * Сумма залогового или другого обеспечения, предусмотренная условиями кредитного договора
     *
     * @property \common\models\sbbolxml\request\CurrAmountType $bailSum
     */
    private $bailSum = null;

    /**
     * Информация о привлечении резидентом кредита (займа), предоставленного нерезидентами на синдицированной (консорциональной) основе
     *
     * @property \common\models\sbbolxml\request\CreditInfoType[] $creditInfos
     */
    private $creditInfos = null;

    /**
     * Gets as basePay
     *
     * Основания заполнения графика платежей по возврату основного долга и процентных платежей
     *
     * @return \common\models\sbbolxml\request\CredBasePaymentsType
     */
    public function getBasePay()
    {
        return $this->basePay;
    }

    /**
     * Sets a new basePay
     *
     * Основания заполнения графика платежей по возврату основного долга и процентных платежей
     *
     * @param \common\models\sbbolxml\request\CredBasePaymentsType $basePay
     * @return static
     */
    public function setBasePay(\common\models\sbbolxml\request\CredBasePaymentsType $basePay)
    {
        $this->basePay = $basePay;
        return $this;
    }

    /**
     * Adds as debtPaymentLoan
     *
     * Описание графика платежей по возврату основного долга и процентных платежей
     *
     * @return static
     * @param \common\models\sbbolxml\request\CredDebtPaymentLoanType $debtPaymentLoan
     */
    public function addToPaymentLoan(\common\models\sbbolxml\request\CredDebtPaymentLoanType $debtPaymentLoan)
    {
        $this->paymentLoan[] = $debtPaymentLoan;
        return $this;
    }

    /**
     * isset paymentLoan
     *
     * Описание графика платежей по возврату основного долга и процентных платежей
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetPaymentLoan($index)
    {
        return isset($this->paymentLoan[$index]);
    }

    /**
     * unset paymentLoan
     *
     * Описание графика платежей по возврату основного долга и процентных платежей
     *
     * @param scalar $index
     * @return void
     */
    public function unsetPaymentLoan($index)
    {
        unset($this->paymentLoan[$index]);
    }

    /**
     * Gets as paymentLoan
     *
     * Описание графика платежей по возврату основного долга и процентных платежей
     *
     * @return \common\models\sbbolxml\request\CredDebtPaymentLoanType[]
     */
    public function getPaymentLoan()
    {
        return $this->paymentLoan;
    }

    /**
     * Sets a new paymentLoan
     *
     * Описание графика платежей по возврату основного долга и процентных платежей
     *
     * @param \common\models\sbbolxml\request\CredDebtPaymentLoanType[] $paymentLoan
     * @return static
     */
    public function setPaymentLoan(array $paymentLoan)
    {
        $this->paymentLoan = $paymentLoan;
        return $this;
    }

    /**
     * Gets as directInvesting
     *
     * Наличие прямого инвестирования (0 – признак не установлен; 1 – признак установлен)
     *
     * @return boolean
     */
    public function getDirectInvesting()
    {
        return $this->directInvesting;
    }

    /**
     * Sets a new directInvesting
     *
     * Наличие прямого инвестирования (0 – признак не установлен; 1 – признак установлен)
     *
     * @param boolean $directInvesting
     * @return static
     */
    public function setDirectInvesting($directInvesting)
    {
        $this->directInvesting = $directInvesting;
        return $this;
    }

    /**
     * Gets as bailSum
     *
     * Сумма залогового или другого обеспечения, предусмотренная условиями кредитного договора
     *
     * @return \common\models\sbbolxml\request\CurrAmountType
     */
    public function getBailSum()
    {
        return $this->bailSum;
    }

    /**
     * Sets a new bailSum
     *
     * Сумма залогового или другого обеспечения, предусмотренная условиями кредитного договора
     *
     * @param \common\models\sbbolxml\request\CurrAmountType $bailSum
     * @return static
     */
    public function setBailSum(\common\models\sbbolxml\request\CurrAmountType $bailSum)
    {
        $this->bailSum = $bailSum;
        return $this;
    }

    /**
     * Adds as creditInfo
     *
     * Информация о привлечении резидентом кредита (займа), предоставленного нерезидентами на синдицированной (консорциональной) основе
     *
     * @return static
     * @param \common\models\sbbolxml\request\CreditInfoType $creditInfo
     */
    public function addToCreditInfos(\common\models\sbbolxml\request\CreditInfoType $creditInfo)
    {
        $this->creditInfos[] = $creditInfo;
        return $this;
    }

    /**
     * isset creditInfos
     *
     * Информация о привлечении резидентом кредита (займа), предоставленного нерезидентами на синдицированной (консорциональной) основе
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetCreditInfos($index)
    {
        return isset($this->creditInfos[$index]);
    }

    /**
     * unset creditInfos
     *
     * Информация о привлечении резидентом кредита (займа), предоставленного нерезидентами на синдицированной (консорциональной) основе
     *
     * @param scalar $index
     * @return void
     */
    public function unsetCreditInfos($index)
    {
        unset($this->creditInfos[$index]);
    }

    /**
     * Gets as creditInfos
     *
     * Информация о привлечении резидентом кредита (займа), предоставленного нерезидентами на синдицированной (консорциональной) основе
     *
     * @return \common\models\sbbolxml\request\CreditInfoType[]
     */
    public function getCreditInfos()
    {
        return $this->creditInfos;
    }

    /**
     * Sets a new creditInfos
     *
     * Информация о привлечении резидентом кредита (займа), предоставленного нерезидентами на синдицированной (консорциональной) основе
     *
     * @param \common\models\sbbolxml\request\CreditInfoType[] $creditInfos
     * @return static
     */
    public function setCreditInfos(array $creditInfos)
    {
        $this->creditInfos = $creditInfos;
        return $this;
    }


}


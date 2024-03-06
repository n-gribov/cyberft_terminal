<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing HelpInfoICSType
 *
 *
 * XSD Type: HelpInfoICS
 */
class HelpInfoICSType
{

    /**
     * Основания осуществления заполнения
     *
     * @property boolean $hasSchedPaymentCred
     */
    private $hasSchedPaymentCred = null;

    /**
     * Описание графика платежей по возврату основного долга и процентных платежей
     *
     * @property \common\models\sbbolxml\response\DebtPaymentLoanICSType[] $paymentLoan
     */
    private $paymentLoan = null;

    /**
     * Наличие прямого инвестирования (0 – признак не установлен; 1 – признак
     *  установлен)
     *
     * @property boolean $directInvesting
     */
    private $directInvesting = null;

    /**
     * Сумма залогового или другого обеспечения, предусмотренная условиями кредитного договора
     *
     * @property \common\models\sbbolxml\response\CurrAmountType $bailSum
     */
    private $bailSum = null;

    /**
     * Информация о привлечении резидентом кредита (займа), предоставленного нерезидентами на синдицированной (консорциональной) основе
     *
     * @property \common\models\sbbolxml\response\CreditInfoType[] $creditInfos
     */
    private $creditInfos = null;

    /**
     * Gets as hasSchedPaymentCred
     *
     * Основания осуществления заполнения
     *
     * @return boolean
     */
    public function getHasSchedPaymentCred()
    {
        return $this->hasSchedPaymentCred;
    }

    /**
     * Sets a new hasSchedPaymentCred
     *
     * Основания осуществления заполнения
     *
     * @param boolean $hasSchedPaymentCred
     * @return static
     */
    public function setHasSchedPaymentCred($hasSchedPaymentCred)
    {
        $this->hasSchedPaymentCred = $hasSchedPaymentCred;
        return $this;
    }

    /**
     * Adds as debtPaymentLoan
     *
     * Описание графика платежей по возврату основного долга и процентных платежей
     *
     * @return static
     * @param \common\models\sbbolxml\response\DebtPaymentLoanICSType $debtPaymentLoan
     */
    public function addToPaymentLoan(\common\models\sbbolxml\response\DebtPaymentLoanICSType $debtPaymentLoan)
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
     * @return \common\models\sbbolxml\response\DebtPaymentLoanICSType[]
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
     * @param \common\models\sbbolxml\response\DebtPaymentLoanICSType[] $paymentLoan
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
     * Наличие прямого инвестирования (0 – признак не установлен; 1 – признак
     *  установлен)
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
     * Наличие прямого инвестирования (0 – признак не установлен; 1 – признак
     *  установлен)
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
     * @return \common\models\sbbolxml\response\CurrAmountType
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
     * @param \common\models\sbbolxml\response\CurrAmountType $bailSum
     * @return static
     */
    public function setBailSum(\common\models\sbbolxml\response\CurrAmountType $bailSum)
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
     * @param \common\models\sbbolxml\response\CreditInfoType $creditInfo
     */
    public function addToCreditInfos(\common\models\sbbolxml\response\CreditInfoType $creditInfo)
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
     * @return \common\models\sbbolxml\response\CreditInfoType[]
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
     * @param \common\models\sbbolxml\response\CreditInfoType[] $creditInfos
     * @return static
     */
    public function setCreditInfos(array $creditInfos)
    {
        $this->creditInfos = $creditInfos;
        return $this;
    }


}


<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing HelpInfoType
 *
 *
 * XSD Type: HelpInfo
 */
class HelpInfoType
{

    /**
     * Основания осуществления заполнения
     *
     * @property \common\models\sbbolxml\response\BasePayType $basePay
     */
    private $basePay = null;

    /**
     * Описание графика платежей по возврату основного долга и процентных платежей
     *
     * @property \common\models\sbbolxml\response\DebtPaymentLoanType[] $paymentLoan
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
     * Gets as basePay
     *
     * Основания осуществления заполнения
     *
     * @return \common\models\sbbolxml\response\BasePayType
     */
    public function getBasePay()
    {
        return $this->basePay;
    }

    /**
     * Sets a new basePay
     *
     * Основания осуществления заполнения
     *
     * @param \common\models\sbbolxml\response\BasePayType $basePay
     * @return static
     */
    public function setBasePay(\common\models\sbbolxml\response\BasePayType $basePay)
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
     * @param \common\models\sbbolxml\response\DebtPaymentLoanType $debtPaymentLoan
     */
    public function addToPaymentLoan(\common\models\sbbolxml\response\DebtPaymentLoanType $debtPaymentLoan)
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
     * @return \common\models\sbbolxml\response\DebtPaymentLoanType[]
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
     * @param \common\models\sbbolxml\response\DebtPaymentLoanType[] $paymentLoan
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


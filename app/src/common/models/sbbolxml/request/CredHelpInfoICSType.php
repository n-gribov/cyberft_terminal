<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing CredHelpInfoICSType
 *
 * Справочная информация о кредитном договоре
 * XSD Type: CredHelpInfoICS
 */
class CredHelpInfoICSType
{

    /**
     * Сведения из кредитного договора (0 – признак не установлен; 1 – признак установлен)
     *
     * @property boolean $basePay
     */
    private $basePay = null;

    /**
     * Описание графика платежей по возврату основного долга и процентных платежей
     *
     * @property \common\models\sbbolxml\request\CredDebtPaymentLoanICSType[] $paymentLoan
     */
    private $paymentLoan = null;

    /**
     * Наличие прямого инвестирования (0 – признак не установлен; 1 – признак установлен)
     *
     * @property boolean $directInvesting
     */
    private $directInvesting = null;

    /**
     * Сумма залогового или другого обеспечения
     *
     * @property \common\models\sbbolxml\request\CredHelpInfoICSType\BailSumAType $bailSum
     */
    private $bailSum = null;

    /**
     * Информация о привлечении резидентом кредита (займа) на синдицированной (консорциональной) основе
     *
     * @property \common\models\sbbolxml\request\CreditInfoICSType[] $creditInfos
     */
    private $creditInfos = null;

    /**
     * Gets as basePay
     *
     * Сведения из кредитного договора (0 – признак не установлен; 1 – признак установлен)
     *
     * @return boolean
     */
    public function getBasePay()
    {
        return $this->basePay;
    }

    /**
     * Sets a new basePay
     *
     * Сведения из кредитного договора (0 – признак не установлен; 1 – признак установлен)
     *
     * @param boolean $basePay
     * @return static
     */
    public function setBasePay($basePay)
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
     * @param \common\models\sbbolxml\request\CredDebtPaymentLoanICSType $debtPaymentLoan
     */
    public function addToPaymentLoan(\common\models\sbbolxml\request\CredDebtPaymentLoanICSType $debtPaymentLoan)
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
     * @return \common\models\sbbolxml\request\CredDebtPaymentLoanICSType[]
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
     * @param \common\models\sbbolxml\request\CredDebtPaymentLoanICSType[] $paymentLoan
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
     * Сумма залогового или другого обеспечения
     *
     * @return \common\models\sbbolxml\request\CredHelpInfoICSType\BailSumAType
     */
    public function getBailSum()
    {
        return $this->bailSum;
    }

    /**
     * Sets a new bailSum
     *
     * Сумма залогового или другого обеспечения
     *
     * @param \common\models\sbbolxml\request\CredHelpInfoICSType\BailSumAType $bailSum
     * @return static
     */
    public function setBailSum(\common\models\sbbolxml\request\CredHelpInfoICSType\BailSumAType $bailSum)
    {
        $this->bailSum = $bailSum;
        return $this;
    }

    /**
     * Adds as creditInfo
     *
     * Информация о привлечении резидентом кредита (займа) на синдицированной (консорциональной) основе
     *
     * @return static
     * @param \common\models\sbbolxml\request\CreditInfoICSType $creditInfo
     */
    public function addToCreditInfos(\common\models\sbbolxml\request\CreditInfoICSType $creditInfo)
    {
        $this->creditInfos[] = $creditInfo;
        return $this;
    }

    /**
     * isset creditInfos
     *
     * Информация о привлечении резидентом кредита (займа) на синдицированной (консорциональной) основе
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
     * Информация о привлечении резидентом кредита (займа) на синдицированной (консорциональной) основе
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
     * Информация о привлечении резидентом кредита (займа) на синдицированной (консорциональной) основе
     *
     * @return \common\models\sbbolxml\request\CreditInfoICSType[]
     */
    public function getCreditInfos()
    {
        return $this->creditInfos;
    }

    /**
     * Sets a new creditInfos
     *
     * Информация о привлечении резидентом кредита (займа) на синдицированной (консорциональной) основе
     *
     * @param \common\models\sbbolxml\request\CreditInfoICSType[] $creditInfos
     * @return static
     */
    public function setCreditInfos(array $creditInfos)
    {
        $this->creditInfos = $creditInfos;
        return $this;
    }


}


<?php

namespace common\models\raiffeisenxml\response\DealPassCred138IType;

/**
 * Class representing HelpInfoAType
 */
class HelpInfoAType
{

    /**
     * 9.1 и
     *  Основания для заполнения пункта 9.2
     *
     * @property \common\models\raiffeisenxml\response\DealPassCred138IType\HelpInfoAType\PaymentsAType $payments
     */
    private $payments = null;

    /**
     * 9.3 Отметка о наличии прямого инвестирования
     *
     * @property \common\models\raiffeisenxml\response\DealPassCred138IType\HelpInfoAType\DirectInvestingAType $directInvesting
     */
    private $directInvesting = null;

    /**
     * 9.5 Информация о привлечении резидентом кредита (займа),
     *  предоставленного нерезидентами на синдицированной
     *  (консорциональной) основе
     *
     * @property \common\models\raiffeisenxml\response\DealPassCred138IType\HelpInfoAType\CredReceiptAType\OperAType[] $credReceipt
     */
    private $credReceipt = null;

    /**
     * 9.4 Сумма залогового или другого обеспечения
     *
     * @property \common\models\raiffeisenxml\response\CurrAmountType $depositSum
     */
    private $depositSum = null;

    /**
     * Gets as payments
     *
     * 9.1 и
     *  Основания для заполнения пункта 9.2
     *
     * @return \common\models\raiffeisenxml\response\DealPassCred138IType\HelpInfoAType\PaymentsAType
     */
    public function getPayments()
    {
        return $this->payments;
    }

    /**
     * Sets a new payments
     *
     * 9.1 и
     *  Основания для заполнения пункта 9.2
     *
     * @param \common\models\raiffeisenxml\response\DealPassCred138IType\HelpInfoAType\PaymentsAType $payments
     * @return static
     */
    public function setPayments(\common\models\raiffeisenxml\response\DealPassCred138IType\HelpInfoAType\PaymentsAType $payments)
    {
        $this->payments = $payments;
        return $this;
    }

    /**
     * Gets as directInvesting
     *
     * 9.3 Отметка о наличии прямого инвестирования
     *
     * @return \common\models\raiffeisenxml\response\DealPassCred138IType\HelpInfoAType\DirectInvestingAType
     */
    public function getDirectInvesting()
    {
        return $this->directInvesting;
    }

    /**
     * Sets a new directInvesting
     *
     * 9.3 Отметка о наличии прямого инвестирования
     *
     * @param \common\models\raiffeisenxml\response\DealPassCred138IType\HelpInfoAType\DirectInvestingAType $directInvesting
     * @return static
     */
    public function setDirectInvesting(\common\models\raiffeisenxml\response\DealPassCred138IType\HelpInfoAType\DirectInvestingAType $directInvesting)
    {
        $this->directInvesting = $directInvesting;
        return $this;
    }

    /**
     * Adds as oper
     *
     * 9.5 Информация о привлечении резидентом кредита (займа),
     *  предоставленного нерезидентами на синдицированной
     *  (консорциональной) основе
     *
     * @return static
     * @param \common\models\raiffeisenxml\response\DealPassCred138IType\HelpInfoAType\CredReceiptAType\OperAType $oper
     */
    public function addToCredReceipt(\common\models\raiffeisenxml\response\DealPassCred138IType\HelpInfoAType\CredReceiptAType\OperAType $oper)
    {
        $this->credReceipt[] = $oper;
        return $this;
    }

    /**
     * isset credReceipt
     *
     * 9.5 Информация о привлечении резидентом кредита (займа),
     *  предоставленного нерезидентами на синдицированной
     *  (консорциональной) основе
     *
     * @param int|string $index
     * @return bool
     */
    public function issetCredReceipt($index)
    {
        return isset($this->credReceipt[$index]);
    }

    /**
     * unset credReceipt
     *
     * 9.5 Информация о привлечении резидентом кредита (займа),
     *  предоставленного нерезидентами на синдицированной
     *  (консорциональной) основе
     *
     * @param int|string $index
     * @return void
     */
    public function unsetCredReceipt($index)
    {
        unset($this->credReceipt[$index]);
    }

    /**
     * Gets as credReceipt
     *
     * 9.5 Информация о привлечении резидентом кредита (займа),
     *  предоставленного нерезидентами на синдицированной
     *  (консорциональной) основе
     *
     * @return \common\models\raiffeisenxml\response\DealPassCred138IType\HelpInfoAType\CredReceiptAType\OperAType[]
     */
    public function getCredReceipt()
    {
        return $this->credReceipt;
    }

    /**
     * Sets a new credReceipt
     *
     * 9.5 Информация о привлечении резидентом кредита (займа),
     *  предоставленного нерезидентами на синдицированной
     *  (консорциональной) основе
     *
     * @param \common\models\raiffeisenxml\response\DealPassCred138IType\HelpInfoAType\CredReceiptAType\OperAType[] $credReceipt
     * @return static
     */
    public function setCredReceipt(array $credReceipt)
    {
        $this->credReceipt = $credReceipt;
        return $this;
    }

    /**
     * Gets as depositSum
     *
     * 9.4 Сумма залогового или другого обеспечения
     *
     * @return \common\models\raiffeisenxml\response\CurrAmountType
     */
    public function getDepositSum()
    {
        return $this->depositSum;
    }

    /**
     * Sets a new depositSum
     *
     * 9.4 Сумма залогового или другого обеспечения
     *
     * @param \common\models\raiffeisenxml\response\CurrAmountType $depositSum
     * @return static
     */
    public function setDepositSum(\common\models\raiffeisenxml\response\CurrAmountType $depositSum)
    {
        $this->depositSum = $depositSum;
        return $this;
    }


}


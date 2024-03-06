<?php

namespace common\models\sbbolxml\response\ContractType\ContractServicesAType\ContractServiceAType\AccountsAType;

/**
 * Class representing AccountAType
 */
class AccountAType
{

    /**
     * Идентификатор счета
     *
     * @property string $accountId
     */
    private $accountId = null;

    /**
     * Признак, заблокирован ли счет:
     *  1 - счет заблокирован;
     *  0 - счет НЕ заблокирован.
     *
     * @property boolean $blocked
     */
    private $blocked = null;

    /**
     * Контроль платежей (актуально
     *  только для рублевых счетов).
     *  Передавать, если стоит признак "Осуществлять
     *  контроль платежей"
     *
     * @property \common\models\sbbolxml\response\ContractType\ContractServicesAType\ContractServiceAType\AccountsAType\AccountAType\PaymentControlAType $paymentControl
     */
    private $paymentControl = null;

    /**
     * Gets as accountId
     *
     * Идентификатор счета
     *
     * @return string
     */
    public function getAccountId()
    {
        return $this->accountId;
    }

    /**
     * Sets a new accountId
     *
     * Идентификатор счета
     *
     * @param string $accountId
     * @return static
     */
    public function setAccountId($accountId)
    {
        $this->accountId = $accountId;
        return $this;
    }

    /**
     * Gets as blocked
     *
     * Признак, заблокирован ли счет:
     *  1 - счет заблокирован;
     *  0 - счет НЕ заблокирован.
     *
     * @return boolean
     */
    public function getBlocked()
    {
        return $this->blocked;
    }

    /**
     * Sets a new blocked
     *
     * Признак, заблокирован ли счет:
     *  1 - счет заблокирован;
     *  0 - счет НЕ заблокирован.
     *
     * @param boolean $blocked
     * @return static
     */
    public function setBlocked($blocked)
    {
        $this->blocked = $blocked;
        return $this;
    }

    /**
     * Gets as paymentControl
     *
     * Контроль платежей (актуально
     *  только для рублевых счетов).
     *  Передавать, если стоит признак "Осуществлять
     *  контроль платежей"
     *
     * @return \common\models\sbbolxml\response\ContractType\ContractServicesAType\ContractServiceAType\AccountsAType\AccountAType\PaymentControlAType
     */
    public function getPaymentControl()
    {
        return $this->paymentControl;
    }

    /**
     * Sets a new paymentControl
     *
     * Контроль платежей (актуально
     *  только для рублевых счетов).
     *  Передавать, если стоит признак "Осуществлять
     *  контроль платежей"
     *
     * @param \common\models\sbbolxml\response\ContractType\ContractServicesAType\ContractServiceAType\AccountsAType\AccountAType\PaymentControlAType $paymentControl
     * @return static
     */
    public function setPaymentControl(\common\models\sbbolxml\response\ContractType\ContractServicesAType\ContractServiceAType\AccountsAType\AccountAType\PaymentControlAType $paymentControl)
    {
        $this->paymentControl = $paymentControl;
        return $this;
    }


}


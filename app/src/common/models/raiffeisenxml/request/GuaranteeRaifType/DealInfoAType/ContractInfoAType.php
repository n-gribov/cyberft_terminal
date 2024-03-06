<?php

namespace common\models\raiffeisenxml\request\GuaranteeRaifType\DealInfoAType;

/**
 * Class representing ContractInfoAType
 */
class ContractInfoAType
{

    /**
     * Предмет контракта
     *
     * @property string $subject
     */
    private $subject = null;

    /**
     * Сумма контракта
     *
     * @property \common\models\raiffeisenxml\request\GuaranteeRaifType\DealInfoAType\ContractInfoAType\SumAType $sum
     */
    private $sum = null;

    /**
     * Условия поставки. Возможные значения: "ИНКОТЕРМС 2000", "ИНКОТЕРМС 2010".
     *
     * @property string $delTermsType
     */
    private $delTermsType = null;

    /**
     * Условие поставки. Значение.
     *
     * @property string $delTerms
     */
    private $delTerms = null;

    /**
     * Условие платежа.
     *
     * @property string $payTerms
     */
    private $payTerms = null;

    /**
     * Гарантия выставляется в пользу
     *
     * @property \common\models\raiffeisenxml\request\GuaranteeRaifType\DealInfoAType\ContractInfoAType\GuaranteeReceiverAType $guaranteeReceiver
     */
    private $guaranteeReceiver = null;

    /**
     * Гарантия направлена
     *
     * @property \common\models\raiffeisenxml\request\GuaranteeRaifType\DealInfoAType\ContractInfoAType\GuaranteeAimedAType $guaranteeAimed
     */
    private $guaranteeAimed = null;

    /**
     * Комиссия банка-корреспондента за счет. Возможные значения: "Бенефициара", "Наш счет", "".
     *
     * @property string $commission
     */
    private $commission = null;

    /**
     * Gets as subject
     *
     * Предмет контракта
     *
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Sets a new subject
     *
     * Предмет контракта
     *
     * @param string $subject
     * @return static
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
        return $this;
    }

    /**
     * Gets as sum
     *
     * Сумма контракта
     *
     * @return \common\models\raiffeisenxml\request\GuaranteeRaifType\DealInfoAType\ContractInfoAType\SumAType
     */
    public function getSum()
    {
        return $this->sum;
    }

    /**
     * Sets a new sum
     *
     * Сумма контракта
     *
     * @param \common\models\raiffeisenxml\request\GuaranteeRaifType\DealInfoAType\ContractInfoAType\SumAType $sum
     * @return static
     */
    public function setSum(\common\models\raiffeisenxml\request\GuaranteeRaifType\DealInfoAType\ContractInfoAType\SumAType $sum)
    {
        $this->sum = $sum;
        return $this;
    }

    /**
     * Gets as delTermsType
     *
     * Условия поставки. Возможные значения: "ИНКОТЕРМС 2000", "ИНКОТЕРМС 2010".
     *
     * @return string
     */
    public function getDelTermsType()
    {
        return $this->delTermsType;
    }

    /**
     * Sets a new delTermsType
     *
     * Условия поставки. Возможные значения: "ИНКОТЕРМС 2000", "ИНКОТЕРМС 2010".
     *
     * @param string $delTermsType
     * @return static
     */
    public function setDelTermsType($delTermsType)
    {
        $this->delTermsType = $delTermsType;
        return $this;
    }

    /**
     * Gets as delTerms
     *
     * Условие поставки. Значение.
     *
     * @return string
     */
    public function getDelTerms()
    {
        return $this->delTerms;
    }

    /**
     * Sets a new delTerms
     *
     * Условие поставки. Значение.
     *
     * @param string $delTerms
     * @return static
     */
    public function setDelTerms($delTerms)
    {
        $this->delTerms = $delTerms;
        return $this;
    }

    /**
     * Gets as payTerms
     *
     * Условие платежа.
     *
     * @return string
     */
    public function getPayTerms()
    {
        return $this->payTerms;
    }

    /**
     * Sets a new payTerms
     *
     * Условие платежа.
     *
     * @param string $payTerms
     * @return static
     */
    public function setPayTerms($payTerms)
    {
        $this->payTerms = $payTerms;
        return $this;
    }

    /**
     * Gets as guaranteeReceiver
     *
     * Гарантия выставляется в пользу
     *
     * @return \common\models\raiffeisenxml\request\GuaranteeRaifType\DealInfoAType\ContractInfoAType\GuaranteeReceiverAType
     */
    public function getGuaranteeReceiver()
    {
        return $this->guaranteeReceiver;
    }

    /**
     * Sets a new guaranteeReceiver
     *
     * Гарантия выставляется в пользу
     *
     * @param \common\models\raiffeisenxml\request\GuaranteeRaifType\DealInfoAType\ContractInfoAType\GuaranteeReceiverAType $guaranteeReceiver
     * @return static
     */
    public function setGuaranteeReceiver(\common\models\raiffeisenxml\request\GuaranteeRaifType\DealInfoAType\ContractInfoAType\GuaranteeReceiverAType $guaranteeReceiver)
    {
        $this->guaranteeReceiver = $guaranteeReceiver;
        return $this;
    }

    /**
     * Gets as guaranteeAimed
     *
     * Гарантия направлена
     *
     * @return \common\models\raiffeisenxml\request\GuaranteeRaifType\DealInfoAType\ContractInfoAType\GuaranteeAimedAType
     */
    public function getGuaranteeAimed()
    {
        return $this->guaranteeAimed;
    }

    /**
     * Sets a new guaranteeAimed
     *
     * Гарантия направлена
     *
     * @param \common\models\raiffeisenxml\request\GuaranteeRaifType\DealInfoAType\ContractInfoAType\GuaranteeAimedAType $guaranteeAimed
     * @return static
     */
    public function setGuaranteeAimed(\common\models\raiffeisenxml\request\GuaranteeRaifType\DealInfoAType\ContractInfoAType\GuaranteeAimedAType $guaranteeAimed)
    {
        $this->guaranteeAimed = $guaranteeAimed;
        return $this;
    }

    /**
     * Gets as commission
     *
     * Комиссия банка-корреспондента за счет. Возможные значения: "Бенефициара", "Наш счет", "".
     *
     * @return string
     */
    public function getCommission()
    {
        return $this->commission;
    }

    /**
     * Sets a new commission
     *
     * Комиссия банка-корреспондента за счет. Возможные значения: "Бенефициара", "Наш счет", "".
     *
     * @param string $commission
     * @return static
     */
    public function setCommission($commission)
    {
        $this->commission = $commission;
        return $this;
    }


}


<?php

namespace common\models\sbbolxml\response\AdmPaymentTemplateType;

/**
 * Class representing PayeeInfoAType
 */
class PayeeInfoAType
{

    /**
     * Наименование
     *
     * @property string $payeeName
     */
    private $payeeName = null;

    /**
     * ИНН
     *
     * @property string $payeeInn
     */
    private $payeeInn = null;

    /**
     * КПП
     *
     * @property string $payeeKpp
     */
    private $payeeKpp = null;

    /**
     * признак "Зачисление для бюджетополучателя", 1 - признак установлен, 0 - признак не установлен
     *
     * @property boolean $enrollmentForBudget
     */
    private $enrollmentForBudget = null;

    /**
     * Дополнительная информация к назначению платежа
     *
     * @property string $additionalInfo
     */
    private $additionalInfo = null;

    /**
     * Реквизиты счета
     *
     * @property \common\models\sbbolxml\response\AdmPaymentTemplateType\PayeeInfoAType\AccountAType $account
     */
    private $account = null;

    /**
     * Gets as payeeName
     *
     * Наименование
     *
     * @return string
     */
    public function getPayeeName()
    {
        return $this->payeeName;
    }

    /**
     * Sets a new payeeName
     *
     * Наименование
     *
     * @param string $payeeName
     * @return static
     */
    public function setPayeeName($payeeName)
    {
        $this->payeeName = $payeeName;
        return $this;
    }

    /**
     * Gets as payeeInn
     *
     * ИНН
     *
     * @return string
     */
    public function getPayeeInn()
    {
        return $this->payeeInn;
    }

    /**
     * Sets a new payeeInn
     *
     * ИНН
     *
     * @param string $payeeInn
     * @return static
     */
    public function setPayeeInn($payeeInn)
    {
        $this->payeeInn = $payeeInn;
        return $this;
    }

    /**
     * Gets as payeeKpp
     *
     * КПП
     *
     * @return string
     */
    public function getPayeeKpp()
    {
        return $this->payeeKpp;
    }

    /**
     * Sets a new payeeKpp
     *
     * КПП
     *
     * @param string $payeeKpp
     * @return static
     */
    public function setPayeeKpp($payeeKpp)
    {
        $this->payeeKpp = $payeeKpp;
        return $this;
    }

    /**
     * Gets as enrollmentForBudget
     *
     * признак "Зачисление для бюджетополучателя", 1 - признак установлен, 0 - признак не установлен
     *
     * @return boolean
     */
    public function getEnrollmentForBudget()
    {
        return $this->enrollmentForBudget;
    }

    /**
     * Sets a new enrollmentForBudget
     *
     * признак "Зачисление для бюджетополучателя", 1 - признак установлен, 0 - признак не установлен
     *
     * @param boolean $enrollmentForBudget
     * @return static
     */
    public function setEnrollmentForBudget($enrollmentForBudget)
    {
        $this->enrollmentForBudget = $enrollmentForBudget;
        return $this;
    }

    /**
     * Gets as additionalInfo
     *
     * Дополнительная информация к назначению платежа
     *
     * @return string
     */
    public function getAdditionalInfo()
    {
        return $this->additionalInfo;
    }

    /**
     * Sets a new additionalInfo
     *
     * Дополнительная информация к назначению платежа
     *
     * @param string $additionalInfo
     * @return static
     */
    public function setAdditionalInfo($additionalInfo)
    {
        $this->additionalInfo = $additionalInfo;
        return $this;
    }

    /**
     * Gets as account
     *
     * Реквизиты счета
     *
     * @return \common\models\sbbolxml\response\AdmPaymentTemplateType\PayeeInfoAType\AccountAType
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * Sets a new account
     *
     * Реквизиты счета
     *
     * @param \common\models\sbbolxml\response\AdmPaymentTemplateType\PayeeInfoAType\AccountAType $account
     * @return static
     */
    public function setAccount(\common\models\sbbolxml\response\AdmPaymentTemplateType\PayeeInfoAType\AccountAType $account)
    {
        $this->account = $account;
        return $this;
    }


}


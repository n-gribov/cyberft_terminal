<?php

namespace common\models\sbbolxml\request\AdmPaymentTemplateType\PayeeInfoAType;

/**
 * Class representing AccountAType
 */
class AccountAType
{

    /**
     * @property string $accNum
     */
    private $accNum = null;

    /**
     * Реквизиты банка, в котором открыт счёт
     *
     * @property \common\models\sbbolxml\request\AdmPaymentTemplateType\PayeeInfoAType\AccountAType\BankAType $bank
     */
    private $bank = null;

    /**
     * Gets as accNum
     *
     * @return string
     */
    public function getAccNum()
    {
        return $this->accNum;
    }

    /**
     * Sets a new accNum
     *
     * @param string $accNum
     * @return static
     */
    public function setAccNum($accNum)
    {
        $this->accNum = $accNum;
        return $this;
    }

    /**
     * Gets as bank
     *
     * Реквизиты банка, в котором открыт счёт
     *
     * @return \common\models\sbbolxml\request\AdmPaymentTemplateType\PayeeInfoAType\AccountAType\BankAType
     */
    public function getBank()
    {
        return $this->bank;
    }

    /**
     * Sets a new bank
     *
     * Реквизиты банка, в котором открыт счёт
     *
     * @param \common\models\sbbolxml\request\AdmPaymentTemplateType\PayeeInfoAType\AccountAType\BankAType $bank
     * @return static
     */
    public function setBank(\common\models\sbbolxml\request\AdmPaymentTemplateType\PayeeInfoAType\AccountAType\BankAType $bank)
    {
        $this->bank = $bank;
        return $this;
    }


}


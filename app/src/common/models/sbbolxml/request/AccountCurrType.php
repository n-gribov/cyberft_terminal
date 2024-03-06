<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing AccountCurrType
 *
 *
 * XSD Type: AccountCurr
 */
class AccountCurrType
{

    /**
     * Номер счёта зачисления валюты
     *
     * @property string $accNum
     */
    private $accNum = null;

    /**
     * Реквизиты банка зачисления валюты
     *
     * @property \common\models\sbbolxml\request\AccCurrBankType $bank
     */
    private $bank = null;

    /**
     * Gets as accNum
     *
     * Номер счёта зачисления валюты
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
     * Номер счёта зачисления валюты
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
     * Реквизиты банка зачисления валюты
     *
     * @return \common\models\sbbolxml\request\AccCurrBankType
     */
    public function getBank()
    {
        return $this->bank;
    }

    /**
     * Sets a new bank
     *
     * Реквизиты банка зачисления валюты
     *
     * @param \common\models\sbbolxml\request\AccCurrBankType $bank
     * @return static
     */
    public function setBank(\common\models\sbbolxml\request\AccCurrBankType $bank)
    {
        $this->bank = $bank;
        return $this;
    }


}


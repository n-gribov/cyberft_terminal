<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing AccountRUType
 *
 * Реквизиты транзитного валютного счёта
 * XSD Type: AccountRU
 */
class AccountRUType
{

    /**
     * Реквизиты банка, в котором открыт транзитный валютный счёт
     *
     * @property \common\models\sbbolxml\request\BankType $bank
     */
    private $bank = null;

    /**
     * Номер транзитного валютного счёта
     *
     * @property string $accNum
     */
    private $accNum = null;

    /**
     * Gets as bank
     *
     * Реквизиты банка, в котором открыт транзитный валютный счёт
     *
     * @return \common\models\sbbolxml\request\BankType
     */
    public function getBank()
    {
        return $this->bank;
    }

    /**
     * Sets a new bank
     *
     * Реквизиты банка, в котором открыт транзитный валютный счёт
     *
     * @param \common\models\sbbolxml\request\BankType $bank
     * @return static
     */
    public function setBank(\common\models\sbbolxml\request\BankType $bank)
    {
        $this->bank = $bank;
        return $this;
    }

    /**
     * Gets as accNum
     *
     * Номер транзитного валютного счёта
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
     * Номер транзитного валютного счёта
     *
     * @param string $accNum
     * @return static
     */
    public function setAccNum($accNum)
    {
        $this->accNum = $accNum;
        return $this;
    }


}


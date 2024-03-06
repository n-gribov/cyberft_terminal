<?php

namespace common\models\raiffeisenxml\request;

/**
 * Class representing AccountRUType
 *
 * Реквизиты счёта (рублевые)
 * XSD Type: AccountRU
 */
class AccountRUType
{

    /**
     * Реквизиты банка зачисления
     *
     * @property \common\models\raiffeisenxml\request\BankType $bank
     */
    private $bank = null;

    /**
     * Номер счета
     *
     * @property string $accNum
     */
    private $accNum = null;

    /**
     * Тип счета (например, расчетный)
     *
     * @property string $accType
     */
    private $accType = null;

    /**
     * Gets as bank
     *
     * Реквизиты банка зачисления
     *
     * @return \common\models\raiffeisenxml\request\BankType
     */
    public function getBank()
    {
        return $this->bank;
    }

    /**
     * Sets a new bank
     *
     * Реквизиты банка зачисления
     *
     * @param \common\models\raiffeisenxml\request\BankType $bank
     * @return static
     */
    public function setBank(\common\models\raiffeisenxml\request\BankType $bank)
    {
        $this->bank = $bank;
        return $this;
    }

    /**
     * Gets as accNum
     *
     * Номер счета
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
     * Номер счета
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
     * Gets as accType
     *
     * Тип счета (например, расчетный)
     *
     * @return string
     */
    public function getAccType()
    {
        return $this->accType;
    }

    /**
     * Sets a new accType
     *
     * Тип счета (например, расчетный)
     *
     * @param string $accType
     * @return static
     */
    public function setAccType($accType)
    {
        $this->accType = $accType;
        return $this;
    }


}


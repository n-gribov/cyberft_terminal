<?php

namespace common\models\raiffeisenxml\request;

/**
 * Class representing AccCURType
 *
 * Реквизиты валютного счёта
 * XSD Type: AccCUR
 */
class AccCURType
{

    /**
     * Реквизиты банка зачисления валюты
     *
     * @property \common\models\raiffeisenxml\request\AccCURType\BankAType $bank
     */
    private $bank = null;

    /**
     * Счет зачисления валюты
     *
     * @property string $accNum
     */
    private $accNum = null;

    /**
     * Gets as bank
     *
     * Реквизиты банка зачисления валюты
     *
     * @return \common\models\raiffeisenxml\request\AccCURType\BankAType
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
     * @param \common\models\raiffeisenxml\request\AccCURType\BankAType $bank
     * @return static
     */
    public function setBank(\common\models\raiffeisenxml\request\AccCURType\BankAType $bank)
    {
        $this->bank = $bank;
        return $this;
    }

    /**
     * Gets as accNum
     *
     * Счет зачисления валюты
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
     * Счет зачисления валюты
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


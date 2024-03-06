<?php

namespace common\models\raiffeisenxml\request\AccCURType;

/**
 * Class representing BankAType
 */
class BankAType
{

    /**
     * SWIFT-код банка перевододателя
     *  Bank Identification Code
     *
     * @property string $bIC
     */
    private $bIC = null;

    /**
     * Иные реквизиты банка зачисления валюты
     *
     * @property string $bankInfo
     */
    private $bankInfo = null;

    /**
     * Gets as bIC
     *
     * SWIFT-код банка перевододателя
     *  Bank Identification Code
     *
     * @return string
     */
    public function getBIC()
    {
        return $this->bIC;
    }

    /**
     * Sets a new bIC
     *
     * SWIFT-код банка перевододателя
     *  Bank Identification Code
     *
     * @param string $bIC
     * @return static
     */
    public function setBIC($bIC)
    {
        $this->bIC = $bIC;
        return $this;
    }

    /**
     * Gets as bankInfo
     *
     * Иные реквизиты банка зачисления валюты
     *
     * @return string
     */
    public function getBankInfo()
    {
        return $this->bankInfo;
    }

    /**
     * Sets a new bankInfo
     *
     * Иные реквизиты банка зачисления валюты
     *
     * @param string $bankInfo
     * @return static
     */
    public function setBankInfo($bankInfo)
    {
        $this->bankInfo = $bankInfo;
        return $this;
    }


}


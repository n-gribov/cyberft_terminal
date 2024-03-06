<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing AccCurrBankType
 *
 *
 * XSD Type: AccCurrBank
 */
class AccCurrBankType
{

    /**
     * БИК банка зачисления валюты
     *
     * @property string $bic
     */
    private $bic = null;

    /**
     * Коррсчёт банка зачисления валюты
     *
     * @property string $correspAcc
     */
    private $correspAcc = null;

    /**
     * Наименование банка зачисления валюты (либо в соответствии с BNKSEEK либо наименование
     *  отделения СБРФ)
     *
     * @property string $name
     */
    private $name = null;

    /**
     * SWIFT банка зачисления валюты
     *
     * @property string $bankSWIFT
     */
    private $bankSWIFT = null;

    /**
     * Иные реквизиты банка зачисления валюты
     *
     * @property string $bankInfo
     */
    private $bankInfo = null;

    /**
     * Gets as bic
     *
     * БИК банка зачисления валюты
     *
     * @return string
     */
    public function getBic()
    {
        return $this->bic;
    }

    /**
     * Sets a new bic
     *
     * БИК банка зачисления валюты
     *
     * @param string $bic
     * @return static
     */
    public function setBic($bic)
    {
        $this->bic = $bic;
        return $this;
    }

    /**
     * Gets as correspAcc
     *
     * Коррсчёт банка зачисления валюты
     *
     * @return string
     */
    public function getCorrespAcc()
    {
        return $this->correspAcc;
    }

    /**
     * Sets a new correspAcc
     *
     * Коррсчёт банка зачисления валюты
     *
     * @param string $correspAcc
     * @return static
     */
    public function setCorrespAcc($correspAcc)
    {
        $this->correspAcc = $correspAcc;
        return $this;
    }

    /**
     * Gets as name
     *
     * Наименование банка зачисления валюты (либо в соответствии с BNKSEEK либо наименование
     *  отделения СБРФ)
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets a new name
     *
     * Наименование банка зачисления валюты (либо в соответствии с BNKSEEK либо наименование
     *  отделения СБРФ)
     *
     * @param string $name
     * @return static
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Gets as bankSWIFT
     *
     * SWIFT банка зачисления валюты
     *
     * @return string
     */
    public function getBankSWIFT()
    {
        return $this->bankSWIFT;
    }

    /**
     * Sets a new bankSWIFT
     *
     * SWIFT банка зачисления валюты
     *
     * @param string $bankSWIFT
     * @return static
     */
    public function setBankSWIFT($bankSWIFT)
    {
        $this->bankSWIFT = $bankSWIFT;
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


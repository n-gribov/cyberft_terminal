<?php

namespace common\models\raiffeisenxml\request;

/**
 * Class representing CurrBuyTransRaifType
 *
 *
 * XSD Type: CurrBuyTransRaif
 */
class CurrBuyTransRaifType
{

    /**
     * Сумма покупаемой валюты
     *
     * @property \common\models\raiffeisenxml\request\CurrBuyTransRaifType\AmountBuyAType $amountBuy
     */
    private $amountBuy = null;

    /**
     * Сумма рублей
     *
     * @property float $amountRu
     */
    private $amountRu = null;

    /**
     * Дата списания
     *
     * @property \DateTime $writeOffDate
     */
    private $writeOffDate = null;

    /**
     * Реквизиты счета списания средств на покупку
     *
     * @property \common\models\raiffeisenxml\request\AccountType $accDoc
     */
    private $accDoc = null;

    /**
     * Счет зачисления валюты
     *
     * @property \common\models\raiffeisenxml\request\AccountType $enrollAcc
     */
    private $enrollAcc = null;

    /**
     * Тип сделки:
     *
     *  1 - По индивидуальным условиям
     *
     *  2 - По курсу банка\internal rate
     *
     * @property string $dealType
     */
    private $dealType = null;

    /**
     * Gets as amountBuy
     *
     * Сумма покупаемой валюты
     *
     * @return \common\models\raiffeisenxml\request\CurrBuyTransRaifType\AmountBuyAType
     */
    public function getAmountBuy()
    {
        return $this->amountBuy;
    }

    /**
     * Sets a new amountBuy
     *
     * Сумма покупаемой валюты
     *
     * @param \common\models\raiffeisenxml\request\CurrBuyTransRaifType\AmountBuyAType $amountBuy
     * @return static
     */
    public function setAmountBuy(\common\models\raiffeisenxml\request\CurrBuyTransRaifType\AmountBuyAType $amountBuy)
    {
        $this->amountBuy = $amountBuy;
        return $this;
    }

    /**
     * Gets as amountRu
     *
     * Сумма рублей
     *
     * @return float
     */
    public function getAmountRu()
    {
        return $this->amountRu;
    }

    /**
     * Sets a new amountRu
     *
     * Сумма рублей
     *
     * @param float $amountRu
     * @return static
     */
    public function setAmountRu($amountRu)
    {
        $this->amountRu = $amountRu;
        return $this;
    }

    /**
     * Gets as writeOffDate
     *
     * Дата списания
     *
     * @return \DateTime
     */
    public function getWriteOffDate()
    {
        return $this->writeOffDate;
    }

    /**
     * Sets a new writeOffDate
     *
     * Дата списания
     *
     * @param \DateTime $writeOffDate
     * @return static
     */
    public function setWriteOffDate(\DateTime $writeOffDate)
    {
        $this->writeOffDate = $writeOffDate;
        return $this;
    }

    /**
     * Gets as accDoc
     *
     * Реквизиты счета списания средств на покупку
     *
     * @return \common\models\raiffeisenxml\request\AccountType
     */
    public function getAccDoc()
    {
        return $this->accDoc;
    }

    /**
     * Sets a new accDoc
     *
     * Реквизиты счета списания средств на покупку
     *
     * @param \common\models\raiffeisenxml\request\AccountType $accDoc
     * @return static
     */
    public function setAccDoc(\common\models\raiffeisenxml\request\AccountType $accDoc)
    {
        $this->accDoc = $accDoc;
        return $this;
    }

    /**
     * Gets as enrollAcc
     *
     * Счет зачисления валюты
     *
     * @return \common\models\raiffeisenxml\request\AccountType
     */
    public function getEnrollAcc()
    {
        return $this->enrollAcc;
    }

    /**
     * Sets a new enrollAcc
     *
     * Счет зачисления валюты
     *
     * @param \common\models\raiffeisenxml\request\AccountType $enrollAcc
     * @return static
     */
    public function setEnrollAcc(\common\models\raiffeisenxml\request\AccountType $enrollAcc)
    {
        $this->enrollAcc = $enrollAcc;
        return $this;
    }

    /**
     * Gets as dealType
     *
     * Тип сделки:
     *
     *  1 - По индивидуальным условиям
     *
     *  2 - По курсу банка\internal rate
     *
     * @return string
     */
    public function getDealType()
    {
        return $this->dealType;
    }

    /**
     * Sets a new dealType
     *
     * Тип сделки:
     *
     *  1 - По индивидуальным условиям
     *
     *  2 - По курсу банка\internal rate
     *
     * @param string $dealType
     * @return static
     */
    public function setDealType($dealType)
    {
        $this->dealType = $dealType;
        return $this;
    }


}


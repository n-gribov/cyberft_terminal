<?php

namespace common\models\raiffeisenxml\request;

/**
 * Class representing CurrConvTransRaifType
 *
 *
 * XSD Type: CurrConvTransRaif
 */
class CurrConvTransRaifType
{

    /**
     * Продаваемая валюта
     *
     * @property \common\models\raiffeisenxml\request\CurrConvTransRaifType\SellAmountAType $sellAmount
     */
    private $sellAmount = null;

    /**
     * Покупаемая валюта
     *
     * @property \common\models\raiffeisenxml\request\CurrConvTransRaifType\BuyAmountAType $buyAmount
     */
    private $buyAmount = null;

    /**
     * Дата списания
     *
     * @property \DateTime $writeOffDate
     */
    private $writeOffDate = null;

    /**
     * Тип сделки
     *
     * @property string $dealType
     */
    private $dealType = null;

    /**
     * Счет списания продаваемой валюты
     *
     * @property \common\models\raiffeisenxml\request\AccountType $accDoc
     */
    private $accDoc = null;

    /**
     * Счет зачисления купленной валюты
     *
     * @property \common\models\raiffeisenxml\request\AccountType $enrollAcc
     */
    private $enrollAcc = null;

    /**
     * Gets as sellAmount
     *
     * Продаваемая валюта
     *
     * @return \common\models\raiffeisenxml\request\CurrConvTransRaifType\SellAmountAType
     */
    public function getSellAmount()
    {
        return $this->sellAmount;
    }

    /**
     * Sets a new sellAmount
     *
     * Продаваемая валюта
     *
     * @param \common\models\raiffeisenxml\request\CurrConvTransRaifType\SellAmountAType $sellAmount
     * @return static
     */
    public function setSellAmount(\common\models\raiffeisenxml\request\CurrConvTransRaifType\SellAmountAType $sellAmount)
    {
        $this->sellAmount = $sellAmount;
        return $this;
    }

    /**
     * Gets as buyAmount
     *
     * Покупаемая валюта
     *
     * @return \common\models\raiffeisenxml\request\CurrConvTransRaifType\BuyAmountAType
     */
    public function getBuyAmount()
    {
        return $this->buyAmount;
    }

    /**
     * Sets a new buyAmount
     *
     * Покупаемая валюта
     *
     * @param \common\models\raiffeisenxml\request\CurrConvTransRaifType\BuyAmountAType $buyAmount
     * @return static
     */
    public function setBuyAmount(\common\models\raiffeisenxml\request\CurrConvTransRaifType\BuyAmountAType $buyAmount)
    {
        $this->buyAmount = $buyAmount;
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
     * Gets as dealType
     *
     * Тип сделки
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
     * Тип сделки
     *
     * @param string $dealType
     * @return static
     */
    public function setDealType($dealType)
    {
        $this->dealType = $dealType;
        return $this;
    }

    /**
     * Gets as accDoc
     *
     * Счет списания продаваемой валюты
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
     * Счет списания продаваемой валюты
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
     * Счет зачисления купленной валюты
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
     * Счет зачисления купленной валюты
     *
     * @param \common\models\raiffeisenxml\request\AccountType $enrollAcc
     * @return static
     */
    public function setEnrollAcc(\common\models\raiffeisenxml\request\AccountType $enrollAcc)
    {
        $this->enrollAcc = $enrollAcc;
        return $this;
    }


}


<?php

namespace common\models\raiffeisenxml\request\AccreditiveCurRaifType;

/**
 * Class representing OtherAType
 */
class OtherAType
{

    /**
     * Дата истечения аккредитива
     *
     * @property \DateTime $expDate
     */
    private $expDate = null;

    /**
     * Место истечения аккредитива
     *
     * @property string $expPlace
     */
    private $expPlace = null;

    /**
     * Аккредитив исполняется путем.
     *  Допустимые значения: "Платежа по предъявлении / Payment at sight", "Платежа с отсрочкой / Deferred payment", "Акцепта тратт / Acceptance of drafts", "Смешанного платежа / Mixed payment", "Негоциации / Negotiation".
     *
     * @property string $executionWay
     */
    private $executionWay = null;

    /**
     * Вариант задания срока отсроченного платежа. Возможные значения: "С даты представления документов / From the date of presentation of docs", "С даты отгрузки товара / From the date of shipment of goods", "Другое / Other".
     *
     * @property string $defPaymentTermType
     */
    private $defPaymentTermType = null;

    /**
     * Значение, для варианта задания "другое"
     *
     * @property string $defPaymentTermOther
     */
    private $defPaymentTermOther = null;

    /**
     * Срок отсроченного платежа.
     *
     * @property string $defPaymentTerm
     */
    private $defPaymentTerm = null;

    /**
     * Детализация.
     *
     * @property string $details
     */
    private $details = null;

    /**
     * Gets as expDate
     *
     * Дата истечения аккредитива
     *
     * @return \DateTime
     */
    public function getExpDate()
    {
        return $this->expDate;
    }

    /**
     * Sets a new expDate
     *
     * Дата истечения аккредитива
     *
     * @param \DateTime $expDate
     * @return static
     */
    public function setExpDate(\DateTime $expDate)
    {
        $this->expDate = $expDate;
        return $this;
    }

    /**
     * Gets as expPlace
     *
     * Место истечения аккредитива
     *
     * @return string
     */
    public function getExpPlace()
    {
        return $this->expPlace;
    }

    /**
     * Sets a new expPlace
     *
     * Место истечения аккредитива
     *
     * @param string $expPlace
     * @return static
     */
    public function setExpPlace($expPlace)
    {
        $this->expPlace = $expPlace;
        return $this;
    }

    /**
     * Gets as executionWay
     *
     * Аккредитив исполняется путем.
     *  Допустимые значения: "Платежа по предъявлении / Payment at sight", "Платежа с отсрочкой / Deferred payment", "Акцепта тратт / Acceptance of drafts", "Смешанного платежа / Mixed payment", "Негоциации / Negotiation".
     *
     * @return string
     */
    public function getExecutionWay()
    {
        return $this->executionWay;
    }

    /**
     * Sets a new executionWay
     *
     * Аккредитив исполняется путем.
     *  Допустимые значения: "Платежа по предъявлении / Payment at sight", "Платежа с отсрочкой / Deferred payment", "Акцепта тратт / Acceptance of drafts", "Смешанного платежа / Mixed payment", "Негоциации / Negotiation".
     *
     * @param string $executionWay
     * @return static
     */
    public function setExecutionWay($executionWay)
    {
        $this->executionWay = $executionWay;
        return $this;
    }

    /**
     * Gets as defPaymentTermType
     *
     * Вариант задания срока отсроченного платежа. Возможные значения: "С даты представления документов / From the date of presentation of docs", "С даты отгрузки товара / From the date of shipment of goods", "Другое / Other".
     *
     * @return string
     */
    public function getDefPaymentTermType()
    {
        return $this->defPaymentTermType;
    }

    /**
     * Sets a new defPaymentTermType
     *
     * Вариант задания срока отсроченного платежа. Возможные значения: "С даты представления документов / From the date of presentation of docs", "С даты отгрузки товара / From the date of shipment of goods", "Другое / Other".
     *
     * @param string $defPaymentTermType
     * @return static
     */
    public function setDefPaymentTermType($defPaymentTermType)
    {
        $this->defPaymentTermType = $defPaymentTermType;
        return $this;
    }

    /**
     * Gets as defPaymentTermOther
     *
     * Значение, для варианта задания "другое"
     *
     * @return string
     */
    public function getDefPaymentTermOther()
    {
        return $this->defPaymentTermOther;
    }

    /**
     * Sets a new defPaymentTermOther
     *
     * Значение, для варианта задания "другое"
     *
     * @param string $defPaymentTermOther
     * @return static
     */
    public function setDefPaymentTermOther($defPaymentTermOther)
    {
        $this->defPaymentTermOther = $defPaymentTermOther;
        return $this;
    }

    /**
     * Gets as defPaymentTerm
     *
     * Срок отсроченного платежа.
     *
     * @return string
     */
    public function getDefPaymentTerm()
    {
        return $this->defPaymentTerm;
    }

    /**
     * Sets a new defPaymentTerm
     *
     * Срок отсроченного платежа.
     *
     * @param string $defPaymentTerm
     * @return static
     */
    public function setDefPaymentTerm($defPaymentTerm)
    {
        $this->defPaymentTerm = $defPaymentTerm;
        return $this;
    }

    /**
     * Gets as details
     *
     * Детализация.
     *
     * @return string
     */
    public function getDetails()
    {
        return $this->details;
    }

    /**
     * Sets a new details
     *
     * Детализация.
     *
     * @param string $details
     * @return static
     */
    public function setDetails($details)
    {
        $this->details = $details;
        return $this;
    }


}


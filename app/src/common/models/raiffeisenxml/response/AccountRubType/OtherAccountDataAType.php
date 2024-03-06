<?php

namespace common\models\raiffeisenxml\response\AccountRubType;

/**
 * Class representing OtherAccountDataAType
 */
class OtherAccountDataAType
{

    /**
     * Наименование счета
     *
     * @property string $name
     */
    private $name = null;

    /**
     * Числовой код валюты счета
     *
     *  Если не пришло, то вычисляем по номеру счета
     *
     * @property string $currencyCode
     */
    private $currencyCode = null;

    /**
     * ISO-код валюты счета
     *
     * @property string $currencyISOCode
     */
    private $currencyISOCode = null;

    /**
     * Тип счёта: 01 – расчетный, 02 – бюджетный, 03 – транзитный, 04- специальный транзитный валютный счет, 05 - ссудный, 06 - счет по учету обеспечения.
     *
     * @property string $accountType
     */
    private $accountType = null;

    /**
     * Тип счёта: 0 – активный, 1 – пассивный (по умолчанию)
     *
     * @property bool $accountTypeP
     */
    private $accountTypeP = null;

    /**
     * Признак бизнес-счёта
     *
     * @property string $business
     */
    private $business = null;

    /**
     * Признак возможности проведения неотложных платежей: 0 - не установлен, 1 - установлен
     *
     * @property bool $isNotDelay
     */
    private $isNotDelay = null;

    /**
     * Признак проведения срочных платежей
     *  Возможные значения: 0 - не установлен, 1 - установлен
     *
     * @property bool $isUrgent
     */
    private $isUrgent = null;

    /**
     * Дата открытия счета
     *
     * @property \DateTime $createDate
     */
    private $createDate = null;

    /**
     * Дата закрытия счёта
     *
     * @property \DateTime $closeDate
     */
    private $closeDate = null;

    /**
     * Признак закрытия счета: 0 - признак не установлен (по умолчаиню), 1 - установлен
     *
     * @property bool $closed
     */
    private $closed = null;

    /**
     * Сумма общего лимита овердрафта в валюте счёта
     *
     * @property float $sumOvd
     */
    private $sumOvd = null;

    /**
     * Дополнительная информация
     *
     * @property string $addInfo
     */
    private $addInfo = null;

    /**
     * Gets as name
     *
     * Наименование счета
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
     * Наименование счета
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
     * Gets as currencyCode
     *
     * Числовой код валюты счета
     *
     *  Если не пришло, то вычисляем по номеру счета
     *
     * @return string
     */
    public function getCurrencyCode()
    {
        return $this->currencyCode;
    }

    /**
     * Sets a new currencyCode
     *
     * Числовой код валюты счета
     *
     *  Если не пришло, то вычисляем по номеру счета
     *
     * @param string $currencyCode
     * @return static
     */
    public function setCurrencyCode($currencyCode)
    {
        $this->currencyCode = $currencyCode;
        return $this;
    }

    /**
     * Gets as currencyISOCode
     *
     * ISO-код валюты счета
     *
     * @return string
     */
    public function getCurrencyISOCode()
    {
        return $this->currencyISOCode;
    }

    /**
     * Sets a new currencyISOCode
     *
     * ISO-код валюты счета
     *
     * @param string $currencyISOCode
     * @return static
     */
    public function setCurrencyISOCode($currencyISOCode)
    {
        $this->currencyISOCode = $currencyISOCode;
        return $this;
    }

    /**
     * Gets as accountType
     *
     * Тип счёта: 01 – расчетный, 02 – бюджетный, 03 – транзитный, 04- специальный транзитный валютный счет, 05 - ссудный, 06 - счет по учету обеспечения.
     *
     * @return string
     */
    public function getAccountType()
    {
        return $this->accountType;
    }

    /**
     * Sets a new accountType
     *
     * Тип счёта: 01 – расчетный, 02 – бюджетный, 03 – транзитный, 04- специальный транзитный валютный счет, 05 - ссудный, 06 - счет по учету обеспечения.
     *
     * @param string $accountType
     * @return static
     */
    public function setAccountType($accountType)
    {
        $this->accountType = $accountType;
        return $this;
    }

    /**
     * Gets as accountTypeP
     *
     * Тип счёта: 0 – активный, 1 – пассивный (по умолчанию)
     *
     * @return bool
     */
    public function getAccountTypeP()
    {
        return $this->accountTypeP;
    }

    /**
     * Sets a new accountTypeP
     *
     * Тип счёта: 0 – активный, 1 – пассивный (по умолчанию)
     *
     * @param bool $accountTypeP
     * @return static
     */
    public function setAccountTypeP($accountTypeP)
    {
        $this->accountTypeP = $accountTypeP;
        return $this;
    }

    /**
     * Gets as business
     *
     * Признак бизнес-счёта
     *
     * @return string
     */
    public function getBusiness()
    {
        return $this->business;
    }

    /**
     * Sets a new business
     *
     * Признак бизнес-счёта
     *
     * @param string $business
     * @return static
     */
    public function setBusiness($business)
    {
        $this->business = $business;
        return $this;
    }

    /**
     * Gets as isNotDelay
     *
     * Признак возможности проведения неотложных платежей: 0 - не установлен, 1 - установлен
     *
     * @return bool
     */
    public function getIsNotDelay()
    {
        return $this->isNotDelay;
    }

    /**
     * Sets a new isNotDelay
     *
     * Признак возможности проведения неотложных платежей: 0 - не установлен, 1 - установлен
     *
     * @param bool $isNotDelay
     * @return static
     */
    public function setIsNotDelay($isNotDelay)
    {
        $this->isNotDelay = $isNotDelay;
        return $this;
    }

    /**
     * Gets as isUrgent
     *
     * Признак проведения срочных платежей
     *  Возможные значения: 0 - не установлен, 1 - установлен
     *
     * @return bool
     */
    public function getIsUrgent()
    {
        return $this->isUrgent;
    }

    /**
     * Sets a new isUrgent
     *
     * Признак проведения срочных платежей
     *  Возможные значения: 0 - не установлен, 1 - установлен
     *
     * @param bool $isUrgent
     * @return static
     */
    public function setIsUrgent($isUrgent)
    {
        $this->isUrgent = $isUrgent;
        return $this;
    }

    /**
     * Gets as createDate
     *
     * Дата открытия счета
     *
     * @return \DateTime
     */
    public function getCreateDate()
    {
        return $this->createDate;
    }

    /**
     * Sets a new createDate
     *
     * Дата открытия счета
     *
     * @param \DateTime $createDate
     * @return static
     */
    public function setCreateDate(\DateTime $createDate)
    {
        $this->createDate = $createDate;
        return $this;
    }

    /**
     * Gets as closeDate
     *
     * Дата закрытия счёта
     *
     * @return \DateTime
     */
    public function getCloseDate()
    {
        return $this->closeDate;
    }

    /**
     * Sets a new closeDate
     *
     * Дата закрытия счёта
     *
     * @param \DateTime $closeDate
     * @return static
     */
    public function setCloseDate(\DateTime $closeDate)
    {
        $this->closeDate = $closeDate;
        return $this;
    }

    /**
     * Gets as closed
     *
     * Признак закрытия счета: 0 - признак не установлен (по умолчаиню), 1 - установлен
     *
     * @return bool
     */
    public function getClosed()
    {
        return $this->closed;
    }

    /**
     * Sets a new closed
     *
     * Признак закрытия счета: 0 - признак не установлен (по умолчаиню), 1 - установлен
     *
     * @param bool $closed
     * @return static
     */
    public function setClosed($closed)
    {
        $this->closed = $closed;
        return $this;
    }

    /**
     * Gets as sumOvd
     *
     * Сумма общего лимита овердрафта в валюте счёта
     *
     * @return float
     */
    public function getSumOvd()
    {
        return $this->sumOvd;
    }

    /**
     * Sets a new sumOvd
     *
     * Сумма общего лимита овердрафта в валюте счёта
     *
     * @param float $sumOvd
     * @return static
     */
    public function setSumOvd($sumOvd)
    {
        $this->sumOvd = $sumOvd;
        return $this;
    }

    /**
     * Gets as addInfo
     *
     * Дополнительная информация
     *
     * @return string
     */
    public function getAddInfo()
    {
        return $this->addInfo;
    }

    /**
     * Sets a new addInfo
     *
     * Дополнительная информация
     *
     * @param string $addInfo
     * @return static
     */
    public function setAddInfo($addInfo)
    {
        $this->addInfo = $addInfo;
        return $this;
    }


}


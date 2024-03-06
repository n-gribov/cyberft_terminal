<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing DealInfoType
 *
 * Информация о сделке
 * XSD Type: DealInfoType
 */
class DealInfoType
{

    /**
     * Номер сделки в DD
     *
     * @property string $dealIDDD
     */
    private $dealIDDD = null;

    /**
     * Номер подтверждения в DD
     *
     * @property string $dealConfID
     */
    private $dealConfID = null;

    /**
     * Тип сделки(инструмент):
     *  1 - FX OTC SPOT (Today),
     *  2 - FX OTC SPOT (Tomorrow),
     *  3 - FX OTC SPOT (T+2),
     *  4 - FX OTC Forward,
     *  5 - FX OTC SWAP
     *
     * @property integer $instr
     */
    private $instr = null;

    /**
     * Дата и время заключения сделки
     *
     * @property \DateTime $dealDateTime
     */
    private $dealDateTime = null;

    /**
     * Наименование Банка
     *
     * @property string $bankName
     */
    private $bankName = null;

    /**
     * Наименование Клиента
     *
     * @property string $clientName
     */
    private $clientName = null;

    /**
     * Признак сделка покупки/продажи. Возможные значения: «B» - Сделка покупки. «S» - Сделка продажи.
     *
     * @property string $tradeType
     */
    private $tradeType = null;

    /**
     * Фамилия, имя, отчество трейдера Сбербанка
     *
     * @property string $dealerSB
     */
    private $dealerSB = null;

    /**
     * Обменный курс сделки
     *
     * @property string $rate
     */
    private $rate = null;

    /**
     * Иные условия
     *
     * @property string $otherDetails
     */
    private $otherDetails = null;

    /**
     * Сумма сделки в валюте расчетов
     *
     * @property string $dealQuantity
     */
    private $dealQuantity = null;

    /**
     * Сделка СВОП
     *
     * @property boolean $swap
     */
    private $swap = null;

    /**
     * Счет получателя
     *
     * @property string $payeeAccount
     */
    private $payeeAccount = null;

    /**
     * БИК
     *
     * @property string $bic
     */
    private $bic = null;

    /**
     * SWIFT
     *
     * @property string $swift
     */
    private $swift = null;

    /**
     * Gets as dealIDDD
     *
     * Номер сделки в DD
     *
     * @return string
     */
    public function getDealIDDD()
    {
        return $this->dealIDDD;
    }

    /**
     * Sets a new dealIDDD
     *
     * Номер сделки в DD
     *
     * @param string $dealIDDD
     * @return static
     */
    public function setDealIDDD($dealIDDD)
    {
        $this->dealIDDD = $dealIDDD;
        return $this;
    }

    /**
     * Gets as dealConfID
     *
     * Номер подтверждения в DD
     *
     * @return string
     */
    public function getDealConfID()
    {
        return $this->dealConfID;
    }

    /**
     * Sets a new dealConfID
     *
     * Номер подтверждения в DD
     *
     * @param string $dealConfID
     * @return static
     */
    public function setDealConfID($dealConfID)
    {
        $this->dealConfID = $dealConfID;
        return $this;
    }

    /**
     * Gets as instr
     *
     * Тип сделки(инструмент):
     *  1 - FX OTC SPOT (Today),
     *  2 - FX OTC SPOT (Tomorrow),
     *  3 - FX OTC SPOT (T+2),
     *  4 - FX OTC Forward,
     *  5 - FX OTC SWAP
     *
     * @return integer
     */
    public function getInstr()
    {
        return $this->instr;
    }

    /**
     * Sets a new instr
     *
     * Тип сделки(инструмент):
     *  1 - FX OTC SPOT (Today),
     *  2 - FX OTC SPOT (Tomorrow),
     *  3 - FX OTC SPOT (T+2),
     *  4 - FX OTC Forward,
     *  5 - FX OTC SWAP
     *
     * @param integer $instr
     * @return static
     */
    public function setInstr($instr)
    {
        $this->instr = $instr;
        return $this;
    }

    /**
     * Gets as dealDateTime
     *
     * Дата и время заключения сделки
     *
     * @return \DateTime
     */
    public function getDealDateTime()
    {
        return $this->dealDateTime;
    }

    /**
     * Sets a new dealDateTime
     *
     * Дата и время заключения сделки
     *
     * @param \DateTime $dealDateTime
     * @return static
     */
    public function setDealDateTime(\DateTime $dealDateTime)
    {
        $this->dealDateTime = $dealDateTime;
        return $this;
    }

    /**
     * Gets as bankName
     *
     * Наименование Банка
     *
     * @return string
     */
    public function getBankName()
    {
        return $this->bankName;
    }

    /**
     * Sets a new bankName
     *
     * Наименование Банка
     *
     * @param string $bankName
     * @return static
     */
    public function setBankName($bankName)
    {
        $this->bankName = $bankName;
        return $this;
    }

    /**
     * Gets as clientName
     *
     * Наименование Клиента
     *
     * @return string
     */
    public function getClientName()
    {
        return $this->clientName;
    }

    /**
     * Sets a new clientName
     *
     * Наименование Клиента
     *
     * @param string $clientName
     * @return static
     */
    public function setClientName($clientName)
    {
        $this->clientName = $clientName;
        return $this;
    }

    /**
     * Gets as tradeType
     *
     * Признак сделка покупки/продажи. Возможные значения: «B» - Сделка покупки. «S» - Сделка продажи.
     *
     * @return string
     */
    public function getTradeType()
    {
        return $this->tradeType;
    }

    /**
     * Sets a new tradeType
     *
     * Признак сделка покупки/продажи. Возможные значения: «B» - Сделка покупки. «S» - Сделка продажи.
     *
     * @param string $tradeType
     * @return static
     */
    public function setTradeType($tradeType)
    {
        $this->tradeType = $tradeType;
        return $this;
    }

    /**
     * Gets as dealerSB
     *
     * Фамилия, имя, отчество трейдера Сбербанка
     *
     * @return string
     */
    public function getDealerSB()
    {
        return $this->dealerSB;
    }

    /**
     * Sets a new dealerSB
     *
     * Фамилия, имя, отчество трейдера Сбербанка
     *
     * @param string $dealerSB
     * @return static
     */
    public function setDealerSB($dealerSB)
    {
        $this->dealerSB = $dealerSB;
        return $this;
    }

    /**
     * Gets as rate
     *
     * Обменный курс сделки
     *
     * @return string
     */
    public function getRate()
    {
        return $this->rate;
    }

    /**
     * Sets a new rate
     *
     * Обменный курс сделки
     *
     * @param string $rate
     * @return static
     */
    public function setRate($rate)
    {
        $this->rate = $rate;
        return $this;
    }

    /**
     * Gets as otherDetails
     *
     * Иные условия
     *
     * @return string
     */
    public function getOtherDetails()
    {
        return $this->otherDetails;
    }

    /**
     * Sets a new otherDetails
     *
     * Иные условия
     *
     * @param string $otherDetails
     * @return static
     */
    public function setOtherDetails($otherDetails)
    {
        $this->otherDetails = $otherDetails;
        return $this;
    }

    /**
     * Gets as dealQuantity
     *
     * Сумма сделки в валюте расчетов
     *
     * @return string
     */
    public function getDealQuantity()
    {
        return $this->dealQuantity;
    }

    /**
     * Sets a new dealQuantity
     *
     * Сумма сделки в валюте расчетов
     *
     * @param string $dealQuantity
     * @return static
     */
    public function setDealQuantity($dealQuantity)
    {
        $this->dealQuantity = $dealQuantity;
        return $this;
    }

    /**
     * Gets as swap
     *
     * Сделка СВОП
     *
     * @return boolean
     */
    public function getSwap()
    {
        return $this->swap;
    }

    /**
     * Sets a new swap
     *
     * Сделка СВОП
     *
     * @param boolean $swap
     * @return static
     */
    public function setSwap($swap)
    {
        $this->swap = $swap;
        return $this;
    }

    /**
     * Gets as payeeAccount
     *
     * Счет получателя
     *
     * @return string
     */
    public function getPayeeAccount()
    {
        return $this->payeeAccount;
    }

    /**
     * Sets a new payeeAccount
     *
     * Счет получателя
     *
     * @param string $payeeAccount
     * @return static
     */
    public function setPayeeAccount($payeeAccount)
    {
        $this->payeeAccount = $payeeAccount;
        return $this;
    }

    /**
     * Gets as bic
     *
     * БИК
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
     * БИК
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
     * Gets as swift
     *
     * SWIFT
     *
     * @return string
     */
    public function getSwift()
    {
        return $this->swift;
    }

    /**
     * Sets a new swift
     *
     * SWIFT
     *
     * @param string $swift
     * @return static
     */
    public function setSwift($swift)
    {
        $this->swift = $swift;
        return $this;
    }


}


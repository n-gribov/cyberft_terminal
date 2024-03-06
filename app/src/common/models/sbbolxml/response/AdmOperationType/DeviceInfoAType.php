<?php

namespace common\models\sbbolxml\response\AdmOperationType;

/**
 * Class representing DeviceInfoAType
 */
class DeviceInfoAType
{

    /**
     * Тип устройства: US или ADM
     *
     * @property string $deviceType
     */
    private $deviceType = null;

    /**
     * Кассовый символ (для устройств самообслуживания)
     *
     * @property string $adaptedPayCashSymbolName
     */
    private $adaptedPayCashSymbolName = null;

    /**
     * Адрес устройства самообслуживания
     *
     * @property string $usAddress
     */
    private $usAddress = null;

    /**
     * Идентификатор устройства самообслуживания
     *
     * @property string $admId
     */
    private $admId = null;

    /**
     * Кассовый символ (для устройств самообслуживания), цифровое значение
     *
     * @property string $cashSymbol
     */
    private $cashSymbol = null;

    /**
     * Наименование торговой точки
     *
     * @property string $tradePointName
     */
    private $tradePointName = null;

    /**
     * Gets as deviceType
     *
     * Тип устройства: US или ADM
     *
     * @return string
     */
    public function getDeviceType()
    {
        return $this->deviceType;
    }

    /**
     * Sets a new deviceType
     *
     * Тип устройства: US или ADM
     *
     * @param string $deviceType
     * @return static
     */
    public function setDeviceType($deviceType)
    {
        $this->deviceType = $deviceType;
        return $this;
    }

    /**
     * Gets as adaptedPayCashSymbolName
     *
     * Кассовый символ (для устройств самообслуживания)
     *
     * @return string
     */
    public function getAdaptedPayCashSymbolName()
    {
        return $this->adaptedPayCashSymbolName;
    }

    /**
     * Sets a new adaptedPayCashSymbolName
     *
     * Кассовый символ (для устройств самообслуживания)
     *
     * @param string $adaptedPayCashSymbolName
     * @return static
     */
    public function setAdaptedPayCashSymbolName($adaptedPayCashSymbolName)
    {
        $this->adaptedPayCashSymbolName = $adaptedPayCashSymbolName;
        return $this;
    }

    /**
     * Gets as usAddress
     *
     * Адрес устройства самообслуживания
     *
     * @return string
     */
    public function getUsAddress()
    {
        return $this->usAddress;
    }

    /**
     * Sets a new usAddress
     *
     * Адрес устройства самообслуживания
     *
     * @param string $usAddress
     * @return static
     */
    public function setUsAddress($usAddress)
    {
        $this->usAddress = $usAddress;
        return $this;
    }

    /**
     * Gets as admId
     *
     * Идентификатор устройства самообслуживания
     *
     * @return string
     */
    public function getAdmId()
    {
        return $this->admId;
    }

    /**
     * Sets a new admId
     *
     * Идентификатор устройства самообслуживания
     *
     * @param string $admId
     * @return static
     */
    public function setAdmId($admId)
    {
        $this->admId = $admId;
        return $this;
    }

    /**
     * Gets as cashSymbol
     *
     * Кассовый символ (для устройств самообслуживания), цифровое значение
     *
     * @return string
     */
    public function getCashSymbol()
    {
        return $this->cashSymbol;
    }

    /**
     * Sets a new cashSymbol
     *
     * Кассовый символ (для устройств самообслуживания), цифровое значение
     *
     * @param string $cashSymbol
     * @return static
     */
    public function setCashSymbol($cashSymbol)
    {
        $this->cashSymbol = $cashSymbol;
        return $this;
    }

    /**
     * Gets as tradePointName
     *
     * Наименование торговой точки
     *
     * @return string
     */
    public function getTradePointName()
    {
        return $this->tradePointName;
    }

    /**
     * Sets a new tradePointName
     *
     * Наименование торговой точки
     *
     * @param string $tradePointName
     * @return static
     */
    public function setTradePointName($tradePointName)
    {
        $this->tradePointName = $tradePointName;
        return $this;
    }


}


<?php

namespace common\models\raiffeisenxml\request\AccreditiveCurRaifType;

/**
 * Class representing ShippingAType
 */
class ShippingAType
{

    /**
     * Частичные отгрузки. Возможные значения (0 - не разрешены, 1 - разрешены).
     *
     * @property bool $partialShipment
     */
    private $partialShipment = null;

    /**
     * Перегрузки. Возможные значения: 0 - не разрешены, 1 - разрешены.
     *
     * @property bool $overload
     */
    private $overload = null;

    /**
     * Место отгрузки
     *
     * @property string $shippingPlace
     */
    private $shippingPlace = null;

    /**
     * Порт погрузки/ аэропорт отправки
     *
     * @property string $depPlace
     */
    private $depPlace = null;

    /**
     * Место назначения
     *
     * @property string $destination
     */
    private $destination = null;

    /**
     * Порт разгрузки/ аэропорт назначения
     *
     * @property string $arrPlace
     */
    private $arrPlace = null;

    /**
     * Не позднее
     *
     * @property \DateTime $notLater
     */
    private $notLater = null;

    /**
     * Период отгрузки
     *
     * @property string $shipmentPeriod
     */
    private $shipmentPeriod = null;

    /**
     * Gets as partialShipment
     *
     * Частичные отгрузки. Возможные значения (0 - не разрешены, 1 - разрешены).
     *
     * @return bool
     */
    public function getPartialShipment()
    {
        return $this->partialShipment;
    }

    /**
     * Sets a new partialShipment
     *
     * Частичные отгрузки. Возможные значения (0 - не разрешены, 1 - разрешены).
     *
     * @param bool $partialShipment
     * @return static
     */
    public function setPartialShipment($partialShipment)
    {
        $this->partialShipment = $partialShipment;
        return $this;
    }

    /**
     * Gets as overload
     *
     * Перегрузки. Возможные значения: 0 - не разрешены, 1 - разрешены.
     *
     * @return bool
     */
    public function getOverload()
    {
        return $this->overload;
    }

    /**
     * Sets a new overload
     *
     * Перегрузки. Возможные значения: 0 - не разрешены, 1 - разрешены.
     *
     * @param bool $overload
     * @return static
     */
    public function setOverload($overload)
    {
        $this->overload = $overload;
        return $this;
    }

    /**
     * Gets as shippingPlace
     *
     * Место отгрузки
     *
     * @return string
     */
    public function getShippingPlace()
    {
        return $this->shippingPlace;
    }

    /**
     * Sets a new shippingPlace
     *
     * Место отгрузки
     *
     * @param string $shippingPlace
     * @return static
     */
    public function setShippingPlace($shippingPlace)
    {
        $this->shippingPlace = $shippingPlace;
        return $this;
    }

    /**
     * Gets as depPlace
     *
     * Порт погрузки/ аэропорт отправки
     *
     * @return string
     */
    public function getDepPlace()
    {
        return $this->depPlace;
    }

    /**
     * Sets a new depPlace
     *
     * Порт погрузки/ аэропорт отправки
     *
     * @param string $depPlace
     * @return static
     */
    public function setDepPlace($depPlace)
    {
        $this->depPlace = $depPlace;
        return $this;
    }

    /**
     * Gets as destination
     *
     * Место назначения
     *
     * @return string
     */
    public function getDestination()
    {
        return $this->destination;
    }

    /**
     * Sets a new destination
     *
     * Место назначения
     *
     * @param string $destination
     * @return static
     */
    public function setDestination($destination)
    {
        $this->destination = $destination;
        return $this;
    }

    /**
     * Gets as arrPlace
     *
     * Порт разгрузки/ аэропорт назначения
     *
     * @return string
     */
    public function getArrPlace()
    {
        return $this->arrPlace;
    }

    /**
     * Sets a new arrPlace
     *
     * Порт разгрузки/ аэропорт назначения
     *
     * @param string $arrPlace
     * @return static
     */
    public function setArrPlace($arrPlace)
    {
        $this->arrPlace = $arrPlace;
        return $this;
    }

    /**
     * Gets as notLater
     *
     * Не позднее
     *
     * @return \DateTime
     */
    public function getNotLater()
    {
        return $this->notLater;
    }

    /**
     * Sets a new notLater
     *
     * Не позднее
     *
     * @param \DateTime $notLater
     * @return static
     */
    public function setNotLater(\DateTime $notLater)
    {
        $this->notLater = $notLater;
        return $this;
    }

    /**
     * Gets as shipmentPeriod
     *
     * Период отгрузки
     *
     * @return string
     */
    public function getShipmentPeriod()
    {
        return $this->shipmentPeriod;
    }

    /**
     * Sets a new shipmentPeriod
     *
     * Период отгрузки
     *
     * @param string $shipmentPeriod
     * @return static
     */
    public function setShipmentPeriod($shipmentPeriod)
    {
        $this->shipmentPeriod = $shipmentPeriod;
        return $this;
    }


}


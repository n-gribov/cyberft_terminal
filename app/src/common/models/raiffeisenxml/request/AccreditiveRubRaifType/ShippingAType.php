<?php

namespace common\models\raiffeisenxml\request\AccreditiveRubRaifType;

/**
 * Class representing ShippingAType
 */
class ShippingAType
{

    /**
     * Номер договора
     *
     * @property string $contractNum
     */
    private $contractNum = null;

    /**
     * Дата договора
     *
     * @property \DateTime $contractDate
     */
    private $contractDate = null;

    /**
     * НДС
     *
     * @property float $nds
     */
    private $nds = null;

    /**
     * Срок отгрузки
     *
     * @property string $shippingDate
     */
    private $shippingDate = null;

    /**
     * Грузополучатель
     *
     * @property string $consignee
     */
    private $consignee = null;

    /**
     * Место назначения
     *
     * @property string $destination
     */
    private $destination = null;

    /**
     * Gets as contractNum
     *
     * Номер договора
     *
     * @return string
     */
    public function getContractNum()
    {
        return $this->contractNum;
    }

    /**
     * Sets a new contractNum
     *
     * Номер договора
     *
     * @param string $contractNum
     * @return static
     */
    public function setContractNum($contractNum)
    {
        $this->contractNum = $contractNum;
        return $this;
    }

    /**
     * Gets as contractDate
     *
     * Дата договора
     *
     * @return \DateTime
     */
    public function getContractDate()
    {
        return $this->contractDate;
    }

    /**
     * Sets a new contractDate
     *
     * Дата договора
     *
     * @param \DateTime $contractDate
     * @return static
     */
    public function setContractDate(\DateTime $contractDate)
    {
        $this->contractDate = $contractDate;
        return $this;
    }

    /**
     * Gets as nds
     *
     * НДС
     *
     * @return float
     */
    public function getNds()
    {
        return $this->nds;
    }

    /**
     * Sets a new nds
     *
     * НДС
     *
     * @param float $nds
     * @return static
     */
    public function setNds($nds)
    {
        $this->nds = $nds;
        return $this;
    }

    /**
     * Gets as shippingDate
     *
     * Срок отгрузки
     *
     * @return string
     */
    public function getShippingDate()
    {
        return $this->shippingDate;
    }

    /**
     * Sets a new shippingDate
     *
     * Срок отгрузки
     *
     * @param string $shippingDate
     * @return static
     */
    public function setShippingDate($shippingDate)
    {
        $this->shippingDate = $shippingDate;
        return $this;
    }

    /**
     * Gets as consignee
     *
     * Грузополучатель
     *
     * @return string
     */
    public function getConsignee()
    {
        return $this->consignee;
    }

    /**
     * Sets a new consignee
     *
     * Грузополучатель
     *
     * @param string $consignee
     * @return static
     */
    public function setConsignee($consignee)
    {
        $this->consignee = $consignee;
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


}


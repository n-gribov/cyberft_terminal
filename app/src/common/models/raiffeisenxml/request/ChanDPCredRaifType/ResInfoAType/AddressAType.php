<?php

namespace common\models\raiffeisenxml\request\ChanDPCredRaifType\ResInfoAType;

/**
 * Class representing AddressAType
 */
class AddressAType
{

    /**
     * Субъект РФ
     *
     * @property string $state
     */
    private $state = null;

    /**
     * Район
     *
     * @property string $district
     */
    private $district = null;

    /**
     * Город
     *
     * @property string $city
     */
    private $city = null;

    /**
     * Населенный пункт
     *
     * @property string $place
     */
    private $place = null;

    /**
     * Улица
     *
     * @property string $street
     */
    private $street = null;

    /**
     * Номер дома
     *
     * @property string $building
     */
    private $building = null;

    /**
     * Корпус/строение
     *
     * @property string $block
     */
    private $block = null;

    /**
     * Офис/квартира
     *
     * @property string $office
     */
    private $office = null;

    /**
     * Gets as state
     *
     * Субъект РФ
     *
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Sets a new state
     *
     * Субъект РФ
     *
     * @param string $state
     * @return static
     */
    public function setState($state)
    {
        $this->state = $state;
        return $this;
    }

    /**
     * Gets as district
     *
     * Район
     *
     * @return string
     */
    public function getDistrict()
    {
        return $this->district;
    }

    /**
     * Sets a new district
     *
     * Район
     *
     * @param string $district
     * @return static
     */
    public function setDistrict($district)
    {
        $this->district = $district;
        return $this;
    }

    /**
     * Gets as city
     *
     * Город
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Sets a new city
     *
     * Город
     *
     * @param string $city
     * @return static
     */
    public function setCity($city)
    {
        $this->city = $city;
        return $this;
    }

    /**
     * Gets as place
     *
     * Населенный пункт
     *
     * @return string
     */
    public function getPlace()
    {
        return $this->place;
    }

    /**
     * Sets a new place
     *
     * Населенный пункт
     *
     * @param string $place
     * @return static
     */
    public function setPlace($place)
    {
        $this->place = $place;
        return $this;
    }

    /**
     * Gets as street
     *
     * Улица
     *
     * @return string
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * Sets a new street
     *
     * Улица
     *
     * @param string $street
     * @return static
     */
    public function setStreet($street)
    {
        $this->street = $street;
        return $this;
    }

    /**
     * Gets as building
     *
     * Номер дома
     *
     * @return string
     */
    public function getBuilding()
    {
        return $this->building;
    }

    /**
     * Sets a new building
     *
     * Номер дома
     *
     * @param string $building
     * @return static
     */
    public function setBuilding($building)
    {
        $this->building = $building;
        return $this;
    }

    /**
     * Gets as block
     *
     * Корпус/строение
     *
     * @return string
     */
    public function getBlock()
    {
        return $this->block;
    }

    /**
     * Sets a new block
     *
     * Корпус/строение
     *
     * @param string $block
     * @return static
     */
    public function setBlock($block)
    {
        $this->block = $block;
        return $this;
    }

    /**
     * Gets as office
     *
     * Офис/квартира
     *
     * @return string
     */
    public function getOffice()
    {
        return $this->office;
    }

    /**
     * Sets a new office
     *
     * Офис/квартира
     *
     * @param string $office
     * @return static
     */
    public function setOffice($office)
    {
        $this->office = $office;
        return $this;
    }


}


<?php

namespace common\models\raiffeisenxml\request;

/**
 * Class representing NCodeType
 *
 *
 * XSD Type: NCode
 */
class NCodeType
{

    /**
     * 2-х символьный клиринговый код страны.
     *
     * @property string $country
     */
    private $country = null;

    /**
     * Клиринговый код (значение)
     *
     * @property string $digital
     */
    private $digital = null;

    /**
     * 2-х символьный клиринговый код
     *
     * @property string $iso2
     */
    private $iso2 = null;

    /**
     * Сокращение национального клирингового кода
     *
     * @property string $shortName
     */
    private $shortName = null;

    /**
     * Gets as country
     *
     * 2-х символьный клиринговый код страны.
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Sets a new country
     *
     * 2-х символьный клиринговый код страны.
     *
     * @param string $country
     * @return static
     */
    public function setCountry($country)
    {
        $this->country = $country;
        return $this;
    }

    /**
     * Gets as digital
     *
     * Клиринговый код (значение)
     *
     * @return string
     */
    public function getDigital()
    {
        return $this->digital;
    }

    /**
     * Sets a new digital
     *
     * Клиринговый код (значение)
     *
     * @param string $digital
     * @return static
     */
    public function setDigital($digital)
    {
        $this->digital = $digital;
        return $this;
    }

    /**
     * Gets as iso2
     *
     * 2-х символьный клиринговый код
     *
     * @return string
     */
    public function getIso2()
    {
        return $this->iso2;
    }

    /**
     * Sets a new iso2
     *
     * 2-х символьный клиринговый код
     *
     * @param string $iso2
     * @return static
     */
    public function setIso2($iso2)
    {
        $this->iso2 = $iso2;
        return $this;
    }

    /**
     * Gets as shortName
     *
     * Сокращение национального клирингового кода
     *
     * @return string
     */
    public function getShortName()
    {
        return $this->shortName;
    }

    /**
     * Sets a new shortName
     *
     * Сокращение национального клирингового кода
     *
     * @param string $shortName
     * @return static
     */
    public function setShortName($shortName)
    {
        $this->shortName = $shortName;
        return $this;
    }


}


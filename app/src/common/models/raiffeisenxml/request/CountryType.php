<?php

namespace common\models\raiffeisenxml\request;

/**
 * Class representing CountryType
 *
 *
 * XSD Type: Country
 */
class CountryType
{

    /**
     * Наименование страны
     *
     * @property string $name
     */
    private $name = null;

    /**
     * Цифровой код страны, например 840
     *
     * @property string $digital
     */
    private $digital = null;

    /**
     * 2х символьный буквенный ISO-код, например, US
     *
     * @property string $iso2
     */
    private $iso2 = null;

    /**
     * Gets as name
     *
     * Наименование страны
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
     * Наименование страны
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
     * Gets as digital
     *
     * Цифровой код страны, например 840
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
     * Цифровой код страны, например 840
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
     * 2х символьный буквенный ISO-код, например, US
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
     * 2х символьный буквенный ISO-код, например, US
     *
     * @param string $iso2
     * @return static
     */
    public function setIso2($iso2)
    {
        $this->iso2 = $iso2;
        return $this;
    }


}


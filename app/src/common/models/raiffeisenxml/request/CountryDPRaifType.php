<?php

namespace common\models\raiffeisenxml\request;

/**
 * Class representing CountryDPRaifType
 *
 *
 * XSD Type: CountryDPRaif
 */
class CountryDPRaifType
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


}


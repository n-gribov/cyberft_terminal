<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing CountryType
 *
 *
 * XSD Type: Country
 */
class CountryType
{

    /**
     * Цифровой код страны, например 840
     *
     * @property string $digital
     */
    private $digital = null;

    /**
     * Двухбуквенный код страны, например, US
     *
     * @property string $iso2
     */
    private $iso2 = null;

    /**
     * Наименование страны на русском языке (краткое наименование)
     *
     * @property string $name
     */
    private $name = null;

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
     * Двухбуквенный код страны, например, US
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
     * Двухбуквенный код страны, например, US
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
     * Gets as name
     *
     * Наименование страны на русском языке (краткое наименование)
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
     * Наименование страны на русском языке (краткое наименование)
     *
     * @param string $name
     * @return static
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }


}


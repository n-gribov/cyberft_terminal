<?php

namespace common\models\sbbolxml\request;

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
     * 2-символьный ISO-код (например, US, или две цифры для особенных случаев)
     *
     * @property string $iso2
     */
    private $iso2 = null;

    /**
     * наименование страны на русском языке (краткое наименование)
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
     * 2-символьный ISO-код (например, US, или две цифры для особенных случаев)
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
     * 2-символьный ISO-код (например, US, или две цифры для особенных случаев)
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
     * наименование страны на русском языке (краткое наименование)
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
     * наименование страны на русском языке (краткое наименование)
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


<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing CliringCodeType
 *
 *
 * XSD Type: CliringCode
 */
class CliringCodeType
{

    /**
     * 2-х символьный код страны
     *
     * @property string $countryCode
     */
    private $countryCode = null;

    /**
     * сокращение
     *
     * @property string $name
     */
    private $name = null;

    /**
     * обозначение
     *
     * @property string $note
     */
    private $note = null;

    /**
     * клиринговый код
     *  Клиринговый код банка бенефициара
     *  National Code
     *  например, FW123456789 или FW
     *
     * @property string $code
     */
    private $code = null;

    /**
     * Gets as countryCode
     *
     * 2-х символьный код страны
     *
     * @return string
     */
    public function getCountryCode()
    {
        return $this->countryCode;
    }

    /**
     * Sets a new countryCode
     *
     * 2-х символьный код страны
     *
     * @param string $countryCode
     * @return static
     */
    public function setCountryCode($countryCode)
    {
        $this->countryCode = $countryCode;
        return $this;
    }

    /**
     * Gets as name
     *
     * сокращение
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
     * сокращение
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
     * Gets as note
     *
     * обозначение
     *
     * @return string
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * Sets a new note
     *
     * обозначение
     *
     * @param string $note
     * @return static
     */
    public function setNote($note)
    {
        $this->note = $note;
        return $this;
    }

    /**
     * Gets as code
     *
     * клиринговый код
     *  Клиринговый код банка бенефициара
     *  National Code
     *  например, FW123456789 или FW
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Sets a new code
     *
     * клиринговый код
     *  Клиринговый код банка бенефициара
     *  National Code
     *  например, FW123456789 или FW
     *
     * @param string $code
     * @return static
     */
    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }


}


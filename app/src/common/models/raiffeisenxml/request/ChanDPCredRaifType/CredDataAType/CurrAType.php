<?php

namespace common\models\raiffeisenxml\request\ChanDPCredRaifType\CredDataAType;

/**
 * Class representing CurrAType
 */
class CurrAType
{

    /**
     * Цифровой код валюты
     *
     * @property string $digCurr
     */
    private $digCurr = null;

    /**
     * Наименование валюты
     *
     * @property string $name
     */
    private $name = null;

    /**
     * Gets as digCurr
     *
     * Цифровой код валюты
     *
     * @return string
     */
    public function getDigCurr()
    {
        return $this->digCurr;
    }

    /**
     * Sets a new digCurr
     *
     * Цифровой код валюты
     *
     * @param string $digCurr
     * @return static
     */
    public function setDigCurr($digCurr)
    {
        $this->digCurr = $digCurr;
        return $this;
    }

    /**
     * Gets as name
     *
     * Наименование валюты
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
     * Наименование валюты
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


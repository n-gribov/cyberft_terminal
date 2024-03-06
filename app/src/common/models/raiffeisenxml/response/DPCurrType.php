<?php

namespace common\models\raiffeisenxml\response;

/**
 * Class representing DPCurrType
 *
 * Валюта цены контракта паспорта сделки
 * XSD Type: DPCurr
 */
class DPCurrType
{

    /**
     * Цифр. код валюты цены контракта
     *
     * @property string $code
     */
    private $code = null;

    /**
     * Наименование валюты цены контракта
     *  (до 64)
     *
     * @property string $name
     */
    private $name = null;

    /**
     * Gets as code
     *
     * Цифр. код валюты цены контракта
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
     * Цифр. код валюты цены контракта
     *
     * @param string $code
     * @return static
     */
    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    /**
     * Gets as name
     *
     * Наименование валюты цены контракта
     *  (до 64)
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
     * Наименование валюты цены контракта
     *  (до 64)
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


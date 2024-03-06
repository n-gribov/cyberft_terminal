<?php

namespace common\models\raiffeisenxml\request\AccreditiveCurRaifType\TermsAType;

/**
 * Class representing DeliveryAType
 */
class DeliveryAType
{

    /**
     * Номер строки
     *
     * @property string $strNum
     */
    private $strNum = null;

    /**
     * Значение
     *
     * @property string $value
     */
    private $value = null;

    /**
     * Пункт / место / терминал / порт
     *
     * @property string $place
     */
    private $place = null;

    /**
     * Gets as strNum
     *
     * Номер строки
     *
     * @return string
     */
    public function getStrNum()
    {
        return $this->strNum;
    }

    /**
     * Sets a new strNum
     *
     * Номер строки
     *
     * @param string $strNum
     * @return static
     */
    public function setStrNum($strNum)
    {
        $this->strNum = $strNum;
        return $this;
    }

    /**
     * Gets as value
     *
     * Значение
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Sets a new value
     *
     * Значение
     *
     * @param string $value
     * @return static
     */
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }

    /**
     * Gets as place
     *
     * Пункт / место / терминал / порт
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
     * Пункт / место / терминал / порт
     *
     * @param string $place
     * @return static
     */
    public function setPlace($place)
    {
        $this->place = $place;
        return $this;
    }


}


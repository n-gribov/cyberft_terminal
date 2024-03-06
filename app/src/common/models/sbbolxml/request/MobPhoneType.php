<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing MobPhoneType
 *
 *
 * XSD Type: MobPhone
 */
class MobPhoneType
{

    /**
     * @property string $number
     */
    private $number = null;

    /**
     * Оператор связи(МТС, МЕГАФОН, БИЛАЙН, Другой)
     *
     * @property string $operType
     */
    private $operType = null;

    /**
     * Название оператора связи. Заполняется, если в поле OperType было выбрано
     *  "Другой"
     *
     * @property string $operName
     */
    private $operName = null;

    /**
     * Gets as number
     *
     * @return string
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Sets a new number
     *
     * @param string $number
     * @return static
     */
    public function setNumber($number)
    {
        $this->number = $number;
        return $this;
    }

    /**
     * Gets as operType
     *
     * Оператор связи(МТС, МЕГАФОН, БИЛАЙН, Другой)
     *
     * @return string
     */
    public function getOperType()
    {
        return $this->operType;
    }

    /**
     * Sets a new operType
     *
     * Оператор связи(МТС, МЕГАФОН, БИЛАЙН, Другой)
     *
     * @param string $operType
     * @return static
     */
    public function setOperType($operType)
    {
        $this->operType = $operType;
        return $this;
    }

    /**
     * Gets as operName
     *
     * Название оператора связи. Заполняется, если в поле OperType было выбрано
     *  "Другой"
     *
     * @return string
     */
    public function getOperName()
    {
        return $this->operName;
    }

    /**
     * Sets a new operName
     *
     * Название оператора связи. Заполняется, если в поле OperType было выбрано
     *  "Другой"
     *
     * @param string $operName
     * @return static
     */
    public function setOperName($operName)
    {
        $this->operName = $operName;
        return $this;
    }


}


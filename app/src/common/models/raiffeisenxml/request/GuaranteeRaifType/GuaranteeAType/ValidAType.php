<?php

namespace common\models\raiffeisenxml\request\GuaranteeRaifType\GuaranteeAType;

/**
 * Class representing ValidAType
 */
class ValidAType
{

    /**
     * с
     *
     * @property \DateTime $from
     */
    private $from = null;

    /**
     * по
     *
     * @property \DateTime $to
     */
    private $to = null;

    /**
     * Возможные значения: "Дата", "Событие начала действия гарантии".
     *
     * @property string $type
     */
    private $type = null;

    /**
     * Список возможных значений: "Иное", "С даты выдачи", "С даты зачисления авансового платежа".
     *
     * @property string $value
     */
    private $value = null;

    /**
     * Описание для "Иное"
     *
     * @property string $other
     */
    private $other = null;

    /**
     * Gets as from
     *
     * с
     *
     * @return \DateTime
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * Sets a new from
     *
     * с
     *
     * @param \DateTime $from
     * @return static
     */
    public function setFrom(\DateTime $from)
    {
        $this->from = $from;
        return $this;
    }

    /**
     * Gets as to
     *
     * по
     *
     * @return \DateTime
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * Sets a new to
     *
     * по
     *
     * @param \DateTime $to
     * @return static
     */
    public function setTo(\DateTime $to)
    {
        $this->to = $to;
        return $this;
    }

    /**
     * Gets as type
     *
     * Возможные значения: "Дата", "Событие начала действия гарантии".
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Sets a new type
     *
     * Возможные значения: "Дата", "Событие начала действия гарантии".
     *
     * @param string $type
     * @return static
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * Gets as value
     *
     * Список возможных значений: "Иное", "С даты выдачи", "С даты зачисления авансового платежа".
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
     * Список возможных значений: "Иное", "С даты выдачи", "С даты зачисления авансового платежа".
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
     * Gets as other
     *
     * Описание для "Иное"
     *
     * @return string
     */
    public function getOther()
    {
        return $this->other;
    }

    /**
     * Sets a new other
     *
     * Описание для "Иное"
     *
     * @param string $other
     * @return static
     */
    public function setOther($other)
    {
        $this->other = $other;
        return $this;
    }


}


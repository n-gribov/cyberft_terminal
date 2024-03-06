<?php

namespace common\models\raiffeisenxml\request;

/**
 * Class representing AccountNoNameType
 *
 * Номер счёта в виде значения элемента и БИК в значении параметра
 * XSD Type: AccountNoName
 */
class AccountNoNameType
{

    /**
     * @property string $__value
     */
    private $__value = null;

    /**
     * БИК банка
     *
     * @property string $bic
     */
    private $bic = null;

    /**
     * Наименование банка
     *
     * @property string $name
     */
    private $name = null;

    /**
     * Construct
     *
     * @param string $value
     */
    public function __construct($value)
    {
        $this->value($value);
    }

    /**
     * Gets or sets the inner value
     *
     * @param string $value
     * @return string
     */
    public function value()
    {
        if ($args = func_get_args()) {
            $this->__value = $args[0];
        }
        return $this->__value;
    }

    /**
     * Gets a string value
     *
     * @return string
     */
    public function __toString()
    {
        return strval($this->__value);
    }

    /**
     * Gets as bic
     *
     * БИК банка
     *
     * @return string
     */
    public function getBic()
    {
        return $this->bic;
    }

    /**
     * Sets a new bic
     *
     * БИК банка
     *
     * @param string $bic
     * @return static
     */
    public function setBic($bic)
    {
        $this->bic = $bic;
        return $this;
    }

    /**
     * Gets as name
     *
     * Наименование банка
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
     * Наименование банка
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


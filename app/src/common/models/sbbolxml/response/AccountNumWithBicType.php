<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing AccountNumWithBicType
 *
 * Номер счёта в виде значения элемента и БИК в значении параметра
 * XSD Type: AccountNumWithBic
 */
class AccountNumWithBicType
{

    /**
     * @property string $__value
     */
    private $__value = null;

    /**
     * @property string $bic
     */
    private $bic = null;

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
     * @return string
     */
    public function getBic()
    {
        return $this->bic;
    }

    /**
     * Sets a new bic
     *
     * @param string $bic
     * @return static
     */
    public function setBic($bic)
    {
        $this->bic = $bic;
        return $this;
    }


}


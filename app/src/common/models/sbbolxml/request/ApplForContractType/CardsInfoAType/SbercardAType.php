<?php

namespace common\models\sbbolxml\request\ApplForContractType\CardsInfoAType;

/**
 * Class representing SbercardAType
 */
class SbercardAType
{

    /**
     * @property integer $__value
     */
    private $__value = null;

    /**
     * 1 - признак установлен, 0 - признак не устанволен
     *
     * @property boolean $marked
     */
    private $marked = null;

    /**
     * Construct
     *
     * @param integer $value
     */
    public function __construct($value)
    {
        $this->value($value);
    }

    /**
     * Gets or sets the inner value
     *
     * @param integer $value
     * @return integer
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
     * Gets as marked
     *
     * 1 - признак установлен, 0 - признак не устанволен
     *
     * @return boolean
     */
    public function getMarked()
    {
        return $this->marked;
    }

    /**
     * Sets a new marked
     *
     * 1 - признак установлен, 0 - признак не устанволен
     *
     * @param boolean $marked
     * @return static
     */
    public function setMarked($marked)
    {
        $this->marked = $marked;
        return $this;
    }


}


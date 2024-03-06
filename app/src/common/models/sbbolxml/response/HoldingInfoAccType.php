<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing HoldingInfoAccType
 *
 * Номер счёта c БИК и сообщением о статусе
 * XSD Type: HoldingInfoAcc
 */
class HoldingInfoAccType
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
     * @property string $status
     */
    private $status = null;

    /**
     * @property string $message
     */
    private $message = null;

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

    /**
     * Gets as status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Sets a new status
     *
     * @param string $status
     * @return static
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * Gets as message
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Sets a new message
     *
     * @param string $message
     * @return static
     */
    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }


}


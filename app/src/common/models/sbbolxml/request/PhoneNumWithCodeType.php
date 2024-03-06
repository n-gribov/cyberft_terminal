<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing PhoneNumWithCodeType
 *
 *
 * XSD Type: PhoneNumWithCode
 */
class PhoneNumWithCodeType
{

    /**
     * Код оператора - 3 симв. (МТС, МЕГАФОН, БИЛАЙН, Другой)
     *
     * @property string $operatorCode
     */
    private $operatorCode = null;

    /**
     * @property string $number
     */
    private $number = null;

    /**
     * Gets as operatorCode
     *
     * Код оператора - 3 симв. (МТС, МЕГАФОН, БИЛАЙН, Другой)
     *
     * @return string
     */
    public function getOperatorCode()
    {
        return $this->operatorCode;
    }

    /**
     * Sets a new operatorCode
     *
     * Код оператора - 3 симв. (МТС, МЕГАФОН, БИЛАЙН, Другой)
     *
     * @param string $operatorCode
     * @return static
     */
    public function setOperatorCode($operatorCode)
    {
        $this->operatorCode = $operatorCode;
        return $this;
    }

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


}


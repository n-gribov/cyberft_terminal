<?php

namespace common\models\sbbolxml\response\DealPassUpdateResultType\PhoneNumbersAType\PhoneNumberAType;

/**
 * Class representing OfficerAType
 */
class OfficerAType
{

    /**
     * Номер телефона
     *
     * @property string $number
     */
    private $number = null;

    /**
     * Gets as number
     *
     * Номер телефона
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
     * Номер телефона
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


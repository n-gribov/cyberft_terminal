<?php

namespace common\models\sbbolxml\response\DealPassUpdateResultType;

/**
 * Class representing PhoneNumbersAType
 */
class PhoneNumbersAType
{

    /**
     * Один телефон горячей линии
     *
     * @property \common\models\sbbolxml\response\DealPassUpdateResultType\PhoneNumbersAType\PhoneNumberAType $phoneNumber
     */
    private $phoneNumber = null;

    /**
     * Gets as phoneNumber
     *
     * Один телефон горячей линии
     *
     * @return \common\models\sbbolxml\response\DealPassUpdateResultType\PhoneNumbersAType\PhoneNumberAType
     */
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    /**
     * Sets a new phoneNumber
     *
     * Один телефон горячей линии
     *
     * @param \common\models\sbbolxml\response\DealPassUpdateResultType\PhoneNumbersAType\PhoneNumberAType $phoneNumber
     * @return static
     */
    public function setPhoneNumber(\common\models\sbbolxml\response\DealPassUpdateResultType\PhoneNumbersAType\PhoneNumberAType $phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;
        return $this;
    }


}


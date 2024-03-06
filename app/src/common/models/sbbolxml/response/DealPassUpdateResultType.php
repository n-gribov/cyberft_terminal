<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing DealPassUpdateResultType
 *
 * Информация по результату сделки
 * XSD Type: DealPassUpdateResult
 */
class DealPassUpdateResultType
{

    /**
     * Номера телефонов ответственных исполнителей и горячей линии для форм отказа
     *
     * @property \common\models\sbbolxml\response\DealPassUpdateResultType\PhoneNumbersAType $phoneNumbers
     */
    private $phoneNumbers = null;

    /**
     * Gets as phoneNumbers
     *
     * Номера телефонов ответственных исполнителей и горячей линии для форм отказа
     *
     * @return \common\models\sbbolxml\response\DealPassUpdateResultType\PhoneNumbersAType
     */
    public function getPhoneNumbers()
    {
        return $this->phoneNumbers;
    }

    /**
     * Sets a new phoneNumbers
     *
     * Номера телефонов ответственных исполнителей и горячей линии для форм отказа
     *
     * @param \common\models\sbbolxml\response\DealPassUpdateResultType\PhoneNumbersAType $phoneNumbers
     * @return static
     */
    public function setPhoneNumbers(\common\models\sbbolxml\response\DealPassUpdateResultType\PhoneNumbersAType $phoneNumbers)
    {
        $this->phoneNumbers = $phoneNumbers;
        return $this;
    }


}


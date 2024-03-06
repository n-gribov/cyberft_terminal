<?php

namespace common\models\sbbolxml\response\DealPassUpdateResultType\PhoneNumbersAType;

/**
 * Class representing PhoneNumberAType
 */
class PhoneNumberAType
{

    /**
     * Телефон горячей линии
     *
     * @property \common\models\sbbolxml\response\DealPassUpdateResultType\PhoneNumbersAType\PhoneNumberAType\HotlineAType[] $hotline
     */
    private $hotline = array(
        
    );

    /**
     * Телефон ответственного исполнителя
     *
     * @property \common\models\sbbolxml\response\DealPassUpdateResultType\PhoneNumbersAType\PhoneNumberAType\OfficerAType[] $officer
     */
    private $officer = array(
        
    );

    /**
     * Adds as hotline
     *
     * Телефон горячей линии
     *
     * @return static
     * @param \common\models\sbbolxml\response\DealPassUpdateResultType\PhoneNumbersAType\PhoneNumberAType\HotlineAType $hotline
     */
    public function addToHotline(\common\models\sbbolxml\response\DealPassUpdateResultType\PhoneNumbersAType\PhoneNumberAType\HotlineAType $hotline)
    {
        $this->hotline[] = $hotline;
        return $this;
    }

    /**
     * isset hotline
     *
     * Телефон горячей линии
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetHotline($index)
    {
        return isset($this->hotline[$index]);
    }

    /**
     * unset hotline
     *
     * Телефон горячей линии
     *
     * @param scalar $index
     * @return void
     */
    public function unsetHotline($index)
    {
        unset($this->hotline[$index]);
    }

    /**
     * Gets as hotline
     *
     * Телефон горячей линии
     *
     * @return \common\models\sbbolxml\response\DealPassUpdateResultType\PhoneNumbersAType\PhoneNumberAType\HotlineAType[]
     */
    public function getHotline()
    {
        return $this->hotline;
    }

    /**
     * Sets a new hotline
     *
     * Телефон горячей линии
     *
     * @param \common\models\sbbolxml\response\DealPassUpdateResultType\PhoneNumbersAType\PhoneNumberAType\HotlineAType[] $hotline
     * @return static
     */
    public function setHotline(array $hotline)
    {
        $this->hotline = $hotline;
        return $this;
    }

    /**
     * Adds as officer
     *
     * Телефон ответственного исполнителя
     *
     * @return static
     * @param \common\models\sbbolxml\response\DealPassUpdateResultType\PhoneNumbersAType\PhoneNumberAType\OfficerAType $officer
     */
    public function addToOfficer(\common\models\sbbolxml\response\DealPassUpdateResultType\PhoneNumbersAType\PhoneNumberAType\OfficerAType $officer)
    {
        $this->officer[] = $officer;
        return $this;
    }

    /**
     * isset officer
     *
     * Телефон ответственного исполнителя
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetOfficer($index)
    {
        return isset($this->officer[$index]);
    }

    /**
     * unset officer
     *
     * Телефон ответственного исполнителя
     *
     * @param scalar $index
     * @return void
     */
    public function unsetOfficer($index)
    {
        unset($this->officer[$index]);
    }

    /**
     * Gets as officer
     *
     * Телефон ответственного исполнителя
     *
     * @return \common\models\sbbolxml\response\DealPassUpdateResultType\PhoneNumbersAType\PhoneNumberAType\OfficerAType[]
     */
    public function getOfficer()
    {
        return $this->officer;
    }

    /**
     * Sets a new officer
     *
     * Телефон ответственного исполнителя
     *
     * @param \common\models\sbbolxml\response\DealPassUpdateResultType\PhoneNumbersAType\PhoneNumberAType\OfficerAType[] $officer
     * @return static
     */
    public function setOfficer(array $officer)
    {
        $this->officer = $officer;
        return $this;
    }


}


<?php

namespace common\models\sbbolxml\response\ResponseType;

/**
 * Class representing GenericLettersFromBankAType
 */
class GenericLettersFromBankAType
{

    /**
     * Письмо из банка
     *
     * @property \common\models\sbbolxml\response\GenericLetterFromBankType[] $genericLetterFromBank
     */
    private $genericLetterFromBank = array(
        
    );

    /**
     * Adds as genericLetterFromBank
     *
     * Письмо из банка
     *
     * @return static
     * @param \common\models\sbbolxml\response\GenericLetterFromBankType $genericLetterFromBank
     */
    public function addToGenericLetterFromBank(\common\models\sbbolxml\response\GenericLetterFromBankType $genericLetterFromBank)
    {
        $this->genericLetterFromBank[] = $genericLetterFromBank;
        return $this;
    }

    /**
     * isset genericLetterFromBank
     *
     * Письмо из банка
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetGenericLetterFromBank($index)
    {
        return isset($this->genericLetterFromBank[$index]);
    }

    /**
     * unset genericLetterFromBank
     *
     * Письмо из банка
     *
     * @param scalar $index
     * @return void
     */
    public function unsetGenericLetterFromBank($index)
    {
        unset($this->genericLetterFromBank[$index]);
    }

    /**
     * Gets as genericLetterFromBank
     *
     * Письмо из банка
     *
     * @return \common\models\sbbolxml\response\GenericLetterFromBankType[]
     */
    public function getGenericLetterFromBank()
    {
        return $this->genericLetterFromBank;
    }

    /**
     * Sets a new genericLetterFromBank
     *
     * Письмо из банка
     *
     * @param \common\models\sbbolxml\response\GenericLetterFromBankType[] $genericLetterFromBank
     * @return static
     */
    public function setGenericLetterFromBank(array $genericLetterFromBank)
    {
        $this->genericLetterFromBank = $genericLetterFromBank;
        return $this;
    }


}


<?php

namespace common\models\sbbolxml\response\ResponseType;

/**
 * Class representing LettersFromBankAType
 */
class LettersFromBankAType
{

    /**
     * Письмо из банка
     *
     * @property \common\models\sbbolxml\response\LetterFromBankType[] $letterFromBank
     */
    private $letterFromBank = array(
        
    );

    /**
     * Adds as letterFromBank
     *
     * Письмо из банка
     *
     * @return static
     * @param \common\models\sbbolxml\response\LetterFromBankType $letterFromBank
     */
    public function addToLetterFromBank(\common\models\sbbolxml\response\LetterFromBankType $letterFromBank)
    {
        $this->letterFromBank[] = $letterFromBank;
        return $this;
    }

    /**
     * isset letterFromBank
     *
     * Письмо из банка
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetLetterFromBank($index)
    {
        return isset($this->letterFromBank[$index]);
    }

    /**
     * unset letterFromBank
     *
     * Письмо из банка
     *
     * @param scalar $index
     * @return void
     */
    public function unsetLetterFromBank($index)
    {
        unset($this->letterFromBank[$index]);
    }

    /**
     * Gets as letterFromBank
     *
     * Письмо из банка
     *
     * @return \common\models\sbbolxml\response\LetterFromBankType[]
     */
    public function getLetterFromBank()
    {
        return $this->letterFromBank;
    }

    /**
     * Sets a new letterFromBank
     *
     * Письмо из банка
     *
     * @param \common\models\sbbolxml\response\LetterFromBankType[] $letterFromBank
     * @return static
     */
    public function setLetterFromBank(array $letterFromBank)
    {
        $this->letterFromBank = $letterFromBank;
        return $this;
    }


}


<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing ComDocDataFromBankType
 *
 *
 * XSD Type: ComDocDataFromBank
 */
class ComDocDataFromBankType extends ComDocDataType
{

    /**
     * Обязательно для прочтения: 0-нет, 1-да
     *
     * @property boolean $requiredForRead
     */
    private $requiredForRead = null;

    /**
     * Gets as requiredForRead
     *
     * Обязательно для прочтения: 0-нет, 1-да
     *
     * @return boolean
     */
    public function getRequiredForRead()
    {
        return $this->requiredForRead;
    }

    /**
     * Sets a new requiredForRead
     *
     * Обязательно для прочтения: 0-нет, 1-да
     *
     * @param boolean $requiredForRead
     * @return static
     */
    public function setRequiredForRead($requiredForRead)
    {
        $this->requiredForRead = $requiredForRead;
        return $this;
    }


}


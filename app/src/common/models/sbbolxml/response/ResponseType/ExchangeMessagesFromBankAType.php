<?php

namespace common\models\sbbolxml\response\ResponseType;

/**
 * Class representing ExchangeMessagesFromBankAType
 */
class ExchangeMessagesFromBankAType
{

    /**
     * Письмо для целей ВК (из банка)
     *
     * @property \common\models\sbbolxml\response\ExchangeMessagesFromBankType[] $exchangeMessageFromBank
     */
    private $exchangeMessageFromBank = array(
        
    );

    /**
     * Adds as exchangeMessageFromBank
     *
     * Письмо для целей ВК (из банка)
     *
     * @return static
     * @param \common\models\sbbolxml\response\ExchangeMessagesFromBankType $exchangeMessageFromBank
     */
    public function addToExchangeMessageFromBank(\common\models\sbbolxml\response\ExchangeMessagesFromBankType $exchangeMessageFromBank)
    {
        $this->exchangeMessageFromBank[] = $exchangeMessageFromBank;
        return $this;
    }

    /**
     * isset exchangeMessageFromBank
     *
     * Письмо для целей ВК (из банка)
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetExchangeMessageFromBank($index)
    {
        return isset($this->exchangeMessageFromBank[$index]);
    }

    /**
     * unset exchangeMessageFromBank
     *
     * Письмо для целей ВК (из банка)
     *
     * @param scalar $index
     * @return void
     */
    public function unsetExchangeMessageFromBank($index)
    {
        unset($this->exchangeMessageFromBank[$index]);
    }

    /**
     * Gets as exchangeMessageFromBank
     *
     * Письмо для целей ВК (из банка)
     *
     * @return \common\models\sbbolxml\response\ExchangeMessagesFromBankType[]
     */
    public function getExchangeMessageFromBank()
    {
        return $this->exchangeMessageFromBank;
    }

    /**
     * Sets a new exchangeMessageFromBank
     *
     * Письмо для целей ВК (из банка)
     *
     * @param \common\models\sbbolxml\response\ExchangeMessagesFromBankType[] $exchangeMessageFromBank
     * @return static
     */
    public function setExchangeMessageFromBank(array $exchangeMessageFromBank)
    {
        $this->exchangeMessageFromBank = $exchangeMessageFromBank;
        return $this;
    }


}


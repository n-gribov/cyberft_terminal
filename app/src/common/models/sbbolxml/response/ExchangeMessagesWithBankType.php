<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing ExchangeMessagesWithBankType
 *
 * Письма для целей ВК в банк
 * XSD Type: ExchangeMessagesWithBank
 */
class ExchangeMessagesWithBankType
{

    /**
     * История сообщений
     *
     * @property string $messageHistory
     */
    private $messageHistory = null;

    /**
     * Gets as messageHistory
     *
     * История сообщений
     *
     * @return string
     */
    public function getMessageHistory()
    {
        return $this->messageHistory;
    }

    /**
     * Sets a new messageHistory
     *
     * История сообщений
     *
     * @param string $messageHistory
     * @return static
     */
    public function setMessageHistory($messageHistory)
    {
        $this->messageHistory = $messageHistory;
        return $this;
    }


}


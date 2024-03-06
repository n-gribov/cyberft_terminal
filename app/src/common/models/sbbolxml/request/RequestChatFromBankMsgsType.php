<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing RequestChatFromBankMsgsType
 *
 * Запрос сообщений чата с банком
 * XSD Type: RequestChatFromBankMsgs
 */
class RequestChatFromBankMsgsType
{

    /**
     * Дата и время последнего запроса сообщений чата с банком(с час. поясами)
     *
     * @property \DateTime $lastIncomingTime
     */
    private $lastIncomingTime = null;

    /**
     * Gets as lastIncomingTime
     *
     * Дата и время последнего запроса сообщений чата с банком(с час. поясами)
     *
     * @return \DateTime
     */
    public function getLastIncomingTime()
    {
        return $this->lastIncomingTime;
    }

    /**
     * Sets a new lastIncomingTime
     *
     * Дата и время последнего запроса сообщений чата с банком(с час. поясами)
     *
     * @param \DateTime $lastIncomingTime
     * @return static
     */
    public function setLastIncomingTime(\DateTime $lastIncomingTime)
    {
        $this->lastIncomingTime = $lastIncomingTime;
        return $this;
    }


}


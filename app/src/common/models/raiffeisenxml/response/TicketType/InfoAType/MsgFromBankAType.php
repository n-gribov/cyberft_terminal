<?php

namespace common\models\raiffeisenxml\response\TicketType\InfoAType;

/**
 * Class representing MsgFromBankAType
 */
class MsgFromBankAType
{

    /**
     * Автор сообщения
     *
     * @property string $author
     */
    private $author = null;

    /**
     * Сообщение из банка
     *
     * @property string $message
     */
    private $message = null;

    /**
     * Gets as author
     *
     * Автор сообщения
     *
     * @return string
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Sets a new author
     *
     * Автор сообщения
     *
     * @param string $author
     * @return static
     */
    public function setAuthor($author)
    {
        $this->author = $author;
        return $this;
    }

    /**
     * Gets as message
     *
     * Сообщение из банка
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Sets a new message
     *
     * Сообщение из банка
     *
     * @param string $message
     * @return static
     */
    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }


}


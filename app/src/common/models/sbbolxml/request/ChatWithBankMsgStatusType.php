<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing ChatWithBankMsgStatusType
 *
 * Получение статуса сообщения чата с банком (в Банк)
 * XSD Type: ChatWithBankMsgStatus
 */
class ChatWithBankMsgStatusType
{

    /**
     * Идентификатор сообщения чата в УС
     *
     * @property string $chatMsgExtId
     */
    private $chatMsgExtId = null;

    /**
     * Идентификатор документа, к которому относится сообщение чата, в СББОЛ
     *
     * @property string $docId
     */
    private $docId = null;

    /**
     * Статус сообщения чата с банком (в Банк)
     *
     * @property string $status
     */
    private $status = null;

    /**
     * Gets as chatMsgExtId
     *
     * Идентификатор сообщения чата в УС
     *
     * @return string
     */
    public function getChatMsgExtId()
    {
        return $this->chatMsgExtId;
    }

    /**
     * Sets a new chatMsgExtId
     *
     * Идентификатор сообщения чата в УС
     *
     * @param string $chatMsgExtId
     * @return static
     */
    public function setChatMsgExtId($chatMsgExtId)
    {
        $this->chatMsgExtId = $chatMsgExtId;
        return $this;
    }

    /**
     * Gets as docId
     *
     * Идентификатор документа, к которому относится сообщение чата, в СББОЛ
     *
     * @return string
     */
    public function getDocId()
    {
        return $this->docId;
    }

    /**
     * Sets a new docId
     *
     * Идентификатор документа, к которому относится сообщение чата, в СББОЛ
     *
     * @param string $docId
     * @return static
     */
    public function setDocId($docId)
    {
        $this->docId = $docId;
        return $this;
    }

    /**
     * Gets as status
     *
     * Статус сообщения чата с банком (в Банк)
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Sets a new status
     *
     * Статус сообщения чата с банком (в Банк)
     *
     * @param string $status
     * @return static
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }


}


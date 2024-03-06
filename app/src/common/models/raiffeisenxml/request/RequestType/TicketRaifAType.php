<?php

namespace common\models\raiffeisenxml\request\RequestType;

/**
 * Class representing TicketRaifAType
 */
class TicketRaifAType
{

    /**
     * Идентификатор документа в системе ДБО, по которому направляется квиток
     *
     * @property string $doctId
     */
    private $doctId = null;

    /**
     * Идентификатор документа во внешней УС Клиента, услу документ успешно загружен.
     *
     * @property string $extId
     */
    private $extId = null;

    /**
     * Состояние обработки документа.
     *
     * @property string $state
     */
    private $state = null;

    /**
     * Комментарии в случае ошибки загрузки/обработки документа из банка.
     *
     * @property string $errorComment
     */
    private $errorComment = null;

    /**
     * Информация о прочтении обязательного письма.
     *
     * @property \common\models\raiffeisenxml\request\RequestType\TicketRaifAType\ReadLetterAType $readLetter
     */
    private $readLetter = null;

    /**
     * Gets as doctId
     *
     * Идентификатор документа в системе ДБО, по которому направляется квиток
     *
     * @return string
     */
    public function getDoctId()
    {
        return $this->doctId;
    }

    /**
     * Sets a new doctId
     *
     * Идентификатор документа в системе ДБО, по которому направляется квиток
     *
     * @param string $doctId
     * @return static
     */
    public function setDoctId($doctId)
    {
        $this->doctId = $doctId;
        return $this;
    }

    /**
     * Gets as extId
     *
     * Идентификатор документа во внешней УС Клиента, услу документ успешно загружен.
     *
     * @return string
     */
    public function getExtId()
    {
        return $this->extId;
    }

    /**
     * Sets a new extId
     *
     * Идентификатор документа во внешней УС Клиента, услу документ успешно загружен.
     *
     * @param string $extId
     * @return static
     */
    public function setExtId($extId)
    {
        $this->extId = $extId;
        return $this;
    }

    /**
     * Gets as state
     *
     * Состояние обработки документа.
     *
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Sets a new state
     *
     * Состояние обработки документа.
     *
     * @param string $state
     * @return static
     */
    public function setState($state)
    {
        $this->state = $state;
        return $this;
    }

    /**
     * Gets as errorComment
     *
     * Комментарии в случае ошибки загрузки/обработки документа из банка.
     *
     * @return string
     */
    public function getErrorComment()
    {
        return $this->errorComment;
    }

    /**
     * Sets a new errorComment
     *
     * Комментарии в случае ошибки загрузки/обработки документа из банка.
     *
     * @param string $errorComment
     * @return static
     */
    public function setErrorComment($errorComment)
    {
        $this->errorComment = $errorComment;
        return $this;
    }

    /**
     * Gets as readLetter
     *
     * Информация о прочтении обязательного письма.
     *
     * @return \common\models\raiffeisenxml\request\RequestType\TicketRaifAType\ReadLetterAType
     */
    public function getReadLetter()
    {
        return $this->readLetter;
    }

    /**
     * Sets a new readLetter
     *
     * Информация о прочтении обязательного письма.
     *
     * @param \common\models\raiffeisenxml\request\RequestType\TicketRaifAType\ReadLetterAType $readLetter
     * @return static
     */
    public function setReadLetter(\common\models\raiffeisenxml\request\RequestType\TicketRaifAType\ReadLetterAType $readLetter)
    {
        $this->readLetter = $readLetter;
        return $this;
    }


}


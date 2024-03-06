<?php

namespace common\models\raiffeisenxml\request\RequestType;

/**
 * Class representing LetterTicketAType
 */
class LetterTicketAType
{

    /**
     * Идентификатор документа в системе ДБО, по которому направляется квиток
     *
     * @property string $doctId
     */
    private $doctId = null;

    /**
     * Информация о прочтении обязательного письма.
     *
     * @property \common\models\raiffeisenxml\request\RequestType\LetterTicketAType\ReadLetterAType $readLetter
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
     * Gets as readLetter
     *
     * Информация о прочтении обязательного письма.
     *
     * @return \common\models\raiffeisenxml\request\RequestType\LetterTicketAType\ReadLetterAType
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
     * @param \common\models\raiffeisenxml\request\RequestType\LetterTicketAType\ReadLetterAType $readLetter
     * @return static
     */
    public function setReadLetter(\common\models\raiffeisenxml\request\RequestType\LetterTicketAType\ReadLetterAType $readLetter)
    {
        $this->readLetter = $readLetter;
        return $this;
    }


}


<?php

namespace common\models\sbbolxml\request\TicketClType;

/**
 * Class representing InfoAType
 */
class InfoAType
{

    /**
     * Код состояния документа
     *
     * @property string $statusStateCode
     */
    private $statusStateCode = null;

    /**
     * Идентификатор документа в УС
     *
     * @property string $docExtId
     */
    private $docExtId = null;

    /**
     * Контролируемая дата, если не заполнено, то обрабатывать @createTime
     *
     * @property \DateTime $acceptDate
     */
    private $acceptDate = null;

    /**
     * Gets as statusStateCode
     *
     * Код состояния документа
     *
     * @return string
     */
    public function getStatusStateCode()
    {
        return $this->statusStateCode;
    }

    /**
     * Sets a new statusStateCode
     *
     * Код состояния документа
     *
     * @param string $statusStateCode
     * @return static
     */
    public function setStatusStateCode($statusStateCode)
    {
        $this->statusStateCode = $statusStateCode;
        return $this;
    }

    /**
     * Gets as docExtId
     *
     * Идентификатор документа в УС
     *
     * @return string
     */
    public function getDocExtId()
    {
        return $this->docExtId;
    }

    /**
     * Sets a new docExtId
     *
     * Идентификатор документа в УС
     *
     * @param string $docExtId
     * @return static
     */
    public function setDocExtId($docExtId)
    {
        $this->docExtId = $docExtId;
        return $this;
    }

    /**
     * Gets as acceptDate
     *
     * Контролируемая дата, если не заполнено, то обрабатывать @createTime
     *
     * @return \DateTime
     */
    public function getAcceptDate()
    {
        return $this->acceptDate;
    }

    /**
     * Sets a new acceptDate
     *
     * Контролируемая дата, если не заполнено, то обрабатывать @createTime
     *
     * @param \DateTime $acceptDate
     * @return static
     */
    public function setAcceptDate(\DateTime $acceptDate)
    {
        $this->acceptDate = $acceptDate;
        return $this;
    }


}


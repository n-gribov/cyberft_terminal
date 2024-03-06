<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing IncomingDealConfType
 *
 * Запрос сообщений о подтверждении сделок
 * XSD Type: IncomingDealConf
 */
class IncomingDealConfType
{

    /**
     * Дата и время последнего запроса (с час. поясами)
     *
     * @property \DateTime $lastIncomingTime
     */
    private $lastIncomingTime = null;

    /**
     * Gets as lastIncomingTime
     *
     * Дата и время последнего запроса (с час. поясами)
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
     * Дата и время последнего запроса (с час. поясами)
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


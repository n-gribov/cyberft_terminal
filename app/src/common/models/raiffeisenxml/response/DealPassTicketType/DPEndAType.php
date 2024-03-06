<?php

namespace common\models\raiffeisenxml\response\DealPassTicketType;

/**
 * Class representing DPEndAType
 */
class DPEndAType
{

    /**
     * Дата закрытия ПС
     *
     * @property \DateTime $date
     */
    private $date = null;

    /**
     * Основание закрытия ПС
     *
     * @property string $reason
     */
    private $reason = null;

    /**
     * По ум. не используется
     *  Реквизиты ПС, которые могут понадобиться в случае невозможности его идентификации по
     *  //Ticket/@docRef
     *
     * @property \common\models\raiffeisenxml\response\DealPassRequisitesType $dPIs
     */
    private $dPIs = null;

    /**
     * Gets as date
     *
     * Дата закрытия ПС
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Sets a new date
     *
     * Дата закрытия ПС
     *
     * @param \DateTime $date
     * @return static
     */
    public function setDate(\DateTime $date)
    {
        $this->date = $date;
        return $this;
    }

    /**
     * Gets as reason
     *
     * Основание закрытия ПС
     *
     * @return string
     */
    public function getReason()
    {
        return $this->reason;
    }

    /**
     * Sets a new reason
     *
     * Основание закрытия ПС
     *
     * @param string $reason
     * @return static
     */
    public function setReason($reason)
    {
        $this->reason = $reason;
        return $this;
    }

    /**
     * Gets as dPIs
     *
     * По ум. не используется
     *  Реквизиты ПС, которые могут понадобиться в случае невозможности его идентификации по
     *  //Ticket/@docRef
     *
     * @return \common\models\raiffeisenxml\response\DealPassRequisitesType
     */
    public function getDPIs()
    {
        return $this->dPIs;
    }

    /**
     * Sets a new dPIs
     *
     * По ум. не используется
     *  Реквизиты ПС, которые могут понадобиться в случае невозможности его идентификации по
     *  //Ticket/@docRef
     *
     * @param \common\models\raiffeisenxml\response\DealPassRequisitesType $dPIs
     * @return static
     */
    public function setDPIs(\common\models\raiffeisenxml\response\DealPassRequisitesType $dPIs)
    {
        $this->dPIs = $dPIs;
        return $this;
    }


}


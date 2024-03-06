<?php

namespace common\models\raiffeisenxml\response\DealPassTicketType;

/**
 * Class representing DPReAType
 */
class DPReAType
{

    /**
     * Номер переоформления
     *
     * @property string $num
     */
    private $num = null;

    /**
     * Дата переоформления ПС
     *
     * @property \DateTime $date
     */
    private $date = null;

    /**
     * По ум. не используется
     *  Реквизиты ПС, которые могут понадобиться в случае невозможности его идентификации по
     *  //Ticket/@docRef
     *
     * @property \common\models\raiffeisenxml\response\DealPassRequisitesType $dPIs
     */
    private $dPIs = null;

    /**
     * Gets as num
     *
     * Номер переоформления
     *
     * @return string
     */
    public function getNum()
    {
        return $this->num;
    }

    /**
     * Sets a new num
     *
     * Номер переоформления
     *
     * @param string $num
     * @return static
     */
    public function setNum($num)
    {
        $this->num = $num;
        return $this;
    }

    /**
     * Gets as date
     *
     * Дата переоформления ПС
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
     * Дата переоформления ПС
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


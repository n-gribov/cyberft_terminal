<?php

namespace common\models\raiffeisenxml\response;

/**
 * Class representing DealPassTicketType
 *
 * Дополнительная информация о паспорте сделки (по обеим формам: 1 и 2) в соответствующем
 *  квитке
 * XSD Type: DealPassTicket
 */
class DealPassTicketType
{

    /**
     * Реквизиты ПС. Данный элемент заполняется в случае оформления ПС
     *
     * @property \common\models\raiffeisenxml\response\DealPassRequisitesType $dPIs
     */
    private $dPIs = null;

    /**
     * Доп. информация, передаваемая в случае переоформления ПС
     *
     * @property \common\models\raiffeisenxml\response\DealPassTicketType\DPReAType $dPRe
     */
    private $dPRe = null;

    /**
     * Доп. информация, передаваемая в случае закрытия ПС
     *
     * @property \common\models\raiffeisenxml\response\DealPassTicketType\DPEndAType $dPEnd
     */
    private $dPEnd = null;

    /**
     * Gets as dPIs
     *
     * Реквизиты ПС. Данный элемент заполняется в случае оформления ПС
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
     * Реквизиты ПС. Данный элемент заполняется в случае оформления ПС
     *
     * @param \common\models\raiffeisenxml\response\DealPassRequisitesType $dPIs
     * @return static
     */
    public function setDPIs(\common\models\raiffeisenxml\response\DealPassRequisitesType $dPIs)
    {
        $this->dPIs = $dPIs;
        return $this;
    }

    /**
     * Gets as dPRe
     *
     * Доп. информация, передаваемая в случае переоформления ПС
     *
     * @return \common\models\raiffeisenxml\response\DealPassTicketType\DPReAType
     */
    public function getDPRe()
    {
        return $this->dPRe;
    }

    /**
     * Sets a new dPRe
     *
     * Доп. информация, передаваемая в случае переоформления ПС
     *
     * @param \common\models\raiffeisenxml\response\DealPassTicketType\DPReAType $dPRe
     * @return static
     */
    public function setDPRe(\common\models\raiffeisenxml\response\DealPassTicketType\DPReAType $dPRe)
    {
        $this->dPRe = $dPRe;
        return $this;
    }

    /**
     * Gets as dPEnd
     *
     * Доп. информация, передаваемая в случае закрытия ПС
     *
     * @return \common\models\raiffeisenxml\response\DealPassTicketType\DPEndAType
     */
    public function getDPEnd()
    {
        return $this->dPEnd;
    }

    /**
     * Sets a new dPEnd
     *
     * Доп. информация, передаваемая в случае закрытия ПС
     *
     * @param \common\models\raiffeisenxml\response\DealPassTicketType\DPEndAType $dPEnd
     * @return static
     */
    public function setDPEnd(\common\models\raiffeisenxml\response\DealPassTicketType\DPEndAType $dPEnd)
    {
        $this->dPEnd = $dPEnd;
        return $this;
    }


}


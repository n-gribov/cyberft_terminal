<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing ContragentAddTicketType
 *
 * Дополнительная информация по квитку на добавление контрагента
 * XSD Type: ContragentAddTicket
 */
class ContragentAddTicketType
{

    /**
     * Идентификатор подтвержденного контрагента в банке
     *
     * @property string $contragentExtId
     */
    private $contragentExtId = null;

    /**
     * Gets as contragentExtId
     *
     * Идентификатор подтвержденного контрагента в банке
     *
     * @return string
     */
    public function getContragentExtId()
    {
        return $this->contragentExtId;
    }

    /**
     * Sets a new contragentExtId
     *
     * Идентификатор подтвержденного контрагента в банке
     *
     * @param string $contragentExtId
     * @return static
     */
    public function setContragentExtId($contragentExtId)
    {
        $this->contragentExtId = $contragentExtId;
        return $this;
    }


}


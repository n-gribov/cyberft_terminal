<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing PayDocRuTicketType
 *
 *
 * XSD Type: PayDocRuTicket
 */
class PayDocRuTicketType
{

    /**
     * Доп. статус СБК (поле РЦК)
     *
     * @property string $rzkStatus
     */
    private $rzkStatus = null;

    /**
     * Последнее действие с документом в СБК (поле РЦК)
     *
     * @property string $rzkAction
     */
    private $rzkAction = null;

    /**
     * Gets as rzkStatus
     *
     * Доп. статус СБК (поле РЦК)
     *
     * @return string
     */
    public function getRzkStatus()
    {
        return $this->rzkStatus;
    }

    /**
     * Sets a new rzkStatus
     *
     * Доп. статус СБК (поле РЦК)
     *
     * @param string $rzkStatus
     * @return static
     */
    public function setRzkStatus($rzkStatus)
    {
        $this->rzkStatus = $rzkStatus;
        return $this;
    }

    /**
     * Gets as rzkAction
     *
     * Последнее действие с документом в СБК (поле РЦК)
     *
     * @return string
     */
    public function getRzkAction()
    {
        return $this->rzkAction;
    }

    /**
     * Sets a new rzkAction
     *
     * Последнее действие с документом в СБК (поле РЦК)
     *
     * @param string $rzkAction
     * @return static
     */
    public function setRzkAction($rzkAction)
    {
        $this->rzkAction = $rzkAction;
        return $this;
    }


}


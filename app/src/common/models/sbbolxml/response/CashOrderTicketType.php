<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing CashOrderTicketType
 *
 * Дополнительная информация по заявке на получение наличных средств
 * XSD Type: CashOrderTicket
 */
class CashOrderTicketType
{

    /**
     * Уникальный номер заявки
     *
     * @property string $reqNumberBase36
     */
    private $reqNumberBase36 = null;

    /**
     * Строка QR-кода
     *
     * @property string $qrCode
     */
    private $qrCode = null;

    /**
     * Сумма комиссии за выдачу наличных
     *
     * @property string $chargeWithdraw
     */
    private $chargeWithdraw = null;

    /**
     * Сумма комисии за купюрность
     *
     * @property string $chargeBanknotes
     */
    private $chargeBanknotes = null;

    /**
     * Уведомления по заявке на получение наличных средств
     *
     * @property \common\models\sbbolxml\response\CashOrderTicketNotifType[] $notifs
     */
    private $notifs = null;

    /**
     * Gets as reqNumberBase36
     *
     * Уникальный номер заявки
     *
     * @return string
     */
    public function getReqNumberBase36()
    {
        return $this->reqNumberBase36;
    }

    /**
     * Sets a new reqNumberBase36
     *
     * Уникальный номер заявки
     *
     * @param string $reqNumberBase36
     * @return static
     */
    public function setReqNumberBase36($reqNumberBase36)
    {
        $this->reqNumberBase36 = $reqNumberBase36;
        return $this;
    }

    /**
     * Gets as qrCode
     *
     * Строка QR-кода
     *
     * @return string
     */
    public function getQrCode()
    {
        return $this->qrCode;
    }

    /**
     * Sets a new qrCode
     *
     * Строка QR-кода
     *
     * @param string $qrCode
     * @return static
     */
    public function setQrCode($qrCode)
    {
        $this->qrCode = $qrCode;
        return $this;
    }

    /**
     * Gets as chargeWithdraw
     *
     * Сумма комиссии за выдачу наличных
     *
     * @return string
     */
    public function getChargeWithdraw()
    {
        return $this->chargeWithdraw;
    }

    /**
     * Sets a new chargeWithdraw
     *
     * Сумма комиссии за выдачу наличных
     *
     * @param string $chargeWithdraw
     * @return static
     */
    public function setChargeWithdraw($chargeWithdraw)
    {
        $this->chargeWithdraw = $chargeWithdraw;
        return $this;
    }

    /**
     * Gets as chargeBanknotes
     *
     * Сумма комисии за купюрность
     *
     * @return string
     */
    public function getChargeBanknotes()
    {
        return $this->chargeBanknotes;
    }

    /**
     * Sets a new chargeBanknotes
     *
     * Сумма комисии за купюрность
     *
     * @param string $chargeBanknotes
     * @return static
     */
    public function setChargeBanknotes($chargeBanknotes)
    {
        $this->chargeBanknotes = $chargeBanknotes;
        return $this;
    }

    /**
     * Adds as cashOrderTicketNotif
     *
     * Уведомления по заявке на получение наличных средств
     *
     * @return static
     * @param \common\models\sbbolxml\response\CashOrderTicketNotifType $cashOrderTicketNotif
     */
    public function addToNotifs(\common\models\sbbolxml\response\CashOrderTicketNotifType $cashOrderTicketNotif)
    {
        $this->notifs[] = $cashOrderTicketNotif;
        return $this;
    }

    /**
     * isset notifs
     *
     * Уведомления по заявке на получение наличных средств
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetNotifs($index)
    {
        return isset($this->notifs[$index]);
    }

    /**
     * unset notifs
     *
     * Уведомления по заявке на получение наличных средств
     *
     * @param scalar $index
     * @return void
     */
    public function unsetNotifs($index)
    {
        unset($this->notifs[$index]);
    }

    /**
     * Gets as notifs
     *
     * Уведомления по заявке на получение наличных средств
     *
     * @return \common\models\sbbolxml\response\CashOrderTicketNotifType[]
     */
    public function getNotifs()
    {
        return $this->notifs;
    }

    /**
     * Sets a new notifs
     *
     * Уведомления по заявке на получение наличных средств
     *
     * @param \common\models\sbbolxml\response\CashOrderTicketNotifType[] $notifs
     * @return static
     */
    public function setNotifs(array $notifs)
    {
        $this->notifs = $notifs;
        return $this;
    }


}


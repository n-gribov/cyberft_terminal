<?php

namespace common\models\raiffeisenxml\response;

/**
 * Class representing MandatorySaleTicketType
 *
 *
 * XSD Type: MandatorySaleTicket
 */
class MandatorySaleTicketType
{

    /**
     * Общая сумма списаных с тр. счета
     *
     * @property \common\models\raiffeisenxml\response\CurrAmountType $totalSum
     */
    private $totalSum = null;

    /**
     * Необязательная продажа
     *
     * @property \common\models\raiffeisenxml\response\MandatorySaleTicketType\SellAType $sell
     */
    private $sell = null;

    /**
     * Обязательная продажа
     *
     * @property \common\models\raiffeisenxml\response\MandatorySaleTicketType\OSellAType $oSell
     */
    private $oSell = null;

    /**
     * Зачисление
     *
     * @property \common\models\raiffeisenxml\response\MandatorySaleTicketType\TransAType $trans
     */
    private $trans = null;

    /**
     * Всего Сумма комиссии
     *
     * @property \common\models\raiffeisenxml\response\CurrAmountType $totalChargeSum
     */
    private $totalChargeSum = null;

    /**
     * Gets as totalSum
     *
     * Общая сумма списаных с тр. счета
     *
     * @return \common\models\raiffeisenxml\response\CurrAmountType
     */
    public function getTotalSum()
    {
        return $this->totalSum;
    }

    /**
     * Sets a new totalSum
     *
     * Общая сумма списаных с тр. счета
     *
     * @param \common\models\raiffeisenxml\response\CurrAmountType $totalSum
     * @return static
     */
    public function setTotalSum(\common\models\raiffeisenxml\response\CurrAmountType $totalSum)
    {
        $this->totalSum = $totalSum;
        return $this;
    }

    /**
     * Gets as sell
     *
     * Необязательная продажа
     *
     * @return \common\models\raiffeisenxml\response\MandatorySaleTicketType\SellAType
     */
    public function getSell()
    {
        return $this->sell;
    }

    /**
     * Sets a new sell
     *
     * Необязательная продажа
     *
     * @param \common\models\raiffeisenxml\response\MandatorySaleTicketType\SellAType $sell
     * @return static
     */
    public function setSell(\common\models\raiffeisenxml\response\MandatorySaleTicketType\SellAType $sell)
    {
        $this->sell = $sell;
        return $this;
    }

    /**
     * Gets as oSell
     *
     * Обязательная продажа
     *
     * @return \common\models\raiffeisenxml\response\MandatorySaleTicketType\OSellAType
     */
    public function getOSell()
    {
        return $this->oSell;
    }

    /**
     * Sets a new oSell
     *
     * Обязательная продажа
     *
     * @param \common\models\raiffeisenxml\response\MandatorySaleTicketType\OSellAType $oSell
     * @return static
     */
    public function setOSell(\common\models\raiffeisenxml\response\MandatorySaleTicketType\OSellAType $oSell)
    {
        $this->oSell = $oSell;
        return $this;
    }

    /**
     * Gets as trans
     *
     * Зачисление
     *
     * @return \common\models\raiffeisenxml\response\MandatorySaleTicketType\TransAType
     */
    public function getTrans()
    {
        return $this->trans;
    }

    /**
     * Sets a new trans
     *
     * Зачисление
     *
     * @param \common\models\raiffeisenxml\response\MandatorySaleTicketType\TransAType $trans
     * @return static
     */
    public function setTrans(\common\models\raiffeisenxml\response\MandatorySaleTicketType\TransAType $trans)
    {
        $this->trans = $trans;
        return $this;
    }

    /**
     * Gets as totalChargeSum
     *
     * Всего Сумма комиссии
     *
     * @return \common\models\raiffeisenxml\response\CurrAmountType
     */
    public function getTotalChargeSum()
    {
        return $this->totalChargeSum;
    }

    /**
     * Sets a new totalChargeSum
     *
     * Всего Сумма комиссии
     *
     * @param \common\models\raiffeisenxml\response\CurrAmountType $totalChargeSum
     * @return static
     */
    public function setTotalChargeSum(\common\models\raiffeisenxml\response\CurrAmountType $totalChargeSum)
    {
        $this->totalChargeSum = $totalChargeSum;
        return $this;
    }


}


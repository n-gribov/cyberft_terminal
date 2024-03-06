<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing PayDocRuInfoTicketType
 *
 *
 * XSD Type: PayDocRuInfoTicket
 */
class PayDocRuInfoTicketType
{

    /**
     * Содержит информацию о реквизитах получателя
     *
     * @property \common\models\sbbolxml\response\ReceiverTicketType $receiver
     */
    private $receiver = null;

    /**
     * Сумма документа
     *
     * @property \common\models\sbbolxml\response\CurrAmountType $docSum
     */
    private $docSum = null;

    /**
     * Gets as receiver
     *
     * Содержит информацию о реквизитах получателя
     *
     * @return \common\models\sbbolxml\response\ReceiverTicketType
     */
    public function getReceiver()
    {
        return $this->receiver;
    }

    /**
     * Sets a new receiver
     *
     * Содержит информацию о реквизитах получателя
     *
     * @param \common\models\sbbolxml\response\ReceiverTicketType $receiver
     * @return static
     */
    public function setReceiver(\common\models\sbbolxml\response\ReceiverTicketType $receiver)
    {
        $this->receiver = $receiver;
        return $this;
    }

    /**
     * Gets as docSum
     *
     * Сумма документа
     *
     * @return \common\models\sbbolxml\response\CurrAmountType
     */
    public function getDocSum()
    {
        return $this->docSum;
    }

    /**
     * Sets a new docSum
     *
     * Сумма документа
     *
     * @param \common\models\sbbolxml\response\CurrAmountType $docSum
     * @return static
     */
    public function setDocSum(\common\models\sbbolxml\response\CurrAmountType $docSum)
    {
        $this->docSum = $docSum;
        return $this;
    }


}


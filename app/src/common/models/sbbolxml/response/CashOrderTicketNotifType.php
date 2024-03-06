<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing CashOrderTicketNotifType
 *
 * Уведомление по заявке на получение наличных средств
 * XSD Type: CashOrderTicketNotif
 */
class CashOrderTicketNotifType
{

    /**
     * Дата и время уведомления
     *
     * @property \DateTime $date
     */
    private $date = null;

    /**
     * Тип уведомления:
     *  1 - "Установлена блокировка",
     *  2 – "Снята блокировка",
     *  3 – "Необходимо пополнить счет",
     *  4 – "Необходимо принести документы",
     *  5 – "Необходимо принести анкету"
     *
     * @property string $type
     */
    private $type = null;

    /**
     * Текст уведомления
     *
     * @property string $text
     */
    private $text = null;

    /**
     * Gets as date
     *
     * Дата и время уведомления
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
     * Дата и время уведомления
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
     * Gets as type
     *
     * Тип уведомления:
     *  1 - "Установлена блокировка",
     *  2 – "Снята блокировка",
     *  3 – "Необходимо пополнить счет",
     *  4 – "Необходимо принести документы",
     *  5 – "Необходимо принести анкету"
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Sets a new type
     *
     * Тип уведомления:
     *  1 - "Установлена блокировка",
     *  2 – "Снята блокировка",
     *  3 – "Необходимо пополнить счет",
     *  4 – "Необходимо принести документы",
     *  5 – "Необходимо принести анкету"
     *
     * @param string $type
     * @return static
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * Gets as text
     *
     * Текст уведомления
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Sets a new text
     *
     * Текст уведомления
     *
     * @param string $text
     * @return static
     */
    public function setText($text)
    {
        $this->text = $text;
        return $this;
    }


}


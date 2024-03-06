<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing ReceiverTicketType
 *
 *
 * XSD Type: ReceiverTicket
 */
class ReceiverTicketType
{

    /**
     * ИНН получателя
     *
     * @property string $inn
     */
    private $inn = null;

    /**
     * Счет получателя
     *
     * @property string $personalAcc
     */
    private $personalAcc = null;

    /**
     * Содержит информацию о наименовании получателя
     *
     * @property string $name
     */
    private $name = null;

    /**
     * Содержит информацию о реквизитах банка
     *
     * @property \common\models\sbbolxml\response\RecBankTicketType $bank
     */
    private $bank = null;

    /**
     * Gets as inn
     *
     * ИНН получателя
     *
     * @return string
     */
    public function getInn()
    {
        return $this->inn;
    }

    /**
     * Sets a new inn
     *
     * ИНН получателя
     *
     * @param string $inn
     * @return static
     */
    public function setInn($inn)
    {
        $this->inn = $inn;
        return $this;
    }

    /**
     * Gets as personalAcc
     *
     * Счет получателя
     *
     * @return string
     */
    public function getPersonalAcc()
    {
        return $this->personalAcc;
    }

    /**
     * Sets a new personalAcc
     *
     * Счет получателя
     *
     * @param string $personalAcc
     * @return static
     */
    public function setPersonalAcc($personalAcc)
    {
        $this->personalAcc = $personalAcc;
        return $this;
    }

    /**
     * Gets as name
     *
     * Содержит информацию о наименовании получателя
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets a new name
     *
     * Содержит информацию о наименовании получателя
     *
     * @param string $name
     * @return static
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Gets as bank
     *
     * Содержит информацию о реквизитах банка
     *
     * @return \common\models\sbbolxml\response\RecBankTicketType
     */
    public function getBank()
    {
        return $this->bank;
    }

    /**
     * Sets a new bank
     *
     * Содержит информацию о реквизитах банка
     *
     * @param \common\models\sbbolxml\response\RecBankTicketType $bank
     * @return static
     */
    public function setBank(\common\models\sbbolxml\response\RecBankTicketType $bank)
    {
        $this->bank = $bank;
        return $this;
    }


}


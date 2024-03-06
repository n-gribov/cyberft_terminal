<?php

namespace common\models\raiffeisenxml\request;

/**
 * Class representing PayeeType
 *
 *
 * XSD Type: Payee
 */
class PayeeType
{

    /**
     * Номер счёта
     *
     * @property string $personalAcc
     */
    private $personalAcc = null;

    /**
     * КПП (до 9)
     *
     * @property string $kpp
     */
    private $kpp = null;

    /**
     * ИНН (до 12)
     *
     * @property string $inn
     */
    private $inn = null;

    /**
     * Уникальный Идентификатора Платежа
     *
     * @property string $uip
     */
    private $uip = null;

    /**
     * Наименование получателя
     *
     * @property string $name
     */
    private $name = null;

    /**
     * Реквизиты банка получателя
     *
     * @property \common\models\raiffeisenxml\request\BankNoNameType $bank
     */
    private $bank = null;

    /**
     * @property string $filial
     */
    private $filial = null;

    /**
     * Gets as personalAcc
     *
     * Номер счёта
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
     * Номер счёта
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
     * Gets as kpp
     *
     * КПП (до 9)
     *
     * @return string
     */
    public function getKpp()
    {
        return $this->kpp;
    }

    /**
     * Sets a new kpp
     *
     * КПП (до 9)
     *
     * @param string $kpp
     * @return static
     */
    public function setKpp($kpp)
    {
        $this->kpp = $kpp;
        return $this;
    }

    /**
     * Gets as inn
     *
     * ИНН (до 12)
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
     * ИНН (до 12)
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
     * Gets as uip
     *
     * Уникальный Идентификатора Платежа
     *
     * @return string
     */
    public function getUip()
    {
        return $this->uip;
    }

    /**
     * Sets a new uip
     *
     * Уникальный Идентификатора Платежа
     *
     * @param string $uip
     * @return static
     */
    public function setUip($uip)
    {
        $this->uip = $uip;
        return $this;
    }

    /**
     * Gets as name
     *
     * Наименование получателя
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
     * Наименование получателя
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
     * Реквизиты банка получателя
     *
     * @return \common\models\raiffeisenxml\request\BankNoNameType
     */
    public function getBank()
    {
        return $this->bank;
    }

    /**
     * Sets a new bank
     *
     * Реквизиты банка получателя
     *
     * @param \common\models\raiffeisenxml\request\BankNoNameType $bank
     * @return static
     */
    public function setBank(\common\models\raiffeisenxml\request\BankNoNameType $bank)
    {
        $this->bank = $bank;
        return $this;
    }

    /**
     * Gets as filial
     *
     * @return string
     */
    public function getFilial()
    {
        return $this->filial;
    }

    /**
     * Sets a new filial
     *
     * @param string $filial
     * @return static
     */
    public function setFilial($filial)
    {
        $this->filial = $filial;
        return $this;
    }


}


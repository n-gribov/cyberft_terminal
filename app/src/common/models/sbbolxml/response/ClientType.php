<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing ClientType
 *
 *
 * XSD Type: Client
 */
class ClientType
{

    /**
     * ИНН (до 12)
     *
     * @property string $inn
     */
    private $inn = null;

    /**
     * КПП (до 9)
     *
     * @property string $kpp
     */
    private $kpp = null;

    /**
     * Номер счёта
     *
     * @property string $personalAcc
     */
    private $personalAcc = null;

    /**
     * Наименование плательщика
     *
     * @property string $name
     */
    private $name = null;

    /**
     * Реквизиты банка плательщика
     *
     * @property \common\models\sbbolxml\response\BankType $bank
     */
    private $bank = null;

    /**
     * @property string $filial
     */
    private $filial = null;

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
     * Gets as name
     *
     * Наименование плательщика
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
     * Наименование плательщика
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
     * Реквизиты банка плательщика
     *
     * @return \common\models\sbbolxml\response\BankType
     */
    public function getBank()
    {
        return $this->bank;
    }

    /**
     * Sets a new bank
     *
     * Реквизиты банка плательщика
     *
     * @param \common\models\sbbolxml\response\BankType $bank
     * @return static
     */
    public function setBank(\common\models\sbbolxml\response\BankType $bank)
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


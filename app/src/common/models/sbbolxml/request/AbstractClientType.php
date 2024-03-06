<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing AbstractClientType
 *
 *
 * XSD Type: AbstractClient
 */
class AbstractClientType
{

    /**
     * Номер счёта
     *
     * @property string $personalAcc
     */
    private $personalAcc = null;

    /**
     * Наименование клиента
     *
     * @property string $name
     */
    private $name = null;

    /**
     * Реквизиты банка клиента
     *
     * @property \common\models\sbbolxml\request\BankType $bank
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
     * Gets as name
     *
     * Наименование клиента
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
     * Наименование клиента
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
     * Реквизиты банка клиента
     *
     * @return \common\models\sbbolxml\request\BankType
     */
    public function getBank()
    {
        return $this->bank;
    }

    /**
     * Sets a new bank
     *
     * Реквизиты банка клиента
     *
     * @param \common\models\sbbolxml\request\BankType $bank
     * @return static
     */
    public function setBank(\common\models\sbbolxml\request\BankType $bank)
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


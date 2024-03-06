<?php

namespace common\models\raiffeisenxml\request\LetterOfDepositRaifType\RefundAType;

/**
 * Class representing PayeeAType
 */
class PayeeAType
{

    /**
     * ИНН
     *
     * @property string $inn
     */
    private $inn = null;

    /**
     * Номер счёта
     *
     * @property string $personalAcc
     */
    private $personalAcc = null;

    /**
     * Наименование получателя
     *
     * @property string $name
     */
    private $name = null;

    /**
     * Gets as inn
     *
     * ИНН
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
     * ИНН
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


}


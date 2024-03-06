<?php

namespace common\models\raiffeisenxml\request\AccreditiveRubRaifType\MainAType;

/**
 * Class representing AccountAType
 */
class AccountAType
{

    /**
     * Номер счета
     *
     * @property string $num
     */
    private $num = null;

    /**
     * БИК банка
     *
     * @property string $bic
     */
    private $bic = null;

    /**
     * Наименование банка
     *
     * @property string $name
     */
    private $name = null;

    /**
     * Корр.счет
     *
     * @property string $corrAcc
     */
    private $corrAcc = null;

    /**
     * Gets as num
     *
     * Номер счета
     *
     * @return string
     */
    public function getNum()
    {
        return $this->num;
    }

    /**
     * Sets a new num
     *
     * Номер счета
     *
     * @param string $num
     * @return static
     */
    public function setNum($num)
    {
        $this->num = $num;
        return $this;
    }

    /**
     * Gets as bic
     *
     * БИК банка
     *
     * @return string
     */
    public function getBic()
    {
        return $this->bic;
    }

    /**
     * Sets a new bic
     *
     * БИК банка
     *
     * @param string $bic
     * @return static
     */
    public function setBic($bic)
    {
        $this->bic = $bic;
        return $this;
    }

    /**
     * Gets as name
     *
     * Наименование банка
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
     * Наименование банка
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
     * Gets as corrAcc
     *
     * Корр.счет
     *
     * @return string
     */
    public function getCorrAcc()
    {
        return $this->corrAcc;
    }

    /**
     * Sets a new corrAcc
     *
     * Корр.счет
     *
     * @param string $corrAcc
     * @return static
     */
    public function setCorrAcc($corrAcc)
    {
        $this->corrAcc = $corrAcc;
        return $this;
    }


}


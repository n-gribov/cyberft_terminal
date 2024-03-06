<?php

namespace common\models\sbbolxml\request\AccountReturnType;

/**
 * Class representing BankAType
 */
class BankAType
{

    /**
     * БИК
     *
     * @property string $bic
     */
    private $bic = null;

    /**
     * Номер корр. счёта банка
     *
     * @property string $correspAcc
     */
    private $correspAcc = null;

    /**
     * Наименование банка
     *
     * @property string $name
     */
    private $name = null;

    /**
     * Gets as bic
     *
     * БИК
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
     * БИК
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
     * Gets as correspAcc
     *
     * Номер корр. счёта банка
     *
     * @return string
     */
    public function getCorrespAcc()
    {
        return $this->correspAcc;
    }

    /**
     * Sets a new correspAcc
     *
     * Номер корр. счёта банка
     *
     * @param string $correspAcc
     * @return static
     */
    public function setCorrespAcc($correspAcc)
    {
        $this->correspAcc = $correspAcc;
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


}


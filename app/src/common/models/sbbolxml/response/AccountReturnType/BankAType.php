<?php

namespace common\models\sbbolxml\response\AccountReturnType;

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


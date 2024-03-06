<?php

namespace common\models\raiffeisenxml\request\LetterOfDepositRaifType\PercentsRefundAType;

/**
 * Class representing MediaBankAType
 */
class MediaBankAType
{

    /**
     * БИК
     *
     * @property string $bic
     */
    private $bic = null;

    /**
     * Счет
     *
     * @property string $acc
     */
    private $acc = null;

    /**
     * Наименование
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
     * Gets as acc
     *
     * Счет
     *
     * @return string
     */
    public function getAcc()
    {
        return $this->acc;
    }

    /**
     * Sets a new acc
     *
     * Счет
     *
     * @param string $acc
     * @return static
     */
    public function setAcc($acc)
    {
        $this->acc = $acc;
        return $this;
    }

    /**
     * Gets as name
     *
     * Наименование
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
     * Наименование
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


<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing ComAccType
 *
 *
 * XSD Type: ComAcc
 */
class ComAccType
{

    /**
     * Номер счёта списания комисс. вознаграждения
     *
     * @property string $accNum
     */
    private $accNum = null;

    /**
     * БИК
     *
     * @property string $bic
     */
    private $bic = null;

    /**
     * @property string $bankName
     */
    private $bankName = null;

    /**
     * Gets as accNum
     *
     * Номер счёта списания комисс. вознаграждения
     *
     * @return string
     */
    public function getAccNum()
    {
        return $this->accNum;
    }

    /**
     * Sets a new accNum
     *
     * Номер счёта списания комисс. вознаграждения
     *
     * @param string $accNum
     * @return static
     */
    public function setAccNum($accNum)
    {
        $this->accNum = $accNum;
        return $this;
    }

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
     * Gets as bankName
     *
     * @return string
     */
    public function getBankName()
    {
        return $this->bankName;
    }

    /**
     * Sets a new bankName
     *
     * @param string $bankName
     * @return static
     */
    public function setBankName($bankName)
    {
        $this->bankName = $bankName;
        return $this;
    }


}


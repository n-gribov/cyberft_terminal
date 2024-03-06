<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing DocAccountType
 *
 *
 * XSD Type: DocAccount
 */
class DocAccountType
{

    /**
     * Номер счёта списания рублёвого покрытия
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
     * Наименование банка
     *
     * @property string $bankName
     */
    private $bankName = null;

    /**
     * Gets as accNum
     *
     * Номер счёта списания рублёвого покрытия
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
     * Номер счёта списания рублёвого покрытия
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
     * Наименование банка
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
     * Наименование банка
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


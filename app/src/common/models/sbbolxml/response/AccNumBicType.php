<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing AccNumBicType
 *
 * Номер счёта, БИК и наименование банка
 * XSD Type: AccNumBicType
 */
class AccNumBicType
{

    /**
     * Номер счёта
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
     * Наименование банка, где открыт счет
     *
     * @property string $bankName
     */
    private $bankName = null;

    /**
     * Gets as accNum
     *
     * Номер счёта
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
     * Номер счёта
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
     * Наименование банка, где открыт счет
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
     * Наименование банка, где открыт счет
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


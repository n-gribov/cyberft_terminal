<?php

namespace common\models\sbbolxml\request\AccountRubType;

/**
 * Class representing AccountAType
 */
class AccountAType
{

    /**
     * Номер счёта списания (р/с)
     *
     * @property string $accNum
     */
    private $accNum = null;

    /**
     * БИК банка счета списания рублей
     *
     * @property string $bic
     */
    private $bic = null;

    /**
     * Реквизиты банка зачисления рублей
     *
     * @property string $bankName
     */
    private $bankName = null;

    /**
     * Gets as accNum
     *
     * Номер счёта списания (р/с)
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
     * Номер счёта списания (р/с)
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
     * БИК банка счета списания рублей
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
     * БИК банка счета списания рублей
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
     * Реквизиты банка зачисления рублей
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
     * Реквизиты банка зачисления рублей
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


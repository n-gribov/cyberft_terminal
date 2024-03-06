<?php

namespace common\models\sbbolxml\request\CurrBuyTransType;

/**
 * Class representing AccAType
 */
class AccAType
{

    /**
     * Номер счёта списания (р/с)
     *
     * @property string $account
     */
    private $account = null;

    /**
     * БИК банка счета списания рублей
     *
     * @property string $bic
     */
    private $bic = null;

    /**
     * Наименование подразделения банка
     *
     * @property string $bankName
     */
    private $bankName = null;

    /**
     * Gets as account
     *
     * Номер счёта списания (р/с)
     *
     * @return string
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * Sets a new account
     *
     * Номер счёта списания (р/с)
     *
     * @param string $account
     * @return static
     */
    public function setAccount($account)
    {
        $this->account = $account;
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
     * Наименование подразделения банка
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
     * Наименование подразделения банка
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


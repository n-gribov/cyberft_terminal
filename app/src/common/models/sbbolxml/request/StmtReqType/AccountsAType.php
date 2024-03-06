<?php

namespace common\models\sbbolxml\request\StmtReqType;

/**
 * Class representing AccountsAType
 */
class AccountsAType
{

    /**
     * Счёт, по которому запрашивается выписка
     *  Желательно, чтобы на первом этапе было ограничение на одинаковый БИК у всех счетов
     *  запроса
     *
     * @property \common\models\sbbolxml\request\AccType[] $account
     */
    private $account = array(
        
    );

    /**
     * Adds as account
     *
     * Счёт, по которому запрашивается выписка
     *  Желательно, чтобы на первом этапе было ограничение на одинаковый БИК у всех счетов
     *  запроса
     *
     * @return static
     * @param \common\models\sbbolxml\request\AccType $account
     */
    public function addToAccount(\common\models\sbbolxml\request\AccType $account)
    {
        $this->account[] = $account;
        return $this;
    }

    /**
     * isset account
     *
     * Счёт, по которому запрашивается выписка
     *  Желательно, чтобы на первом этапе было ограничение на одинаковый БИК у всех счетов
     *  запроса
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetAccount($index)
    {
        return isset($this->account[$index]);
    }

    /**
     * unset account
     *
     * Счёт, по которому запрашивается выписка
     *  Желательно, чтобы на первом этапе было ограничение на одинаковый БИК у всех счетов
     *  запроса
     *
     * @param scalar $index
     * @return void
     */
    public function unsetAccount($index)
    {
        unset($this->account[$index]);
    }

    /**
     * Gets as account
     *
     * Счёт, по которому запрашивается выписка
     *  Желательно, чтобы на первом этапе было ограничение на одинаковый БИК у всех счетов
     *  запроса
     *
     * @return \common\models\sbbolxml\request\AccType[]
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * Sets a new account
     *
     * Счёт, по которому запрашивается выписка
     *  Желательно, чтобы на первом этапе было ограничение на одинаковый БИК у всех счетов
     *  запроса
     *
     * @param \common\models\sbbolxml\request\AccType[] $account
     * @return static
     */
    public function setAccount(array $account)
    {
        $this->account = $account;
        return $this;
    }


}


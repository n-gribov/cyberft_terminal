<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing RemainResponseType
 *
 *
 * XSD Type: RemainResponse
 */
class RemainResponseType
{

    /**
     * Информация по счетам
     *
     * @property \common\models\sbbolxml\response\RemainResponseType\AccountsAType\AccountAType[] $accounts
     */
    private $accounts = null;

    /**
     * Adds as account
     *
     * Информация по счетам
     *
     * @return static
     * @param \common\models\sbbolxml\response\RemainResponseType\AccountsAType\AccountAType $account
     */
    public function addToAccounts(\common\models\sbbolxml\response\RemainResponseType\AccountsAType\AccountAType $account)
    {
        $this->accounts[] = $account;
        return $this;
    }

    /**
     * isset accounts
     *
     * Информация по счетам
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetAccounts($index)
    {
        return isset($this->accounts[$index]);
    }

    /**
     * unset accounts
     *
     * Информация по счетам
     *
     * @param scalar $index
     * @return void
     */
    public function unsetAccounts($index)
    {
        unset($this->accounts[$index]);
    }

    /**
     * Gets as accounts
     *
     * Информация по счетам
     *
     * @return \common\models\sbbolxml\response\RemainResponseType\AccountsAType\AccountAType[]
     */
    public function getAccounts()
    {
        return $this->accounts;
    }

    /**
     * Sets a new accounts
     *
     * Информация по счетам
     *
     * @param \common\models\sbbolxml\response\RemainResponseType\AccountsAType\AccountAType[] $accounts
     * @return static
     */
    public function setAccounts(array $accounts)
    {
        $this->accounts = $accounts;
        return $this;
    }


}


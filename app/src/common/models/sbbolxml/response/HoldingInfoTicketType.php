<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing HoldingInfoTicketType
 *
 *
 * XSD Type: HoldingInfoTicket
 */
class HoldingInfoTicketType
{

    /**
     * @property \common\models\sbbolxml\response\HoldingInfoAccType[] $accounts
     */
    private $accounts = null;

    /**
     * Adds as account
     *
     * @return static
     * @param \common\models\sbbolxml\response\HoldingInfoAccType $account
     */
    public function addToAccounts(\common\models\sbbolxml\response\HoldingInfoAccType $account)
    {
        $this->accounts[] = $account;
        return $this;
    }

    /**
     * isset accounts
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
     * @return \common\models\sbbolxml\response\HoldingInfoAccType[]
     */
    public function getAccounts()
    {
        return $this->accounts;
    }

    /**
     * Sets a new accounts
     *
     * @param \common\models\sbbolxml\response\HoldingInfoAccType[] $accounts
     * @return static
     */
    public function setAccounts(array $accounts)
    {
        $this->accounts = $accounts;
        return $this;
    }


}


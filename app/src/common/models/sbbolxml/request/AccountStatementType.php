<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing AccountStatementType
 *
 * Запрос сформированных выписок по счетам
 * XSD Type: AccountStatement
 */
class AccountStatementType
{

    /**
     * Дата и время последнего запроса выписок(с час. поясами)
     *
     * @property \DateTime $lastRequestTime
     */
    private $lastRequestTime = null;

    /**
     * Формат предоставляемой выписки ('XML' , 'MT940' (англ)). Если тег не указан, то 'XML'
     *
     * @property string $statementFormat
     */
    private $statementFormat = null;

    /**
     * Счёта, по которым нужно получить выписки
     *
     * @property \common\models\sbbolxml\request\AccountType[] $accounts
     */
    private $accounts = null;

    /**
     * Gets as lastRequestTime
     *
     * Дата и время последнего запроса выписок(с час. поясами)
     *
     * @return \DateTime
     */
    public function getLastRequestTime()
    {
        return $this->lastRequestTime;
    }

    /**
     * Sets a new lastRequestTime
     *
     * Дата и время последнего запроса выписок(с час. поясами)
     *
     * @param \DateTime $lastRequestTime
     * @return static
     */
    public function setLastRequestTime(\DateTime $lastRequestTime)
    {
        $this->lastRequestTime = $lastRequestTime;
        return $this;
    }

    /**
     * Gets as statementFormat
     *
     * Формат предоставляемой выписки ('XML' , 'MT940' (англ)). Если тег не указан, то 'XML'
     *
     * @return string
     */
    public function getStatementFormat()
    {
        return $this->statementFormat;
    }

    /**
     * Sets a new statementFormat
     *
     * Формат предоставляемой выписки ('XML' , 'MT940' (англ)). Если тег не указан, то 'XML'
     *
     * @param string $statementFormat
     * @return static
     */
    public function setStatementFormat($statementFormat)
    {
        $this->statementFormat = $statementFormat;
        return $this;
    }

    /**
     * Adds as account
     *
     * Счёта, по которым нужно получить выписки
     *
     * @return static
     * @param \common\models\sbbolxml\request\AccountType $account
     */
    public function addToAccounts(\common\models\sbbolxml\request\AccountType $account)
    {
        $this->accounts[] = $account;
        return $this;
    }

    /**
     * isset accounts
     *
     * Счёта, по которым нужно получить выписки
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
     * Счёта, по которым нужно получить выписки
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
     * Счёта, по которым нужно получить выписки
     *
     * @return \common\models\sbbolxml\request\AccountType[]
     */
    public function getAccounts()
    {
        return $this->accounts;
    }

    /**
     * Sets a new accounts
     *
     * Счёта, по которым нужно получить выписки
     *
     * @param \common\models\sbbolxml\request\AccountType[] $accounts
     * @return static
     */
    public function setAccounts(array $accounts)
    {
        $this->accounts = $accounts;
        return $this;
    }


}


<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing IncrementStatementsType
 *
 * Запрос на получение выписки с новыми операциями
 * XSD Type: IncrementStatements
 */
class IncrementStatementsType
{

    /**
     * Используется для определения на стороне УПШ какие операции должны быть отданы в Response/Statements.
     *  Для значения 1- содержащая только документы после предыдущего запроса.
     *  Для значения 0 - содержащая все документы.
     *
     * @property boolean $statementIncrement
     */
    private $statementIncrement = null;

    /**
     * @property \common\models\sbbolxml\request\AccWithDateType[] $accounts
     */
    private $accounts = null;

    /**
     * Gets as statementIncrement
     *
     * Используется для определения на стороне УПШ какие операции должны быть отданы в Response/Statements.
     *  Для значения 1- содержащая только документы после предыдущего запроса.
     *  Для значения 0 - содержащая все документы.
     *
     * @return boolean
     */
    public function getStatementIncrement()
    {
        return $this->statementIncrement;
    }

    /**
     * Sets a new statementIncrement
     *
     * Используется для определения на стороне УПШ какие операции должны быть отданы в Response/Statements.
     *  Для значения 1- содержащая только документы после предыдущего запроса.
     *  Для значения 0 - содержащая все документы.
     *
     * @param boolean $statementIncrement
     * @return static
     */
    public function setStatementIncrement($statementIncrement)
    {
        $this->statementIncrement = $statementIncrement;
        return $this;
    }

    /**
     * Adds as account
     *
     * @return static
     * @param \common\models\sbbolxml\request\AccWithDateType $account
     */
    public function addToAccounts(\common\models\sbbolxml\request\AccWithDateType $account)
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
     * @return \common\models\sbbolxml\request\AccWithDateType[]
     */
    public function getAccounts()
    {
        return $this->accounts;
    }

    /**
     * Sets a new accounts
     *
     * @param \common\models\sbbolxml\request\AccWithDateType[] $accounts
     * @return static
     */
    public function setAccounts(array $accounts)
    {
        $this->accounts = $accounts;
        return $this;
    }


}


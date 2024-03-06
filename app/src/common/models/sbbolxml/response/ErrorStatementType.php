<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing ErrorStatementType
 *
 * Ошибка формирования выписки по счету
 * XSD Type: ErrorStatementType
 */
class ErrorStatementType
{

    /**
     * Номер счёта
     *
     * @property string $account
     */
    private $account = null;

    /**
     * Текст ошибки
     *
     * @property string $errorMessage
     */
    private $errorMessage = null;

    /**
     * Gets as account
     *
     * Номер счёта
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
     * Номер счёта
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
     * Gets as errorMessage
     *
     * Текст ошибки
     *
     * @return string
     */
    public function getErrorMessage()
    {
        return $this->errorMessage;
    }

    /**
     * Sets a new errorMessage
     *
     * Текст ошибки
     *
     * @param string $errorMessage
     * @return static
     */
    public function setErrorMessage($errorMessage)
    {
        $this->errorMessage = $errorMessage;
        return $this;
    }


}


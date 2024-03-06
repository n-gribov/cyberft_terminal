<?php

namespace addons\VTB\models\soap\messages\WSGetCustomer;

use addons\VTB\models\soap\messages\BaseMessage;

class GetAccountsListResponse extends BaseMessage
{
    protected $Accounts;
    protected $BSError;
    protected $BSErrorCode;

    public function setAccounts($value)
    {
        $this->setProperty('Accounts', $value);
        return $this;
    }

    public function getAccounts()
    {
        $this->checkPropertyExists('Accounts');
        return $this->Accounts;
    }

    public function setBSError($value)
    {
        $this->setProperty('BSError', $value);
        return $this;
    }

    public function getBSError()
    {
        $this->checkPropertyExists('BSError');
        return $this->BSError;
    }

    public function setBSErrorCode($value)
    {
        $this->setProperty('BSErrorCode', $value);
        return $this;
    }

    public function getBSErrorCode()
    {
        $this->checkPropertyExists('BSErrorCode');
        return $this->BSErrorCode;
    }

}

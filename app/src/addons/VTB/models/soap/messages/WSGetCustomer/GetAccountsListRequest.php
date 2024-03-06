<?php

namespace addons\VTB\models\soap\messages\WSGetCustomer;

use addons\VTB\models\soap\messages\BaseMessage;

class GetAccountsListRequest extends BaseMessage
{
    protected $Account;
    protected $Accounts;
    protected $BIC;
    protected $BSError;
    protected $BSErrorCode;
    protected $CustID;
    protected $RemoteUser;

    public function setAccount($value)
    {
        $this->setProperty('Account', $value);
        return $this;
    }

    public function getAccount()
    {
        $this->checkPropertyExists('Account');
        return $this->Account;
    }

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

    public function setBIC($value)
    {
        $this->setProperty('BIC', $value);
        return $this;
    }

    public function getBIC()
    {
        $this->checkPropertyExists('BIC');
        return $this->BIC;
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

    public function setCustID($value)
    {
        $this->setProperty('CustID', $value);
        return $this;
    }

    public function getCustID()
    {
        $this->checkPropertyExists('CustID');
        return $this->CustID;
    }

    public function setRemoteUser($value)
    {
        $this->setProperty('RemoteUser', $value);
        return $this;
    }

    public function getRemoteUser()
    {
        $this->checkPropertyExists('RemoteUser');
        return $this->RemoteUser;
    }

}

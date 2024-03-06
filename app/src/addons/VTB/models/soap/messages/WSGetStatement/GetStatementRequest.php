<?php

namespace addons\VTB\models\soap\messages\WSGetStatement;

use addons\VTB\models\soap\messages\BaseMessage;

class GetStatementRequest extends BaseMessage
{
    protected $Account;
    protected $BIC;
    protected $BSError;
    protected $BSErrorCode;
    protected $CustID;
    protected $RemoteUser;
    protected $SignData;
    protected $StatementDate;
    protected $StatementDoc;
    protected $StatementType;

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

    public function setSignData($value)
    {
        $this->setProperty('SignData', $value);
        return $this;
    }

    public function getSignData()
    {
        $this->checkPropertyExists('SignData');
        return $this->SignData;
    }

    public function setStatementDate($value)
    {
        $this->setProperty('StatementDate', $value);
        return $this;
    }

    public function getStatementDate()
    {
        $this->checkPropertyExists('StatementDate');
        return $this->StatementDate;
    }

    public function setStatementDoc($value)
    {
        $this->setProperty('StatementDoc', $value);
        return $this;
    }

    public function getStatementDoc()
    {
        $this->checkPropertyExists('StatementDoc');
        return $this->StatementDoc;
    }

    public function setStatementType($value)
    {
        $this->setProperty('StatementType', $value);
        return $this;
    }

    public function getStatementType()
    {
        $this->checkPropertyExists('StatementType');
        return $this->StatementType;
    }

}

<?php

namespace addons\VTB\models\soap\messages\WSPrepareDocForCancel;

use addons\VTB\models\soap\messages\BaseMessage;

class PrepareDocForCancelRequest extends BaseMessage
{
    protected $BSError;
    protected $BSErrorCode;
    protected $CancelFields;
    protected $CustID;
    protected $DocScheme;
    protected $RecordID;
    protected $RemoteUser;

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

    public function setCancelFields($value)
    {
        $this->setProperty('CancelFields', $value);
        return $this;
    }

    public function getCancelFields()
    {
        $this->checkPropertyExists('CancelFields');
        return $this->CancelFields;
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

    public function setDocScheme($value)
    {
        $this->setProperty('DocScheme', $value);
        return $this;
    }

    public function getDocScheme()
    {
        $this->checkPropertyExists('DocScheme');
        return $this->DocScheme;
    }

    public function setRecordID($value)
    {
        $this->setProperty('RecordID', $value);
        return $this;
    }

    public function getRecordID()
    {
        $this->checkPropertyExists('RecordID');
        return $this->RecordID;
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

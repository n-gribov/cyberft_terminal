<?php

namespace addons\VTB\models\soap\messages\WSGetDocStatus;

use addons\VTB\models\soap\messages\BaseMessage;

class GetDocStatusRequest extends BaseMessage
{
    protected $BSError;
    protected $BSErrorCode;
    protected $CustID;
    protected $DocInfo;
    protected $DocScheme;
    protected $DocStatus;
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

    public function setDocInfo($value)
    {
        $this->setProperty('DocInfo', $value);
        return $this;
    }

    public function getDocInfo()
    {
        $this->checkPropertyExists('DocInfo');
        return $this->DocInfo;
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

    public function setDocStatus($value)
    {
        $this->setProperty('DocStatus', $value);
        return $this;
    }

    public function getDocStatus()
    {
        $this->checkPropertyExists('DocStatus');
        return $this->DocStatus;
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

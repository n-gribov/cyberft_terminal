<?php

namespace addons\VTB\models\soap\messages\WSGetFreeBankDocsGOZ;

use addons\VTB\models\soap\messages\BaseMessage;

class GetFreeBankDocGOZRequest extends BaseMessage
{
    protected $BSError;
    protected $BSErrorCode;
    protected $CustID;
    protected $Document;
    protected $RecordID;
    protected $RemoteUser;
    protected $SignData;

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

    public function setDocument($value)
    {
        $this->setProperty('Document', $value);
        return $this;
    }

    public function getDocument()
    {
        $this->checkPropertyExists('Document');
        return $this->Document;
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

}

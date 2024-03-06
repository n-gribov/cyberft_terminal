<?php

namespace addons\VTB\models\soap\messages\WSImportDocument;

use addons\VTB\models\soap\messages\BaseMessage;

class ImportDocumentRequest extends BaseMessage
{
    protected $BSError;
    protected $BSErrorCode;
    protected $CustID;
    protected $DocData;
    protected $DocScheme;
    protected $DocVersion;
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

    public function setDocData($value)
    {
        $this->setProperty('DocData', $value);
        return $this;
    }

    public function getDocData()
    {
        $this->checkPropertyExists('DocData');
        return $this->DocData;
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

    public function setDocVersion($value)
    {
        $this->setProperty('DocVersion', $value);
        return $this;
    }

    public function getDocVersion()
    {
        $this->checkPropertyExists('DocVersion');
        return $this->DocVersion;
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

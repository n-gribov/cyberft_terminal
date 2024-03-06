<?php

namespace addons\VTB\models\soap\messages\WSGetFreeBankDocsGOZ;

use addons\VTB\models\soap\messages\BaseMessage;

class GetFreeBankDocGOZListRequest extends BaseMessage
{
    protected $BSError;
    protected $BSErrorCode;
    protected $CustID;
    protected $DocList;
    protected $DocumentDate;
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

    public function setDocList($value)
    {
        $this->setProperty('DocList', $value);
        return $this;
    }

    public function getDocList()
    {
        $this->checkPropertyExists('DocList');
        return $this->DocList;
    }

    public function setDocumentDate($value)
    {
        $this->setProperty('DocumentDate', $value);
        return $this;
    }

    public function getDocumentDate()
    {
        $this->checkPropertyExists('DocumentDate');
        return $this->DocumentDate;
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

<?php

namespace addons\VTB\models\soap\messages\WSGetDocStatus;

use addons\VTB\models\soap\messages\BaseMessage;

class GetDocStatusResponse extends BaseMessage
{
    protected $BSError;
    protected $BSErrorCode;
    protected $DocInfo;
    protected $DocStatus;

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

}

<?php

namespace addons\VTB\models\soap\messages\WSGetFreeBankDocs;

use addons\VTB\models\soap\messages\BaseMessage;

class GetFreeBankDocListResponse extends BaseMessage
{
    protected $BSError;
    protected $BSErrorCode;
    protected $DocList;

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

}

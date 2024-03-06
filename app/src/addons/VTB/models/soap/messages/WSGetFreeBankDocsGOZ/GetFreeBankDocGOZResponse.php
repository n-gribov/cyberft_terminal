<?php

namespace addons\VTB\models\soap\messages\WSGetFreeBankDocsGOZ;

use addons\VTB\models\soap\messages\BaseMessage;

class GetFreeBankDocGOZResponse extends BaseMessage
{
    protected $BSError;
    protected $BSErrorCode;
    protected $Document;
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

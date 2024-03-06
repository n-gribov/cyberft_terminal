<?php

namespace addons\VTB\models\soap\messages\WSGetStatement;

use addons\VTB\models\soap\messages\BaseMessage;

class GetStatementResponse extends BaseMessage
{
    protected $BSError;
    protected $BSErrorCode;
    protected $SignData;
    protected $StatementDoc;

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

}

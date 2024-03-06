<?php

namespace addons\VTB\models\soap\messages\WSPrepareDocForCancel;

use addons\VTB\models\soap\messages\BaseMessage;

class PrepareDocForCancelResponse extends BaseMessage
{
    protected $BSError;
    protected $BSErrorCode;
    protected $CancelFields;

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

}

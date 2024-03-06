<?php

namespace addons\VTB\models\soap\messages\WSGetCustomer;

use addons\VTB\models\soap\messages\BaseMessage;

class GetCustsAndBranchesResponse extends BaseMessage
{
    protected $BSError;
    protected $BSErrorCode;
    protected $CustsAndBranches;

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

    public function setCustsAndBranches($value)
    {
        $this->setProperty('CustsAndBranches', $value);
        return $this;
    }

    public function getCustsAndBranches()
    {
        $this->checkPropertyExists('CustsAndBranches');
        return $this->CustsAndBranches;
    }

}

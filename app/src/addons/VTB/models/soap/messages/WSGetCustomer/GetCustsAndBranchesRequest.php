<?php

namespace addons\VTB\models\soap\messages\WSGetCustomer;

use addons\VTB\models\soap\messages\BaseMessage;

class GetCustsAndBranchesRequest extends BaseMessage
{
    protected $BSError;
    protected $BSErrorCode;
    protected $CustsAndBranches;
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

<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing ChangeAccDetailsDataType
 *
 *
 * XSD Type: ChangeAccDetailsData
 */
class ChangeAccDetailsDataType extends DocBaseType
{

    /**
     * @property \common\models\sbbolxml\request\DocDataChangeAccDetailsType $docData
     */
    private $docData = null;

    /**
     * @property \common\models\sbbolxml\request\DepositType $deposit
     */
    private $deposit = null;

    /**
     * @property \common\models\sbbolxml\request\AccountRubType $docAccount
     */
    private $docAccount = null;

    /**
     * @property \common\models\sbbolxml\request\AccountReturnType $oldReqAccount
     */
    private $oldReqAccount = null;

    /**
     * @property \common\models\sbbolxml\request\AccountReturnType $reqAccount
     */
    private $reqAccount = null;

    /**
     * Gets as docData
     *
     * @return \common\models\sbbolxml\request\DocDataChangeAccDetailsType
     */
    public function getDocData()
    {
        return $this->docData;
    }

    /**
     * Sets a new docData
     *
     * @param \common\models\sbbolxml\request\DocDataChangeAccDetailsType $docData
     * @return static
     */
    public function setDocData(\common\models\sbbolxml\request\DocDataChangeAccDetailsType $docData)
    {
        $this->docData = $docData;
        return $this;
    }

    /**
     * Gets as deposit
     *
     * @return \common\models\sbbolxml\request\DepositType
     */
    public function getDeposit()
    {
        return $this->deposit;
    }

    /**
     * Sets a new deposit
     *
     * @param \common\models\sbbolxml\request\DepositType $deposit
     * @return static
     */
    public function setDeposit(\common\models\sbbolxml\request\DepositType $deposit)
    {
        $this->deposit = $deposit;
        return $this;
    }

    /**
     * Gets as docAccount
     *
     * @return \common\models\sbbolxml\request\AccountRubType
     */
    public function getDocAccount()
    {
        return $this->docAccount;
    }

    /**
     * Sets a new docAccount
     *
     * @param \common\models\sbbolxml\request\AccountRubType $docAccount
     * @return static
     */
    public function setDocAccount(\common\models\sbbolxml\request\AccountRubType $docAccount)
    {
        $this->docAccount = $docAccount;
        return $this;
    }

    /**
     * Gets as oldReqAccount
     *
     * @return \common\models\sbbolxml\request\AccountReturnType
     */
    public function getOldReqAccount()
    {
        return $this->oldReqAccount;
    }

    /**
     * Sets a new oldReqAccount
     *
     * @param \common\models\sbbolxml\request\AccountReturnType $oldReqAccount
     * @return static
     */
    public function setOldReqAccount(\common\models\sbbolxml\request\AccountReturnType $oldReqAccount)
    {
        $this->oldReqAccount = $oldReqAccount;
        return $this;
    }

    /**
     * Gets as reqAccount
     *
     * @return \common\models\sbbolxml\request\AccountReturnType
     */
    public function getReqAccount()
    {
        return $this->reqAccount;
    }

    /**
     * Sets a new reqAccount
     *
     * @param \common\models\sbbolxml\request\AccountReturnType $reqAccount
     * @return static
     */
    public function setReqAccount(\common\models\sbbolxml\request\AccountReturnType $reqAccount)
    {
        $this->reqAccount = $reqAccount;
        return $this;
    }


}


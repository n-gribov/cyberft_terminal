<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing EarlyRecallDataType
 *
 *
 * XSD Type: EarlyRecallData
 */
class EarlyRecallDataType extends DocBaseType
{

    /**
     * @property \common\models\sbbolxml\request\DocDataEarlyRecallType $docData
     */
    private $docData = null;

    /**
     * @property \common\models\sbbolxml\request\DepositType $deposit
     */
    private $deposit = null;

    /**
     * @property \common\models\sbbolxml\request\AccountReturnType $reqAccount
     */
    private $reqAccount = null;

    /**
     * Gets as docData
     *
     * @return \common\models\sbbolxml\request\DocDataEarlyRecallType
     */
    public function getDocData()
    {
        return $this->docData;
    }

    /**
     * Sets a new docData
     *
     * @param \common\models\sbbolxml\request\DocDataEarlyRecallType $docData
     * @return static
     */
    public function setDocData(\common\models\sbbolxml\request\DocDataEarlyRecallType $docData)
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


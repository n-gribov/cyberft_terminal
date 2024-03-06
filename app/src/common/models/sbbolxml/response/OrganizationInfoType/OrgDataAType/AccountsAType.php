<?php

namespace common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType;

/**
 * Class representing AccountsAType
 */
class AccountsAType
{

    /**
     * @property \common\models\sbbolxml\response\AccountRubType[] $account
     */
    private $account = array(
        
    );

    /**
     * Adds as account
     *
     * @return static
     * @param \common\models\sbbolxml\response\AccountRubType $account
     */
    public function addToAccount(\common\models\sbbolxml\response\AccountRubType $account)
    {
        $this->account[] = $account;
        return $this;
    }

    /**
     * isset account
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetAccount($index)
    {
        return isset($this->account[$index]);
    }

    /**
     * unset account
     *
     * @param scalar $index
     * @return void
     */
    public function unsetAccount($index)
    {
        unset($this->account[$index]);
    }

    /**
     * Gets as account
     *
     * @return \common\models\sbbolxml\response\AccountRubType[]
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * Sets a new account
     *
     * @param \common\models\sbbolxml\response\AccountRubType[] $account
     * @return static
     */
    public function setAccount(array $account)
    {
        $this->account = $account;
        return $this;
    }


}


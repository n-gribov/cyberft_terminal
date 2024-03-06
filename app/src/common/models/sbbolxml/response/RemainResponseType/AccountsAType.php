<?php

namespace common\models\sbbolxml\response\RemainResponseType;

/**
 * Class representing AccountsAType
 */
class AccountsAType
{

    /**
     * Информация по одному счету
     *
     * @property \common\models\sbbolxml\response\RemainResponseType\AccountsAType\AccountAType[] $account
     */
    private $account = array(
        
    );

    /**
     * Adds as account
     *
     * Информация по одному счету
     *
     * @return static
     * @param \common\models\sbbolxml\response\RemainResponseType\AccountsAType\AccountAType $account
     */
    public function addToAccount(\common\models\sbbolxml\response\RemainResponseType\AccountsAType\AccountAType $account)
    {
        $this->account[] = $account;
        return $this;
    }

    /**
     * isset account
     *
     * Информация по одному счету
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
     * Информация по одному счету
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
     * Информация по одному счету
     *
     * @return \common\models\sbbolxml\response\RemainResponseType\AccountsAType\AccountAType[]
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * Sets a new account
     *
     * Информация по одному счету
     *
     * @param \common\models\sbbolxml\response\RemainResponseType\AccountsAType\AccountAType[] $account
     * @return static
     */
    public function setAccount(array $account)
    {
        $this->account = $account;
        return $this;
    }


}


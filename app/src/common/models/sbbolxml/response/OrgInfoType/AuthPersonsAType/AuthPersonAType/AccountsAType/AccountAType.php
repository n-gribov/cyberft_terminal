<?php

namespace common\models\sbbolxml\response\OrgInfoType\AuthPersonsAType\AuthPersonAType\AccountsAType;

/**
 * Class representing AccountAType
 */
class AccountAType
{

    /**
     * Идентификатор счета
     *
     * @property string $accountId
     */
    private $accountId = null;

    /**
     * Gets as accountId
     *
     * Идентификатор счета
     *
     * @return string
     */
    public function getAccountId()
    {
        return $this->accountId;
    }

    /**
     * Sets a new accountId
     *
     * Идентификатор счета
     *
     * @param string $accountId
     * @return static
     */
    public function setAccountId($accountId)
    {
        $this->accountId = $accountId;
        return $this;
    }


}


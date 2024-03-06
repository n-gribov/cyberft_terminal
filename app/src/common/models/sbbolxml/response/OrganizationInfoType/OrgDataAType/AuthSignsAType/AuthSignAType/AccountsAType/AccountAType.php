<?php

namespace common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\AuthSignsAType\AuthSignAType\AccountsAType;

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
     * Срок полномочий
     *
     * @property \common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\AuthSignsAType\AuthSignAType\AccountsAType\AccountAType\DurationAType $duration
     */
    private $duration = null;

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

    /**
     * Gets as duration
     *
     * Срок полномочий
     *
     * @return \common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\AuthSignsAType\AuthSignAType\AccountsAType\AccountAType\DurationAType
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * Sets a new duration
     *
     * Срок полномочий
     *
     * @param \common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\AuthSignsAType\AuthSignAType\AccountsAType\AccountAType\DurationAType $duration
     * @return static
     */
    public function setDuration(\common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\AuthSignsAType\AuthSignAType\AccountsAType\AccountAType\DurationAType $duration)
    {
        $this->duration = $duration;
        return $this;
    }


}


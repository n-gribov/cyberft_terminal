<?php

namespace common\models\sbbolxml\response\OrganizationInfoType\AuthPersonsAType\AuthPersonAType;

/**
 * Class representing UserRestrictionsAType
 */
class UserRestrictionsAType
{

    /**
     * @property \common\models\sbbolxml\response\UserRestrictionType[] $userRestriction
     */
    private $userRestriction = array(
        
    );

    /**
     * Adds as userRestriction
     *
     * @return static
     * @param \common\models\sbbolxml\response\UserRestrictionType $userRestriction
     */
    public function addToUserRestriction(\common\models\sbbolxml\response\UserRestrictionType $userRestriction)
    {
        $this->userRestriction[] = $userRestriction;
        return $this;
    }

    /**
     * isset userRestriction
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetUserRestriction($index)
    {
        return isset($this->userRestriction[$index]);
    }

    /**
     * unset userRestriction
     *
     * @param scalar $index
     * @return void
     */
    public function unsetUserRestriction($index)
    {
        unset($this->userRestriction[$index]);
    }

    /**
     * Gets as userRestriction
     *
     * @return \common\models\sbbolxml\response\UserRestrictionType[]
     */
    public function getUserRestriction()
    {
        return $this->userRestriction;
    }

    /**
     * Sets a new userRestriction
     *
     * @param \common\models\sbbolxml\response\UserRestrictionType[] $userRestriction
     * @return static
     */
    public function setUserRestriction(array $userRestriction)
    {
        $this->userRestriction = $userRestriction;
        return $this;
    }


}


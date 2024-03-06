<?php

namespace common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType;

/**
 * Class representing AuthSignsAType
 */
class AuthSignsAType
{

    /**
     * Предназначение криптопрофиля
     *
     * @property \common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\AuthSignsAType\AuthSignAType[] $authSign
     */
    private $authSign = array(
        
    );

    /**
     * Adds as authSign
     *
     * Предназначение криптопрофиля
     *
     * @return static
     * @param \common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\AuthSignsAType\AuthSignAType $authSign
     */
    public function addToAuthSign(\common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\AuthSignsAType\AuthSignAType $authSign)
    {
        $this->authSign[] = $authSign;
        return $this;
    }

    /**
     * isset authSign
     *
     * Предназначение криптопрофиля
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetAuthSign($index)
    {
        return isset($this->authSign[$index]);
    }

    /**
     * unset authSign
     *
     * Предназначение криптопрофиля
     *
     * @param scalar $index
     * @return void
     */
    public function unsetAuthSign($index)
    {
        unset($this->authSign[$index]);
    }

    /**
     * Gets as authSign
     *
     * Предназначение криптопрофиля
     *
     * @return \common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\AuthSignsAType\AuthSignAType[]
     */
    public function getAuthSign()
    {
        return $this->authSign;
    }

    /**
     * Sets a new authSign
     *
     * Предназначение криптопрофиля
     *
     * @param \common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\AuthSignsAType\AuthSignAType[] $authSign
     * @return static
     */
    public function setAuthSign(array $authSign)
    {
        $this->authSign = $authSign;
        return $this;
    }


}


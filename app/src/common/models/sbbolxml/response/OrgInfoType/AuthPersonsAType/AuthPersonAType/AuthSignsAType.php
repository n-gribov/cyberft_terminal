<?php

namespace common\models\sbbolxml\response\OrgInfoType\AuthPersonsAType\AuthPersonAType;

/**
 * Class representing AuthSignsAType
 */
class AuthSignsAType
{

    /**
     * Полномочие подписи
     *
     * @property \common\models\sbbolxml\response\OrgInfoType\AuthPersonsAType\AuthPersonAType\AuthSignsAType\AuthSignAType[] $authSign
     */
    private $authSign = array(
        
    );

    /**
     * Adds as authSign
     *
     * Полномочие подписи
     *
     * @return static
     * @param \common\models\sbbolxml\response\OrgInfoType\AuthPersonsAType\AuthPersonAType\AuthSignsAType\AuthSignAType $authSign
     */
    public function addToAuthSign(\common\models\sbbolxml\response\OrgInfoType\AuthPersonsAType\AuthPersonAType\AuthSignsAType\AuthSignAType $authSign)
    {
        $this->authSign[] = $authSign;
        return $this;
    }

    /**
     * isset authSign
     *
     * Полномочие подписи
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
     * Полномочие подписи
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
     * Полномочие подписи
     *
     * @return \common\models\sbbolxml\response\OrgInfoType\AuthPersonsAType\AuthPersonAType\AuthSignsAType\AuthSignAType[]
     */
    public function getAuthSign()
    {
        return $this->authSign;
    }

    /**
     * Sets a new authSign
     *
     * Полномочие подписи
     *
     * @param \common\models\sbbolxml\response\OrgInfoType\AuthPersonsAType\AuthPersonAType\AuthSignsAType\AuthSignAType[] $authSign
     * @return static
     */
    public function setAuthSign(array $authSign)
    {
        $this->authSign = $authSign;
        return $this;
    }


}


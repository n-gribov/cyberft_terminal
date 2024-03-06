<?php

namespace common\models\raiffeisenxml\response\OrgInfoType\AuthPersonsAType\AuthPersonAType;

/**
 * Class representing AuthSignsAType
 */
class AuthSignsAType
{

    /**
     * Полномочие подписи
     *
     * @property \common\models\raiffeisenxml\response\OrgInfoType\AuthPersonsAType\AuthPersonAType\AuthSignsAType\AuthSignAType[] $authSign
     */
    private $authSign = [
        
    ];

    /**
     * Adds as authSign
     *
     * Полномочие подписи
     *
     * @return static
     * @param \common\models\raiffeisenxml\response\OrgInfoType\AuthPersonsAType\AuthPersonAType\AuthSignsAType\AuthSignAType $authSign
     */
    public function addToAuthSign(\common\models\raiffeisenxml\response\OrgInfoType\AuthPersonsAType\AuthPersonAType\AuthSignsAType\AuthSignAType $authSign)
    {
        $this->authSign[] = $authSign;
        return $this;
    }

    /**
     * isset authSign
     *
     * Полномочие подписи
     *
     * @param int|string $index
     * @return bool
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
     * @param int|string $index
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
     * @return \common\models\raiffeisenxml\response\OrgInfoType\AuthPersonsAType\AuthPersonAType\AuthSignsAType\AuthSignAType[]
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
     * @param \common\models\raiffeisenxml\response\OrgInfoType\AuthPersonsAType\AuthPersonAType\AuthSignsAType\AuthSignAType[] $authSign
     * @return static
     */
    public function setAuthSign(array $authSign)
    {
        $this->authSign = $authSign;
        return $this;
    }


}


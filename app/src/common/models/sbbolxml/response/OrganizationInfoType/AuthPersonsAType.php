<?php

namespace common\models\sbbolxml\response\OrganizationInfoType;

/**
 * Class representing AuthPersonsAType
 */
class AuthPersonsAType
{

    /**
     * Учетная запись клиента
     *
     * @property \common\models\sbbolxml\response\OrganizationInfoType\AuthPersonsAType\AuthPersonAType[] $authPerson
     */
    private $authPerson = array(
        
    );

    /**
     * Adds as authPerson
     *
     * Учетная запись клиента
     *
     * @return static
     * @param \common\models\sbbolxml\response\OrganizationInfoType\AuthPersonsAType\AuthPersonAType $authPerson
     */
    public function addToAuthPerson(\common\models\sbbolxml\response\OrganizationInfoType\AuthPersonsAType\AuthPersonAType $authPerson)
    {
        $this->authPerson[] = $authPerson;
        return $this;
    }

    /**
     * isset authPerson
     *
     * Учетная запись клиента
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetAuthPerson($index)
    {
        return isset($this->authPerson[$index]);
    }

    /**
     * unset authPerson
     *
     * Учетная запись клиента
     *
     * @param scalar $index
     * @return void
     */
    public function unsetAuthPerson($index)
    {
        unset($this->authPerson[$index]);
    }

    /**
     * Gets as authPerson
     *
     * Учетная запись клиента
     *
     * @return \common\models\sbbolxml\response\OrganizationInfoType\AuthPersonsAType\AuthPersonAType[]
     */
    public function getAuthPerson()
    {
        return $this->authPerson;
    }

    /**
     * Sets a new authPerson
     *
     * Учетная запись клиента
     *
     * @param \common\models\sbbolxml\response\OrganizationInfoType\AuthPersonsAType\AuthPersonAType[] $authPerson
     * @return static
     */
    public function setAuthPerson(array $authPerson)
    {
        $this->authPerson = $authPerson;
        return $this;
    }


}


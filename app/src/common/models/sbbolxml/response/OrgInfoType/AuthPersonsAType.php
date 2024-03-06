<?php

namespace common\models\sbbolxml\response\OrgInfoType;

/**
 * Class representing AuthPersonsAType
 */
class AuthPersonsAType
{

    /**
     * Уполномоченное лицо клиента
     *
     * @property \common\models\sbbolxml\response\OrgInfoType\AuthPersonsAType\AuthPersonAType[] $authPerson
     */
    private $authPerson = array(
        
    );

    /**
     * Adds as authPerson
     *
     * Уполномоченное лицо клиента
     *
     * @return static
     * @param \common\models\sbbolxml\response\OrgInfoType\AuthPersonsAType\AuthPersonAType $authPerson
     */
    public function addToAuthPerson(\common\models\sbbolxml\response\OrgInfoType\AuthPersonsAType\AuthPersonAType $authPerson)
    {
        $this->authPerson[] = $authPerson;
        return $this;
    }

    /**
     * isset authPerson
     *
     * Уполномоченное лицо клиента
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
     * Уполномоченное лицо клиента
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
     * Уполномоченное лицо клиента
     *
     * @return \common\models\sbbolxml\response\OrgInfoType\AuthPersonsAType\AuthPersonAType[]
     */
    public function getAuthPerson()
    {
        return $this->authPerson;
    }

    /**
     * Sets a new authPerson
     *
     * Уполномоченное лицо клиента
     *
     * @param \common\models\sbbolxml\response\OrgInfoType\AuthPersonsAType\AuthPersonAType[] $authPerson
     * @return static
     */
    public function setAuthPerson(array $authPerson)
    {
        $this->authPerson = $authPerson;
        return $this;
    }


}


<?php

namespace common\models\raiffeisenxml\response\OrgInfoType;

/**
 * Class representing AuthPersonsAType
 */
class AuthPersonsAType
{

    /**
     * Уполномоченное лицо клиента
     *
     * @property \common\models\raiffeisenxml\response\OrgInfoType\AuthPersonsAType\AuthPersonAType[] $authPerson
     */
    private $authPerson = [
        
    ];

    /**
     * Adds as authPerson
     *
     * Уполномоченное лицо клиента
     *
     * @return static
     * @param \common\models\raiffeisenxml\response\OrgInfoType\AuthPersonsAType\AuthPersonAType $authPerson
     */
    public function addToAuthPerson(\common\models\raiffeisenxml\response\OrgInfoType\AuthPersonsAType\AuthPersonAType $authPerson)
    {
        $this->authPerson[] = $authPerson;
        return $this;
    }

    /**
     * isset authPerson
     *
     * Уполномоченное лицо клиента
     *
     * @param int|string $index
     * @return bool
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
     * @param int|string $index
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
     * @return \common\models\raiffeisenxml\response\OrgInfoType\AuthPersonsAType\AuthPersonAType[]
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
     * @param \common\models\raiffeisenxml\response\OrgInfoType\AuthPersonsAType\AuthPersonAType[] $authPerson
     * @return static
     */
    public function setAuthPerson(array $authPerson)
    {
        $this->authPerson = $authPerson;
        return $this;
    }


}


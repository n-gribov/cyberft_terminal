<?php

namespace common\models\raiffeisenxml\response\OrgInfoType\OrgDataAType\OtherOrgDataAType;

/**
 * Class representing ContactsAType
 */
class ContactsAType
{

    /**
     * @property string $manager
     */
    private $manager = null;

    /**
     * @property string $managerPhone
     */
    private $managerPhone = null;

    /**
     * @property string $accountant
     */
    private $accountant = null;

    /**
     * @property string $accountantPhone
     */
    private $accountantPhone = null;

    /**
     * @property string $fax
     */
    private $fax = null;

    /**
     * @property string $email
     */
    private $email = null;

    /**
     * @property string $additionalInfo
     */
    private $additionalInfo = null;

    /**
     * Gets as manager
     *
     * @return string
     */
    public function getManager()
    {
        return $this->manager;
    }

    /**
     * Sets a new manager
     *
     * @param string $manager
     * @return static
     */
    public function setManager($manager)
    {
        $this->manager = $manager;
        return $this;
    }

    /**
     * Gets as managerPhone
     *
     * @return string
     */
    public function getManagerPhone()
    {
        return $this->managerPhone;
    }

    /**
     * Sets a new managerPhone
     *
     * @param string $managerPhone
     * @return static
     */
    public function setManagerPhone($managerPhone)
    {
        $this->managerPhone = $managerPhone;
        return $this;
    }

    /**
     * Gets as accountant
     *
     * @return string
     */
    public function getAccountant()
    {
        return $this->accountant;
    }

    /**
     * Sets a new accountant
     *
     * @param string $accountant
     * @return static
     */
    public function setAccountant($accountant)
    {
        $this->accountant = $accountant;
        return $this;
    }

    /**
     * Gets as accountantPhone
     *
     * @return string
     */
    public function getAccountantPhone()
    {
        return $this->accountantPhone;
    }

    /**
     * Sets a new accountantPhone
     *
     * @param string $accountantPhone
     * @return static
     */
    public function setAccountantPhone($accountantPhone)
    {
        $this->accountantPhone = $accountantPhone;
        return $this;
    }

    /**
     * Gets as fax
     *
     * @return string
     */
    public function getFax()
    {
        return $this->fax;
    }

    /**
     * Sets a new fax
     *
     * @param string $fax
     * @return static
     */
    public function setFax($fax)
    {
        $this->fax = $fax;
        return $this;
    }

    /**
     * Gets as email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Sets a new email
     *
     * @param string $email
     * @return static
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * Gets as additionalInfo
     *
     * @return string
     */
    public function getAdditionalInfo()
    {
        return $this->additionalInfo;
    }

    /**
     * Sets a new additionalInfo
     *
     * @param string $additionalInfo
     * @return static
     */
    public function setAdditionalInfo($additionalInfo)
    {
        $this->additionalInfo = $additionalInfo;
        return $this;
    }


}


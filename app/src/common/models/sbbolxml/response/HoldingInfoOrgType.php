<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing HoldingInfoOrgType
 *
 *
 * XSD Type: HoldingInfoOrg
 */
class HoldingInfoOrgType
{

    /**
     * OrgId ДЗО
     *
     * @property string $orgId
     */
    private $orgId = null;

    /**
     * @property string $name
     */
    private $name = null;

    /**
     * @property string $iNN
     */
    private $iNN = null;

    /**
     * @property string $bIC
     */
    private $bIC = null;

    /**
     * Счета ДЗО, прикрепляемые к головной компании холдинга
     *
     * @property string[] $accounts
     */
    private $accounts = null;

    /**
     * Gets as orgId
     *
     * OrgId ДЗО
     *
     * @return string
     */
    public function getOrgId()
    {
        return $this->orgId;
    }

    /**
     * Sets a new orgId
     *
     * OrgId ДЗО
     *
     * @param string $orgId
     * @return static
     */
    public function setOrgId($orgId)
    {
        $this->orgId = $orgId;
        return $this;
    }

    /**
     * Gets as name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets a new name
     *
     * @param string $name
     * @return static
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Gets as iNN
     *
     * @return string
     */
    public function getINN()
    {
        return $this->iNN;
    }

    /**
     * Sets a new iNN
     *
     * @param string $iNN
     * @return static
     */
    public function setINN($iNN)
    {
        $this->iNN = $iNN;
        return $this;
    }

    /**
     * Gets as bIC
     *
     * @return string
     */
    public function getBIC()
    {
        return $this->bIC;
    }

    /**
     * Sets a new bIC
     *
     * @param string $bIC
     * @return static
     */
    public function setBIC($bIC)
    {
        $this->bIC = $bIC;
        return $this;
    }

    /**
     * Adds as account
     *
     * Счета ДЗО, прикрепляемые к головной компании холдинга
     *
     * @return static
     * @param string $account
     */
    public function addToAccounts($account)
    {
        $this->accounts[] = $account;
        return $this;
    }

    /**
     * isset accounts
     *
     * Счета ДЗО, прикрепляемые к головной компании холдинга
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetAccounts($index)
    {
        return isset($this->accounts[$index]);
    }

    /**
     * unset accounts
     *
     * Счета ДЗО, прикрепляемые к головной компании холдинга
     *
     * @param scalar $index
     * @return void
     */
    public function unsetAccounts($index)
    {
        unset($this->accounts[$index]);
    }

    /**
     * Gets as accounts
     *
     * Счета ДЗО, прикрепляемые к головной компании холдинга
     *
     * @return string[]
     */
    public function getAccounts()
    {
        return $this->accounts;
    }

    /**
     * Sets a new accounts
     *
     * Счета ДЗО, прикрепляемые к головной компании холдинга
     *
     * @param string $accounts
     * @return static
     */
    public function setAccounts(array $accounts)
    {
        $this->accounts = $accounts;
        return $this;
    }


}


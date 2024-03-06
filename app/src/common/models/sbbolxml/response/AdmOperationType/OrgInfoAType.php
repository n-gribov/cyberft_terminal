<?php

namespace common\models\sbbolxml\response\AdmOperationType;

/**
 * Class representing OrgInfoAType
 */
class OrgInfoAType
{

    /**
     * Наименование организации
     *
     * @property string $orgName
     */
    private $orgName = null;

    /**
     * БИК счета
     *
     * @property string $payeeBic
     */
    private $payeeBic = null;

    /**
     * Номер счета
     *
     * @property string $payeeAccount
     */
    private $payeeAccount = null;

    /**
     * Gets as orgName
     *
     * Наименование организации
     *
     * @return string
     */
    public function getOrgName()
    {
        return $this->orgName;
    }

    /**
     * Sets a new orgName
     *
     * Наименование организации
     *
     * @param string $orgName
     * @return static
     */
    public function setOrgName($orgName)
    {
        $this->orgName = $orgName;
        return $this;
    }

    /**
     * Gets as payeeBic
     *
     * БИК счета
     *
     * @return string
     */
    public function getPayeeBic()
    {
        return $this->payeeBic;
    }

    /**
     * Sets a new payeeBic
     *
     * БИК счета
     *
     * @param string $payeeBic
     * @return static
     */
    public function setPayeeBic($payeeBic)
    {
        $this->payeeBic = $payeeBic;
        return $this;
    }

    /**
     * Gets as payeeAccount
     *
     * Номер счета
     *
     * @return string
     */
    public function getPayeeAccount()
    {
        return $this->payeeAccount;
    }

    /**
     * Sets a new payeeAccount
     *
     * Номер счета
     *
     * @param string $payeeAccount
     * @return static
     */
    public function setPayeeAccount($payeeAccount)
    {
        $this->payeeAccount = $payeeAccount;
        return $this;
    }


}


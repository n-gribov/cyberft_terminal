<?php

namespace common\models\sbbolxml\response\OrganizationInfoType;

/**
 * Class representing ElectionAccountsAType
 */
class ElectionAccountsAType
{

    /**
     * Избирательный счет
     *
     * @property \common\models\sbbolxml\response\OrganizationInfoType\ElectionAccountsAType\ElectionAccountAType[] $electionAccount
     */
    private $electionAccount = array(
        
    );

    /**
     * Adds as electionAccount
     *
     * Избирательный счет
     *
     * @return static
     * @param \common\models\sbbolxml\response\OrganizationInfoType\ElectionAccountsAType\ElectionAccountAType $electionAccount
     */
    public function addToElectionAccount(\common\models\sbbolxml\response\OrganizationInfoType\ElectionAccountsAType\ElectionAccountAType $electionAccount)
    {
        $this->electionAccount[] = $electionAccount;
        return $this;
    }

    /**
     * isset electionAccount
     *
     * Избирательный счет
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetElectionAccount($index)
    {
        return isset($this->electionAccount[$index]);
    }

    /**
     * unset electionAccount
     *
     * Избирательный счет
     *
     * @param scalar $index
     * @return void
     */
    public function unsetElectionAccount($index)
    {
        unset($this->electionAccount[$index]);
    }

    /**
     * Gets as electionAccount
     *
     * Избирательный счет
     *
     * @return \common\models\sbbolxml\response\OrganizationInfoType\ElectionAccountsAType\ElectionAccountAType[]
     */
    public function getElectionAccount()
    {
        return $this->electionAccount;
    }

    /**
     * Sets a new electionAccount
     *
     * Избирательный счет
     *
     * @param \common\models\sbbolxml\response\OrganizationInfoType\ElectionAccountsAType\ElectionAccountAType[] $electionAccount
     * @return static
     */
    public function setElectionAccount(array $electionAccount)
    {
        $this->electionAccount = $electionAccount;
        return $this;
    }


}


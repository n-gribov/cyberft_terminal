<?php

namespace common\models\sbbolxml\response\OrgInfoType;

/**
 * Class representing BranchesAType
 */
class BranchesAType
{

    /**
     * Информация о подразделении, где обслуживается клиент и обо всех
     *  вышестоящих
     *  (родительских)
     *  подразделениях
     *
     * @property \common\models\sbbolxml\response\OrgInfoType\BranchesAType\BranchAType[] $branch
     */
    private $branch = array(
        
    );

    /**
     * Adds as branch
     *
     * Информация о подразделении, где обслуживается клиент и обо всех
     *  вышестоящих
     *  (родительских)
     *  подразделениях
     *
     * @return static
     * @param \common\models\sbbolxml\response\OrgInfoType\BranchesAType\BranchAType $branch
     */
    public function addToBranch(\common\models\sbbolxml\response\OrgInfoType\BranchesAType\BranchAType $branch)
    {
        $this->branch[] = $branch;
        return $this;
    }

    /**
     * isset branch
     *
     * Информация о подразделении, где обслуживается клиент и обо всех
     *  вышестоящих
     *  (родительских)
     *  подразделениях
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetBranch($index)
    {
        return isset($this->branch[$index]);
    }

    /**
     * unset branch
     *
     * Информация о подразделении, где обслуживается клиент и обо всех
     *  вышестоящих
     *  (родительских)
     *  подразделениях
     *
     * @param scalar $index
     * @return void
     */
    public function unsetBranch($index)
    {
        unset($this->branch[$index]);
    }

    /**
     * Gets as branch
     *
     * Информация о подразделении, где обслуживается клиент и обо всех
     *  вышестоящих
     *  (родительских)
     *  подразделениях
     *
     * @return \common\models\sbbolxml\response\OrgInfoType\BranchesAType\BranchAType[]
     */
    public function getBranch()
    {
        return $this->branch;
    }

    /**
     * Sets a new branch
     *
     * Информация о подразделении, где обслуживается клиент и обо всех
     *  вышестоящих
     *  (родительских)
     *  подразделениях
     *
     * @param \common\models\sbbolxml\response\OrgInfoType\BranchesAType\BranchAType[] $branch
     * @return static
     */
    public function setBranch(array $branch)
    {
        $this->branch = $branch;
        return $this;
    }


}


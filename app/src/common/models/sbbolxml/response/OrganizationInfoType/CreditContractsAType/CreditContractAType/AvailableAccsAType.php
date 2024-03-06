<?php

namespace common\models\sbbolxml\response\OrganizationInfoType\CreditContractsAType\CreditContractAType;

/**
 * Class representing AvailableAccsAType
 */
class AvailableAccsAType
{

    /**
     * @property string $accNum
     */
    private $accNum = null;

    /**
     * Gets as accNum
     *
     * @return string
     */
    public function getAccNum()
    {
        return $this->accNum;
    }

    /**
     * Sets a new accNum
     *
     * @param string $accNum
     * @return static
     */
    public function setAccNum($accNum)
    {
        $this->accNum = $accNum;
        return $this;
    }


}


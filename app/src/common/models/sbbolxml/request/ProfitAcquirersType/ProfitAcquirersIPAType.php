<?php

namespace common\models\sbbolxml\request\ProfitAcquirersType;

/**
 * Class representing ProfitAcquirersIPAType
 */
class ProfitAcquirersIPAType
{

    /**
     * @property \common\models\sbbolxml\request\ProfitAcquirerIPType[] $profitAcquirerIP
     */
    private $profitAcquirerIP = array(
        
    );

    /**
     * Adds as profitAcquirerIP
     *
     * @return static
     * @param \common\models\sbbolxml\request\ProfitAcquirerIPType $profitAcquirerIP
     */
    public function addToProfitAcquirerIP(\common\models\sbbolxml\request\ProfitAcquirerIPType $profitAcquirerIP)
    {
        $this->profitAcquirerIP[] = $profitAcquirerIP;
        return $this;
    }

    /**
     * isset profitAcquirerIP
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetProfitAcquirerIP($index)
    {
        return isset($this->profitAcquirerIP[$index]);
    }

    /**
     * unset profitAcquirerIP
     *
     * @param scalar $index
     * @return void
     */
    public function unsetProfitAcquirerIP($index)
    {
        unset($this->profitAcquirerIP[$index]);
    }

    /**
     * Gets as profitAcquirerIP
     *
     * @return \common\models\sbbolxml\request\ProfitAcquirerIPType[]
     */
    public function getProfitAcquirerIP()
    {
        return $this->profitAcquirerIP;
    }

    /**
     * Sets a new profitAcquirerIP
     *
     * @param \common\models\sbbolxml\request\ProfitAcquirerIPType[] $profitAcquirerIP
     * @return static
     */
    public function setProfitAcquirerIP(array $profitAcquirerIP)
    {
        $this->profitAcquirerIP = $profitAcquirerIP;
        return $this;
    }


}


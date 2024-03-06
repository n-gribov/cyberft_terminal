<?php

namespace common\models\sbbolxml\request\ProfitAcquirersType;

/**
 * Class representing ProfitAcquirersULAType
 */
class ProfitAcquirersULAType
{

    /**
     * @property \common\models\sbbolxml\request\ProfitAcquirerULType[] $profitAcquirerUL
     */
    private $profitAcquirerUL = array(
        
    );

    /**
     * Adds as profitAcquirerUL
     *
     * @return static
     * @param \common\models\sbbolxml\request\ProfitAcquirerULType $profitAcquirerUL
     */
    public function addToProfitAcquirerUL(\common\models\sbbolxml\request\ProfitAcquirerULType $profitAcquirerUL)
    {
        $this->profitAcquirerUL[] = $profitAcquirerUL;
        return $this;
    }

    /**
     * isset profitAcquirerUL
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetProfitAcquirerUL($index)
    {
        return isset($this->profitAcquirerUL[$index]);
    }

    /**
     * unset profitAcquirerUL
     *
     * @param scalar $index
     * @return void
     */
    public function unsetProfitAcquirerUL($index)
    {
        unset($this->profitAcquirerUL[$index]);
    }

    /**
     * Gets as profitAcquirerUL
     *
     * @return \common\models\sbbolxml\request\ProfitAcquirerULType[]
     */
    public function getProfitAcquirerUL()
    {
        return $this->profitAcquirerUL;
    }

    /**
     * Sets a new profitAcquirerUL
     *
     * @param \common\models\sbbolxml\request\ProfitAcquirerULType[] $profitAcquirerUL
     * @return static
     */
    public function setProfitAcquirerUL(array $profitAcquirerUL)
    {
        $this->profitAcquirerUL = $profitAcquirerUL;
        return $this;
    }


}


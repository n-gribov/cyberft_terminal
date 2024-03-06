<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing ProfitAcquirersType
 *
 * Выгодоприобретатели
 *  True, если выбрано «Есть», False, если выбрано «Нет»
 * XSD Type: ProfitAcquirers
 */
class ProfitAcquirersType
{

    /**
     * @property \common\models\sbbolxml\request\ProfitAcquirerULType[] $profitAcquirersUL
     */
    private $profitAcquirersUL = null;

    /**
     * @property \common\models\sbbolxml\request\ProfitAcquirerIPType[] $profitAcquirersIP
     */
    private $profitAcquirersIP = null;

    /**
     * Adds as profitAcquirerUL
     *
     * @return static
     * @param \common\models\sbbolxml\request\ProfitAcquirerULType $profitAcquirerUL
     */
    public function addToProfitAcquirersUL(\common\models\sbbolxml\request\ProfitAcquirerULType $profitAcquirerUL)
    {
        $this->profitAcquirersUL[] = $profitAcquirerUL;
        return $this;
    }

    /**
     * isset profitAcquirersUL
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetProfitAcquirersUL($index)
    {
        return isset($this->profitAcquirersUL[$index]);
    }

    /**
     * unset profitAcquirersUL
     *
     * @param scalar $index
     * @return void
     */
    public function unsetProfitAcquirersUL($index)
    {
        unset($this->profitAcquirersUL[$index]);
    }

    /**
     * Gets as profitAcquirersUL
     *
     * @return \common\models\sbbolxml\request\ProfitAcquirerULType[]
     */
    public function getProfitAcquirersUL()
    {
        return $this->profitAcquirersUL;
    }

    /**
     * Sets a new profitAcquirersUL
     *
     * @param \common\models\sbbolxml\request\ProfitAcquirerULType[] $profitAcquirersUL
     * @return static
     */
    public function setProfitAcquirersUL(array $profitAcquirersUL)
    {
        $this->profitAcquirersUL = $profitAcquirersUL;
        return $this;
    }

    /**
     * Adds as profitAcquirerIP
     *
     * @return static
     * @param \common\models\sbbolxml\request\ProfitAcquirerIPType $profitAcquirerIP
     */
    public function addToProfitAcquirersIP(\common\models\sbbolxml\request\ProfitAcquirerIPType $profitAcquirerIP)
    {
        $this->profitAcquirersIP[] = $profitAcquirerIP;
        return $this;
    }

    /**
     * isset profitAcquirersIP
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetProfitAcquirersIP($index)
    {
        return isset($this->profitAcquirersIP[$index]);
    }

    /**
     * unset profitAcquirersIP
     *
     * @param scalar $index
     * @return void
     */
    public function unsetProfitAcquirersIP($index)
    {
        unset($this->profitAcquirersIP[$index]);
    }

    /**
     * Gets as profitAcquirersIP
     *
     * @return \common\models\sbbolxml\request\ProfitAcquirerIPType[]
     */
    public function getProfitAcquirersIP()
    {
        return $this->profitAcquirersIP;
    }

    /**
     * Sets a new profitAcquirersIP
     *
     * @param \common\models\sbbolxml\request\ProfitAcquirerIPType[] $profitAcquirersIP
     * @return static
     */
    public function setProfitAcquirersIP(array $profitAcquirersIP)
    {
        $this->profitAcquirersIP = $profitAcquirersIP;
        return $this;
    }


}


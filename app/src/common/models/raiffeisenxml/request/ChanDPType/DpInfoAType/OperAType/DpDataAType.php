<?php

namespace common\models\raiffeisenxml\request\ChanDPType\DpInfoAType\OperAType;

/**
 * Class representing DpDataAType
 */
class DpDataAType
{

    /**
     * Номер строки по порядку
     *
     * @property string $strNum
     */
    private $strNum = null;

    /**
     * Номер ПС
     *
     * @property string $dpNum
     */
    private $dpNum = null;

    /**
     * Дата ПС
     *
     * @property \DateTime $dpDate
     */
    private $dpDate = null;

    /**
     * @property \common\models\raiffeisenxml\request\ContractReissueType $contract
     */
    private $contract = null;

    /**
     * Gets as strNum
     *
     * Номер строки по порядку
     *
     * @return string
     */
    public function getStrNum()
    {
        return $this->strNum;
    }

    /**
     * Sets a new strNum
     *
     * Номер строки по порядку
     *
     * @param string $strNum
     * @return static
     */
    public function setStrNum($strNum)
    {
        $this->strNum = $strNum;
        return $this;
    }

    /**
     * Gets as dpNum
     *
     * Номер ПС
     *
     * @return string
     */
    public function getDpNum()
    {
        return $this->dpNum;
    }

    /**
     * Sets a new dpNum
     *
     * Номер ПС
     *
     * @param string $dpNum
     * @return static
     */
    public function setDpNum($dpNum)
    {
        $this->dpNum = $dpNum;
        return $this;
    }

    /**
     * Gets as dpDate
     *
     * Дата ПС
     *
     * @return \DateTime
     */
    public function getDpDate()
    {
        return $this->dpDate;
    }

    /**
     * Sets a new dpDate
     *
     * Дата ПС
     *
     * @param \DateTime $dpDate
     * @return static
     */
    public function setDpDate(\DateTime $dpDate)
    {
        $this->dpDate = $dpDate;
        return $this;
    }

    /**
     * Gets as contract
     *
     * @return \common\models\raiffeisenxml\request\ContractReissueType
     */
    public function getContract()
    {
        return $this->contract;
    }

    /**
     * Sets a new contract
     *
     * @param \common\models\raiffeisenxml\request\ContractReissueType $contract
     * @return static
     */
    public function setContract(\common\models\raiffeisenxml\request\ContractReissueType $contract)
    {
        $this->contract = $contract;
        return $this;
    }


}


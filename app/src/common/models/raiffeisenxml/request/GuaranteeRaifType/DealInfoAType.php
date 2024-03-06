<?php

namespace common\models\raiffeisenxml\request\GuaranteeRaifType;

/**
 * Class representing DealInfoAType
 */
class DealInfoAType
{

    /**
     * Основание для выдачи гарантии
     *
     * @property \common\models\raiffeisenxml\request\GuaranteeRaifType\DealInfoAType\GroundsAType\GroundAType[] $grounds
     */
    private $grounds = null;

    /**
     * Сведения о контракте
     *
     * @property \common\models\raiffeisenxml\request\GuaranteeRaifType\DealInfoAType\ContractInfoAType $contractInfo
     */
    private $contractInfo = null;

    /**
     * Adds as ground
     *
     * Основание для выдачи гарантии
     *
     * @return static
     * @param \common\models\raiffeisenxml\request\GuaranteeRaifType\DealInfoAType\GroundsAType\GroundAType $ground
     */
    public function addToGrounds(\common\models\raiffeisenxml\request\GuaranteeRaifType\DealInfoAType\GroundsAType\GroundAType $ground)
    {
        $this->grounds[] = $ground;
        return $this;
    }

    /**
     * isset grounds
     *
     * Основание для выдачи гарантии
     *
     * @param int|string $index
     * @return bool
     */
    public function issetGrounds($index)
    {
        return isset($this->grounds[$index]);
    }

    /**
     * unset grounds
     *
     * Основание для выдачи гарантии
     *
     * @param int|string $index
     * @return void
     */
    public function unsetGrounds($index)
    {
        unset($this->grounds[$index]);
    }

    /**
     * Gets as grounds
     *
     * Основание для выдачи гарантии
     *
     * @return \common\models\raiffeisenxml\request\GuaranteeRaifType\DealInfoAType\GroundsAType\GroundAType[]
     */
    public function getGrounds()
    {
        return $this->grounds;
    }

    /**
     * Sets a new grounds
     *
     * Основание для выдачи гарантии
     *
     * @param \common\models\raiffeisenxml\request\GuaranteeRaifType\DealInfoAType\GroundsAType\GroundAType[] $grounds
     * @return static
     */
    public function setGrounds(array $grounds)
    {
        $this->grounds = $grounds;
        return $this;
    }

    /**
     * Gets as contractInfo
     *
     * Сведения о контракте
     *
     * @return \common\models\raiffeisenxml\request\GuaranteeRaifType\DealInfoAType\ContractInfoAType
     */
    public function getContractInfo()
    {
        return $this->contractInfo;
    }

    /**
     * Sets a new contractInfo
     *
     * Сведения о контракте
     *
     * @param \common\models\raiffeisenxml\request\GuaranteeRaifType\DealInfoAType\ContractInfoAType $contractInfo
     * @return static
     */
    public function setContractInfo(\common\models\raiffeisenxml\request\GuaranteeRaifType\DealInfoAType\ContractInfoAType $contractInfo)
    {
        $this->contractInfo = $contractInfo;
        return $this;
    }


}


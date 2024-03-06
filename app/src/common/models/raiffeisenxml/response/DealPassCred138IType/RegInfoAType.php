<?php

namespace common\models\raiffeisenxml\response\DealPassCred138IType;

/**
 * Class representing RegInfoAType
 */
class RegInfoAType
{

    /**
     * @property \common\models\raiffeisenxml\response\DealPassCred138IType\RegInfoAType\RegAType[] $reg
     */
    private $reg = [
        
    ];

    /**
     * Adds as reg
     *
     * @return static
     * @param \common\models\raiffeisenxml\response\DealPassCred138IType\RegInfoAType\RegAType $reg
     */
    public function addToReg(\common\models\raiffeisenxml\response\DealPassCred138IType\RegInfoAType\RegAType $reg)
    {
        $this->reg[] = $reg;
        return $this;
    }

    /**
     * isset reg
     *
     * @param int|string $index
     * @return bool
     */
    public function issetReg($index)
    {
        return isset($this->reg[$index]);
    }

    /**
     * unset reg
     *
     * @param int|string $index
     * @return void
     */
    public function unsetReg($index)
    {
        unset($this->reg[$index]);
    }

    /**
     * Gets as reg
     *
     * @return \common\models\raiffeisenxml\response\DealPassCred138IType\RegInfoAType\RegAType[]
     */
    public function getReg()
    {
        return $this->reg;
    }

    /**
     * Sets a new reg
     *
     * @param \common\models\raiffeisenxml\response\DealPassCred138IType\RegInfoAType\RegAType[] $reg
     * @return static
     */
    public function setReg(array $reg)
    {
        $this->reg = $reg;
        return $this;
    }


}


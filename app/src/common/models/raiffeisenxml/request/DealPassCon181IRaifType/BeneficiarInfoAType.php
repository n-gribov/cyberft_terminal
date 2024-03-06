<?php

namespace common\models\raiffeisenxml\request\DealPassCon181IRaifType;

/**
 * Class representing BeneficiarInfoAType
 */
class BeneficiarInfoAType
{

    /**
     * Реквизиты иностранного контрагента
     *
     * @property \common\models\raiffeisenxml\request\BeneficiarInfoDPRaifType[] $beneficiar
     */
    private $beneficiar = [
        
    ];

    /**
     * Adds as beneficiar
     *
     * Реквизиты иностранного контрагента
     *
     * @return static
     * @param \common\models\raiffeisenxml\request\BeneficiarInfoDPRaifType $beneficiar
     */
    public function addToBeneficiar(\common\models\raiffeisenxml\request\BeneficiarInfoDPRaifType $beneficiar)
    {
        $this->beneficiar[] = $beneficiar;
        return $this;
    }

    /**
     * isset beneficiar
     *
     * Реквизиты иностранного контрагента
     *
     * @param int|string $index
     * @return bool
     */
    public function issetBeneficiar($index)
    {
        return isset($this->beneficiar[$index]);
    }

    /**
     * unset beneficiar
     *
     * Реквизиты иностранного контрагента
     *
     * @param int|string $index
     * @return void
     */
    public function unsetBeneficiar($index)
    {
        unset($this->beneficiar[$index]);
    }

    /**
     * Gets as beneficiar
     *
     * Реквизиты иностранного контрагента
     *
     * @return \common\models\raiffeisenxml\request\BeneficiarInfoDPRaifType[]
     */
    public function getBeneficiar()
    {
        return $this->beneficiar;
    }

    /**
     * Sets a new beneficiar
     *
     * Реквизиты иностранного контрагента
     *
     * @param \common\models\raiffeisenxml\request\BeneficiarInfoDPRaifType[] $beneficiar
     * @return static
     */
    public function setBeneficiar(array $beneficiar)
    {
        $this->beneficiar = $beneficiar;
        return $this;
    }


}


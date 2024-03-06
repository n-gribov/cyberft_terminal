<?php

namespace common\models\raiffeisenxml\request\PayDocCurRaifType;

/**
 * Class representing VoSumInfoAType
 */
class VoSumInfoAType
{

    /**
     * @property \common\models\raiffeisenxml\request\PayDocCurRaifType\VoSumInfoAType\VoSumAType[] $voSum
     */
    private $voSum = [
        
    ];

    /**
     * Adds as voSum
     *
     * @return static
     * @param \common\models\raiffeisenxml\request\PayDocCurRaifType\VoSumInfoAType\VoSumAType $voSum
     */
    public function addToVoSum(\common\models\raiffeisenxml\request\PayDocCurRaifType\VoSumInfoAType\VoSumAType $voSum)
    {
        $this->voSum[] = $voSum;
        return $this;
    }

    /**
     * isset voSum
     *
     * @param int|string $index
     * @return bool
     */
    public function issetVoSum($index)
    {
        return isset($this->voSum[$index]);
    }

    /**
     * unset voSum
     *
     * @param int|string $index
     * @return void
     */
    public function unsetVoSum($index)
    {
        unset($this->voSum[$index]);
    }

    /**
     * Gets as voSum
     *
     * @return \common\models\raiffeisenxml\request\PayDocCurRaifType\VoSumInfoAType\VoSumAType[]
     */
    public function getVoSum()
    {
        return $this->voSum;
    }

    /**
     * Sets a new voSum
     *
     * @param \common\models\raiffeisenxml\request\PayDocCurRaifType\VoSumInfoAType\VoSumAType[] $voSum
     * @return static
     */
    public function setVoSum(array $voSum)
    {
        $this->voSum = $voSum;
        return $this;
    }


}


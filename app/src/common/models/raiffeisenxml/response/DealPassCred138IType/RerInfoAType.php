<?php

namespace common\models\raiffeisenxml\response\DealPassCred138IType;

/**
 * Class representing RerInfoAType
 */
class RerInfoAType
{

    /**
     * @property \common\models\raiffeisenxml\response\DealPassCred138IType\RerInfoAType\RerAType[] $rer
     */
    private $rer = [
        
    ];

    /**
     * Adds as rer
     *
     * @return static
     * @param \common\models\raiffeisenxml\response\DealPassCred138IType\RerInfoAType\RerAType $rer
     */
    public function addToRer(\common\models\raiffeisenxml\response\DealPassCred138IType\RerInfoAType\RerAType $rer)
    {
        $this->rer[] = $rer;
        return $this;
    }

    /**
     * isset rer
     *
     * @param int|string $index
     * @return bool
     */
    public function issetRer($index)
    {
        return isset($this->rer[$index]);
    }

    /**
     * unset rer
     *
     * @param int|string $index
     * @return void
     */
    public function unsetRer($index)
    {
        unset($this->rer[$index]);
    }

    /**
     * Gets as rer
     *
     * @return \common\models\raiffeisenxml\response\DealPassCred138IType\RerInfoAType\RerAType[]
     */
    public function getRer()
    {
        return $this->rer;
    }

    /**
     * Sets a new rer
     *
     * @param \common\models\raiffeisenxml\response\DealPassCred138IType\RerInfoAType\RerAType[] $rer
     * @return static
     */
    public function setRer(array $rer)
    {
        $this->rer = $rer;
        return $this;
    }


}


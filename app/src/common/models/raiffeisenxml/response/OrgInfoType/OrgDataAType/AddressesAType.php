<?php

namespace common\models\raiffeisenxml\response\OrgInfoType\OrgDataAType;

/**
 * Class representing AddressesAType
 */
class AddressesAType
{

    /**
     * @property \common\models\raiffeisenxml\response\OrgInfoType\OrgDataAType\AddressesAType\AddressAType[] $address
     */
    private $address = [
        
    ];

    /**
     * Adds as address
     *
     * @return static
     * @param \common\models\raiffeisenxml\response\OrgInfoType\OrgDataAType\AddressesAType\AddressAType $address
     */
    public function addToAddress(\common\models\raiffeisenxml\response\OrgInfoType\OrgDataAType\AddressesAType\AddressAType $address)
    {
        $this->address[] = $address;
        return $this;
    }

    /**
     * isset address
     *
     * @param int|string $index
     * @return bool
     */
    public function issetAddress($index)
    {
        return isset($this->address[$index]);
    }

    /**
     * unset address
     *
     * @param int|string $index
     * @return void
     */
    public function unsetAddress($index)
    {
        unset($this->address[$index]);
    }

    /**
     * Gets as address
     *
     * @return \common\models\raiffeisenxml\response\OrgInfoType\OrgDataAType\AddressesAType\AddressAType[]
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Sets a new address
     *
     * @param \common\models\raiffeisenxml\response\OrgInfoType\OrgDataAType\AddressesAType\AddressAType[] $address
     * @return static
     */
    public function setAddress(array $address)
    {
        $this->address = $address;
        return $this;
    }


}


<?php

namespace common\models\raiffeisenxml\response\OrgInfoType\BranchesAType\BranchAType;

/**
 * Class representing AddressesAType
 */
class AddressesAType
{

    /**
     * Адрес подразделения
     *
     * @property \common\models\raiffeisenxml\response\OrgInfoType\BranchesAType\BranchAType\AddressesAType\AddressAType[] $address
     */
    private $address = [
        
    ];

    /**
     * Adds as address
     *
     * Адрес подразделения
     *
     * @return static
     * @param \common\models\raiffeisenxml\response\OrgInfoType\BranchesAType\BranchAType\AddressesAType\AddressAType $address
     */
    public function addToAddress(\common\models\raiffeisenxml\response\OrgInfoType\BranchesAType\BranchAType\AddressesAType\AddressAType $address)
    {
        $this->address[] = $address;
        return $this;
    }

    /**
     * isset address
     *
     * Адрес подразделения
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
     * Адрес подразделения
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
     * Адрес подразделения
     *
     * @return \common\models\raiffeisenxml\response\OrgInfoType\BranchesAType\BranchAType\AddressesAType\AddressAType[]
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Sets a new address
     *
     * Адрес подразделения
     *
     * @param \common\models\raiffeisenxml\response\OrgInfoType\BranchesAType\BranchAType\AddressesAType\AddressAType[] $address
     * @return static
     */
    public function setAddress(array $address)
    {
        $this->address = $address;
        return $this;
    }


}


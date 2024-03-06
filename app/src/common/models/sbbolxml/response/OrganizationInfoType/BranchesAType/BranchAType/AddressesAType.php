<?php

namespace common\models\sbbolxml\response\OrganizationInfoType\BranchesAType\BranchAType;

/**
 * Class representing AddressesAType
 */
class AddressesAType
{

    /**
     * Адрес подразделения
     *
     * @property \common\models\sbbolxml\response\OrganizationInfoType\BranchesAType\BranchAType\AddressesAType\AddressAType[] $address
     */
    private $address = array(
        
    );

    /**
     * Adds as address
     *
     * Адрес подразделения
     *
     * @return static
     * @param \common\models\sbbolxml\response\OrganizationInfoType\BranchesAType\BranchAType\AddressesAType\AddressAType $address
     */
    public function addToAddress(\common\models\sbbolxml\response\OrganizationInfoType\BranchesAType\BranchAType\AddressesAType\AddressAType $address)
    {
        $this->address[] = $address;
        return $this;
    }

    /**
     * isset address
     *
     * Адрес подразделения
     *
     * @param scalar $index
     * @return boolean
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
     * @param scalar $index
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
     * @return \common\models\sbbolxml\response\OrganizationInfoType\BranchesAType\BranchAType\AddressesAType\AddressAType[]
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
     * @param \common\models\sbbolxml\response\OrganizationInfoType\BranchesAType\BranchAType\AddressesAType\AddressAType[] $address
     * @return static
     */
    public function setAddress(array $address)
    {
        $this->address = $address;
        return $this;
    }


}


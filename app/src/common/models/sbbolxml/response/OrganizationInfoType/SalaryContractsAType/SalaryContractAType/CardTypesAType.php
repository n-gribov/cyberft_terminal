<?php

namespace common\models\sbbolxml\response\OrganizationInfoType\SalaryContractsAType\SalaryContractAType;

/**
 * Class representing CardTypesAType
 */
class CardTypesAType
{

    /**
     * Допустимый тип карты
     *
     * @property \common\models\sbbolxml\response\OrganizationInfoType\SalaryContractsAType\SalaryContractAType\CardTypesAType\CardTypeAType[] $cardType
     */
    private $cardType = array(
        
    );

    /**
     * Adds as cardType
     *
     * Допустимый тип карты
     *
     * @return static
     * @param \common\models\sbbolxml\response\OrganizationInfoType\SalaryContractsAType\SalaryContractAType\CardTypesAType\CardTypeAType $cardType
     */
    public function addToCardType(\common\models\sbbolxml\response\OrganizationInfoType\SalaryContractsAType\SalaryContractAType\CardTypesAType\CardTypeAType $cardType)
    {
        $this->cardType[] = $cardType;
        return $this;
    }

    /**
     * isset cardType
     *
     * Допустимый тип карты
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetCardType($index)
    {
        return isset($this->cardType[$index]);
    }

    /**
     * unset cardType
     *
     * Допустимый тип карты
     *
     * @param scalar $index
     * @return void
     */
    public function unsetCardType($index)
    {
        unset($this->cardType[$index]);
    }

    /**
     * Gets as cardType
     *
     * Допустимый тип карты
     *
     * @return \common\models\sbbolxml\response\OrganizationInfoType\SalaryContractsAType\SalaryContractAType\CardTypesAType\CardTypeAType[]
     */
    public function getCardType()
    {
        return $this->cardType;
    }

    /**
     * Sets a new cardType
     *
     * Допустимый тип карты
     *
     * @param \common\models\sbbolxml\response\OrganizationInfoType\SalaryContractsAType\SalaryContractAType\CardTypesAType\CardTypeAType[] $cardType
     * @return static
     */
    public function setCardType(array $cardType)
    {
        $this->cardType = $cardType;
        return $this;
    }


}


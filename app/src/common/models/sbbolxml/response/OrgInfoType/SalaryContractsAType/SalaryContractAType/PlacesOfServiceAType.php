<?php

namespace common\models\sbbolxml\response\OrgInfoType\SalaryContractsAType\SalaryContractAType;

/**
 * Class representing PlacesOfServiceAType
 */
class PlacesOfServiceAType
{

    /**
     * Место обслуживания физ. лица
     *
     * @property \common\models\sbbolxml\response\OrgInfoType\SalaryContractsAType\SalaryContractAType\PlacesOfServiceAType\PlaceOfServiceAType[] $placeOfService
     */
    private $placeOfService = array(
        
    );

    /**
     * Adds as placeOfService
     *
     * Место обслуживания физ. лица
     *
     * @return static
     * @param \common\models\sbbolxml\response\OrgInfoType\SalaryContractsAType\SalaryContractAType\PlacesOfServiceAType\PlaceOfServiceAType $placeOfService
     */
    public function addToPlaceOfService(\common\models\sbbolxml\response\OrgInfoType\SalaryContractsAType\SalaryContractAType\PlacesOfServiceAType\PlaceOfServiceAType $placeOfService)
    {
        $this->placeOfService[] = $placeOfService;
        return $this;
    }

    /**
     * isset placeOfService
     *
     * Место обслуживания физ. лица
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetPlaceOfService($index)
    {
        return isset($this->placeOfService[$index]);
    }

    /**
     * unset placeOfService
     *
     * Место обслуживания физ. лица
     *
     * @param scalar $index
     * @return void
     */
    public function unsetPlaceOfService($index)
    {
        unset($this->placeOfService[$index]);
    }

    /**
     * Gets as placeOfService
     *
     * Место обслуживания физ. лица
     *
     * @return \common\models\sbbolxml\response\OrgInfoType\SalaryContractsAType\SalaryContractAType\PlacesOfServiceAType\PlaceOfServiceAType[]
     */
    public function getPlaceOfService()
    {
        return $this->placeOfService;
    }

    /**
     * Sets a new placeOfService
     *
     * Место обслуживания физ. лица
     *
     * @param \common\models\sbbolxml\response\OrgInfoType\SalaryContractsAType\SalaryContractAType\PlacesOfServiceAType\PlaceOfServiceAType[] $placeOfService
     * @return static
     */
    public function setPlaceOfService(array $placeOfService)
    {
        $this->placeOfService = $placeOfService;
        return $this;
    }


}


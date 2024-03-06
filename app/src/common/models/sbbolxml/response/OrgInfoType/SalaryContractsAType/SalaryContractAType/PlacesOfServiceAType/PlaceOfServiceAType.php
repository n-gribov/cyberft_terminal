<?php

namespace common\models\sbbolxml\response\OrgInfoType\SalaryContractsAType\SalaryContractAType\PlacesOfServiceAType;

/**
 * Class representing PlaceOfServiceAType
 */
class PlaceOfServiceAType
{

    /**
     * Код подразделения места обслуживания
     *  физ. лица
     *
     * @property string $branchCode
     */
    private $branchCode = null;

    /**
     * БИК подразделения
     *
     * @property string $bic
     */
    private $bic = null;

    /**
     * Название подразделения места
     *  обслуживания физ. лица
     *
     * @property string $name
     */
    private $name = null;

    /**
     * Адрес места обслуживания. Одной
     *  сторокой
     *
     * @property string $adress
     */
    private $adress = null;

    /**
     * Gets as branchCode
     *
     * Код подразделения места обслуживания
     *  физ. лица
     *
     * @return string
     */
    public function getBranchCode()
    {
        return $this->branchCode;
    }

    /**
     * Sets a new branchCode
     *
     * Код подразделения места обслуживания
     *  физ. лица
     *
     * @param string $branchCode
     * @return static
     */
    public function setBranchCode($branchCode)
    {
        $this->branchCode = $branchCode;
        return $this;
    }

    /**
     * Gets as bic
     *
     * БИК подразделения
     *
     * @return string
     */
    public function getBic()
    {
        return $this->bic;
    }

    /**
     * Sets a new bic
     *
     * БИК подразделения
     *
     * @param string $bic
     * @return static
     */
    public function setBic($bic)
    {
        $this->bic = $bic;
        return $this;
    }

    /**
     * Gets as name
     *
     * Название подразделения места
     *  обслуживания физ. лица
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets a new name
     *
     * Название подразделения места
     *  обслуживания физ. лица
     *
     * @param string $name
     * @return static
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Gets as adress
     *
     * Адрес места обслуживания. Одной
     *  сторокой
     *
     * @return string
     */
    public function getAdress()
    {
        return $this->adress;
    }

    /**
     * Sets a new adress
     *
     * Адрес места обслуживания. Одной
     *  сторокой
     *
     * @param string $adress
     * @return static
     */
    public function setAdress($adress)
    {
        $this->adress = $adress;
        return $this;
    }


}


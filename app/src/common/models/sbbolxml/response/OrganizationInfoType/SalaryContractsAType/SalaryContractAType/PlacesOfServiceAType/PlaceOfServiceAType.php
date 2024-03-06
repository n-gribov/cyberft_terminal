<?php

namespace common\models\sbbolxml\response\OrganizationInfoType\SalaryContractsAType\SalaryContractAType\PlacesOfServiceAType;

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
     * Код Территорильного банка СБРФ для
     *  места
     *  обслуживания физ.лиц (для АС Прометей, 2 символа)
     *
     * @property string $tBNum
     */
    private $tBNum = null;

    /**
     * Номер отделения Сбербанка для места обслуживания
     *  физ.лица (для АС Прометей, 4 символа)
     *
     * @property string $oSBNum
     */
    private $oSBNum = null;

    /**
     * Номер внутреннего структурного
     *  подразделения (филиала)
     *  для места обслуживания физ.лица (для АС Прометей, 4
     *  символа)
     *
     * @property string $branchOSBNum
     */
    private $branchOSBNum = null;

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
     * Gets as tBNum
     *
     * Код Территорильного банка СБРФ для
     *  места
     *  обслуживания физ.лиц (для АС Прометей, 2 символа)
     *
     * @return string
     */
    public function getTBNum()
    {
        return $this->tBNum;
    }

    /**
     * Sets a new tBNum
     *
     * Код Территорильного банка СБРФ для
     *  места
     *  обслуживания физ.лиц (для АС Прометей, 2 символа)
     *
     * @param string $tBNum
     * @return static
     */
    public function setTBNum($tBNum)
    {
        $this->tBNum = $tBNum;
        return $this;
    }

    /**
     * Gets as oSBNum
     *
     * Номер отделения Сбербанка для места обслуживания
     *  физ.лица (для АС Прометей, 4 символа)
     *
     * @return string
     */
    public function getOSBNum()
    {
        return $this->oSBNum;
    }

    /**
     * Sets a new oSBNum
     *
     * Номер отделения Сбербанка для места обслуживания
     *  физ.лица (для АС Прометей, 4 символа)
     *
     * @param string $oSBNum
     * @return static
     */
    public function setOSBNum($oSBNum)
    {
        $this->oSBNum = $oSBNum;
        return $this;
    }

    /**
     * Gets as branchOSBNum
     *
     * Номер внутреннего структурного
     *  подразделения (филиала)
     *  для места обслуживания физ.лица (для АС Прометей, 4
     *  символа)
     *
     * @return string
     */
    public function getBranchOSBNum()
    {
        return $this->branchOSBNum;
    }

    /**
     * Sets a new branchOSBNum
     *
     * Номер внутреннего структурного
     *  подразделения (филиала)
     *  для места обслуживания физ.лица (для АС Прометей, 4
     *  символа)
     *
     * @param string $branchOSBNum
     * @return static
     */
    public function setBranchOSBNum($branchOSBNum)
    {
        $this->branchOSBNum = $branchOSBNum;
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


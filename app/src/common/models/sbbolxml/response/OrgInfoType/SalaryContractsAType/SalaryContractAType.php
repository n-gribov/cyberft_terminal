<?php

namespace common\models\sbbolxml\response\OrgInfoType\SalaryContractsAType;

/**
 * Class representing SalaryContractAType
 */
class SalaryContractAType
{

    /**
     * Идентификатор контракта на услуги ДБО, связанный с зарплатным
     *  договором
     *
     * @property string $contDboId
     */
    private $contDboId = null;

    /**
     * Идентификатор договора
     *
     * @property string $contrID
     */
    private $contrID = null;

    /**
     * Номер договора
     *
     * @property string $contrNum
     */
    private $contrNum = null;

    /**
     * Дата договора
     *
     * @property \DateTime $contrDate
     */
    private $contrDate = null;

    /**
     * Безакцептное списание (Резервирование)
     *  1- безакцептное списание, 0-иное
     *
     * @property boolean $accept
     */
    private $accept = null;

    /**
     * ИНН
     *
     * @property string $inn
     */
    private $inn = null;

    /**
     * Номер филиала
     *
     * @property string $filialNum
     */
    private $filialNum = null;

    /**
     * БИК филиала
     *
     * @property string $bic
     */
    private $bic = null;

    /**
     * Идентификатор подразделения банка
     *
     * @property string $branchId
     */
    private $branchId = null;

    /**
     * Наименование договора
     *
     * @property string $contrName
     */
    private $contrName = null;

    /**
     * Места обслуживания физических лиц
     *
     * @property \common\models\sbbolxml\response\OrgInfoType\SalaryContractsAType\SalaryContractAType\PlacesOfServiceAType\PlaceOfServiceAType[] $placesOfService
     */
    private $placesOfService = null;

    /**
     * Gets as contDboId
     *
     * Идентификатор контракта на услуги ДБО, связанный с зарплатным
     *  договором
     *
     * @return string
     */
    public function getContDboId()
    {
        return $this->contDboId;
    }

    /**
     * Sets a new contDboId
     *
     * Идентификатор контракта на услуги ДБО, связанный с зарплатным
     *  договором
     *
     * @param string $contDboId
     * @return static
     */
    public function setContDboId($contDboId)
    {
        $this->contDboId = $contDboId;
        return $this;
    }

    /**
     * Gets as contrID
     *
     * Идентификатор договора
     *
     * @return string
     */
    public function getContrID()
    {
        return $this->contrID;
    }

    /**
     * Sets a new contrID
     *
     * Идентификатор договора
     *
     * @param string $contrID
     * @return static
     */
    public function setContrID($contrID)
    {
        $this->contrID = $contrID;
        return $this;
    }

    /**
     * Gets as contrNum
     *
     * Номер договора
     *
     * @return string
     */
    public function getContrNum()
    {
        return $this->contrNum;
    }

    /**
     * Sets a new contrNum
     *
     * Номер договора
     *
     * @param string $contrNum
     * @return static
     */
    public function setContrNum($contrNum)
    {
        $this->contrNum = $contrNum;
        return $this;
    }

    /**
     * Gets as contrDate
     *
     * Дата договора
     *
     * @return \DateTime
     */
    public function getContrDate()
    {
        return $this->contrDate;
    }

    /**
     * Sets a new contrDate
     *
     * Дата договора
     *
     * @param \DateTime $contrDate
     * @return static
     */
    public function setContrDate(\DateTime $contrDate)
    {
        $this->contrDate = $contrDate;
        return $this;
    }

    /**
     * Gets as accept
     *
     * Безакцептное списание (Резервирование)
     *  1- безакцептное списание, 0-иное
     *
     * @return boolean
     */
    public function getAccept()
    {
        return $this->accept;
    }

    /**
     * Sets a new accept
     *
     * Безакцептное списание (Резервирование)
     *  1- безакцептное списание, 0-иное
     *
     * @param boolean $accept
     * @return static
     */
    public function setAccept($accept)
    {
        $this->accept = $accept;
        return $this;
    }

    /**
     * Gets as inn
     *
     * ИНН
     *
     * @return string
     */
    public function getInn()
    {
        return $this->inn;
    }

    /**
     * Sets a new inn
     *
     * ИНН
     *
     * @param string $inn
     * @return static
     */
    public function setInn($inn)
    {
        $this->inn = $inn;
        return $this;
    }

    /**
     * Gets as filialNum
     *
     * Номер филиала
     *
     * @return string
     */
    public function getFilialNum()
    {
        return $this->filialNum;
    }

    /**
     * Sets a new filialNum
     *
     * Номер филиала
     *
     * @param string $filialNum
     * @return static
     */
    public function setFilialNum($filialNum)
    {
        $this->filialNum = $filialNum;
        return $this;
    }

    /**
     * Gets as bic
     *
     * БИК филиала
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
     * БИК филиала
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
     * Gets as branchId
     *
     * Идентификатор подразделения банка
     *
     * @return string
     */
    public function getBranchId()
    {
        return $this->branchId;
    }

    /**
     * Sets a new branchId
     *
     * Идентификатор подразделения банка
     *
     * @param string $branchId
     * @return static
     */
    public function setBranchId($branchId)
    {
        $this->branchId = $branchId;
        return $this;
    }

    /**
     * Gets as contrName
     *
     * Наименование договора
     *
     * @return string
     */
    public function getContrName()
    {
        return $this->contrName;
    }

    /**
     * Sets a new contrName
     *
     * Наименование договора
     *
     * @param string $contrName
     * @return static
     */
    public function setContrName($contrName)
    {
        $this->contrName = $contrName;
        return $this;
    }

    /**
     * Adds as placeOfService
     *
     * Места обслуживания физических лиц
     *
     * @return static
     * @param \common\models\sbbolxml\response\OrgInfoType\SalaryContractsAType\SalaryContractAType\PlacesOfServiceAType\PlaceOfServiceAType $placeOfService
     */
    public function addToPlacesOfService(\common\models\sbbolxml\response\OrgInfoType\SalaryContractsAType\SalaryContractAType\PlacesOfServiceAType\PlaceOfServiceAType $placeOfService)
    {
        $this->placesOfService[] = $placeOfService;
        return $this;
    }

    /**
     * isset placesOfService
     *
     * Места обслуживания физических лиц
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetPlacesOfService($index)
    {
        return isset($this->placesOfService[$index]);
    }

    /**
     * unset placesOfService
     *
     * Места обслуживания физических лиц
     *
     * @param scalar $index
     * @return void
     */
    public function unsetPlacesOfService($index)
    {
        unset($this->placesOfService[$index]);
    }

    /**
     * Gets as placesOfService
     *
     * Места обслуживания физических лиц
     *
     * @return \common\models\sbbolxml\response\OrgInfoType\SalaryContractsAType\SalaryContractAType\PlacesOfServiceAType\PlaceOfServiceAType[]
     */
    public function getPlacesOfService()
    {
        return $this->placesOfService;
    }

    /**
     * Sets a new placesOfService
     *
     * Места обслуживания физических лиц
     *
     * @param \common\models\sbbolxml\response\OrgInfoType\SalaryContractsAType\SalaryContractAType\PlacesOfServiceAType\PlaceOfServiceAType[] $placesOfService
     * @return static
     */
    public function setPlacesOfService(array $placesOfService)
    {
        $this->placesOfService = $placesOfService;
        return $this;
    }


}


<?php

namespace common\models\sbbolxml\response\OrganizationInfoType\SalaryContractsAType;

/**
 * Class representing SalaryContractAType
 */
class SalaryContractAType
{

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
     * Сокращенное наименование Территорильного банка СБРФ
     *
     * @property string $tBName
     */
    private $tBName = null;

    /**
     * Сокращенное наименование отделения Сбербанка
     *
     * @property string $oSBName
     */
    private $oSBName = null;

    /**
     * Сокращенное наименование внутреннего структурного
     *  подразделения (филиала)
     *
     * @property string $vSPName
     */
    private $vSPName = null;

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
     * @property \common\models\sbbolxml\response\OrganizationInfoType\SalaryContractsAType\SalaryContractAType\PlacesOfServiceAType\PlaceOfServiceAType[] $placesOfService
     */
    private $placesOfService = null;

    /**
     * Допустимые виды зачислений
     *
     * @property \common\models\sbbolxml\response\OrganizationInfoType\SalaryContractsAType\SalaryContractAType\SalTypesAType\SalTypeAType[] $salTypes
     */
    private $salTypes = null;

    /**
     * Допустимые типы карт
     *
     * @property \common\models\sbbolxml\response\OrganizationInfoType\SalaryContractsAType\SalaryContractAType\CardTypesAType\CardTypeAType[] $cardTypes
     */
    private $cardTypes = null;

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
     * Gets as tBName
     *
     * Сокращенное наименование Территорильного банка СБРФ
     *
     * @return string
     */
    public function getTBName()
    {
        return $this->tBName;
    }

    /**
     * Sets a new tBName
     *
     * Сокращенное наименование Территорильного банка СБРФ
     *
     * @param string $tBName
     * @return static
     */
    public function setTBName($tBName)
    {
        $this->tBName = $tBName;
        return $this;
    }

    /**
     * Gets as oSBName
     *
     * Сокращенное наименование отделения Сбербанка
     *
     * @return string
     */
    public function getOSBName()
    {
        return $this->oSBName;
    }

    /**
     * Sets a new oSBName
     *
     * Сокращенное наименование отделения Сбербанка
     *
     * @param string $oSBName
     * @return static
     */
    public function setOSBName($oSBName)
    {
        $this->oSBName = $oSBName;
        return $this;
    }

    /**
     * Gets as vSPName
     *
     * Сокращенное наименование внутреннего структурного
     *  подразделения (филиала)
     *
     * @return string
     */
    public function getVSPName()
    {
        return $this->vSPName;
    }

    /**
     * Sets a new vSPName
     *
     * Сокращенное наименование внутреннего структурного
     *  подразделения (филиала)
     *
     * @param string $vSPName
     * @return static
     */
    public function setVSPName($vSPName)
    {
        $this->vSPName = $vSPName;
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
     * @param \common\models\sbbolxml\response\OrganizationInfoType\SalaryContractsAType\SalaryContractAType\PlacesOfServiceAType\PlaceOfServiceAType $placeOfService
     */
    public function addToPlacesOfService(\common\models\sbbolxml\response\OrganizationInfoType\SalaryContractsAType\SalaryContractAType\PlacesOfServiceAType\PlaceOfServiceAType $placeOfService)
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
     * @return \common\models\sbbolxml\response\OrganizationInfoType\SalaryContractsAType\SalaryContractAType\PlacesOfServiceAType\PlaceOfServiceAType[]
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
     * @param \common\models\sbbolxml\response\OrganizationInfoType\SalaryContractsAType\SalaryContractAType\PlacesOfServiceAType\PlaceOfServiceAType[] $placesOfService
     * @return static
     */
    public function setPlacesOfService(array $placesOfService)
    {
        $this->placesOfService = $placesOfService;
        return $this;
    }

    /**
     * Adds as salType
     *
     * Допустимые виды зачислений
     *
     * @return static
     * @param \common\models\sbbolxml\response\OrganizationInfoType\SalaryContractsAType\SalaryContractAType\SalTypesAType\SalTypeAType $salType
     */
    public function addToSalTypes(\common\models\sbbolxml\response\OrganizationInfoType\SalaryContractsAType\SalaryContractAType\SalTypesAType\SalTypeAType $salType)
    {
        $this->salTypes[] = $salType;
        return $this;
    }

    /**
     * isset salTypes
     *
     * Допустимые виды зачислений
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetSalTypes($index)
    {
        return isset($this->salTypes[$index]);
    }

    /**
     * unset salTypes
     *
     * Допустимые виды зачислений
     *
     * @param scalar $index
     * @return void
     */
    public function unsetSalTypes($index)
    {
        unset($this->salTypes[$index]);
    }

    /**
     * Gets as salTypes
     *
     * Допустимые виды зачислений
     *
     * @return \common\models\sbbolxml\response\OrganizationInfoType\SalaryContractsAType\SalaryContractAType\SalTypesAType\SalTypeAType[]
     */
    public function getSalTypes()
    {
        return $this->salTypes;
    }

    /**
     * Sets a new salTypes
     *
     * Допустимые виды зачислений
     *
     * @param \common\models\sbbolxml\response\OrganizationInfoType\SalaryContractsAType\SalaryContractAType\SalTypesAType\SalTypeAType[] $salTypes
     * @return static
     */
    public function setSalTypes(array $salTypes)
    {
        $this->salTypes = $salTypes;
        return $this;
    }

    /**
     * Adds as cardType
     *
     * Допустимые типы карт
     *
     * @return static
     * @param \common\models\sbbolxml\response\OrganizationInfoType\SalaryContractsAType\SalaryContractAType\CardTypesAType\CardTypeAType $cardType
     */
    public function addToCardTypes(\common\models\sbbolxml\response\OrganizationInfoType\SalaryContractsAType\SalaryContractAType\CardTypesAType\CardTypeAType $cardType)
    {
        $this->cardTypes[] = $cardType;
        return $this;
    }

    /**
     * isset cardTypes
     *
     * Допустимые типы карт
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetCardTypes($index)
    {
        return isset($this->cardTypes[$index]);
    }

    /**
     * unset cardTypes
     *
     * Допустимые типы карт
     *
     * @param scalar $index
     * @return void
     */
    public function unsetCardTypes($index)
    {
        unset($this->cardTypes[$index]);
    }

    /**
     * Gets as cardTypes
     *
     * Допустимые типы карт
     *
     * @return \common\models\sbbolxml\response\OrganizationInfoType\SalaryContractsAType\SalaryContractAType\CardTypesAType\CardTypeAType[]
     */
    public function getCardTypes()
    {
        return $this->cardTypes;
    }

    /**
     * Sets a new cardTypes
     *
     * Допустимые типы карт
     *
     * @param \common\models\sbbolxml\response\OrganizationInfoType\SalaryContractsAType\SalaryContractAType\CardTypesAType\CardTypeAType[] $cardTypes
     * @return static
     */
    public function setCardTypes(array $cardTypes)
    {
        $this->cardTypes = $cardTypes;
        return $this;
    }


}


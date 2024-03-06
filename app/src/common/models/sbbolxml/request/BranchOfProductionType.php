<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing BranchOfProductionType
 *
 * Сфера деятельности/отрасль производства
 * XSD Type: BranchOfProduction
 */
class BranchOfProductionType
{

    /**
     * Предоставление услуг
     *
     * @property boolean $renderingOfServices
     */
    private $renderingOfServices = null;

    /**
     * Оптовая торговля
     *
     * @property boolean $paid
     */
    private $paid = null;

    /**
     * Розничная торговля
     *
     * @property boolean $wholesaleTrade
     */
    private $wholesaleTrade = null;

    /**
     * Строительство
     *
     * @property boolean $development
     */
    private $development = null;

    /**
     * Энергетика
     *
     * @property boolean $energetics
     */
    private $energetics = null;

    /**
     * Деятельность, связанная с производством оружия, или посредническая деятельность по реализации оружия
     *
     * @property boolean $armamentProduction
     */
    private $armamentProduction = null;

    /**
     * Туристская деятельность (туроператорская и турагентская деятельность, а также иная деятельность по организации
     *  путешествий)
     *
     * @property boolean $touristIndustry
     */
    private $touristIndustry = null;

    /**
     * Комиссионная деятельность (автотранспорт)
     *
     * @property boolean $commissionBusinessAuto
     */
    private $commissionBusinessAuto = null;

    /**
     * Комиссионная деятельность (предметы искусства)
     *
     * @property boolean $commissionBusinessArt
     */
    private $commissionBusinessArt = null;

    /**
     * Комиссионная деятельность (антиквариат)
     *
     * @property boolean $commissionBusinessAntiques
     */
    private $commissionBusinessAntiques = null;

    /**
     * Комиссионная деятельность (мебель)
     *
     * @property boolean $commissionBusinessFurniture
     */
    private $commissionBusinessFurniture = null;

    /**
     * Деятельность, связанная с содержанием тотализаторов и игорных заведений (казино, букмекерских контор и др.),
     *  по организации и проведению лотерей, тотализаторов (взаимных пари) и иных основанных на риске игр, в том числе в электронной
     *  форме, а также деятельность ломбардов
     *
     * @property boolean $gamblingIndustry
     */
    private $gamblingIndustry = null;

    /**
     * Совершение сделок с недвижимым имуществом и оказание посреднических услуг при совершении сделок с недвижимым
     *  имуществом
     *
     * @property boolean $jewelleryBusiness
     */
    private $jewelleryBusiness = null;

    /**
     * Совершение сделок с недвижимым имуществом и оказание посреднических услуг при совершении сделок с недвижимым
     *  имуществом
     *
     * @property boolean $realEstateBusiness
     */
    private $realEstateBusiness = null;

    /**
     * Благотворительная деятельность
     *
     * @property boolean $charity
     */
    private $charity = null;

    /**
     * Иная (указать)
     *
     * @property boolean $otherBranchOfProduction
     */
    private $otherBranchOfProduction = null;

    /**
     * Иная (текст)
     *
     * @property string $otherBranchOfProductionText
     */
    private $otherBranchOfProductionText = null;

    /**
     * Gets as renderingOfServices
     *
     * Предоставление услуг
     *
     * @return boolean
     */
    public function getRenderingOfServices()
    {
        return $this->renderingOfServices;
    }

    /**
     * Sets a new renderingOfServices
     *
     * Предоставление услуг
     *
     * @param boolean $renderingOfServices
     * @return static
     */
    public function setRenderingOfServices($renderingOfServices)
    {
        $this->renderingOfServices = $renderingOfServices;
        return $this;
    }

    /**
     * Gets as paid
     *
     * Оптовая торговля
     *
     * @return boolean
     */
    public function getPaid()
    {
        return $this->paid;
    }

    /**
     * Sets a new paid
     *
     * Оптовая торговля
     *
     * @param boolean $paid
     * @return static
     */
    public function setPaid($paid)
    {
        $this->paid = $paid;
        return $this;
    }

    /**
     * Gets as wholesaleTrade
     *
     * Розничная торговля
     *
     * @return boolean
     */
    public function getWholesaleTrade()
    {
        return $this->wholesaleTrade;
    }

    /**
     * Sets a new wholesaleTrade
     *
     * Розничная торговля
     *
     * @param boolean $wholesaleTrade
     * @return static
     */
    public function setWholesaleTrade($wholesaleTrade)
    {
        $this->wholesaleTrade = $wholesaleTrade;
        return $this;
    }

    /**
     * Gets as development
     *
     * Строительство
     *
     * @return boolean
     */
    public function getDevelopment()
    {
        return $this->development;
    }

    /**
     * Sets a new development
     *
     * Строительство
     *
     * @param boolean $development
     * @return static
     */
    public function setDevelopment($development)
    {
        $this->development = $development;
        return $this;
    }

    /**
     * Gets as energetics
     *
     * Энергетика
     *
     * @return boolean
     */
    public function getEnergetics()
    {
        return $this->energetics;
    }

    /**
     * Sets a new energetics
     *
     * Энергетика
     *
     * @param boolean $energetics
     * @return static
     */
    public function setEnergetics($energetics)
    {
        $this->energetics = $energetics;
        return $this;
    }

    /**
     * Gets as armamentProduction
     *
     * Деятельность, связанная с производством оружия, или посредническая деятельность по реализации оружия
     *
     * @return boolean
     */
    public function getArmamentProduction()
    {
        return $this->armamentProduction;
    }

    /**
     * Sets a new armamentProduction
     *
     * Деятельность, связанная с производством оружия, или посредническая деятельность по реализации оружия
     *
     * @param boolean $armamentProduction
     * @return static
     */
    public function setArmamentProduction($armamentProduction)
    {
        $this->armamentProduction = $armamentProduction;
        return $this;
    }

    /**
     * Gets as touristIndustry
     *
     * Туристская деятельность (туроператорская и турагентская деятельность, а также иная деятельность по организации
     *  путешествий)
     *
     * @return boolean
     */
    public function getTouristIndustry()
    {
        return $this->touristIndustry;
    }

    /**
     * Sets a new touristIndustry
     *
     * Туристская деятельность (туроператорская и турагентская деятельность, а также иная деятельность по организации
     *  путешествий)
     *
     * @param boolean $touristIndustry
     * @return static
     */
    public function setTouristIndustry($touristIndustry)
    {
        $this->touristIndustry = $touristIndustry;
        return $this;
    }

    /**
     * Gets as commissionBusinessAuto
     *
     * Комиссионная деятельность (автотранспорт)
     *
     * @return boolean
     */
    public function getCommissionBusinessAuto()
    {
        return $this->commissionBusinessAuto;
    }

    /**
     * Sets a new commissionBusinessAuto
     *
     * Комиссионная деятельность (автотранспорт)
     *
     * @param boolean $commissionBusinessAuto
     * @return static
     */
    public function setCommissionBusinessAuto($commissionBusinessAuto)
    {
        $this->commissionBusinessAuto = $commissionBusinessAuto;
        return $this;
    }

    /**
     * Gets as commissionBusinessArt
     *
     * Комиссионная деятельность (предметы искусства)
     *
     * @return boolean
     */
    public function getCommissionBusinessArt()
    {
        return $this->commissionBusinessArt;
    }

    /**
     * Sets a new commissionBusinessArt
     *
     * Комиссионная деятельность (предметы искусства)
     *
     * @param boolean $commissionBusinessArt
     * @return static
     */
    public function setCommissionBusinessArt($commissionBusinessArt)
    {
        $this->commissionBusinessArt = $commissionBusinessArt;
        return $this;
    }

    /**
     * Gets as commissionBusinessAntiques
     *
     * Комиссионная деятельность (антиквариат)
     *
     * @return boolean
     */
    public function getCommissionBusinessAntiques()
    {
        return $this->commissionBusinessAntiques;
    }

    /**
     * Sets a new commissionBusinessAntiques
     *
     * Комиссионная деятельность (антиквариат)
     *
     * @param boolean $commissionBusinessAntiques
     * @return static
     */
    public function setCommissionBusinessAntiques($commissionBusinessAntiques)
    {
        $this->commissionBusinessAntiques = $commissionBusinessAntiques;
        return $this;
    }

    /**
     * Gets as commissionBusinessFurniture
     *
     * Комиссионная деятельность (мебель)
     *
     * @return boolean
     */
    public function getCommissionBusinessFurniture()
    {
        return $this->commissionBusinessFurniture;
    }

    /**
     * Sets a new commissionBusinessFurniture
     *
     * Комиссионная деятельность (мебель)
     *
     * @param boolean $commissionBusinessFurniture
     * @return static
     */
    public function setCommissionBusinessFurniture($commissionBusinessFurniture)
    {
        $this->commissionBusinessFurniture = $commissionBusinessFurniture;
        return $this;
    }

    /**
     * Gets as gamblingIndustry
     *
     * Деятельность, связанная с содержанием тотализаторов и игорных заведений (казино, букмекерских контор и др.),
     *  по организации и проведению лотерей, тотализаторов (взаимных пари) и иных основанных на риске игр, в том числе в электронной
     *  форме, а также деятельность ломбардов
     *
     * @return boolean
     */
    public function getGamblingIndustry()
    {
        return $this->gamblingIndustry;
    }

    /**
     * Sets a new gamblingIndustry
     *
     * Деятельность, связанная с содержанием тотализаторов и игорных заведений (казино, букмекерских контор и др.),
     *  по организации и проведению лотерей, тотализаторов (взаимных пари) и иных основанных на риске игр, в том числе в электронной
     *  форме, а также деятельность ломбардов
     *
     * @param boolean $gamblingIndustry
     * @return static
     */
    public function setGamblingIndustry($gamblingIndustry)
    {
        $this->gamblingIndustry = $gamblingIndustry;
        return $this;
    }

    /**
     * Gets as jewelleryBusiness
     *
     * Совершение сделок с недвижимым имуществом и оказание посреднических услуг при совершении сделок с недвижимым
     *  имуществом
     *
     * @return boolean
     */
    public function getJewelleryBusiness()
    {
        return $this->jewelleryBusiness;
    }

    /**
     * Sets a new jewelleryBusiness
     *
     * Совершение сделок с недвижимым имуществом и оказание посреднических услуг при совершении сделок с недвижимым
     *  имуществом
     *
     * @param boolean $jewelleryBusiness
     * @return static
     */
    public function setJewelleryBusiness($jewelleryBusiness)
    {
        $this->jewelleryBusiness = $jewelleryBusiness;
        return $this;
    }

    /**
     * Gets as realEstateBusiness
     *
     * Совершение сделок с недвижимым имуществом и оказание посреднических услуг при совершении сделок с недвижимым
     *  имуществом
     *
     * @return boolean
     */
    public function getRealEstateBusiness()
    {
        return $this->realEstateBusiness;
    }

    /**
     * Sets a new realEstateBusiness
     *
     * Совершение сделок с недвижимым имуществом и оказание посреднических услуг при совершении сделок с недвижимым
     *  имуществом
     *
     * @param boolean $realEstateBusiness
     * @return static
     */
    public function setRealEstateBusiness($realEstateBusiness)
    {
        $this->realEstateBusiness = $realEstateBusiness;
        return $this;
    }

    /**
     * Gets as charity
     *
     * Благотворительная деятельность
     *
     * @return boolean
     */
    public function getCharity()
    {
        return $this->charity;
    }

    /**
     * Sets a new charity
     *
     * Благотворительная деятельность
     *
     * @param boolean $charity
     * @return static
     */
    public function setCharity($charity)
    {
        $this->charity = $charity;
        return $this;
    }

    /**
     * Gets as otherBranchOfProduction
     *
     * Иная (указать)
     *
     * @return boolean
     */
    public function getOtherBranchOfProduction()
    {
        return $this->otherBranchOfProduction;
    }

    /**
     * Sets a new otherBranchOfProduction
     *
     * Иная (указать)
     *
     * @param boolean $otherBranchOfProduction
     * @return static
     */
    public function setOtherBranchOfProduction($otherBranchOfProduction)
    {
        $this->otherBranchOfProduction = $otherBranchOfProduction;
        return $this;
    }

    /**
     * Gets as otherBranchOfProductionText
     *
     * Иная (текст)
     *
     * @return string
     */
    public function getOtherBranchOfProductionText()
    {
        return $this->otherBranchOfProductionText;
    }

    /**
     * Sets a new otherBranchOfProductionText
     *
     * Иная (текст)
     *
     * @param string $otherBranchOfProductionText
     * @return static
     */
    public function setOtherBranchOfProductionText($otherBranchOfProductionText)
    {
        $this->otherBranchOfProductionText = $otherBranchOfProductionText;
        return $this;
    }


}


<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing BeneficiarType
 *
 * Наличие бенефициарных владельцев
 * XSD Type: Beneficiar
 */
class BeneficiarType
{

    /**
     * Органом государственной власти, иным государственным органом, органом местного самоуправления, учреждением,
     *  находящимся в его ведении, государственным внебюджетным фондом
     *
     * @property boolean $publicAuthority
     */
    private $publicAuthority = null;

    /**
     * Государственной корпорацией или организацией, в которой Российская Федерация, субъекты РФ либо муниципальные
     *  образования имеют более 50 % акций (долей) в капитале
     *
     * @property boolean $stateCorporation
     */
    private $stateCorporation = null;

    /**
     * Международной организацией, иностранным государством или административно-территориальной единицей иностранных
     *  государств, обладающей самостоятельной правоспособностью
     *
     * @property boolean $internationalOrganization
     */
    private $internationalOrganization = null;

    /**
     * Эмитентом ценных бумаг, допущенным к организованным торгам, который раскрывает информацию в соответствии с
     *  законодательством Российской Федерации о ценных бумаг
     *
     * @property boolean $issuerOfSecurities
     */
    private $issuerOfSecurities = null;

    /**
     * @property \common\models\sbbolxml\request\BeneficiaryType[] $beneficiaryes
     */
    private $beneficiaryes = null;

    /**
     * Gets as publicAuthority
     *
     * Органом государственной власти, иным государственным органом, органом местного самоуправления, учреждением,
     *  находящимся в его ведении, государственным внебюджетным фондом
     *
     * @return boolean
     */
    public function getPublicAuthority()
    {
        return $this->publicAuthority;
    }

    /**
     * Sets a new publicAuthority
     *
     * Органом государственной власти, иным государственным органом, органом местного самоуправления, учреждением,
     *  находящимся в его ведении, государственным внебюджетным фондом
     *
     * @param boolean $publicAuthority
     * @return static
     */
    public function setPublicAuthority($publicAuthority)
    {
        $this->publicAuthority = $publicAuthority;
        return $this;
    }

    /**
     * Gets as stateCorporation
     *
     * Государственной корпорацией или организацией, в которой Российская Федерация, субъекты РФ либо муниципальные
     *  образования имеют более 50 % акций (долей) в капитале
     *
     * @return boolean
     */
    public function getStateCorporation()
    {
        return $this->stateCorporation;
    }

    /**
     * Sets a new stateCorporation
     *
     * Государственной корпорацией или организацией, в которой Российская Федерация, субъекты РФ либо муниципальные
     *  образования имеют более 50 % акций (долей) в капитале
     *
     * @param boolean $stateCorporation
     * @return static
     */
    public function setStateCorporation($stateCorporation)
    {
        $this->stateCorporation = $stateCorporation;
        return $this;
    }

    /**
     * Gets as internationalOrganization
     *
     * Международной организацией, иностранным государством или административно-территориальной единицей иностранных
     *  государств, обладающей самостоятельной правоспособностью
     *
     * @return boolean
     */
    public function getInternationalOrganization()
    {
        return $this->internationalOrganization;
    }

    /**
     * Sets a new internationalOrganization
     *
     * Международной организацией, иностранным государством или административно-территориальной единицей иностранных
     *  государств, обладающей самостоятельной правоспособностью
     *
     * @param boolean $internationalOrganization
     * @return static
     */
    public function setInternationalOrganization($internationalOrganization)
    {
        $this->internationalOrganization = $internationalOrganization;
        return $this;
    }

    /**
     * Gets as issuerOfSecurities
     *
     * Эмитентом ценных бумаг, допущенным к организованным торгам, который раскрывает информацию в соответствии с
     *  законодательством Российской Федерации о ценных бумаг
     *
     * @return boolean
     */
    public function getIssuerOfSecurities()
    {
        return $this->issuerOfSecurities;
    }

    /**
     * Sets a new issuerOfSecurities
     *
     * Эмитентом ценных бумаг, допущенным к организованным торгам, который раскрывает информацию в соответствии с
     *  законодательством Российской Федерации о ценных бумаг
     *
     * @param boolean $issuerOfSecurities
     * @return static
     */
    public function setIssuerOfSecurities($issuerOfSecurities)
    {
        $this->issuerOfSecurities = $issuerOfSecurities;
        return $this;
    }

    /**
     * Adds as beneficiary
     *
     * @return static
     * @param \common\models\sbbolxml\request\BeneficiaryType $beneficiary
     */
    public function addToBeneficiaryes(\common\models\sbbolxml\request\BeneficiaryType $beneficiary)
    {
        $this->beneficiaryes[] = $beneficiary;
        return $this;
    }

    /**
     * isset beneficiaryes
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetBeneficiaryes($index)
    {
        return isset($this->beneficiaryes[$index]);
    }

    /**
     * unset beneficiaryes
     *
     * @param scalar $index
     * @return void
     */
    public function unsetBeneficiaryes($index)
    {
        unset($this->beneficiaryes[$index]);
    }

    /**
     * Gets as beneficiaryes
     *
     * @return \common\models\sbbolxml\request\BeneficiaryType[]
     */
    public function getBeneficiaryes()
    {
        return $this->beneficiaryes;
    }

    /**
     * Sets a new beneficiaryes
     *
     * @param \common\models\sbbolxml\request\BeneficiaryType[] $beneficiaryes
     * @return static
     */
    public function setBeneficiaryes(array $beneficiaryes)
    {
        $this->beneficiaryes = $beneficiaryes;
        return $this;
    }


}


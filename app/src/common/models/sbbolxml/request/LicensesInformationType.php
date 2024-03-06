<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing LicensesInformationType
 *
 * Сведения о лицензиях на право осуществления деятельности, подлежащей лицензированию
 * XSD Type: LicensesInformation
 */
class LicensesInformationType
{

    /**
     * Количество лицензий
     *
     * @property integer $licensesQuantity
     */
    private $licensesQuantity = null;

    /**
     * @property \common\models\sbbolxml\request\LicenseInformationType[] $licenseInformation
     */
    private $licenseInformation = array(
        
    );

    /**
     * Gets as licensesQuantity
     *
     * Количество лицензий
     *
     * @return integer
     */
    public function getLicensesQuantity()
    {
        return $this->licensesQuantity;
    }

    /**
     * Sets a new licensesQuantity
     *
     * Количество лицензий
     *
     * @param integer $licensesQuantity
     * @return static
     */
    public function setLicensesQuantity($licensesQuantity)
    {
        $this->licensesQuantity = $licensesQuantity;
        return $this;
    }

    /**
     * Adds as licenseInformation
     *
     * @return static
     * @param \common\models\sbbolxml\request\LicenseInformationType $licenseInformation
     */
    public function addToLicenseInformation(\common\models\sbbolxml\request\LicenseInformationType $licenseInformation)
    {
        $this->licenseInformation[] = $licenseInformation;
        return $this;
    }

    /**
     * isset licenseInformation
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetLicenseInformation($index)
    {
        return isset($this->licenseInformation[$index]);
    }

    /**
     * unset licenseInformation
     *
     * @param scalar $index
     * @return void
     */
    public function unsetLicenseInformation($index)
    {
        unset($this->licenseInformation[$index]);
    }

    /**
     * Gets as licenseInformation
     *
     * @return \common\models\sbbolxml\request\LicenseInformationType[]
     */
    public function getLicenseInformation()
    {
        return $this->licenseInformation;
    }

    /**
     * Sets a new licenseInformation
     *
     * @param \common\models\sbbolxml\request\LicenseInformationType[] $licenseInformation
     * @return static
     */
    public function setLicenseInformation(array $licenseInformation)
    {
        $this->licenseInformation = $licenseInformation;
        return $this;
    }


}


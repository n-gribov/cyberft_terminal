<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing LicenseInformationType
 *
 * Информация об одной лицензии
 * XSD Type: LicenseInformation
 */
class LicenseInformationType
{

    /**
     * Вид лицензии
     *
     * @property string $licenseType
     */
    private $licenseType = null;

    /**
     * Номер
     *
     * @property string $licenseNumber
     */
    private $licenseNumber = null;

    /**
     * Дата выдачи
     *
     * @property \DateTime $licenseDateOfIssue
     */
    private $licenseDateOfIssue = null;

    /**
     * Орган, выдавший лицензию
     *
     * @property string $licenseIssueOrg
     */
    private $licenseIssueOrg = null;

    /**
     * Срок действия
     *
     * @property \DateTime $licenseExpirationDate
     */
    private $licenseExpirationDate = null;

    /**
     * Перечень видов деятельности
     *
     * @property string $licenseLinesOfBusinessList
     */
    private $licenseLinesOfBusinessList = null;

    /**
     * Gets as licenseType
     *
     * Вид лицензии
     *
     * @return string
     */
    public function getLicenseType()
    {
        return $this->licenseType;
    }

    /**
     * Sets a new licenseType
     *
     * Вид лицензии
     *
     * @param string $licenseType
     * @return static
     */
    public function setLicenseType($licenseType)
    {
        $this->licenseType = $licenseType;
        return $this;
    }

    /**
     * Gets as licenseNumber
     *
     * Номер
     *
     * @return string
     */
    public function getLicenseNumber()
    {
        return $this->licenseNumber;
    }

    /**
     * Sets a new licenseNumber
     *
     * Номер
     *
     * @param string $licenseNumber
     * @return static
     */
    public function setLicenseNumber($licenseNumber)
    {
        $this->licenseNumber = $licenseNumber;
        return $this;
    }

    /**
     * Gets as licenseDateOfIssue
     *
     * Дата выдачи
     *
     * @return \DateTime
     */
    public function getLicenseDateOfIssue()
    {
        return $this->licenseDateOfIssue;
    }

    /**
     * Sets a new licenseDateOfIssue
     *
     * Дата выдачи
     *
     * @param \DateTime $licenseDateOfIssue
     * @return static
     */
    public function setLicenseDateOfIssue(\DateTime $licenseDateOfIssue)
    {
        $this->licenseDateOfIssue = $licenseDateOfIssue;
        return $this;
    }

    /**
     * Gets as licenseIssueOrg
     *
     * Орган, выдавший лицензию
     *
     * @return string
     */
    public function getLicenseIssueOrg()
    {
        return $this->licenseIssueOrg;
    }

    /**
     * Sets a new licenseIssueOrg
     *
     * Орган, выдавший лицензию
     *
     * @param string $licenseIssueOrg
     * @return static
     */
    public function setLicenseIssueOrg($licenseIssueOrg)
    {
        $this->licenseIssueOrg = $licenseIssueOrg;
        return $this;
    }

    /**
     * Gets as licenseExpirationDate
     *
     * Срок действия
     *
     * @return \DateTime
     */
    public function getLicenseExpirationDate()
    {
        return $this->licenseExpirationDate;
    }

    /**
     * Sets a new licenseExpirationDate
     *
     * Срок действия
     *
     * @param \DateTime $licenseExpirationDate
     * @return static
     */
    public function setLicenseExpirationDate(\DateTime $licenseExpirationDate)
    {
        $this->licenseExpirationDate = $licenseExpirationDate;
        return $this;
    }

    /**
     * Gets as licenseLinesOfBusinessList
     *
     * Перечень видов деятельности
     *
     * @return string
     */
    public function getLicenseLinesOfBusinessList()
    {
        return $this->licenseLinesOfBusinessList;
    }

    /**
     * Sets a new licenseLinesOfBusinessList
     *
     * Перечень видов деятельности
     *
     * @param string $licenseLinesOfBusinessList
     * @return static
     */
    public function setLicenseLinesOfBusinessList($licenseLinesOfBusinessList)
    {
        $this->licenseLinesOfBusinessList = $licenseLinesOfBusinessList;
        return $this;
    }


}


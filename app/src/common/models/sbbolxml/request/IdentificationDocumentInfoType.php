<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing IdentificationDocumentInfoType
 *
 * Реквизиты документа, удостоверяющего личность
 * XSD Type: IdentificationDocumentInfo
 */
class IdentificationDocumentInfoType
{

    /**
     * 4.1 Наименование документа, удостоверяющего личность
     *
     * @property string $identificationDocumentName
     */
    private $identificationDocumentName = null;

    /**
     * 4.2 серия
     *
     * @property string $identificationDocumentSeries
     */
    private $identificationDocumentSeries = null;

    /**
     * 4.3 №
     *
     * @property string $identificationDocumentNumber
     */
    private $identificationDocumentNumber = null;

    /**
     * 4.4 Дата выдачи
     *
     * @property \DateTime $identificationDocumentIssueDate
     */
    private $identificationDocumentIssueDate = null;

    /**
     * 4.5 Наименование органа, выдавшего документ
     *
     * @property string $identificationDocumentIssueOrgName
     */
    private $identificationDocumentIssueOrgName = null;

    /**
     * 4.6 Код подразделения
     *
     * @property string $identificationDocumentIssueOrgCode
     */
    private $identificationDocumentIssueOrgCode = null;

    /**
     * Gets as identificationDocumentName
     *
     * 4.1 Наименование документа, удостоверяющего личность
     *
     * @return string
     */
    public function getIdentificationDocumentName()
    {
        return $this->identificationDocumentName;
    }

    /**
     * Sets a new identificationDocumentName
     *
     * 4.1 Наименование документа, удостоверяющего личность
     *
     * @param string $identificationDocumentName
     * @return static
     */
    public function setIdentificationDocumentName($identificationDocumentName)
    {
        $this->identificationDocumentName = $identificationDocumentName;
        return $this;
    }

    /**
     * Gets as identificationDocumentSeries
     *
     * 4.2 серия
     *
     * @return string
     */
    public function getIdentificationDocumentSeries()
    {
        return $this->identificationDocumentSeries;
    }

    /**
     * Sets a new identificationDocumentSeries
     *
     * 4.2 серия
     *
     * @param string $identificationDocumentSeries
     * @return static
     */
    public function setIdentificationDocumentSeries($identificationDocumentSeries)
    {
        $this->identificationDocumentSeries = $identificationDocumentSeries;
        return $this;
    }

    /**
     * Gets as identificationDocumentNumber
     *
     * 4.3 №
     *
     * @return string
     */
    public function getIdentificationDocumentNumber()
    {
        return $this->identificationDocumentNumber;
    }

    /**
     * Sets a new identificationDocumentNumber
     *
     * 4.3 №
     *
     * @param string $identificationDocumentNumber
     * @return static
     */
    public function setIdentificationDocumentNumber($identificationDocumentNumber)
    {
        $this->identificationDocumentNumber = $identificationDocumentNumber;
        return $this;
    }

    /**
     * Gets as identificationDocumentIssueDate
     *
     * 4.4 Дата выдачи
     *
     * @return \DateTime
     */
    public function getIdentificationDocumentIssueDate()
    {
        return $this->identificationDocumentIssueDate;
    }

    /**
     * Sets a new identificationDocumentIssueDate
     *
     * 4.4 Дата выдачи
     *
     * @param \DateTime $identificationDocumentIssueDate
     * @return static
     */
    public function setIdentificationDocumentIssueDate(\DateTime $identificationDocumentIssueDate)
    {
        $this->identificationDocumentIssueDate = $identificationDocumentIssueDate;
        return $this;
    }

    /**
     * Gets as identificationDocumentIssueOrgName
     *
     * 4.5 Наименование органа, выдавшего документ
     *
     * @return string
     */
    public function getIdentificationDocumentIssueOrgName()
    {
        return $this->identificationDocumentIssueOrgName;
    }

    /**
     * Sets a new identificationDocumentIssueOrgName
     *
     * 4.5 Наименование органа, выдавшего документ
     *
     * @param string $identificationDocumentIssueOrgName
     * @return static
     */
    public function setIdentificationDocumentIssueOrgName($identificationDocumentIssueOrgName)
    {
        $this->identificationDocumentIssueOrgName = $identificationDocumentIssueOrgName;
        return $this;
    }

    /**
     * Gets as identificationDocumentIssueOrgCode
     *
     * 4.6 Код подразделения
     *
     * @return string
     */
    public function getIdentificationDocumentIssueOrgCode()
    {
        return $this->identificationDocumentIssueOrgCode;
    }

    /**
     * Sets a new identificationDocumentIssueOrgCode
     *
     * 4.6 Код подразделения
     *
     * @param string $identificationDocumentIssueOrgCode
     * @return static
     */
    public function setIdentificationDocumentIssueOrgCode($identificationDocumentIssueOrgCode)
    {
        $this->identificationDocumentIssueOrgCode = $identificationDocumentIssueOrgCode;
        return $this;
    }


}


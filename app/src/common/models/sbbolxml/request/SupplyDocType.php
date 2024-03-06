<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing SupplyDocType
 *
 * Досылаемые документы
 * XSD Type: SupplyDoc
 */
class SupplyDocType extends DocBaseType
{

    /**
     * Дата составления документа
     *
     * @property \DateTime $docDate
     */
    private $docDate = null;

    /**
     * Номер документа
     *
     * @property string $docNum
     */
    private $docNum = null;

    /**
     * Значение Response/OrganizationsInfo/OrganizationsInfo/Branches/Branch/SystemName – системное имя подразделения банка, которое передается в рамках OrganizationsInfo
     *
     * @property string $bankName
     */
    private $bankName = null;

    /**
     * Внешний идентификатор документы, которому досылаются сущности ГОЗ
     *
     * @property string $payDocRuId
     */
    private $payDocRuId = null;

    /**
     * @property \common\models\sbbolxml\request\SuppDocsType\SuppDocAType[] $suppDocs
     */
    private $suppDocs = null;

    /**
     * Gets as docDate
     *
     * Дата составления документа
     *
     * @return \DateTime
     */
    public function getDocDate()
    {
        return $this->docDate;
    }

    /**
     * Sets a new docDate
     *
     * Дата составления документа
     *
     * @param \DateTime $docDate
     * @return static
     */
    public function setDocDate(\DateTime $docDate)
    {
        $this->docDate = $docDate;
        return $this;
    }

    /**
     * Gets as docNum
     *
     * Номер документа
     *
     * @return string
     */
    public function getDocNum()
    {
        return $this->docNum;
    }

    /**
     * Sets a new docNum
     *
     * Номер документа
     *
     * @param string $docNum
     * @return static
     */
    public function setDocNum($docNum)
    {
        $this->docNum = $docNum;
        return $this;
    }

    /**
     * Gets as bankName
     *
     * Значение Response/OrganizationsInfo/OrganizationsInfo/Branches/Branch/SystemName – системное имя подразделения банка, которое передается в рамках OrganizationsInfo
     *
     * @return string
     */
    public function getBankName()
    {
        return $this->bankName;
    }

    /**
     * Sets a new bankName
     *
     * Значение Response/OrganizationsInfo/OrganizationsInfo/Branches/Branch/SystemName – системное имя подразделения банка, которое передается в рамках OrganizationsInfo
     *
     * @param string $bankName
     * @return static
     */
    public function setBankName($bankName)
    {
        $this->bankName = $bankName;
        return $this;
    }

    /**
     * Gets as payDocRuId
     *
     * Внешний идентификатор документы, которому досылаются сущности ГОЗ
     *
     * @return string
     */
    public function getPayDocRuId()
    {
        return $this->payDocRuId;
    }

    /**
     * Sets a new payDocRuId
     *
     * Внешний идентификатор документы, которому досылаются сущности ГОЗ
     *
     * @param string $payDocRuId
     * @return static
     */
    public function setPayDocRuId($payDocRuId)
    {
        $this->payDocRuId = $payDocRuId;
        return $this;
    }

    /**
     * Adds as suppDoc
     *
     * @return static
     * @param \common\models\sbbolxml\request\SuppDocsType\SuppDocAType $suppDoc
     */
    public function addToSuppDocs(\common\models\sbbolxml\request\SuppDocsType\SuppDocAType $suppDoc)
    {
        $this->suppDocs[] = $suppDoc;
        return $this;
    }

    /**
     * isset suppDocs
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetSuppDocs($index)
    {
        return isset($this->suppDocs[$index]);
    }

    /**
     * unset suppDocs
     *
     * @param scalar $index
     * @return void
     */
    public function unsetSuppDocs($index)
    {
        unset($this->suppDocs[$index]);
    }

    /**
     * Gets as suppDocs
     *
     * @return \common\models\sbbolxml\request\SuppDocsType\SuppDocAType[]
     */
    public function getSuppDocs()
    {
        return $this->suppDocs;
    }

    /**
     * Sets a new suppDocs
     *
     * @param \common\models\sbbolxml\request\SuppDocsType\SuppDocAType[] $suppDocs
     * @return static
     */
    public function setSuppDocs(array $suppDocs)
    {
        $this->suppDocs = $suppDocs;
        return $this;
    }


}


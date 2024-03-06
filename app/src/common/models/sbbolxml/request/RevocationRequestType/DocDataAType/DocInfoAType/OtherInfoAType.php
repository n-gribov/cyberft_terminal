<?php

namespace common\models\sbbolxml\request\RevocationRequestType\DocDataAType\DocInfoAType;

/**
 * Class representing OtherInfoAType
 */
class OtherInfoAType
{

    /**
     * Тип отзываемого документа
     *
     * @property string $docType
     */
    private $docType = null;

    /**
     * Номер отзываемого документа
     *
     * @property string $docNumber
     */
    private $docNumber = null;

    /**
     * Дата отзываемого документа
     *
     * @property \DateTime $docDate
     */
    private $docDate = null;

    /**
     * Краткая информация об отзываемом документе
     *
     * @property string $documentInfo
     */
    private $documentInfo = null;

    /**
     * Дополнительная информация об отзываемом документе
     *
     * @property string $documentInfo2
     */
    private $documentInfo2 = null;

    /**
     * Gets as docType
     *
     * Тип отзываемого документа
     *
     * @return string
     */
    public function getDocType()
    {
        return $this->docType;
    }

    /**
     * Sets a new docType
     *
     * Тип отзываемого документа
     *
     * @param string $docType
     * @return static
     */
    public function setDocType($docType)
    {
        $this->docType = $docType;
        return $this;
    }

    /**
     * Gets as docNumber
     *
     * Номер отзываемого документа
     *
     * @return string
     */
    public function getDocNumber()
    {
        return $this->docNumber;
    }

    /**
     * Sets a new docNumber
     *
     * Номер отзываемого документа
     *
     * @param string $docNumber
     * @return static
     */
    public function setDocNumber($docNumber)
    {
        $this->docNumber = $docNumber;
        return $this;
    }

    /**
     * Gets as docDate
     *
     * Дата отзываемого документа
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
     * Дата отзываемого документа
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
     * Gets as documentInfo
     *
     * Краткая информация об отзываемом документе
     *
     * @return string
     */
    public function getDocumentInfo()
    {
        return $this->documentInfo;
    }

    /**
     * Sets a new documentInfo
     *
     * Краткая информация об отзываемом документе
     *
     * @param string $documentInfo
     * @return static
     */
    public function setDocumentInfo($documentInfo)
    {
        $this->documentInfo = $documentInfo;
        return $this;
    }

    /**
     * Gets as documentInfo2
     *
     * Дополнительная информация об отзываемом документе
     *
     * @return string
     */
    public function getDocumentInfo2()
    {
        return $this->documentInfo2;
    }

    /**
     * Sets a new documentInfo2
     *
     * Дополнительная информация об отзываемом документе
     *
     * @param string $documentInfo2
     * @return static
     */
    public function setDocumentInfo2($documentInfo2)
    {
        $this->documentInfo2 = $documentInfo2;
        return $this;
    }


}


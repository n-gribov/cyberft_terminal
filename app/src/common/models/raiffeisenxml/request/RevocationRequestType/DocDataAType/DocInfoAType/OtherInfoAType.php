<?php

namespace common\models\raiffeisenxml\request\RevocationRequestType\DocDataAType\DocInfoAType;

/**
 * Class representing OtherInfoAType
 */
class OtherInfoAType
{

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
     * Краткая информация об отзываемом документе. Должна передаваться информация об
     *  отзываемом документе, включая Дату, Номер, Счет, Сумму и Валюту
     *
     * @property string $documentInfo
     */
    private $documentInfo = null;

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
     * Краткая информация об отзываемом документе. Должна передаваться информация об
     *  отзываемом документе, включая Дату, Номер, Счет, Сумму и Валюту
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
     * Краткая информация об отзываемом документе. Должна передаваться информация об
     *  отзываемом документе, включая Дату, Номер, Счет, Сумму и Валюту
     *
     * @param string $documentInfo
     * @return static
     */
    public function setDocumentInfo($documentInfo)
    {
        $this->documentInfo = $documentInfo;
        return $this;
    }


}


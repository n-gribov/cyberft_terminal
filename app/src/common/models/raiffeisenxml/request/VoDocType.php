<?php

namespace common\models\raiffeisenxml\request;

/**
 * Class representing VoDocType
 *
 *
 * XSD Type: VoDoc
 */
class VoDocType
{

    /**
     * Тип обосн. документа
     *
     * @property string $docType
     */
    private $docType = null;

    /**
     * Осн. данные документа
     *
     * @property \common\models\raiffeisenxml\request\VoDocType\DocDataAType $docData
     */
    private $docData = null;

    /**
     * Примечание
     *
     * @property string $addInfo
     */
    private $addInfo = null;

    /**
     * Gets as docType
     *
     * Тип обосн. документа
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
     * Тип обосн. документа
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
     * Gets as docData
     *
     * Осн. данные документа
     *
     * @return \common\models\raiffeisenxml\request\VoDocType\DocDataAType
     */
    public function getDocData()
    {
        return $this->docData;
    }

    /**
     * Sets a new docData
     *
     * Осн. данные документа
     *
     * @param \common\models\raiffeisenxml\request\VoDocType\DocDataAType $docData
     * @return static
     */
    public function setDocData(\common\models\raiffeisenxml\request\VoDocType\DocDataAType $docData)
    {
        $this->docData = $docData;
        return $this;
    }

    /**
     * Gets as addInfo
     *
     * Примечание
     *
     * @return string
     */
    public function getAddInfo()
    {
        return $this->addInfo;
    }

    /**
     * Sets a new addInfo
     *
     * Примечание
     *
     * @param string $addInfo
     * @return static
     */
    public function setAddInfo($addInfo)
    {
        $this->addInfo = $addInfo;
        return $this;
    }


}


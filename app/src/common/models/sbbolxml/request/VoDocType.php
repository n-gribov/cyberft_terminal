<?php

namespace common\models\sbbolxml\request;

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
     * Наименование документа (из справочника системы ДБО)
     *
     * @property string $docName
     */
    private $docName = null;

    /**
     * Осн. данные документа
     *
     * @property \common\models\sbbolxml\request\DocDataType $docData
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
     * Gets as docName
     *
     * Наименование документа (из справочника системы ДБО)
     *
     * @return string
     */
    public function getDocName()
    {
        return $this->docName;
    }

    /**
     * Sets a new docName
     *
     * Наименование документа (из справочника системы ДБО)
     *
     * @param string $docName
     * @return static
     */
    public function setDocName($docName)
    {
        $this->docName = $docName;
        return $this;
    }

    /**
     * Gets as docData
     *
     * Осн. данные документа
     *
     * @return \common\models\sbbolxml\request\DocDataType
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
     * @param \common\models\sbbolxml\request\DocDataType $docData
     * @return static
     */
    public function setDocData(\common\models\sbbolxml\request\DocDataType $docData)
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


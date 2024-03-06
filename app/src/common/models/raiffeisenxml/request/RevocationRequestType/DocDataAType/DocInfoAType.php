<?php

namespace common\models\raiffeisenxml\request\RevocationRequestType\DocDataAType;

/**
 * Class representing DocInfoAType
 */
class DocInfoAType
{

    /**
     * Тип отзываемого документа
     *
     * @property string $docType
     */
    private $docType = null;

    /**
     * Идентификатор документа в Системе клиента
     *
     * @property string $docExtId
     */
    private $docExtId = null;

    /**
     * Причина отзыва
     *
     * @property string $addInfo
     */
    private $addInfo = null;

    /**
     * Прочая информация об отзываемом документе
     *
     * @property \common\models\raiffeisenxml\request\RevocationRequestType\DocDataAType\DocInfoAType\OtherInfoAType $otherInfo
     */
    private $otherInfo = null;

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
     * Gets as docExtId
     *
     * Идентификатор документа в Системе клиента
     *
     * @return string
     */
    public function getDocExtId()
    {
        return $this->docExtId;
    }

    /**
     * Sets a new docExtId
     *
     * Идентификатор документа в Системе клиента
     *
     * @param string $docExtId
     * @return static
     */
    public function setDocExtId($docExtId)
    {
        $this->docExtId = $docExtId;
        return $this;
    }

    /**
     * Gets as addInfo
     *
     * Причина отзыва
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
     * Причина отзыва
     *
     * @param string $addInfo
     * @return static
     */
    public function setAddInfo($addInfo)
    {
        $this->addInfo = $addInfo;
        return $this;
    }

    /**
     * Gets as otherInfo
     *
     * Прочая информация об отзываемом документе
     *
     * @return \common\models\raiffeisenxml\request\RevocationRequestType\DocDataAType\DocInfoAType\OtherInfoAType
     */
    public function getOtherInfo()
    {
        return $this->otherInfo;
    }

    /**
     * Sets a new otherInfo
     *
     * Прочая информация об отзываемом документе
     *
     * @param \common\models\raiffeisenxml\request\RevocationRequestType\DocDataAType\DocInfoAType\OtherInfoAType $otherInfo
     * @return static
     */
    public function setOtherInfo(\common\models\raiffeisenxml\request\RevocationRequestType\DocDataAType\DocInfoAType\OtherInfoAType $otherInfo)
    {
        $this->otherInfo = $otherInfo;
        return $this;
    }


}


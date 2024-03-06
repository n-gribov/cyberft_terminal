<?php

namespace common\models\sbbolxml\request\RevocationRequestType\DocDataAType;

/**
 * Class representing DocInfoAType
 */
class DocInfoAType
{

    /**
     * Идентификатор отзываемого документа в Correqts
     *
     * @property string $docId
     */
    private $docId = null;

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
     * @property \common\models\sbbolxml\request\RevocationRequestType\DocDataAType\DocInfoAType\OtherInfoAType $otherInfo
     */
    private $otherInfo = null;

    /**
     * Gets as docId
     *
     * Идентификатор отзываемого документа в Correqts
     *
     * @return string
     */
    public function getDocId()
    {
        return $this->docId;
    }

    /**
     * Sets a new docId
     *
     * Идентификатор отзываемого документа в Correqts
     *
     * @param string $docId
     * @return static
     */
    public function setDocId($docId)
    {
        $this->docId = $docId;
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
     * @return \common\models\sbbolxml\request\RevocationRequestType\DocDataAType\DocInfoAType\OtherInfoAType
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
     * @param \common\models\sbbolxml\request\RevocationRequestType\DocDataAType\DocInfoAType\OtherInfoAType $otherInfo
     * @return static
     */
    public function setOtherInfo(\common\models\sbbolxml\request\RevocationRequestType\DocDataAType\DocInfoAType\OtherInfoAType $otherInfo)
    {
        $this->otherInfo = $otherInfo;
        return $this;
    }


}


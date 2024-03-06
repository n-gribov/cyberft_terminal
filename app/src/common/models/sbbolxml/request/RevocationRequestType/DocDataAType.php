<?php

namespace common\models\sbbolxml\request\RevocationRequestType;

/**
 * Class representing DocDataAType
 */
class DocDataAType
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
     * Основные реквизиты организации, указываемые в документе
     *
     * @property \common\models\sbbolxml\request\OrgDataCCType $orgData
     */
    private $orgData = null;

    /**
     * Информация об отзываемом документе
     *
     * @property \common\models\sbbolxml\request\RevocationRequestType\DocDataAType\DocInfoAType $docInfo
     */
    private $docInfo = null;

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
     * Gets as orgData
     *
     * Основные реквизиты организации, указываемые в документе
     *
     * @return \common\models\sbbolxml\request\OrgDataCCType
     */
    public function getOrgData()
    {
        return $this->orgData;
    }

    /**
     * Sets a new orgData
     *
     * Основные реквизиты организации, указываемые в документе
     *
     * @param \common\models\sbbolxml\request\OrgDataCCType $orgData
     * @return static
     */
    public function setOrgData(\common\models\sbbolxml\request\OrgDataCCType $orgData)
    {
        $this->orgData = $orgData;
        return $this;
    }

    /**
     * Gets as docInfo
     *
     * Информация об отзываемом документе
     *
     * @return \common\models\sbbolxml\request\RevocationRequestType\DocDataAType\DocInfoAType
     */
    public function getDocInfo()
    {
        return $this->docInfo;
    }

    /**
     * Sets a new docInfo
     *
     * Информация об отзываемом документе
     *
     * @param \common\models\sbbolxml\request\RevocationRequestType\DocDataAType\DocInfoAType $docInfo
     * @return static
     */
    public function setDocInfo(\common\models\sbbolxml\request\RevocationRequestType\DocDataAType\DocInfoAType $docInfo)
    {
        $this->docInfo = $docInfo;
        return $this;
    }


}


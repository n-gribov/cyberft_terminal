<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing DocDataCCICSRestructType
 *
 * Реквизиты документа
 * XSD Type: DocDataCCICSRestruct
 */
class DocDataCCICSRestructType
{

    /**
     * Дата создания документа по местному времени
     *
     * @property \DateTime $docDate
     */
    private $docDate = null;

    /**
     * Номер сформированного документа
     *
     * @property string $docNum
     */
    private $docNum = null;

    /**
     * Основные реквизиты организации, указываемые в документе
     *
     * @property \common\models\sbbolxml\request\OrgDataICSRestructType $orgData
     */
    private $orgData = null;

    /**
     * Данные отв.
     *
     * @property \common\models\sbbolxml\request\AuthPersICSRestructType $authPers
     */
    private $authPers = null;

    /**
     * Gets as docDate
     *
     * Дата создания документа по местному времени
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
     * Дата создания документа по местному времени
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
     * Номер сформированного документа
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
     * Номер сформированного документа
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
     * @return \common\models\sbbolxml\request\OrgDataICSRestructType
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
     * @param \common\models\sbbolxml\request\OrgDataICSRestructType $orgData
     * @return static
     */
    public function setOrgData(\common\models\sbbolxml\request\OrgDataICSRestructType $orgData)
    {
        $this->orgData = $orgData;
        return $this;
    }

    /**
     * Gets as authPers
     *
     * Данные отв.
     *
     * @return \common\models\sbbolxml\request\AuthPersICSRestructType
     */
    public function getAuthPers()
    {
        return $this->authPers;
    }

    /**
     * Sets a new authPers
     *
     * Данные отв.
     *
     * @param \common\models\sbbolxml\request\AuthPersICSRestructType $authPers
     * @return static
     */
    public function setAuthPers(\common\models\sbbolxml\request\AuthPersICSRestructType $authPers)
    {
        $this->authPers = $authPers;
        return $this;
    }


}


<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing DocDataCCType
 *
 * Общие реквизиты ВК (паспортов сделок) ДБО
 * XSD Type: DocDataCC
 */
class DocDataCCType
{

    /**
     * Системное наименование подразделения банка
     *
     * @property string $branchSystemName
     */
    private $branchSystemName = null;

    /**
     * Номер сформированного документа
     *
     * @property string $docNum
     */
    private $docNum = null;

    /**
     * Дата создания документа по местному времени
     *
     * @property \DateTime $docDate
     */
    private $docDate = null;

    /**
     * Признак "Срочное оформление". Возможные значения – 0 (признак не установлен), 1 (признак установлен)
     *
     * @property boolean $urgent
     */
    private $urgent = null;

    /**
     * Наименование уполномоченного банка
     *
     * @property string $authBankName
     */
    private $authBankName = null;

    /**
     * БИК уполномоченного банка
     *
     * @property string $authBankBIC
     */
    private $authBankBIC = null;

    /**
     * Уполномоченный сотрудник организации клиента
     *
     * @property \common\models\sbbolxml\response\AuthPersType $authPers
     */
    private $authPers = null;

    /**
     * Основные реквизиты организации, указываемые в документе
     *
     * @property \common\models\sbbolxml\response\OrgDataCCType $orgData
     */
    private $orgData = null;

    /**
     * Gets as branchSystemName
     *
     * Системное наименование подразделения банка
     *
     * @return string
     */
    public function getBranchSystemName()
    {
        return $this->branchSystemName;
    }

    /**
     * Sets a new branchSystemName
     *
     * Системное наименование подразделения банка
     *
     * @param string $branchSystemName
     * @return static
     */
    public function setBranchSystemName($branchSystemName)
    {
        $this->branchSystemName = $branchSystemName;
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
     * Gets as urgent
     *
     * Признак "Срочное оформление". Возможные значения – 0 (признак не установлен), 1 (признак установлен)
     *
     * @return boolean
     */
    public function getUrgent()
    {
        return $this->urgent;
    }

    /**
     * Sets a new urgent
     *
     * Признак "Срочное оформление". Возможные значения – 0 (признак не установлен), 1 (признак установлен)
     *
     * @param boolean $urgent
     * @return static
     */
    public function setUrgent($urgent)
    {
        $this->urgent = $urgent;
        return $this;
    }

    /**
     * Gets as authBankName
     *
     * Наименование уполномоченного банка
     *
     * @return string
     */
    public function getAuthBankName()
    {
        return $this->authBankName;
    }

    /**
     * Sets a new authBankName
     *
     * Наименование уполномоченного банка
     *
     * @param string $authBankName
     * @return static
     */
    public function setAuthBankName($authBankName)
    {
        $this->authBankName = $authBankName;
        return $this;
    }

    /**
     * Gets as authBankBIC
     *
     * БИК уполномоченного банка
     *
     * @return string
     */
    public function getAuthBankBIC()
    {
        return $this->authBankBIC;
    }

    /**
     * Sets a new authBankBIC
     *
     * БИК уполномоченного банка
     *
     * @param string $authBankBIC
     * @return static
     */
    public function setAuthBankBIC($authBankBIC)
    {
        $this->authBankBIC = $authBankBIC;
        return $this;
    }

    /**
     * Gets as authPers
     *
     * Уполномоченный сотрудник организации клиента
     *
     * @return \common\models\sbbolxml\response\AuthPersType
     */
    public function getAuthPers()
    {
        return $this->authPers;
    }

    /**
     * Sets a new authPers
     *
     * Уполномоченный сотрудник организации клиента
     *
     * @param \common\models\sbbolxml\response\AuthPersType $authPers
     * @return static
     */
    public function setAuthPers(\common\models\sbbolxml\response\AuthPersType $authPers)
    {
        $this->authPers = $authPers;
        return $this;
    }

    /**
     * Gets as orgData
     *
     * Основные реквизиты организации, указываемые в документе
     *
     * @return \common\models\sbbolxml\response\OrgDataCCType
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
     * @param \common\models\sbbolxml\response\OrgDataCCType $orgData
     * @return static
     */
    public function setOrgData(\common\models\sbbolxml\response\OrgDataCCType $orgData)
    {
        $this->orgData = $orgData;
        return $this;
    }


}


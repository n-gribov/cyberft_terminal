<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing DocDataConf181IType
 *
 *
 * XSD Type: DocDataConf181I
 */
class DocDataConf181IType
{

    /**
     * Дата составления документа
     *
     * @property \DateTime $docDate
     */
    private $docDate = null;

    /**
     * Номер документа (клиентский)
     *
     * @property string $docNumber
     */
    private $docNumber = null;

    /**
     * Справка от (дата справки)
     *
     * @property \DateTime $statementFrom
     */
    private $statementFrom = null;

    /**
     * Системное наименование подразделения банка
     *
     * @property string $branchSystemName
     */
    private $branchSystemName = null;

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
     * Основные реквизиты организации, указываемые в документе
     *
     * @property \common\models\sbbolxml\request\OrgDataCCType $orgData
     */
    private $orgData = null;

    /**
     * Номер ПС (строка со слешами)
     *
     * @property string $contractNum
     */
    private $contractNum = null;

    /**
     * Уполномоченный сотрудник организации клиента
     *
     * @property \common\models\sbbolxml\request\AuthPersType $authPers
     */
    private $authPers = null;

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
     * Gets as docNumber
     *
     * Номер документа (клиентский)
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
     * Номер документа (клиентский)
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
     * Gets as statementFrom
     *
     * Справка от (дата справки)
     *
     * @return \DateTime
     */
    public function getStatementFrom()
    {
        return $this->statementFrom;
    }

    /**
     * Sets a new statementFrom
     *
     * Справка от (дата справки)
     *
     * @param \DateTime $statementFrom
     * @return static
     */
    public function setStatementFrom(\DateTime $statementFrom)
    {
        $this->statementFrom = $statementFrom;
        return $this;
    }

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
     * Gets as contractNum
     *
     * Номер ПС (строка со слешами)
     *
     * @return string
     */
    public function getContractNum()
    {
        return $this->contractNum;
    }

    /**
     * Sets a new contractNum
     *
     * Номер ПС (строка со слешами)
     *
     * @param string $contractNum
     * @return static
     */
    public function setContractNum($contractNum)
    {
        $this->contractNum = $contractNum;
        return $this;
    }

    /**
     * Gets as authPers
     *
     * Уполномоченный сотрудник организации клиента
     *
     * @return \common\models\sbbolxml\request\AuthPersType
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
     * @param \common\models\sbbolxml\request\AuthPersType $authPers
     * @return static
     */
    public function setAuthPers(\common\models\sbbolxml\request\AuthPersType $authPers)
    {
        $this->authPers = $authPers;
        return $this;
    }


}

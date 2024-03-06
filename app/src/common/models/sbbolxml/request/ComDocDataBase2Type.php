<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing ComDocDataBase2Type
 *
 *
 * XSD Type: ComDocDataBase2
 */
class ComDocDataBase2Type
{

    /**
     * Дата составления документа
     *
     * @property \DateTime $docDate
     */
    private $docDate = null;

    /**
     * Значение Response/OrganizationsInfo/OrganizationsInfo/Branches/Branch/SystemName –
     *  системное имя подразделения
     *  банка, которое передается в рамках OrganizationsInfo
     *
     * @property string $bankName
     */
    private $bankName = null;

    /**
     * Номер подразделения банка получателя заявления в ДБО
     *
     * @property string $bankNum
     */
    private $bankNum = null;

    /**
     * Идентификатор договора депозита
     *
     * @property string $initialCard
     */
    private $initialCard = null;

    /**
     * Основные реквизиты организации, указываемые в документе
     *
     * @property \common\models\sbbolxml\request\OrgDataType $orgData
     */
    private $orgData = null;

    /**
     * Уполномоченный сотрудник организации клиента
     *
     * @property \common\models\sbbolxml\request\AuthPers2Type $authPers
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
     * Gets as bankName
     *
     * Значение Response/OrganizationsInfo/OrganizationsInfo/Branches/Branch/SystemName –
     *  системное имя подразделения
     *  банка, которое передается в рамках OrganizationsInfo
     *
     * @return string
     */
    public function getBankName()
    {
        return $this->bankName;
    }

    /**
     * Sets a new bankName
     *
     * Значение Response/OrganizationsInfo/OrganizationsInfo/Branches/Branch/SystemName –
     *  системное имя подразделения
     *  банка, которое передается в рамках OrganizationsInfo
     *
     * @param string $bankName
     * @return static
     */
    public function setBankName($bankName)
    {
        $this->bankName = $bankName;
        return $this;
    }

    /**
     * Gets as bankNum
     *
     * Номер подразделения банка получателя заявления в ДБО
     *
     * @return string
     */
    public function getBankNum()
    {
        return $this->bankNum;
    }

    /**
     * Sets a new bankNum
     *
     * Номер подразделения банка получателя заявления в ДБО
     *
     * @param string $bankNum
     * @return static
     */
    public function setBankNum($bankNum)
    {
        $this->bankNum = $bankNum;
        return $this;
    }

    /**
     * Gets as initialCard
     *
     * Идентификатор договора депозита
     *
     * @return string
     */
    public function getInitialCard()
    {
        return $this->initialCard;
    }

    /**
     * Sets a new initialCard
     *
     * Идентификатор договора депозита
     *
     * @param string $initialCard
     * @return static
     */
    public function setInitialCard($initialCard)
    {
        $this->initialCard = $initialCard;
        return $this;
    }

    /**
     * Gets as orgData
     *
     * Основные реквизиты организации, указываемые в документе
     *
     * @return \common\models\sbbolxml\request\OrgDataType
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
     * @param \common\models\sbbolxml\request\OrgDataType $orgData
     * @return static
     */
    public function setOrgData(\common\models\sbbolxml\request\OrgDataType $orgData)
    {
        $this->orgData = $orgData;
        return $this;
    }

    /**
     * Gets as authPers
     *
     * Уполномоченный сотрудник организации клиента
     *
     * @return \common\models\sbbolxml\request\AuthPers2Type
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
     * @param \common\models\sbbolxml\request\AuthPers2Type $authPers
     * @return static
     */
    public function setAuthPers(\common\models\sbbolxml\request\AuthPers2Type $authPers)
    {
        $this->authPers = $authPers;
        return $this;
    }


}


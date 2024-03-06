<?php

namespace common\models\raiffeisenxml\request;

/**
 * Class representing DocDataConf181IRaifType
 *
 *
 * XSD Type: DocDataConf181IRaif
 */
class DocDataConf181IRaifType
{

    /**
     * Номер документа
     *
     * @property string $docNum
     */
    private $docNum = null;

    /**
     * @property string $accNum
     */
    private $accNum = null;

    /**
     * @property string $bic
     */
    private $bic = null;

    /**
     * Наименование уполномоченного банка
     *
     * @property string $authBankName
     */
    private $authBankName = null;

    /**
     * Дата справки
     *
     * @property \DateTime $certificate181IDate
     */
    private $certificate181IDate = null;

    /**
     * Основные реквизиты организации, указываемые в документе
     *
     * @property \common\models\raiffeisenxml\request\OrgDataCCType $orgData
     */
    private $orgData = null;

    /**
     * Уполномоченный сотрудник организации клиента
     *
     * @property \common\models\raiffeisenxml\request\AuthPersCCType $authPers
     */
    private $authPers = null;

    /**
     * Признак корректировки
     *  0 - нет
     *  1 - да
     *
     * @property bool $updating
     */
    private $updating = null;

    /**
     * Номер ПС (строка со слешами)
     *
     * @property string $dealPassNum
     */
    private $dealPassNum = null;

    /**
     * Срочное формление СПД
     *  0 - нет
     *  1 - да
     *
     * @property bool $urgent
     */
    private $urgent = null;

    /**
     * Технический аутсорсинг
     *  0 - нет
     *  1 - да
     *
     * @property bool $outsource
     */
    private $outsource = null;

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
     * Gets as accNum
     *
     * @return string
     */
    public function getAccNum()
    {
        return $this->accNum;
    }

    /**
     * Sets a new accNum
     *
     * @param string $accNum
     * @return static
     */
    public function setAccNum($accNum)
    {
        $this->accNum = $accNum;
        return $this;
    }

    /**
     * Gets as bic
     *
     * @return string
     */
    public function getBic()
    {
        return $this->bic;
    }

    /**
     * Sets a new bic
     *
     * @param string $bic
     * @return static
     */
    public function setBic($bic)
    {
        $this->bic = $bic;
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
     * Gets as certificate181IDate
     *
     * Дата справки
     *
     * @return \DateTime
     */
    public function getCertificate181IDate()
    {
        return $this->certificate181IDate;
    }

    /**
     * Sets a new certificate181IDate
     *
     * Дата справки
     *
     * @param \DateTime $certificate181IDate
     * @return static
     */
    public function setCertificate181IDate(\DateTime $certificate181IDate)
    {
        $this->certificate181IDate = $certificate181IDate;
        return $this;
    }

    /**
     * Gets as orgData
     *
     * Основные реквизиты организации, указываемые в документе
     *
     * @return \common\models\raiffeisenxml\request\OrgDataCCType
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
     * @param \common\models\raiffeisenxml\request\OrgDataCCType $orgData
     * @return static
     */
    public function setOrgData(\common\models\raiffeisenxml\request\OrgDataCCType $orgData)
    {
        $this->orgData = $orgData;
        return $this;
    }

    /**
     * Gets as authPers
     *
     * Уполномоченный сотрудник организации клиента
     *
     * @return \common\models\raiffeisenxml\request\AuthPersCCType
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
     * @param \common\models\raiffeisenxml\request\AuthPersCCType $authPers
     * @return static
     */
    public function setAuthPers(\common\models\raiffeisenxml\request\AuthPersCCType $authPers)
    {
        $this->authPers = $authPers;
        return $this;
    }

    /**
     * Gets as updating
     *
     * Признак корректировки
     *  0 - нет
     *  1 - да
     *
     * @return bool
     */
    public function getUpdating()
    {
        return $this->updating;
    }

    /**
     * Sets a new updating
     *
     * Признак корректировки
     *  0 - нет
     *  1 - да
     *
     * @param bool $updating
     * @return static
     */
    public function setUpdating($updating)
    {
        $this->updating = $updating;
        return $this;
    }

    /**
     * Gets as dealPassNum
     *
     * Номер ПС (строка со слешами)
     *
     * @return string
     */
    public function getDealPassNum()
    {
        return $this->dealPassNum;
    }

    /**
     * Sets a new dealPassNum
     *
     * Номер ПС (строка со слешами)
     *
     * @param string $dealPassNum
     * @return static
     */
    public function setDealPassNum($dealPassNum)
    {
        $this->dealPassNum = $dealPassNum;
        return $this;
    }

    /**
     * Gets as urgent
     *
     * Срочное формление СПД
     *  0 - нет
     *  1 - да
     *
     * @return bool
     */
    public function getUrgent()
    {
        return $this->urgent;
    }

    /**
     * Sets a new urgent
     *
     * Срочное формление СПД
     *  0 - нет
     *  1 - да
     *
     * @param bool $urgent
     * @return static
     */
    public function setUrgent($urgent)
    {
        $this->urgent = $urgent;
        return $this;
    }

    /**
     * Gets as outsource
     *
     * Технический аутсорсинг
     *  0 - нет
     *  1 - да
     *
     * @return bool
     */
    public function getOutsource()
    {
        return $this->outsource;
    }

    /**
     * Sets a new outsource
     *
     * Технический аутсорсинг
     *  0 - нет
     *  1 - да
     *
     * @param bool $outsource
     * @return static
     */
    public function setOutsource($outsource)
    {
        $this->outsource = $outsource;
        return $this;
    }


}


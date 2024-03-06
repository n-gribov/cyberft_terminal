<?php

namespace common\models\raiffeisenxml\request;

/**
 * Class representing DocDataConf138IRaifType
 *
 *
 * XSD Type: DocDataConf138IRaif
 */
class DocDataConf138IRaifType
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
     * @property \DateTime $certificate138IDate
     */
    private $certificate138IDate = null;

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
     * Номер корректировки, до 3 цифр
     *
     * @property int $correctionNum
     */
    private $correctionNum = null;

    /**
     * Номер ПС (строка со слешами)
     *
     * @property string $dealPassNum
     */
    private $dealPassNum = null;

    /**
     * СПД под платеж
     *
     * @property \common\models\raiffeisenxml\request\DocDataConf138IRaifType\AgainstPaymentAType $againstPayment
     */
    private $againstPayment = null;

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
     * Gets as certificate138IDate
     *
     * Дата справки
     *
     * @return \DateTime
     */
    public function getCertificate138IDate()
    {
        return $this->certificate138IDate;
    }

    /**
     * Sets a new certificate138IDate
     *
     * Дата справки
     *
     * @param \DateTime $certificate138IDate
     * @return static
     */
    public function setCertificate138IDate(\DateTime $certificate138IDate)
    {
        $this->certificate138IDate = $certificate138IDate;
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
     * Gets as correctionNum
     *
     * Номер корректировки, до 3 цифр
     *
     * @return int
     */
    public function getCorrectionNum()
    {
        return $this->correctionNum;
    }

    /**
     * Sets a new correctionNum
     *
     * Номер корректировки, до 3 цифр
     *
     * @param int $correctionNum
     * @return static
     */
    public function setCorrectionNum($correctionNum)
    {
        $this->correctionNum = $correctionNum;
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
     * Gets as againstPayment
     *
     * СПД под платеж
     *
     * @return \common\models\raiffeisenxml\request\DocDataConf138IRaifType\AgainstPaymentAType
     */
    public function getAgainstPayment()
    {
        return $this->againstPayment;
    }

    /**
     * Sets a new againstPayment
     *
     * СПД под платеж
     *
     * @param \common\models\raiffeisenxml\request\DocDataConf138IRaifType\AgainstPaymentAType $againstPayment
     * @return static
     */
    public function setAgainstPayment(\common\models\raiffeisenxml\request\DocDataConf138IRaifType\AgainstPaymentAType $againstPayment)
    {
        $this->againstPayment = $againstPayment;
        return $this;
    }


}


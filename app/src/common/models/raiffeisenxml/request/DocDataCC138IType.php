<?php

namespace common\models\raiffeisenxml\request;

/**
 * Class representing DocDataCC138IType
 *
 *
 * XSD Type: DocDataCC138I
 */
class DocDataCC138IType
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
     * Выдать на руки
     *
     * @property bool $onHands
     */
    private $onHands = null;

    /**
     * Выслать почтой
     *
     * @property bool $byMail
     */
    private $byMail = null;

    /**
     * Выслать электронной почтой
     *
     * @property bool $byEmail
     */
    private $byEmail = null;

    /**
     * Основные реквизиты организации, указываемые в документе
     *
     * @property \common\models\raiffeisenxml\request\OrgDataCCType $orgData
     */
    private $orgData = null;

    /**
     * Уполномоченный сотрудник организации клиента
     *
     * @property \common\models\raiffeisenxml\request\AuthPersType $authPers
     */
    private $authPers = null;

    /**
     * Код страны банка нерезедента
     *
     * @property \common\models\raiffeisenxml\request\CountryNameType $country
     */
    private $country = null;

    /**
     * Признак корректировки
     *  0 - нет
     *  1 - да
     *
     * @property bool $updating
     */
    private $updating = null;

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
     * Gets as onHands
     *
     * Выдать на руки
     *
     * @return bool
     */
    public function getOnHands()
    {
        return $this->onHands;
    }

    /**
     * Sets a new onHands
     *
     * Выдать на руки
     *
     * @param bool $onHands
     * @return static
     */
    public function setOnHands($onHands)
    {
        $this->onHands = $onHands;
        return $this;
    }

    /**
     * Gets as byMail
     *
     * Выслать почтой
     *
     * @return bool
     */
    public function getByMail()
    {
        return $this->byMail;
    }

    /**
     * Sets a new byMail
     *
     * Выслать почтой
     *
     * @param bool $byMail
     * @return static
     */
    public function setByMail($byMail)
    {
        $this->byMail = $byMail;
        return $this;
    }

    /**
     * Gets as byEmail
     *
     * Выслать электронной почтой
     *
     * @return bool
     */
    public function getByEmail()
    {
        return $this->byEmail;
    }

    /**
     * Sets a new byEmail
     *
     * Выслать электронной почтой
     *
     * @param bool $byEmail
     * @return static
     */
    public function setByEmail($byEmail)
    {
        $this->byEmail = $byEmail;
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
     * @return \common\models\raiffeisenxml\request\AuthPersType
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
     * @param \common\models\raiffeisenxml\request\AuthPersType $authPers
     * @return static
     */
    public function setAuthPers(\common\models\raiffeisenxml\request\AuthPersType $authPers)
    {
        $this->authPers = $authPers;
        return $this;
    }

    /**
     * Gets as country
     *
     * Код страны банка нерезедента
     *
     * @return \common\models\raiffeisenxml\request\CountryNameType
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Sets a new country
     *
     * Код страны банка нерезедента
     *
     * @param \common\models\raiffeisenxml\request\CountryNameType $country
     * @return static
     */
    public function setCountry(\common\models\raiffeisenxml\request\CountryNameType $country)
    {
        $this->country = $country;
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


}


<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing DocDataCC181IType
 *
 *
 * XSD Type: DocDataCC181I
 */
class DocDataCC181IType
{

    /**
     * Дата документа
     *
     * @property \DateTime $docDate
     */
    private $docDate = null;

    /**
     * Номер документа (клиентский)
     *
     * @property string $dealNum
     */
    private $dealNum = null;

    /**
     * Признак счёта в другом банке
     *
     * @property boolean $accountInOtherBank
     */
    private $accountInOtherBank = null;

    /**
     * Дата справки
     *
     * @property string $account
     */
    private $account = null;

    /**
     * Данные валютной операции
     *
     * @property \common\models\sbbolxml\request\PaymentType $payment
     */
    private $payment = null;

    /**
     * Основные реквизиты организации - резидента, указываемые в документе
     *
     * @property \common\models\sbbolxml\request\OrgDataCCType $orgData
     */
    private $orgData = null;

    /**
     * Признак корректировки (0 – признак не установлен; 1 – признак установлен)
     *
     * @property boolean $adjustment
     */
    private $adjustment = null;

    /**
     * Порядковый номер корректировки
     *
     * @property integer $adjustmentNumber
     */
    private $adjustmentNumber = null;

    /**
     * Код страны банка-нерезидента
     *
     * @property \common\models\sbbolxml\request\CountryNameType $country
     */
    private $country = null;

    /**
     * Дополнительная информация
     *
     * @property string $addInfo
     */
    private $addInfo = null;

    /**
     * ФИО и телефон исполнителя
     *
     * @property \common\models\sbbolxml\request\AuthPersType $authPers
     */
    private $authPers = null;

    /**
     * Gets as docDate
     *
     * Дата документа
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
     * Дата документа
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
     * Gets as dealNum
     *
     * Номер документа (клиентский)
     *
     * @return string
     */
    public function getDealNum()
    {
        return $this->dealNum;
    }

    /**
     * Sets a new dealNum
     *
     * Номер документа (клиентский)
     *
     * @param string $dealNum
     * @return static
     */
    public function setDealNum($dealNum)
    {
        $this->dealNum = $dealNum;
        return $this;
    }

    /**
     * Gets as accountInOtherBank
     *
     * Признак счёта в другом банке
     *
     * @return boolean
     */
    public function getAccountInOtherBank()
    {
        return $this->accountInOtherBank;
    }

    /**
     * Sets a new accountInOtherBank
     *
     * Признак счёта в другом банке
     *
     * @param boolean $accountInOtherBank
     * @return static
     */
    public function setAccountInOtherBank($accountInOtherBank)
    {
        $this->accountInOtherBank = $accountInOtherBank;
        return $this;
    }

    /**
     * Gets as account
     *
     * Дата справки
     *
     * @return string
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * Sets a new account
     *
     * Дата справки
     *
     * @param string $account
     * @return static
     */
    public function setAccount($account)
    {
        $this->account = $account;
        return $this;
    }

    /**
     * Gets as payment
     *
     * Данные валютной операции
     *
     * @return \common\models\sbbolxml\request\PaymentType
     */
    public function getPayment()
    {
        return $this->payment;
    }

    /**
     * Sets a new payment
     *
     * Данные валютной операции
     *
     * @param \common\models\sbbolxml\request\PaymentType $payment
     * @return static
     */
    public function setPayment(\common\models\sbbolxml\request\PaymentType $payment)
    {
        $this->payment = $payment;
        return $this;
    }

    /**
     * Gets as orgData
     *
     * Основные реквизиты организации - резидента, указываемые в документе
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
     * Основные реквизиты организации - резидента, указываемые в документе
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
     * Gets as adjustment
     *
     * Признак корректировки (0 – признак не установлен; 1 – признак установлен)
     *
     * @return boolean
     */
    public function getAdjustment()
    {
        return $this->adjustment;
    }

    /**
     * Sets a new adjustment
     *
     * Признак корректировки (0 – признак не установлен; 1 – признак установлен)
     *
     * @param boolean $adjustment
     * @return static
     */
    public function setAdjustment($adjustment)
    {
        $this->adjustment = $adjustment;
        return $this;
    }

    /**
     * Gets as adjustmentNumber
     *
     * Порядковый номер корректировки
     *
     * @return integer
     */
    public function getAdjustmentNumber()
    {
        return $this->adjustmentNumber;
    }

    /**
     * Sets a new adjustmentNumber
     *
     * Порядковый номер корректировки
     *
     * @param integer $adjustmentNumber
     * @return static
     */
    public function setAdjustmentNumber($adjustmentNumber)
    {
        $this->adjustmentNumber = $adjustmentNumber;
        return $this;
    }

    /**
     * Gets as country
     *
     * Код страны банка-нерезидента
     *
     * @return \common\models\sbbolxml\request\CountryNameType
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Sets a new country
     *
     * Код страны банка-нерезидента
     *
     * @param \common\models\sbbolxml\request\CountryNameType $country
     * @return static
     */
    public function setCountry(\common\models\sbbolxml\request\CountryNameType $country)
    {
        $this->country = $country;
        return $this;
    }

    /**
     * Gets as addInfo
     *
     * Дополнительная информация
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
     * Дополнительная информация
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
     * Gets as authPers
     *
     * ФИО и телефон исполнителя
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
     * ФИО и телефон исполнителя
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


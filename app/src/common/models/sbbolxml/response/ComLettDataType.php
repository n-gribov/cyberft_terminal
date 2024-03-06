<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing ComLettDataType
 *
 *
 * XSD Type: ComLettData
 */
class ComLettDataType
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
     * Наименование подразделения банка получателя заявления
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
     * Обязательно для прочтения: 0-нет, 1-да
     *
     * @property boolean $requiredForRead
     */
    private $requiredForRead = null;

    /**
     * Основные реквизиты организации, указываемые в документе
     *
     * @property \common\models\sbbolxml\response\OrgDataType $orgData
     */
    private $orgData = null;

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
     * Gets as bankName
     *
     * Наименование подразделения банка получателя заявления
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
     * Наименование подразделения банка получателя заявления
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
     * Gets as requiredForRead
     *
     * Обязательно для прочтения: 0-нет, 1-да
     *
     * @return boolean
     */
    public function getRequiredForRead()
    {
        return $this->requiredForRead;
    }

    /**
     * Sets a new requiredForRead
     *
     * Обязательно для прочтения: 0-нет, 1-да
     *
     * @param boolean $requiredForRead
     * @return static
     */
    public function setRequiredForRead($requiredForRead)
    {
        $this->requiredForRead = $requiredForRead;
        return $this;
    }

    /**
     * Gets as orgData
     *
     * Основные реквизиты организации, указываемые в документе
     *
     * @return \common\models\sbbolxml\response\OrgDataType
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
     * @param \common\models\sbbolxml\response\OrgDataType $orgData
     * @return static
     */
    public function setOrgData(\common\models\sbbolxml\response\OrgDataType $orgData)
    {
        $this->orgData = $orgData;
        return $this;
    }


}


<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing GenericLetterFromBankType
 *
 * Письмо свободного формата из банка
 * XSD Type: GenericLetterFromBank
 */
class GenericLetterFromBankType
{

    /**
     * Идентификатор документа в системе ДБО
     *
     * @property string $docExtId
     */
    private $docExtId = null;

    /**
     * Идентификатор организации в СББОЛ
     *
     * @property string $orgId
     */
    private $orgId = null;

    /**
     * Общие реквизиты документа ДБО
     *
     * @property \common\models\sbbolxml\response\GenLetDocDataType $docData
     */
    private $docData = null;

    /**
     * Общие реквизиты документа ДБО
     *
     * @property string $pSFType
     */
    private $pSFType = null;

    /**
     * Общие реквизиты документа ДБО
     *
     * @property string $pSFTypeSystemName
     */
    private $pSFTypeSystemName = null;

    /**
     * Общие реквизиты документа ДБО
     *
     * @property string $text
     */
    private $text = null;

    /**
     * ЭП клиента
     *
     * @property \common\models\sbbolxml\response\DigitalSignType $sign
     */
    private $sign = null;

    /**
     * Gets as docExtId
     *
     * Идентификатор документа в системе ДБО
     *
     * @return string
     */
    public function getDocExtId()
    {
        return $this->docExtId;
    }

    /**
     * Sets a new docExtId
     *
     * Идентификатор документа в системе ДБО
     *
     * @param string $docExtId
     * @return static
     */
    public function setDocExtId($docExtId)
    {
        $this->docExtId = $docExtId;
        return $this;
    }

    /**
     * Gets as orgId
     *
     * Идентификатор организации в СББОЛ
     *
     * @return string
     */
    public function getOrgId()
    {
        return $this->orgId;
    }

    /**
     * Sets a new orgId
     *
     * Идентификатор организации в СББОЛ
     *
     * @param string $orgId
     * @return static
     */
    public function setOrgId($orgId)
    {
        $this->orgId = $orgId;
        return $this;
    }

    /**
     * Gets as docData
     *
     * Общие реквизиты документа ДБО
     *
     * @return \common\models\sbbolxml\response\GenLetDocDataType
     */
    public function getDocData()
    {
        return $this->docData;
    }

    /**
     * Sets a new docData
     *
     * Общие реквизиты документа ДБО
     *
     * @param \common\models\sbbolxml\response\GenLetDocDataType $docData
     * @return static
     */
    public function setDocData(\common\models\sbbolxml\response\GenLetDocDataType $docData)
    {
        $this->docData = $docData;
        return $this;
    }

    /**
     * Gets as pSFType
     *
     * Общие реквизиты документа ДБО
     *
     * @return string
     */
    public function getPSFType()
    {
        return $this->pSFType;
    }

    /**
     * Sets a new pSFType
     *
     * Общие реквизиты документа ДБО
     *
     * @param string $pSFType
     * @return static
     */
    public function setPSFType($pSFType)
    {
        $this->pSFType = $pSFType;
        return $this;
    }

    /**
     * Gets as pSFTypeSystemName
     *
     * Общие реквизиты документа ДБО
     *
     * @return string
     */
    public function getPSFTypeSystemName()
    {
        return $this->pSFTypeSystemName;
    }

    /**
     * Sets a new pSFTypeSystemName
     *
     * Общие реквизиты документа ДБО
     *
     * @param string $pSFTypeSystemName
     * @return static
     */
    public function setPSFTypeSystemName($pSFTypeSystemName)
    {
        $this->pSFTypeSystemName = $pSFTypeSystemName;
        return $this;
    }

    /**
     * Gets as text
     *
     * Общие реквизиты документа ДБО
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Sets a new text
     *
     * Общие реквизиты документа ДБО
     *
     * @param string $text
     * @return static
     */
    public function setText($text)
    {
        $this->text = $text;
        return $this;
    }

    /**
     * Gets as sign
     *
     * ЭП клиента
     *
     * @return \common\models\sbbolxml\response\DigitalSignType
     */
    public function getSign()
    {
        return $this->sign;
    }

    /**
     * Sets a new sign
     *
     * ЭП клиента
     *
     * @param \common\models\sbbolxml\response\DigitalSignType $sign
     * @return static
     */
    public function setSign(\common\models\sbbolxml\response\DigitalSignType $sign)
    {
        $this->sign = $sign;
        return $this;
    }


}


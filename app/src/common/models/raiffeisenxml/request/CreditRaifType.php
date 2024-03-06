<?php

namespace common\models\raiffeisenxml\request;

/**
 * Class representing CreditRaifType
 *
 *
 * XSD Type: CreditRaif
 */
class CreditRaifType
{

    /**
     * Идентификатор документа в УС
     *
     * @property string $docExtId
     */
    private $docExtId = null;

    /**
     * Общие реквизиты документа ДБО
     *
     * @property \common\models\raiffeisenxml\request\CreditRaifType\DocDataAType $docData
     */
    private $docData = null;

    /**
     * Основные поля
     *
     * @property \common\models\raiffeisenxml\request\CreditRaifType\MainAType $main
     */
    private $main = null;

    /**
     * Примечание
     *
     * @property string $addInfo
     */
    private $addInfo = null;

    /**
     * Gets as docExtId
     *
     * Идентификатор документа в УС
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
     * Идентификатор документа в УС
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
     * Gets as docData
     *
     * Общие реквизиты документа ДБО
     *
     * @return \common\models\raiffeisenxml\request\CreditRaifType\DocDataAType
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
     * @param \common\models\raiffeisenxml\request\CreditRaifType\DocDataAType $docData
     * @return static
     */
    public function setDocData(\common\models\raiffeisenxml\request\CreditRaifType\DocDataAType $docData)
    {
        $this->docData = $docData;
        return $this;
    }

    /**
     * Gets as main
     *
     * Основные поля
     *
     * @return \common\models\raiffeisenxml\request\CreditRaifType\MainAType
     */
    public function getMain()
    {
        return $this->main;
    }

    /**
     * Sets a new main
     *
     * Основные поля
     *
     * @param \common\models\raiffeisenxml\request\CreditRaifType\MainAType $main
     * @return static
     */
    public function setMain(\common\models\raiffeisenxml\request\CreditRaifType\MainAType $main)
    {
        $this->main = $main;
        return $this;
    }

    /**
     * Gets as addInfo
     *
     * Примечание
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
     * Примечание
     *
     * @param string $addInfo
     * @return static
     */
    public function setAddInfo($addInfo)
    {
        $this->addInfo = $addInfo;
        return $this;
    }


}


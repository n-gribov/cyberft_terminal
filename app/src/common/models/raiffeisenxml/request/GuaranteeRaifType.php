<?php

namespace common\models\raiffeisenxml\request;

/**
 * Class representing GuaranteeRaifType
 *
 *
 * XSD Type: GuaranteeRaif
 */
class GuaranteeRaifType
{

    /**
     * Идентификатор документа в УС
     *
     * @property string $docExtId
     */
    private $docExtId = null;

    /**
     * Общие реквизиты платежного документа ДБО
     *
     * @property \common\models\raiffeisenxml\request\GuaranteeRaifType\DocDataAType $docData
     */
    private $docData = null;

    /**
     * Принципал/аппликант
     *
     * @property \common\models\raiffeisenxml\request\GuaranteeRaifType\PrincipalAType $principal
     */
    private $principal = null;

    /**
     * Бенефициар
     *
     * @property \common\models\raiffeisenxml\request\GuaranteeRaifType\BeneficiarAType $beneficiar
     */
    private $beneficiar = null;

    /**
     * Данные гарантии
     *
     * @property \common\models\raiffeisenxml\request\GuaranteeRaifType\GuaranteeAType $guarantee
     */
    private $guarantee = null;

    /**
     * Сведения по сделке
     *
     * @property \common\models\raiffeisenxml\request\GuaranteeRaifType\DealInfoAType $dealInfo
     */
    private $dealInfo = null;

    /**
     * @property \common\models\raiffeisenxml\request\GuaranteeRaifType\AddInfoAType $addInfo
     */
    private $addInfo = null;

    /**
     * Текст гарантии
     *
     * @property \common\models\raiffeisenxml\request\GuaranteeRaifType\GuaranteeTextAType $guaranteeText
     */
    private $guaranteeText = null;

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
     * Общие реквизиты платежного документа ДБО
     *
     * @return \common\models\raiffeisenxml\request\GuaranteeRaifType\DocDataAType
     */
    public function getDocData()
    {
        return $this->docData;
    }

    /**
     * Sets a new docData
     *
     * Общие реквизиты платежного документа ДБО
     *
     * @param \common\models\raiffeisenxml\request\GuaranteeRaifType\DocDataAType $docData
     * @return static
     */
    public function setDocData(\common\models\raiffeisenxml\request\GuaranteeRaifType\DocDataAType $docData)
    {
        $this->docData = $docData;
        return $this;
    }

    /**
     * Gets as principal
     *
     * Принципал/аппликант
     *
     * @return \common\models\raiffeisenxml\request\GuaranteeRaifType\PrincipalAType
     */
    public function getPrincipal()
    {
        return $this->principal;
    }

    /**
     * Sets a new principal
     *
     * Принципал/аппликант
     *
     * @param \common\models\raiffeisenxml\request\GuaranteeRaifType\PrincipalAType $principal
     * @return static
     */
    public function setPrincipal(\common\models\raiffeisenxml\request\GuaranteeRaifType\PrincipalAType $principal)
    {
        $this->principal = $principal;
        return $this;
    }

    /**
     * Gets as beneficiar
     *
     * Бенефициар
     *
     * @return \common\models\raiffeisenxml\request\GuaranteeRaifType\BeneficiarAType
     */
    public function getBeneficiar()
    {
        return $this->beneficiar;
    }

    /**
     * Sets a new beneficiar
     *
     * Бенефициар
     *
     * @param \common\models\raiffeisenxml\request\GuaranteeRaifType\BeneficiarAType $beneficiar
     * @return static
     */
    public function setBeneficiar(\common\models\raiffeisenxml\request\GuaranteeRaifType\BeneficiarAType $beneficiar)
    {
        $this->beneficiar = $beneficiar;
        return $this;
    }

    /**
     * Gets as guarantee
     *
     * Данные гарантии
     *
     * @return \common\models\raiffeisenxml\request\GuaranteeRaifType\GuaranteeAType
     */
    public function getGuarantee()
    {
        return $this->guarantee;
    }

    /**
     * Sets a new guarantee
     *
     * Данные гарантии
     *
     * @param \common\models\raiffeisenxml\request\GuaranteeRaifType\GuaranteeAType $guarantee
     * @return static
     */
    public function setGuarantee(\common\models\raiffeisenxml\request\GuaranteeRaifType\GuaranteeAType $guarantee)
    {
        $this->guarantee = $guarantee;
        return $this;
    }

    /**
     * Gets as dealInfo
     *
     * Сведения по сделке
     *
     * @return \common\models\raiffeisenxml\request\GuaranteeRaifType\DealInfoAType
     */
    public function getDealInfo()
    {
        return $this->dealInfo;
    }

    /**
     * Sets a new dealInfo
     *
     * Сведения по сделке
     *
     * @param \common\models\raiffeisenxml\request\GuaranteeRaifType\DealInfoAType $dealInfo
     * @return static
     */
    public function setDealInfo(\common\models\raiffeisenxml\request\GuaranteeRaifType\DealInfoAType $dealInfo)
    {
        $this->dealInfo = $dealInfo;
        return $this;
    }

    /**
     * Gets as addInfo
     *
     * @return \common\models\raiffeisenxml\request\GuaranteeRaifType\AddInfoAType
     */
    public function getAddInfo()
    {
        return $this->addInfo;
    }

    /**
     * Sets a new addInfo
     *
     * @param \common\models\raiffeisenxml\request\GuaranteeRaifType\AddInfoAType $addInfo
     * @return static
     */
    public function setAddInfo(\common\models\raiffeisenxml\request\GuaranteeRaifType\AddInfoAType $addInfo)
    {
        $this->addInfo = $addInfo;
        return $this;
    }

    /**
     * Gets as guaranteeText
     *
     * Текст гарантии
     *
     * @return \common\models\raiffeisenxml\request\GuaranteeRaifType\GuaranteeTextAType
     */
    public function getGuaranteeText()
    {
        return $this->guaranteeText;
    }

    /**
     * Sets a new guaranteeText
     *
     * Текст гарантии
     *
     * @param \common\models\raiffeisenxml\request\GuaranteeRaifType\GuaranteeTextAType $guaranteeText
     * @return static
     */
    public function setGuaranteeText(\common\models\raiffeisenxml\request\GuaranteeRaifType\GuaranteeTextAType $guaranteeText)
    {
        $this->guaranteeText = $guaranteeText;
        return $this;
    }


}


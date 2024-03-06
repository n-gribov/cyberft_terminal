<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing CashOrderRecipientDocInfoType
 *
 * Реквизиты документа, удостоверяющего личность получателя заяки на получение наличных
 *  средств
 * XSD Type: CashOrderRecipientDocInfo
 */
class CashOrderRecipientDocInfoType
{

    /**
     * Код типа документа
     *
     * @property string $docTypeCode
     */
    private $docTypeCode = null;

    /**
     * Наименование типа документа
     *
     * @property string $docTypeName
     */
    private $docTypeName = null;

    /**
     * Серия документа
     *
     * @property string $docSerial
     */
    private $docSerial = null;

    /**
     * Серия документа
     *
     * @property string $docNumber
     */
    private $docNumber = null;

    /**
     * Дата выдачи документа
     *
     * @property \DateTime $docDate
     */
    private $docDate = null;

    /**
     * Согласие на обработку данных
     *
     * @property boolean $persInfoConsent
     */
    private $persInfoConsent = null;

    /**
     * Gets as docTypeCode
     *
     * Код типа документа
     *
     * @return string
     */
    public function getDocTypeCode()
    {
        return $this->docTypeCode;
    }

    /**
     * Sets a new docTypeCode
     *
     * Код типа документа
     *
     * @param string $docTypeCode
     * @return static
     */
    public function setDocTypeCode($docTypeCode)
    {
        $this->docTypeCode = $docTypeCode;
        return $this;
    }

    /**
     * Gets as docTypeName
     *
     * Наименование типа документа
     *
     * @return string
     */
    public function getDocTypeName()
    {
        return $this->docTypeName;
    }

    /**
     * Sets a new docTypeName
     *
     * Наименование типа документа
     *
     * @param string $docTypeName
     * @return static
     */
    public function setDocTypeName($docTypeName)
    {
        $this->docTypeName = $docTypeName;
        return $this;
    }

    /**
     * Gets as docSerial
     *
     * Серия документа
     *
     * @return string
     */
    public function getDocSerial()
    {
        return $this->docSerial;
    }

    /**
     * Sets a new docSerial
     *
     * Серия документа
     *
     * @param string $docSerial
     * @return static
     */
    public function setDocSerial($docSerial)
    {
        $this->docSerial = $docSerial;
        return $this;
    }

    /**
     * Gets as docNumber
     *
     * Серия документа
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
     * Серия документа
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
     * Gets as docDate
     *
     * Дата выдачи документа
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
     * Дата выдачи документа
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
     * Gets as persInfoConsent
     *
     * Согласие на обработку данных
     *
     * @return boolean
     */
    public function getPersInfoConsent()
    {
        return $this->persInfoConsent;
    }

    /**
     * Sets a new persInfoConsent
     *
     * Согласие на обработку данных
     *
     * @param boolean $persInfoConsent
     * @return static
     */
    public function setPersInfoConsent($persInfoConsent)
    {
        $this->persInfoConsent = $persInfoConsent;
        return $this;
    }


}


<?php

namespace common\models\raiffeisenxml\request;

/**
 * Class representing PayDocCurType
 *
 *
 * XSD Type: PayDocCur
 */
class PayDocCurType
{

    /**
     * Идентификатор документа в УС
     *
     * @property string $docExtId
     */
    private $docExtId = null;

    /**
     * Общие реквизиты платёжного документа ДБО
     *
     * @property \common\models\raiffeisenxml\request\ComDocDataType $docData
     */
    private $docData = null;

    /**
     * 0 - НЕ срочный
     *
     * @property bool $urgent
     */
    private $urgent = null;

    /**
     * 50: Перевододатель
     *  Ordering Customer
     *
     * @property \common\models\raiffeisenxml\request\Payer50Type $payer50
     */
    private $payer50 = null;

    /**
     * 33B: Валюта и сумма поручения
     *  Currency /Instructed Amount
     *
     * @property \common\models\raiffeisenxml\request\DocSum33BType $docSum33B
     */
    private $docSum33B = null;

    /**
     * 59: Бенефициар
     *  Beneficiary Customer
     *
     * @property \common\models\raiffeisenxml\request\Beneficiar59Type $beneficiar59
     */
    private $beneficiar59 = null;

    /**
     * 52: Банк перевододателя
     *
     * @property \common\models\raiffeisenxml\request\BankPayer52Type $bankPayer52
     */
    private $bankPayer52 = null;

    /**
     * 56: Банк-посредник
     *
     * @property \common\models\raiffeisenxml\request\ImediaBank56Type $imediaBank56
     */
    private $imediaBank56 = null;

    /**
     * Банк бенефициара
     *
     * @property \common\models\raiffeisenxml\request\BankBeneficiar57Type $bankBeneficiar57
     */
    private $bankBeneficiar57 = null;

    /**
     * Назначение платежа
     *
     * @property string $paymentDetails70
     */
    private $paymentDetails70 = null;

    /**
     * Комиссии и расходы, поле 71A
     *
     * @property \common\models\raiffeisenxml\request\Charge71AType $charge71A
     */
    private $charge71A = null;

    /**
     * Информация получателю платежа (дополнительная информация), поле 72
     *
     * @property string $addInfo72
     */
    private $addInfo72 = null;

    /**
     * Реквизиты ПП на уплату НДС
     *
     * @property string $tax
     */
    private $tax = null;

    /**
     * Реквизиты справки о валютных операциях
     *
     * @property \common\models\raiffeisenxml\request\DocDataType $currDealInquiry
     */
    private $currDealInquiry = null;

    /**
     * Код вида ВО - Сумма
     *
     * @property \common\models\raiffeisenxml\request\PayDocCurType\VoSumInfoAType\VoSumAType[] $voSumInfo
     */
    private $voSumInfo = null;

    /**
     * Обосновывающие документы
     *
     * @property \common\models\raiffeisenxml\request\VoDocType[] $voDocs
     */
    private $voDocs = null;

    /**
     * Связанные документы, например, реестр пополнения корп. карт, зарплатные ведомости
     *  и т.п.
     *
     * @property \common\models\raiffeisenxml\request\LinkedDocsType\LDocAType[] $linkedDocs
     */
    private $linkedDocs = null;

    /**
     * Приложенные к документу отсканированные образы-вложения
     *
     * @property \common\models\raiffeisenxml\request\AttachmentsType\AttachmentAType[] $attachments
     */
    private $attachments = null;

    /**
     * Продукты и услуги плательщика (доп. продукты РЦК, например, консолидация,
     *  финансирование и т.п.)
     *
     * @property \common\models\raiffeisenxml\request\ServicesType $services
     */
    private $services = null;

    /**
     * Дата валютирования
     *
     * @property \DateTime $valueDate
     */
    private $valueDate = null;

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
     * Общие реквизиты платёжного документа ДБО
     *
     * @return \common\models\raiffeisenxml\request\ComDocDataType
     */
    public function getDocData()
    {
        return $this->docData;
    }

    /**
     * Sets a new docData
     *
     * Общие реквизиты платёжного документа ДБО
     *
     * @param \common\models\raiffeisenxml\request\ComDocDataType $docData
     * @return static
     */
    public function setDocData(\common\models\raiffeisenxml\request\ComDocDataType $docData)
    {
        $this->docData = $docData;
        return $this;
    }

    /**
     * Gets as urgent
     *
     * 0 - НЕ срочный
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
     * 0 - НЕ срочный
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
     * Gets as payer50
     *
     * 50: Перевододатель
     *  Ordering Customer
     *
     * @return \common\models\raiffeisenxml\request\Payer50Type
     */
    public function getPayer50()
    {
        return $this->payer50;
    }

    /**
     * Sets a new payer50
     *
     * 50: Перевододатель
     *  Ordering Customer
     *
     * @param \common\models\raiffeisenxml\request\Payer50Type $payer50
     * @return static
     */
    public function setPayer50(\common\models\raiffeisenxml\request\Payer50Type $payer50)
    {
        $this->payer50 = $payer50;
        return $this;
    }

    /**
     * Gets as docSum33B
     *
     * 33B: Валюта и сумма поручения
     *  Currency /Instructed Amount
     *
     * @return \common\models\raiffeisenxml\request\DocSum33BType
     */
    public function getDocSum33B()
    {
        return $this->docSum33B;
    }

    /**
     * Sets a new docSum33B
     *
     * 33B: Валюта и сумма поручения
     *  Currency /Instructed Amount
     *
     * @param \common\models\raiffeisenxml\request\DocSum33BType $docSum33B
     * @return static
     */
    public function setDocSum33B(\common\models\raiffeisenxml\request\DocSum33BType $docSum33B)
    {
        $this->docSum33B = $docSum33B;
        return $this;
    }

    /**
     * Gets as beneficiar59
     *
     * 59: Бенефициар
     *  Beneficiary Customer
     *
     * @return \common\models\raiffeisenxml\request\Beneficiar59Type
     */
    public function getBeneficiar59()
    {
        return $this->beneficiar59;
    }

    /**
     * Sets a new beneficiar59
     *
     * 59: Бенефициар
     *  Beneficiary Customer
     *
     * @param \common\models\raiffeisenxml\request\Beneficiar59Type $beneficiar59
     * @return static
     */
    public function setBeneficiar59(\common\models\raiffeisenxml\request\Beneficiar59Type $beneficiar59)
    {
        $this->beneficiar59 = $beneficiar59;
        return $this;
    }

    /**
     * Gets as bankPayer52
     *
     * 52: Банк перевододателя
     *
     * @return \common\models\raiffeisenxml\request\BankPayer52Type
     */
    public function getBankPayer52()
    {
        return $this->bankPayer52;
    }

    /**
     * Sets a new bankPayer52
     *
     * 52: Банк перевододателя
     *
     * @param \common\models\raiffeisenxml\request\BankPayer52Type $bankPayer52
     * @return static
     */
    public function setBankPayer52(\common\models\raiffeisenxml\request\BankPayer52Type $bankPayer52)
    {
        $this->bankPayer52 = $bankPayer52;
        return $this;
    }

    /**
     * Gets as imediaBank56
     *
     * 56: Банк-посредник
     *
     * @return \common\models\raiffeisenxml\request\ImediaBank56Type
     */
    public function getImediaBank56()
    {
        return $this->imediaBank56;
    }

    /**
     * Sets a new imediaBank56
     *
     * 56: Банк-посредник
     *
     * @param \common\models\raiffeisenxml\request\ImediaBank56Type $imediaBank56
     * @return static
     */
    public function setImediaBank56(\common\models\raiffeisenxml\request\ImediaBank56Type $imediaBank56)
    {
        $this->imediaBank56 = $imediaBank56;
        return $this;
    }

    /**
     * Gets as bankBeneficiar57
     *
     * Банк бенефициара
     *
     * @return \common\models\raiffeisenxml\request\BankBeneficiar57Type
     */
    public function getBankBeneficiar57()
    {
        return $this->bankBeneficiar57;
    }

    /**
     * Sets a new bankBeneficiar57
     *
     * Банк бенефициара
     *
     * @param \common\models\raiffeisenxml\request\BankBeneficiar57Type $bankBeneficiar57
     * @return static
     */
    public function setBankBeneficiar57(\common\models\raiffeisenxml\request\BankBeneficiar57Type $bankBeneficiar57)
    {
        $this->bankBeneficiar57 = $bankBeneficiar57;
        return $this;
    }

    /**
     * Gets as paymentDetails70
     *
     * Назначение платежа
     *
     * @return string
     */
    public function getPaymentDetails70()
    {
        return $this->paymentDetails70;
    }

    /**
     * Sets a new paymentDetails70
     *
     * Назначение платежа
     *
     * @param string $paymentDetails70
     * @return static
     */
    public function setPaymentDetails70($paymentDetails70)
    {
        $this->paymentDetails70 = $paymentDetails70;
        return $this;
    }

    /**
     * Gets as charge71A
     *
     * Комиссии и расходы, поле 71A
     *
     * @return \common\models\raiffeisenxml\request\Charge71AType
     */
    public function getCharge71A()
    {
        return $this->charge71A;
    }

    /**
     * Sets a new charge71A
     *
     * Комиссии и расходы, поле 71A
     *
     * @param \common\models\raiffeisenxml\request\Charge71AType $charge71A
     * @return static
     */
    public function setCharge71A(\common\models\raiffeisenxml\request\Charge71AType $charge71A)
    {
        $this->charge71A = $charge71A;
        return $this;
    }

    /**
     * Gets as addInfo72
     *
     * Информация получателю платежа (дополнительная информация), поле 72
     *
     * @return string
     */
    public function getAddInfo72()
    {
        return $this->addInfo72;
    }

    /**
     * Sets a new addInfo72
     *
     * Информация получателю платежа (дополнительная информация), поле 72
     *
     * @param string $addInfo72
     * @return static
     */
    public function setAddInfo72($addInfo72)
    {
        $this->addInfo72 = $addInfo72;
        return $this;
    }

    /**
     * Gets as tax
     *
     * Реквизиты ПП на уплату НДС
     *
     * @return string
     */
    public function getTax()
    {
        return $this->tax;
    }

    /**
     * Sets a new tax
     *
     * Реквизиты ПП на уплату НДС
     *
     * @param string $tax
     * @return static
     */
    public function setTax($tax)
    {
        $this->tax = $tax;
        return $this;
    }

    /**
     * Gets as currDealInquiry
     *
     * Реквизиты справки о валютных операциях
     *
     * @return \common\models\raiffeisenxml\request\DocDataType
     */
    public function getCurrDealInquiry()
    {
        return $this->currDealInquiry;
    }

    /**
     * Sets a new currDealInquiry
     *
     * Реквизиты справки о валютных операциях
     *
     * @param \common\models\raiffeisenxml\request\DocDataType $currDealInquiry
     * @return static
     */
    public function setCurrDealInquiry(\common\models\raiffeisenxml\request\DocDataType $currDealInquiry)
    {
        $this->currDealInquiry = $currDealInquiry;
        return $this;
    }

    /**
     * Adds as voSum
     *
     * Код вида ВО - Сумма
     *
     * @return static
     * @param \common\models\raiffeisenxml\request\PayDocCurType\VoSumInfoAType\VoSumAType $voSum
     */
    public function addToVoSumInfo(\common\models\raiffeisenxml\request\PayDocCurType\VoSumInfoAType\VoSumAType $voSum)
    {
        $this->voSumInfo[] = $voSum;
        return $this;
    }

    /**
     * isset voSumInfo
     *
     * Код вида ВО - Сумма
     *
     * @param int|string $index
     * @return bool
     */
    public function issetVoSumInfo($index)
    {
        return isset($this->voSumInfo[$index]);
    }

    /**
     * unset voSumInfo
     *
     * Код вида ВО - Сумма
     *
     * @param int|string $index
     * @return void
     */
    public function unsetVoSumInfo($index)
    {
        unset($this->voSumInfo[$index]);
    }

    /**
     * Gets as voSumInfo
     *
     * Код вида ВО - Сумма
     *
     * @return \common\models\raiffeisenxml\request\PayDocCurType\VoSumInfoAType\VoSumAType[]
     */
    public function getVoSumInfo()
    {
        return $this->voSumInfo;
    }

    /**
     * Sets a new voSumInfo
     *
     * Код вида ВО - Сумма
     *
     * @param \common\models\raiffeisenxml\request\PayDocCurType\VoSumInfoAType\VoSumAType[] $voSumInfo
     * @return static
     */
    public function setVoSumInfo(array $voSumInfo)
    {
        $this->voSumInfo = $voSumInfo;
        return $this;
    }

    /**
     * Adds as voDoc
     *
     * Обосновывающие документы
     *
     * @return static
     * @param \common\models\raiffeisenxml\request\VoDocType $voDoc
     */
    public function addToVoDocs(\common\models\raiffeisenxml\request\VoDocType $voDoc)
    {
        $this->voDocs[] = $voDoc;
        return $this;
    }

    /**
     * isset voDocs
     *
     * Обосновывающие документы
     *
     * @param int|string $index
     * @return bool
     */
    public function issetVoDocs($index)
    {
        return isset($this->voDocs[$index]);
    }

    /**
     * unset voDocs
     *
     * Обосновывающие документы
     *
     * @param int|string $index
     * @return void
     */
    public function unsetVoDocs($index)
    {
        unset($this->voDocs[$index]);
    }

    /**
     * Gets as voDocs
     *
     * Обосновывающие документы
     *
     * @return \common\models\raiffeisenxml\request\VoDocType[]
     */
    public function getVoDocs()
    {
        return $this->voDocs;
    }

    /**
     * Sets a new voDocs
     *
     * Обосновывающие документы
     *
     * @param \common\models\raiffeisenxml\request\VoDocType[] $voDocs
     * @return static
     */
    public function setVoDocs(array $voDocs)
    {
        $this->voDocs = $voDocs;
        return $this;
    }

    /**
     * Adds as lDoc
     *
     * Связанные документы, например, реестр пополнения корп. карт, зарплатные ведомости
     *  и т.п.
     *
     * @return static
     * @param \common\models\raiffeisenxml\request\LinkedDocsType\LDocAType $lDoc
     */
    public function addToLinkedDocs(\common\models\raiffeisenxml\request\LinkedDocsType\LDocAType $lDoc)
    {
        $this->linkedDocs[] = $lDoc;
        return $this;
    }

    /**
     * isset linkedDocs
     *
     * Связанные документы, например, реестр пополнения корп. карт, зарплатные ведомости
     *  и т.п.
     *
     * @param int|string $index
     * @return bool
     */
    public function issetLinkedDocs($index)
    {
        return isset($this->linkedDocs[$index]);
    }

    /**
     * unset linkedDocs
     *
     * Связанные документы, например, реестр пополнения корп. карт, зарплатные ведомости
     *  и т.п.
     *
     * @param int|string $index
     * @return void
     */
    public function unsetLinkedDocs($index)
    {
        unset($this->linkedDocs[$index]);
    }

    /**
     * Gets as linkedDocs
     *
     * Связанные документы, например, реестр пополнения корп. карт, зарплатные ведомости
     *  и т.п.
     *
     * @return \common\models\raiffeisenxml\request\LinkedDocsType\LDocAType[]
     */
    public function getLinkedDocs()
    {
        return $this->linkedDocs;
    }

    /**
     * Sets a new linkedDocs
     *
     * Связанные документы, например, реестр пополнения корп. карт, зарплатные ведомости
     *  и т.п.
     *
     * @param \common\models\raiffeisenxml\request\LinkedDocsType\LDocAType[] $linkedDocs
     * @return static
     */
    public function setLinkedDocs(array $linkedDocs)
    {
        $this->linkedDocs = $linkedDocs;
        return $this;
    }

    /**
     * Adds as attachment
     *
     * Приложенные к документу отсканированные образы-вложения
     *
     * @return static
     * @param \common\models\raiffeisenxml\request\AttachmentsType\AttachmentAType $attachment
     */
    public function addToAttachments(\common\models\raiffeisenxml\request\AttachmentsType\AttachmentAType $attachment)
    {
        $this->attachments[] = $attachment;
        return $this;
    }

    /**
     * isset attachments
     *
     * Приложенные к документу отсканированные образы-вложения
     *
     * @param int|string $index
     * @return bool
     */
    public function issetAttachments($index)
    {
        return isset($this->attachments[$index]);
    }

    /**
     * unset attachments
     *
     * Приложенные к документу отсканированные образы-вложения
     *
     * @param int|string $index
     * @return void
     */
    public function unsetAttachments($index)
    {
        unset($this->attachments[$index]);
    }

    /**
     * Gets as attachments
     *
     * Приложенные к документу отсканированные образы-вложения
     *
     * @return \common\models\raiffeisenxml\request\AttachmentsType\AttachmentAType[]
     */
    public function getAttachments()
    {
        return $this->attachments;
    }

    /**
     * Sets a new attachments
     *
     * Приложенные к документу отсканированные образы-вложения
     *
     * @param \common\models\raiffeisenxml\request\AttachmentsType\AttachmentAType[] $attachments
     * @return static
     */
    public function setAttachments(array $attachments)
    {
        $this->attachments = $attachments;
        return $this;
    }

    /**
     * Gets as services
     *
     * Продукты и услуги плательщика (доп. продукты РЦК, например, консолидация,
     *  финансирование и т.п.)
     *
     * @return \common\models\raiffeisenxml\request\ServicesType
     */
    public function getServices()
    {
        return $this->services;
    }

    /**
     * Sets a new services
     *
     * Продукты и услуги плательщика (доп. продукты РЦК, например, консолидация,
     *  финансирование и т.п.)
     *
     * @param \common\models\raiffeisenxml\request\ServicesType $services
     * @return static
     */
    public function setServices(\common\models\raiffeisenxml\request\ServicesType $services)
    {
        $this->services = $services;
        return $this;
    }

    /**
     * Gets as valueDate
     *
     * Дата валютирования
     *
     * @return \DateTime
     */
    public function getValueDate()
    {
        return $this->valueDate;
    }

    /**
     * Sets a new valueDate
     *
     * Дата валютирования
     *
     * @param \DateTime $valueDate
     * @return static
     */
    public function setValueDate(\DateTime $valueDate)
    {
        $this->valueDate = $valueDate;
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


<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing PayDocCurType
 *
 *
 * XSD Type: PayDocCur
 */
class PayDocCurType extends DocBaseType
{

    /**
     * Общие реквизиты платёжного документа ДБО
     *
     * @property \common\models\sbbolxml\request\PayDocCurDocDataType $docData
     */
    private $docData = null;

    /**
     * 0 - НЕ срочный
     *  1 - срочный
     *
     * @property boolean $urgent
     */
    private $urgent = null;

    /**
     * 50: Плательщик
     *  Ordering Customer
     *
     * @property \common\models\sbbolxml\request\Payer50Type $payer50
     */
    private $payer50 = null;

    /**
     * 33B: Валюта и сумма поручения
     *  Currency /Instructed Amount
     *
     * @property \common\models\sbbolxml\request\DocSum33BType $docSum33B
     */
    private $docSum33B = null;

    /**
     * 59: Бенефициар
     *  Beneficiary Customer
     *
     * @property \common\models\sbbolxml\request\Beneficiar59Type $beneficiar59
     */
    private $beneficiar59 = null;

    /**
     * 52: Банк плательщика
     *
     * @property \common\models\sbbolxml\request\BankPayer52Type $bankPayer52
     */
    private $bankPayer52 = null;

    /**
     * 56: Банк-посредник
     *
     * @property \common\models\sbbolxml\request\ImediaBank56Type $imediaBank56
     */
    private $imediaBank56 = null;

    /**
     * Банк бенефициара
     *
     * @property \common\models\sbbolxml\request\BankBeneficiar57Type $bankBeneficiar57
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
     * @property \common\models\sbbolxml\request\Charge71AType $charge71A
     */
    private $charge71A = null;

    /**
     * Информация получателю платежа (дополнительная информация), поле 72
     *
     * @property string $addInfo72
     */
    private $addInfo72 = null;

    /**
     * @property \common\models\sbbolxml\request\PayDocCurType\OptionsAType $options
     */
    private $options = null;

    /**
     * Документ по распределенной схеме
     *  1 - чек выстален
     *  0 - чек не выставлен
     *
     * @property boolean $sheme
     */
    private $sheme = null;

    /**
     * Реквизиты справки о валютных операциях
     *
     * @property \common\models\sbbolxml\request\DocDataType $currDealInquiry
     */
    private $currDealInquiry = null;

    /**
     * Коды видов валютных операций (Инструкция ЦБ РФ № 138-И от 04.06.2012)
     *
     * @property \common\models\sbbolxml\request\VoInfoType[] $voSumInfo
     */
    private $voSumInfo = null;

    /**
     * Обосновывающие документы
     *
     * @property \common\models\sbbolxml\request\VoDocType[] $voDocs
     */
    private $voDocs = null;

    /**
     * Направление платежа (Платеж внутри или вне СБРФ):
     *  0-внутри
     *  1-вне
     *
     * @property boolean $paymentDirection
     */
    private $paymentDirection = null;

    /**
     * Информация для регулирующих органов
     *
     * @property string $b77Info
     */
    private $b77Info = null;

    /**
     * Кредитный договор
     *
     * @property \common\models\sbbolxml\request\CreditType $credit
     */
    private $credit = null;

    /**
     * 23E: Код инструкции
     *
     * @property \common\models\sbbolxml\request\PayDocCurType\Codes23eAType\Code23eAType[] $codes23e
     */
    private $codes23e = null;

    /**
     * Связанные документы, например, реестр пополнения корп. карт, зарплатные ведомости
     *  и т.п.
     *
     * @property \common\models\sbbolxml\request\LinkedDocsType\LDocAType[] $linkedDocs
     */
    private $linkedDocs = null;

    /**
     * Приложенные к документу отсканированные образы-вложения
     *
     * @property \common\models\sbbolxml\request\AttachmentsType\AttachmentAType[] $attachments
     */
    private $attachments = null;

    /**
     * Данные о больших файлах, связанных с сущностью
     *
     * @property \common\models\sbbolxml\request\BigFileAttachmentType[] $bigFileAttachments
     */
    private $bigFileAttachments = null;

    /**
     * Продукты и услуги плательщика (доп. продукты РЦК, например, консолидация,
     *  финансирование и т.п.)
     *
     * @property \common\models\sbbolxml\request\ServicesType $services
     */
    private $services = null;

    /**
     * Примечание
     *
     * @property string $addInfo
     */
    private $addInfo = null;

    /**
     * @property \common\models\sbbolxml\request\RzkType $rzk
     */
    private $rzk = null;

    /**
     * Gets as docData
     *
     * Общие реквизиты платёжного документа ДБО
     *
     * @return \common\models\sbbolxml\request\PayDocCurDocDataType
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
     * @param \common\models\sbbolxml\request\PayDocCurDocDataType $docData
     * @return static
     */
    public function setDocData(\common\models\sbbolxml\request\PayDocCurDocDataType $docData)
    {
        $this->docData = $docData;
        return $this;
    }

    /**
     * Gets as urgent
     *
     * 0 - НЕ срочный
     *  1 - срочный
     *
     * @return boolean
     */
    public function getUrgent()
    {
        return $this->urgent;
    }

    /**
     * Sets a new urgent
     *
     * 0 - НЕ срочный
     *  1 - срочный
     *
     * @param boolean $urgent
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
     * 50: Плательщик
     *  Ordering Customer
     *
     * @return \common\models\sbbolxml\request\Payer50Type
     */
    public function getPayer50()
    {
        return $this->payer50;
    }

    /**
     * Sets a new payer50
     *
     * 50: Плательщик
     *  Ordering Customer
     *
     * @param \common\models\sbbolxml\request\Payer50Type $payer50
     * @return static
     */
    public function setPayer50(\common\models\sbbolxml\request\Payer50Type $payer50)
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
     * @return \common\models\sbbolxml\request\DocSum33BType
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
     * @param \common\models\sbbolxml\request\DocSum33BType $docSum33B
     * @return static
     */
    public function setDocSum33B(\common\models\sbbolxml\request\DocSum33BType $docSum33B)
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
     * @return \common\models\sbbolxml\request\Beneficiar59Type
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
     * @param \common\models\sbbolxml\request\Beneficiar59Type $beneficiar59
     * @return static
     */
    public function setBeneficiar59(\common\models\sbbolxml\request\Beneficiar59Type $beneficiar59)
    {
        $this->beneficiar59 = $beneficiar59;
        return $this;
    }

    /**
     * Gets as bankPayer52
     *
     * 52: Банк плательщика
     *
     * @return \common\models\sbbolxml\request\BankPayer52Type
     */
    public function getBankPayer52()
    {
        return $this->bankPayer52;
    }

    /**
     * Sets a new bankPayer52
     *
     * 52: Банк плательщика
     *
     * @param \common\models\sbbolxml\request\BankPayer52Type $bankPayer52
     * @return static
     */
    public function setBankPayer52(\common\models\sbbolxml\request\BankPayer52Type $bankPayer52)
    {
        $this->bankPayer52 = $bankPayer52;
        return $this;
    }

    /**
     * Gets as imediaBank56
     *
     * 56: Банк-посредник
     *
     * @return \common\models\sbbolxml\request\ImediaBank56Type
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
     * @param \common\models\sbbolxml\request\ImediaBank56Type $imediaBank56
     * @return static
     */
    public function setImediaBank56(\common\models\sbbolxml\request\ImediaBank56Type $imediaBank56)
    {
        $this->imediaBank56 = $imediaBank56;
        return $this;
    }

    /**
     * Gets as bankBeneficiar57
     *
     * Банк бенефициара
     *
     * @return \common\models\sbbolxml\request\BankBeneficiar57Type
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
     * @param \common\models\sbbolxml\request\BankBeneficiar57Type $bankBeneficiar57
     * @return static
     */
    public function setBankBeneficiar57(\common\models\sbbolxml\request\BankBeneficiar57Type $bankBeneficiar57)
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
     * @return \common\models\sbbolxml\request\Charge71AType
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
     * @param \common\models\sbbolxml\request\Charge71AType $charge71A
     * @return static
     */
    public function setCharge71A(\common\models\sbbolxml\request\Charge71AType $charge71A)
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
     * Gets as options
     *
     * @return \common\models\sbbolxml\request\PayDocCurType\OptionsAType
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Sets a new options
     *
     * @param \common\models\sbbolxml\request\PayDocCurType\OptionsAType $options
     * @return static
     */
    public function setOptions(\common\models\sbbolxml\request\PayDocCurType\OptionsAType $options)
    {
        $this->options = $options;
        return $this;
    }

    /**
     * Gets as sheme
     *
     * Документ по распределенной схеме
     *  1 - чек выстален
     *  0 - чек не выставлен
     *
     * @return boolean
     */
    public function getSheme()
    {
        return $this->sheme;
    }

    /**
     * Sets a new sheme
     *
     * Документ по распределенной схеме
     *  1 - чек выстален
     *  0 - чек не выставлен
     *
     * @param boolean $sheme
     * @return static
     */
    public function setSheme($sheme)
    {
        $this->sheme = $sheme;
        return $this;
    }

    /**
     * Gets as currDealInquiry
     *
     * Реквизиты справки о валютных операциях
     *
     * @return \common\models\sbbolxml\request\DocDataType
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
     * @param \common\models\sbbolxml\request\DocDataType $currDealInquiry
     * @return static
     */
    public function setCurrDealInquiry(\common\models\sbbolxml\request\DocDataType $currDealInquiry)
    {
        $this->currDealInquiry = $currDealInquiry;
        return $this;
    }

    /**
     * Adds as voInfo
     *
     * Коды видов валютных операций (Инструкция ЦБ РФ № 138-И от 04.06.2012)
     *
     * @return static
     * @param \common\models\sbbolxml\request\VoInfoType $voInfo
     */
    public function addToVoSumInfo(\common\models\sbbolxml\request\VoInfoType $voInfo)
    {
        $this->voSumInfo[] = $voInfo;
        return $this;
    }

    /**
     * isset voSumInfo
     *
     * Коды видов валютных операций (Инструкция ЦБ РФ № 138-И от 04.06.2012)
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetVoSumInfo($index)
    {
        return isset($this->voSumInfo[$index]);
    }

    /**
     * unset voSumInfo
     *
     * Коды видов валютных операций (Инструкция ЦБ РФ № 138-И от 04.06.2012)
     *
     * @param scalar $index
     * @return void
     */
    public function unsetVoSumInfo($index)
    {
        unset($this->voSumInfo[$index]);
    }

    /**
     * Gets as voSumInfo
     *
     * Коды видов валютных операций (Инструкция ЦБ РФ № 138-И от 04.06.2012)
     *
     * @return \common\models\sbbolxml\request\VoInfoType[]
     */
    public function getVoSumInfo()
    {
        return $this->voSumInfo;
    }

    /**
     * Sets a new voSumInfo
     *
     * Коды видов валютных операций (Инструкция ЦБ РФ № 138-И от 04.06.2012)
     *
     * @param \common\models\sbbolxml\request\VoInfoType[] $voSumInfo
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
     * @param \common\models\sbbolxml\request\VoDocType $voDoc
     */
    public function addToVoDocs(\common\models\sbbolxml\request\VoDocType $voDoc)
    {
        $this->voDocs[] = $voDoc;
        return $this;
    }

    /**
     * isset voDocs
     *
     * Обосновывающие документы
     *
     * @param scalar $index
     * @return boolean
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
     * @param scalar $index
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
     * @return \common\models\sbbolxml\request\VoDocType[]
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
     * @param \common\models\sbbolxml\request\VoDocType[] $voDocs
     * @return static
     */
    public function setVoDocs(array $voDocs)
    {
        $this->voDocs = $voDocs;
        return $this;
    }

    /**
     * Gets as paymentDirection
     *
     * Направление платежа (Платеж внутри или вне СБРФ):
     *  0-внутри
     *  1-вне
     *
     * @return boolean
     */
    public function getPaymentDirection()
    {
        return $this->paymentDirection;
    }

    /**
     * Sets a new paymentDirection
     *
     * Направление платежа (Платеж внутри или вне СБРФ):
     *  0-внутри
     *  1-вне
     *
     * @param boolean $paymentDirection
     * @return static
     */
    public function setPaymentDirection($paymentDirection)
    {
        $this->paymentDirection = $paymentDirection;
        return $this;
    }

    /**
     * Gets as b77Info
     *
     * Информация для регулирующих органов
     *
     * @return string
     */
    public function getB77Info()
    {
        return $this->b77Info;
    }

    /**
     * Sets a new b77Info
     *
     * Информация для регулирующих органов
     *
     * @param string $b77Info
     * @return static
     */
    public function setB77Info($b77Info)
    {
        $this->b77Info = $b77Info;
        return $this;
    }

    /**
     * Gets as credit
     *
     * Кредитный договор
     *
     * @return \common\models\sbbolxml\request\CreditType
     */
    public function getCredit()
    {
        return $this->credit;
    }

    /**
     * Sets a new credit
     *
     * Кредитный договор
     *
     * @param \common\models\sbbolxml\request\CreditType $credit
     * @return static
     */
    public function setCredit(\common\models\sbbolxml\request\CreditType $credit)
    {
        $this->credit = $credit;
        return $this;
    }

    /**
     * Adds as code23e
     *
     * 23E: Код инструкции
     *
     * @return static
     * @param \common\models\sbbolxml\request\PayDocCurType\Codes23eAType\Code23eAType $code23e
     */
    public function addToCodes23e(\common\models\sbbolxml\request\PayDocCurType\Codes23eAType\Code23eAType $code23e)
    {
        $this->codes23e[] = $code23e;
        return $this;
    }

    /**
     * isset codes23e
     *
     * 23E: Код инструкции
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetCodes23e($index)
    {
        return isset($this->codes23e[$index]);
    }

    /**
     * unset codes23e
     *
     * 23E: Код инструкции
     *
     * @param scalar $index
     * @return void
     */
    public function unsetCodes23e($index)
    {
        unset($this->codes23e[$index]);
    }

    /**
     * Gets as codes23e
     *
     * 23E: Код инструкции
     *
     * @return \common\models\sbbolxml\request\PayDocCurType\Codes23eAType\Code23eAType[]
     */
    public function getCodes23e()
    {
        return $this->codes23e;
    }

    /**
     * Sets a new codes23e
     *
     * 23E: Код инструкции
     *
     * @param \common\models\sbbolxml\request\PayDocCurType\Codes23eAType\Code23eAType[] $codes23e
     * @return static
     */
    public function setCodes23e(array $codes23e)
    {
        $this->codes23e = $codes23e;
        return $this;
    }

    /**
     * Adds as lDoc
     *
     * Связанные документы, например, реестр пополнения корп. карт, зарплатные ведомости
     *  и т.п.
     *
     * @return static
     * @param \common\models\sbbolxml\request\LinkedDocsType\LDocAType $lDoc
     */
    public function addToLinkedDocs(\common\models\sbbolxml\request\LinkedDocsType\LDocAType $lDoc)
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
     * @param scalar $index
     * @return boolean
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
     * @param scalar $index
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
     * @return \common\models\sbbolxml\request\LinkedDocsType\LDocAType[]
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
     * @param \common\models\sbbolxml\request\LinkedDocsType\LDocAType[] $linkedDocs
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
     * @param \common\models\sbbolxml\request\AttachmentsType\AttachmentAType $attachment
     */
    public function addToAttachments(\common\models\sbbolxml\request\AttachmentsType\AttachmentAType $attachment)
    {
        $this->attachments[] = $attachment;
        return $this;
    }

    /**
     * isset attachments
     *
     * Приложенные к документу отсканированные образы-вложения
     *
     * @param scalar $index
     * @return boolean
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
     * @param scalar $index
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
     * @return \common\models\sbbolxml\request\AttachmentsType\AttachmentAType[]
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
     * @param \common\models\sbbolxml\request\AttachmentsType\AttachmentAType[] $attachments
     * @return static
     */
    public function setAttachments(array $attachments)
    {
        $this->attachments = $attachments;
        return $this;
    }

    /**
     * Adds as bigFileAttachment
     *
     * Данные о больших файлах, связанных с сущностью
     *
     * @return static
     * @param \common\models\sbbolxml\request\BigFileAttachmentType $bigFileAttachment
     */
    public function addToBigFileAttachments(\common\models\sbbolxml\request\BigFileAttachmentType $bigFileAttachment)
    {
        $this->bigFileAttachments[] = $bigFileAttachment;
        return $this;
    }

    /**
     * isset bigFileAttachments
     *
     * Данные о больших файлах, связанных с сущностью
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetBigFileAttachments($index)
    {
        return isset($this->bigFileAttachments[$index]);
    }

    /**
     * unset bigFileAttachments
     *
     * Данные о больших файлах, связанных с сущностью
     *
     * @param scalar $index
     * @return void
     */
    public function unsetBigFileAttachments($index)
    {
        unset($this->bigFileAttachments[$index]);
    }

    /**
     * Gets as bigFileAttachments
     *
     * Данные о больших файлах, связанных с сущностью
     *
     * @return \common\models\sbbolxml\request\BigFileAttachmentType[]
     */
    public function getBigFileAttachments()
    {
        return $this->bigFileAttachments;
    }

    /**
     * Sets a new bigFileAttachments
     *
     * Данные о больших файлах, связанных с сущностью
     *
     * @param \common\models\sbbolxml\request\BigFileAttachmentType[] $bigFileAttachments
     * @return static
     */
    public function setBigFileAttachments(array $bigFileAttachments)
    {
        $this->bigFileAttachments = $bigFileAttachments;
        return $this;
    }

    /**
     * Gets as services
     *
     * Продукты и услуги плательщика (доп. продукты РЦК, например, консолидация,
     *  финансирование и т.п.)
     *
     * @return \common\models\sbbolxml\request\ServicesType
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
     * @param \common\models\sbbolxml\request\ServicesType $services
     * @return static
     */
    public function setServices(\common\models\sbbolxml\request\ServicesType $services)
    {
        $this->services = $services;
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

    /**
     * Gets as rzk
     *
     * @return \common\models\sbbolxml\request\RzkType
     */
    public function getRzk()
    {
        return $this->rzk;
    }

    /**
     * Sets a new rzk
     *
     * @param \common\models\sbbolxml\request\RzkType $rzk
     * @return static
     */
    public function setRzk(\common\models\sbbolxml\request\RzkType $rzk)
    {
        $this->rzk = $rzk;
        return $this;
    }


}


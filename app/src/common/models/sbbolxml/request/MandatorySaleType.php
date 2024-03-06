<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing MandatorySaleType
 *
 *
 * XSD Type: MandatorySale
 */
class MandatorySaleType extends DocBaseType
{

    /**
     * Общие реквизиты платёжного документа ДБО
     *
     * @property \common\models\sbbolxml\request\ComDocDataType $docData
     */
    private $docData = null;

    /**
     * Транзитный валютный счёт
     *
     * @property \common\models\sbbolxml\request\AccountRUType $accDoc
     */
    private $accDoc = null;

    /**
     * Уведомления о поступлении денежных средств на транзитный валютный счет
     *
     * @property \common\models\sbbolxml\request\MandatorySaleType\AdviceAType $advice
     */
    private $advice = null;

    /**
     * Общая сумма поступивших денежных средств в валюте транзитного валютного счёта
     *
     * @property \common\models\sbbolxml\request\CurrAmountType $totalSum
     */
    private $totalSum = null;

    /**
     * Вычет из общей суммы валютной выручки
     *
     * @property \common\models\sbbolxml\request\MandatorySaleType\SubtractAType $subtract
     */
    private $subtract = null;

    /**
     * Обязательная продажа. Т.к. она пока 0%, можно не отправлять данные, тег
     *  необязательный
     *
     * @property \common\models\sbbolxml\request\MandatorySaleType\ObligatorySaleAType $obligatorySale
     */
    private $obligatorySale = null;

    /**
     * Необязательная продажа
     *
     * @property \common\models\sbbolxml\request\MandatorySaleType\SellAType $sell
     */
    private $sell = null;

    /**
     * Зачисление
     *
     * @property \common\models\sbbolxml\request\MandatorySaleType\TransAType $trans
     */
    private $trans = null;

    /**
     * Комиссионное вознаграждение (ComAcc ИЛИ ComOrder)
     *
     * @property \common\models\sbbolxml\request\CommisionType $commis
     */
    private $commis = null;

    /**
     * Реквизиты СоВО
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
     * Примечание
     *
     * @property string $addInfo
     */
    private $addInfo = null;

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
     * Gets as docData
     *
     * Общие реквизиты платёжного документа ДБО
     *
     * @return \common\models\sbbolxml\request\ComDocDataType
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
     * @param \common\models\sbbolxml\request\ComDocDataType $docData
     * @return static
     */
    public function setDocData(\common\models\sbbolxml\request\ComDocDataType $docData)
    {
        $this->docData = $docData;
        return $this;
    }

    /**
     * Gets as accDoc
     *
     * Транзитный валютный счёт
     *
     * @return \common\models\sbbolxml\request\AccountRUType
     */
    public function getAccDoc()
    {
        return $this->accDoc;
    }

    /**
     * Sets a new accDoc
     *
     * Транзитный валютный счёт
     *
     * @param \common\models\sbbolxml\request\AccountRUType $accDoc
     * @return static
     */
    public function setAccDoc(\common\models\sbbolxml\request\AccountRUType $accDoc)
    {
        $this->accDoc = $accDoc;
        return $this;
    }

    /**
     * Gets as advice
     *
     * Уведомления о поступлении денежных средств на транзитный валютный счет
     *
     * @return \common\models\sbbolxml\request\MandatorySaleType\AdviceAType
     */
    public function getAdvice()
    {
        return $this->advice;
    }

    /**
     * Sets a new advice
     *
     * Уведомления о поступлении денежных средств на транзитный валютный счет
     *
     * @param \common\models\sbbolxml\request\MandatorySaleType\AdviceAType $advice
     * @return static
     */
    public function setAdvice(\common\models\sbbolxml\request\MandatorySaleType\AdviceAType $advice)
    {
        $this->advice = $advice;
        return $this;
    }

    /**
     * Gets as totalSum
     *
     * Общая сумма поступивших денежных средств в валюте транзитного валютного счёта
     *
     * @return \common\models\sbbolxml\request\CurrAmountType
     */
    public function getTotalSum()
    {
        return $this->totalSum;
    }

    /**
     * Sets a new totalSum
     *
     * Общая сумма поступивших денежных средств в валюте транзитного валютного счёта
     *
     * @param \common\models\sbbolxml\request\CurrAmountType $totalSum
     * @return static
     */
    public function setTotalSum(\common\models\sbbolxml\request\CurrAmountType $totalSum)
    {
        $this->totalSum = $totalSum;
        return $this;
    }

    /**
     * Gets as subtract
     *
     * Вычет из общей суммы валютной выручки
     *
     * @return \common\models\sbbolxml\request\MandatorySaleType\SubtractAType
     */
    public function getSubtract()
    {
        return $this->subtract;
    }

    /**
     * Sets a new subtract
     *
     * Вычет из общей суммы валютной выручки
     *
     * @param \common\models\sbbolxml\request\MandatorySaleType\SubtractAType $subtract
     * @return static
     */
    public function setSubtract(\common\models\sbbolxml\request\MandatorySaleType\SubtractAType $subtract)
    {
        $this->subtract = $subtract;
        return $this;
    }

    /**
     * Gets as obligatorySale
     *
     * Обязательная продажа. Т.к. она пока 0%, можно не отправлять данные, тег
     *  необязательный
     *
     * @return \common\models\sbbolxml\request\MandatorySaleType\ObligatorySaleAType
     */
    public function getObligatorySale()
    {
        return $this->obligatorySale;
    }

    /**
     * Sets a new obligatorySale
     *
     * Обязательная продажа. Т.к. она пока 0%, можно не отправлять данные, тег
     *  необязательный
     *
     * @param \common\models\sbbolxml\request\MandatorySaleType\ObligatorySaleAType $obligatorySale
     * @return static
     */
    public function setObligatorySale(\common\models\sbbolxml\request\MandatorySaleType\ObligatorySaleAType $obligatorySale)
    {
        $this->obligatorySale = $obligatorySale;
        return $this;
    }

    /**
     * Gets as sell
     *
     * Необязательная продажа
     *
     * @return \common\models\sbbolxml\request\MandatorySaleType\SellAType
     */
    public function getSell()
    {
        return $this->sell;
    }

    /**
     * Sets a new sell
     *
     * Необязательная продажа
     *
     * @param \common\models\sbbolxml\request\MandatorySaleType\SellAType $sell
     * @return static
     */
    public function setSell(\common\models\sbbolxml\request\MandatorySaleType\SellAType $sell)
    {
        $this->sell = $sell;
        return $this;
    }

    /**
     * Gets as trans
     *
     * Зачисление
     *
     * @return \common\models\sbbolxml\request\MandatorySaleType\TransAType
     */
    public function getTrans()
    {
        return $this->trans;
    }

    /**
     * Sets a new trans
     *
     * Зачисление
     *
     * @param \common\models\sbbolxml\request\MandatorySaleType\TransAType $trans
     * @return static
     */
    public function setTrans(\common\models\sbbolxml\request\MandatorySaleType\TransAType $trans)
    {
        $this->trans = $trans;
        return $this;
    }

    /**
     * Gets as commis
     *
     * Комиссионное вознаграждение (ComAcc ИЛИ ComOrder)
     *
     * @return \common\models\sbbolxml\request\CommisionType
     */
    public function getCommis()
    {
        return $this->commis;
    }

    /**
     * Sets a new commis
     *
     * Комиссионное вознаграждение (ComAcc ИЛИ ComOrder)
     *
     * @param \common\models\sbbolxml\request\CommisionType $commis
     * @return static
     */
    public function setCommis(\common\models\sbbolxml\request\CommisionType $commis)
    {
        $this->commis = $commis;
        return $this;
    }

    /**
     * Gets as currDealInquiry
     *
     * Реквизиты СоВО
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
     * Реквизиты СоВО
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


}


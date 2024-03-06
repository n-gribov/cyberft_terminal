<?php

namespace common\models\raiffeisenxml\request;

/**
 * Class representing MandatorySaleBoxType
 *
 *
 * XSD Type: MandatorySaleBox
 */
class MandatorySaleBoxType
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
     * Транзитный счет
     *
     * @property \common\models\raiffeisenxml\request\AccountRUType $accDoc
     */
    private $accDoc = null;

    /**
     * Уведомления о поступлении денежных средств на транзитный валютный счет
     *
     * @property \common\models\raiffeisenxml\request\MandatorySaleBoxType\AdviceAType\DocAType[] $advice
     */
    private $advice = null;

    /**
     * Общая сумма поступивших денежных средств в валюте счета
     *
     * @property \common\models\raiffeisenxml\request\CurrAmountType $totalSum
     */
    private $totalSum = null;

    /**
     * Зачисление
     *
     * @property \common\models\raiffeisenxml\request\MandatorySaleBoxType\TransAType $trans
     */
    private $trans = null;

    /**
     * Продажа
     *
     * @property \common\models\raiffeisenxml\request\MandatorySaleBoxType\SellAType $sell
     */
    private $sell = null;

    /**
     * Ком. вознаграждение списать с нашего счета
     *
     * @property \common\models\raiffeisenxml\request\AccountRUType $accCom
     */
    private $accCom = null;

    /**
     * Удержать из суммы сделки
     *
     * @property bool $amount
     */
    private $amount = null;

    /**
     * Перечислены платежным поручением
     *
     * @property \common\models\raiffeisenxml\request\DocDataType $payDocRu
     */
    private $payDocRu = null;

    /**
     * Доп. информация
     *
     * @property string $addInfo
     */
    private $addInfo = null;

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
     * Реквизиты справки о валютных операциях
     *
     * @property \common\models\raiffeisenxml\request\DocDataType $currDealInquiry
     */
    private $currDealInquiry = null;

    /**
     * Обосновывающие документы
     *
     * @property \common\models\raiffeisenxml\request\VoDocType[] $voDocs
     */
    private $voDocs = null;

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
     * Gets as accDoc
     *
     * Транзитный счет
     *
     * @return \common\models\raiffeisenxml\request\AccountRUType
     */
    public function getAccDoc()
    {
        return $this->accDoc;
    }

    /**
     * Sets a new accDoc
     *
     * Транзитный счет
     *
     * @param \common\models\raiffeisenxml\request\AccountRUType $accDoc
     * @return static
     */
    public function setAccDoc(\common\models\raiffeisenxml\request\AccountRUType $accDoc)
    {
        $this->accDoc = $accDoc;
        return $this;
    }

    /**
     * Adds as doc
     *
     * Уведомления о поступлении денежных средств на транзитный валютный счет
     *
     * @return static
     * @param \common\models\raiffeisenxml\request\MandatorySaleBoxType\AdviceAType\DocAType $doc
     */
    public function addToAdvice(\common\models\raiffeisenxml\request\MandatorySaleBoxType\AdviceAType\DocAType $doc)
    {
        $this->advice[] = $doc;
        return $this;
    }

    /**
     * isset advice
     *
     * Уведомления о поступлении денежных средств на транзитный валютный счет
     *
     * @param int|string $index
     * @return bool
     */
    public function issetAdvice($index)
    {
        return isset($this->advice[$index]);
    }

    /**
     * unset advice
     *
     * Уведомления о поступлении денежных средств на транзитный валютный счет
     *
     * @param int|string $index
     * @return void
     */
    public function unsetAdvice($index)
    {
        unset($this->advice[$index]);
    }

    /**
     * Gets as advice
     *
     * Уведомления о поступлении денежных средств на транзитный валютный счет
     *
     * @return \common\models\raiffeisenxml\request\MandatorySaleBoxType\AdviceAType\DocAType[]
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
     * @param \common\models\raiffeisenxml\request\MandatorySaleBoxType\AdviceAType\DocAType[] $advice
     * @return static
     */
    public function setAdvice(array $advice)
    {
        $this->advice = $advice;
        return $this;
    }

    /**
     * Gets as totalSum
     *
     * Общая сумма поступивших денежных средств в валюте счета
     *
     * @return \common\models\raiffeisenxml\request\CurrAmountType
     */
    public function getTotalSum()
    {
        return $this->totalSum;
    }

    /**
     * Sets a new totalSum
     *
     * Общая сумма поступивших денежных средств в валюте счета
     *
     * @param \common\models\raiffeisenxml\request\CurrAmountType $totalSum
     * @return static
     */
    public function setTotalSum(\common\models\raiffeisenxml\request\CurrAmountType $totalSum)
    {
        $this->totalSum = $totalSum;
        return $this;
    }

    /**
     * Gets as trans
     *
     * Зачисление
     *
     * @return \common\models\raiffeisenxml\request\MandatorySaleBoxType\TransAType
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
     * @param \common\models\raiffeisenxml\request\MandatorySaleBoxType\TransAType $trans
     * @return static
     */
    public function setTrans(\common\models\raiffeisenxml\request\MandatorySaleBoxType\TransAType $trans)
    {
        $this->trans = $trans;
        return $this;
    }

    /**
     * Gets as sell
     *
     * Продажа
     *
     * @return \common\models\raiffeisenxml\request\MandatorySaleBoxType\SellAType
     */
    public function getSell()
    {
        return $this->sell;
    }

    /**
     * Sets a new sell
     *
     * Продажа
     *
     * @param \common\models\raiffeisenxml\request\MandatorySaleBoxType\SellAType $sell
     * @return static
     */
    public function setSell(\common\models\raiffeisenxml\request\MandatorySaleBoxType\SellAType $sell)
    {
        $this->sell = $sell;
        return $this;
    }

    /**
     * Gets as accCom
     *
     * Ком. вознаграждение списать с нашего счета
     *
     * @return \common\models\raiffeisenxml\request\AccountRUType
     */
    public function getAccCom()
    {
        return $this->accCom;
    }

    /**
     * Sets a new accCom
     *
     * Ком. вознаграждение списать с нашего счета
     *
     * @param \common\models\raiffeisenxml\request\AccountRUType $accCom
     * @return static
     */
    public function setAccCom(\common\models\raiffeisenxml\request\AccountRUType $accCom)
    {
        $this->accCom = $accCom;
        return $this;
    }

    /**
     * Gets as amount
     *
     * Удержать из суммы сделки
     *
     * @return bool
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Sets a new amount
     *
     * Удержать из суммы сделки
     *
     * @param bool $amount
     * @return static
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
        return $this;
    }

    /**
     * Gets as payDocRu
     *
     * Перечислены платежным поручением
     *
     * @return \common\models\raiffeisenxml\request\DocDataType
     */
    public function getPayDocRu()
    {
        return $this->payDocRu;
    }

    /**
     * Sets a new payDocRu
     *
     * Перечислены платежным поручением
     *
     * @param \common\models\raiffeisenxml\request\DocDataType $payDocRu
     * @return static
     */
    public function setPayDocRu(\common\models\raiffeisenxml\request\DocDataType $payDocRu)
    {
        $this->payDocRu = $payDocRu;
        return $this;
    }

    /**
     * Gets as addInfo
     *
     * Доп. информация
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
     * Доп. информация
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


}


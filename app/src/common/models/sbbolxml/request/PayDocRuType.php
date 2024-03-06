<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing PayDocRuType
 *
 *
 * XSD Type: PayDocRu
 */
class PayDocRuType extends DocBaseType
{

    /**
     * Тип списания.
     *  Заполняется значением UpgRplDictionary/DirTypeEntry/@code
     *
     * @property integer $cancType
     */
    private $cancType = null;

    /**
     * Реквизиты платёжного документа
     *
     * @property \common\models\sbbolxml\request\AccDocType $accDoc
     */
    private $accDoc = null;

    /**
     * Реквизиты плательщика
     *
     * @property \common\models\sbbolxml\request\PayDocRuClientType $payer
     */
    private $payer = null;

    /**
     * Реквизиты получателя
     *
     * @property \common\models\sbbolxml\request\ContragentType $payee
     */
    private $payee = null;

    /**
     * Налоговая информация: поля 101, 104 - 110 платёжного поручения
     *
     * @property \common\models\sbbolxml\request\BudgetDepartmentalInfoType $departmentalInfo
     */
    private $departmentalInfo = null;

    /**
     * Информация получателю
     *
     * @property string $information
     */
    private $information = null;

    /**
     * Доп. реквизиты. Указываются для платёжных документов, проводимых за счёт
     *  предоставляемого банком кредита
     *
     * @property \common\models\sbbolxml\request\CreditType $credit
     */
    private $credit = null;

    /**
     * Связанные документы, например, реестр пополнения корп. карт, зарплатные
     *  ведомости
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
     * @property \common\models\sbbolxml\request\PayDocRuType\CurrDealCertificateInfoAType $currDealCertificateInfo
     */
    private $currDealCertificateInfo = null;

    /**
     * Многострочная аналитика и параметры обработки платежного документа в РЦК
     *  (СБК)
     *
     * @property \common\models\sbbolxml\request\RzkType $rzk
     */
    private $rzk = null;

    /**
     * @property \common\models\sbbolxml\request\SuppDocsType\SuppDocAType[] $suppDocs
     */
    private $suppDocs = null;

    /**
     * Gets as cancType
     *
     * Тип списания.
     *  Заполняется значением UpgRplDictionary/DirTypeEntry/@code
     *
     * @return integer
     */
    public function getCancType()
    {
        return $this->cancType;
    }

    /**
     * Sets a new cancType
     *
     * Тип списания.
     *  Заполняется значением UpgRplDictionary/DirTypeEntry/@code
     *
     * @param integer $cancType
     * @return static
     */
    public function setCancType($cancType)
    {
        $this->cancType = $cancType;
        return $this;
    }

    /**
     * Gets as accDoc
     *
     * Реквизиты платёжного документа
     *
     * @return \common\models\sbbolxml\request\AccDocType
     */
    public function getAccDoc()
    {
        return $this->accDoc;
    }

    /**
     * Sets a new accDoc
     *
     * Реквизиты платёжного документа
     *
     * @param \common\models\sbbolxml\request\AccDocType $accDoc
     * @return static
     */
    public function setAccDoc(\common\models\sbbolxml\request\AccDocType $accDoc)
    {
        $this->accDoc = $accDoc;
        return $this;
    }

    /**
     * Gets as payer
     *
     * Реквизиты плательщика
     *
     * @return \common\models\sbbolxml\request\PayDocRuClientType
     */
    public function getPayer()
    {
        return $this->payer;
    }

    /**
     * Sets a new payer
     *
     * Реквизиты плательщика
     *
     * @param \common\models\sbbolxml\request\PayDocRuClientType $payer
     * @return static
     */
    public function setPayer(\common\models\sbbolxml\request\PayDocRuClientType $payer)
    {
        $this->payer = $payer;
        return $this;
    }

    /**
     * Gets as payee
     *
     * Реквизиты получателя
     *
     * @return \common\models\sbbolxml\request\ContragentType
     */
    public function getPayee()
    {
        return $this->payee;
    }

    /**
     * Sets a new payee
     *
     * Реквизиты получателя
     *
     * @param \common\models\sbbolxml\request\ContragentType $payee
     * @return static
     */
    public function setPayee(\common\models\sbbolxml\request\ContragentType $payee)
    {
        $this->payee = $payee;
        return $this;
    }

    /**
     * Gets as departmentalInfo
     *
     * Налоговая информация: поля 101, 104 - 110 платёжного поручения
     *
     * @return \common\models\sbbolxml\request\BudgetDepartmentalInfoType
     */
    public function getDepartmentalInfo()
    {
        return $this->departmentalInfo;
    }

    /**
     * Sets a new departmentalInfo
     *
     * Налоговая информация: поля 101, 104 - 110 платёжного поручения
     *
     * @param \common\models\sbbolxml\request\BudgetDepartmentalInfoType $departmentalInfo
     * @return static
     */
    public function setDepartmentalInfo(\common\models\sbbolxml\request\BudgetDepartmentalInfoType $departmentalInfo)
    {
        $this->departmentalInfo = $departmentalInfo;
        return $this;
    }

    /**
     * Gets as information
     *
     * Информация получателю
     *
     * @return string
     */
    public function getInformation()
    {
        return $this->information;
    }

    /**
     * Sets a new information
     *
     * Информация получателю
     *
     * @param string $information
     * @return static
     */
    public function setInformation($information)
    {
        $this->information = $information;
        return $this;
    }

    /**
     * Gets as credit
     *
     * Доп. реквизиты. Указываются для платёжных документов, проводимых за счёт
     *  предоставляемого банком кредита
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
     * Доп. реквизиты. Указываются для платёжных документов, проводимых за счёт
     *  предоставляемого банком кредита
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
     * Adds as lDoc
     *
     * Связанные документы, например, реестр пополнения корп. карт, зарплатные
     *  ведомости
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
     * Связанные документы, например, реестр пополнения корп. карт, зарплатные
     *  ведомости
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
     * Связанные документы, например, реестр пополнения корп. карт, зарплатные
     *  ведомости
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
     * Связанные документы, например, реестр пополнения корп. карт, зарплатные
     *  ведомости
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
     * Связанные документы, например, реестр пополнения корп. карт, зарплатные
     *  ведомости
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
     * Gets as currDealCertificateInfo
     *
     * @return \common\models\sbbolxml\request\PayDocRuType\CurrDealCertificateInfoAType
     */
    public function getCurrDealCertificateInfo()
    {
        return $this->currDealCertificateInfo;
    }

    /**
     * Sets a new currDealCertificateInfo
     *
     * @param \common\models\sbbolxml\request\PayDocRuType\CurrDealCertificateInfoAType $currDealCertificateInfo
     * @return static
     */
    public function setCurrDealCertificateInfo(\common\models\sbbolxml\request\PayDocRuType\CurrDealCertificateInfoAType $currDealCertificateInfo)
    {
        $this->currDealCertificateInfo = $currDealCertificateInfo;
        return $this;
    }

    /**
     * Gets as rzk
     *
     * Многострочная аналитика и параметры обработки платежного документа в РЦК
     *  (СБК)
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
     * Многострочная аналитика и параметры обработки платежного документа в РЦК
     *  (СБК)
     *
     * @param \common\models\sbbolxml\request\RzkType $rzk
     * @return static
     */
    public function setRzk(\common\models\sbbolxml\request\RzkType $rzk)
    {
        $this->rzk = $rzk;
        return $this;
    }

    /**
     * Adds as suppDoc
     *
     * @return static
     * @param \common\models\sbbolxml\request\SuppDocsType\SuppDocAType $suppDoc
     */
    public function addToSuppDocs(\common\models\sbbolxml\request\SuppDocsType\SuppDocAType $suppDoc)
    {
        $this->suppDocs[] = $suppDoc;
        return $this;
    }

    /**
     * isset suppDocs
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetSuppDocs($index)
    {
        return isset($this->suppDocs[$index]);
    }

    /**
     * unset suppDocs
     *
     * @param scalar $index
     * @return void
     */
    public function unsetSuppDocs($index)
    {
        unset($this->suppDocs[$index]);
    }

    /**
     * Gets as suppDocs
     *
     * @return \common\models\sbbolxml\request\SuppDocsType\SuppDocAType[]
     */
    public function getSuppDocs()
    {
        return $this->suppDocs;
    }

    /**
     * Sets a new suppDocs
     *
     * @param \common\models\sbbolxml\request\SuppDocsType\SuppDocAType[] $suppDocs
     * @return static
     */
    public function setSuppDocs(array $suppDocs)
    {
        $this->suppDocs = $suppDocs;
        return $this;
    }


}


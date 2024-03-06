<?php

namespace common\models\raiffeisenxml\request;

/**
 * Class representing PayDocRuType
 *
 *
 * XSD Type: PayDocRu
 */
class PayDocRuType
{

    /**
     * Идентификатор документа в УС (в оффлайн клиенте)
     *
     * @property string $docExtId
     */
    private $docExtId = null;

    /**
     * Реквизиты платёжного документа
     *
     * @property \common\models\raiffeisenxml\request\AccDocType $accDoc
     */
    private $accDoc = null;

    /**
     * Реквизиты плательщика
     *
     * @property \common\models\raiffeisenxml\request\PayerNoNameType $payer
     */
    private $payer = null;

    /**
     * Реквизиты получателя
     *
     * @property \common\models\raiffeisenxml\request\PayeeType $payee
     */
    private $payee = null;

    /**
     * Налоговая информация: поля 101, 104 - 110 платёжного поручения
     *
     * @property \common\models\raiffeisenxml\request\DepartmentalInfoType $departmentalInfo
     */
    private $departmentalInfo = null;

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
     * Gets as docExtId
     *
     * Идентификатор документа в УС (в оффлайн клиенте)
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
     * Идентификатор документа в УС (в оффлайн клиенте)
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
     * Gets as accDoc
     *
     * Реквизиты платёжного документа
     *
     * @return \common\models\raiffeisenxml\request\AccDocType
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
     * @param \common\models\raiffeisenxml\request\AccDocType $accDoc
     * @return static
     */
    public function setAccDoc(\common\models\raiffeisenxml\request\AccDocType $accDoc)
    {
        $this->accDoc = $accDoc;
        return $this;
    }

    /**
     * Gets as payer
     *
     * Реквизиты плательщика
     *
     * @return \common\models\raiffeisenxml\request\PayerNoNameType
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
     * @param \common\models\raiffeisenxml\request\PayerNoNameType $payer
     * @return static
     */
    public function setPayer(\common\models\raiffeisenxml\request\PayerNoNameType $payer)
    {
        $this->payer = $payer;
        return $this;
    }

    /**
     * Gets as payee
     *
     * Реквизиты получателя
     *
     * @return \common\models\raiffeisenxml\request\PayeeType
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
     * @param \common\models\raiffeisenxml\request\PayeeType $payee
     * @return static
     */
    public function setPayee(\common\models\raiffeisenxml\request\PayeeType $payee)
    {
        $this->payee = $payee;
        return $this;
    }

    /**
     * Gets as departmentalInfo
     *
     * Налоговая информация: поля 101, 104 - 110 платёжного поручения
     *
     * @return \common\models\raiffeisenxml\request\DepartmentalInfoType
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
     * @param \common\models\raiffeisenxml\request\DepartmentalInfoType $departmentalInfo
     * @return static
     */
    public function setDepartmentalInfo(\common\models\raiffeisenxml\request\DepartmentalInfoType $departmentalInfo)
    {
        $this->departmentalInfo = $departmentalInfo;
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


}


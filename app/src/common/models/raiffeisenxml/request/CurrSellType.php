<?php

namespace common\models\raiffeisenxml\request;

/**
 * Class representing CurrSellType
 *
 *
 * XSD Type: CurrSell
 */
class CurrSellType
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
     * Сделка
     *
     * @property \common\models\raiffeisenxml\request\CurrSellTransType $trans
     */
    private $trans = null;

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
     * Обосновывающие документы
     *
     * @property \common\models\raiffeisenxml\request\VoDocType[] $voDocs
     */
    private $voDocs = null;

    /**
     * Реквизиты справки о валютных операциях
     *
     * @property \common\models\raiffeisenxml\request\DocDataType $currDealInquiry
     */
    private $currDealInquiry = null;

    /**
     * Код вида валютной операции
     *
     * @property string $vo
     */
    private $vo = null;

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
     * Gets as trans
     *
     * Сделка
     *
     * @return \common\models\raiffeisenxml\request\CurrSellTransType
     */
    public function getTrans()
    {
        return $this->trans;
    }

    /**
     * Sets a new trans
     *
     * Сделка
     *
     * @param \common\models\raiffeisenxml\request\CurrSellTransType $trans
     * @return static
     */
    public function setTrans(\common\models\raiffeisenxml\request\CurrSellTransType $trans)
    {
        $this->trans = $trans;
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
     * Gets as vo
     *
     * Код вида валютной операции
     *
     * @return string
     */
    public function getVo()
    {
        return $this->vo;
    }

    /**
     * Sets a new vo
     *
     * Код вида валютной операции
     *
     * @param string $vo
     * @return static
     */
    public function setVo($vo)
    {
        $this->vo = $vo;
        return $this;
    }


}


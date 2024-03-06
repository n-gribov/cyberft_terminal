<?php

namespace common\models\raiffeisenxml\request;

/**
 * Class representing ConfDocCertificate138IRaifType
 *
 *
 * XSD Type: ConfDocCertificate138IRaif
 */
class ConfDocCertificate138IRaifType
{

    /**
     * Идентификатор документа в УС
     *
     * @property string $docExtId
     */
    private $docExtId = null;

    /**
     * Общие реквизиты документа
     *
     * @property \common\models\raiffeisenxml\request\DocDataConf138IRaifType $docData
     */
    private $docData = null;

    /**
     * @property \common\models\raiffeisenxml\request\ConfDocCertificateDoc138IRaifType[] $table
     */
    private $table = null;

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
     * Общие реквизиты документа
     *
     * @return \common\models\raiffeisenxml\request\DocDataConf138IRaifType
     */
    public function getDocData()
    {
        return $this->docData;
    }

    /**
     * Sets a new docData
     *
     * Общие реквизиты документа
     *
     * @param \common\models\raiffeisenxml\request\DocDataConf138IRaifType $docData
     * @return static
     */
    public function setDocData(\common\models\raiffeisenxml\request\DocDataConf138IRaifType $docData)
    {
        $this->docData = $docData;
        return $this;
    }

    /**
     * Adds as oper
     *
     * @return static
     * @param \common\models\raiffeisenxml\request\ConfDocCertificateDoc138IRaifType $oper
     */
    public function addToTable(\common\models\raiffeisenxml\request\ConfDocCertificateDoc138IRaifType $oper)
    {
        $this->table[] = $oper;
        return $this;
    }

    /**
     * isset table
     *
     * @param int|string $index
     * @return bool
     */
    public function issetTable($index)
    {
        return isset($this->table[$index]);
    }

    /**
     * unset table
     *
     * @param int|string $index
     * @return void
     */
    public function unsetTable($index)
    {
        unset($this->table[$index]);
    }

    /**
     * Gets as table
     *
     * @return \common\models\raiffeisenxml\request\ConfDocCertificateDoc138IRaifType[]
     */
    public function getTable()
    {
        return $this->table;
    }

    /**
     * Sets a new table
     *
     * @param \common\models\raiffeisenxml\request\ConfDocCertificateDoc138IRaifType[] $table
     * @return static
     */
    public function setTable(array $table)
    {
        $this->table = $table;
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


}


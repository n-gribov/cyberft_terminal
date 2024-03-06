<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing ExchangeMessagesWithBankType
 *
 *
 * XSD Type: ExchangeMessagesWithBank
 */
class ExchangeMessagesWithBankType extends DocBaseType
{

    /**
     * Уникальный идентификатор документа в системе ВК Банка.
     *
     * @property string $docExtGuid
     */
    private $docExtGuid = null;

    /**
     * @property \common\models\sbbolxml\request\ComDocData2Type $docData
     */
    private $docData = null;

    /**
     * Тип
     *
     * @property string $type
     */
    private $type = null;

    /**
     * Тема
     *
     * @property string $theme
     */
    private $theme = null;

    /**
     * Текст сообщения
     *
     * @property string $addInfo
     */
    private $addInfo = null;

    /**
     * Тип документа
     *
     * @property string $templateDocumentType
     */
    private $templateDocumentType = null;

    /**
     * Номер документа
     *
     * @property string $templateDocumentNumber
     */
    private $templateDocumentNumber = null;

    /**
     * Дата документа
     *
     * @property \DateTime $templateDocumentDate
     */
    private $templateDocumentDate = null;

    /**
     * Тип документа
     *
     * @property string $templateDocumentName
     */
    private $templateDocumentName = null;

    /**
     * Связанные документы, например, реестр пополнения корп. карт, зарплатные ведомости и т.п.
     *
     * @property \common\models\sbbolxml\request\LinkedDocsEMType $linkedDocs
     */
    private $linkedDocs = null;

    /**
     * Данные о больших файлах, связанных с сущностью
     *
     * @property \common\models\sbbolxml\request\BigFileAttachmentType[] $bigFileAttachments
     */
    private $bigFileAttachments = null;

    /**
     * Приложенные к документу отсканированные образы-вложения
     *
     * @property \common\models\sbbolxml\request\AttachmentsType\AttachmentAType[] $attachments
     */
    private $attachments = null;

    /**
     * Gets as docExtGuid
     *
     * Уникальный идентификатор документа в системе ВК Банка.
     *
     * @return string
     */
    public function getDocExtGuid()
    {
        return $this->docExtGuid;
    }

    /**
     * Sets a new docExtGuid
     *
     * Уникальный идентификатор документа в системе ВК Банка.
     *
     * @param string $docExtGuid
     * @return static
     */
    public function setDocExtGuid($docExtGuid)
    {
        $this->docExtGuid = $docExtGuid;
        return $this;
    }

    /**
     * Gets as docData
     *
     * @return \common\models\sbbolxml\request\ComDocData2Type
     */
    public function getDocData()
    {
        return $this->docData;
    }

    /**
     * Sets a new docData
     *
     * @param \common\models\sbbolxml\request\ComDocData2Type $docData
     * @return static
     */
    public function setDocData(\common\models\sbbolxml\request\ComDocData2Type $docData)
    {
        $this->docData = $docData;
        return $this;
    }

    /**
     * Gets as type
     *
     * Тип
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Sets a new type
     *
     * Тип
     *
     * @param string $type
     * @return static
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * Gets as theme
     *
     * Тема
     *
     * @return string
     */
    public function getTheme()
    {
        return $this->theme;
    }

    /**
     * Sets a new theme
     *
     * Тема
     *
     * @param string $theme
     * @return static
     */
    public function setTheme($theme)
    {
        $this->theme = $theme;
        return $this;
    }

    /**
     * Gets as addInfo
     *
     * Текст сообщения
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
     * Текст сообщения
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
     * Gets as templateDocumentType
     *
     * Тип документа
     *
     * @return string
     */
    public function getTemplateDocumentType()
    {
        return $this->templateDocumentType;
    }

    /**
     * Sets a new templateDocumentType
     *
     * Тип документа
     *
     * @param string $templateDocumentType
     * @return static
     */
    public function setTemplateDocumentType($templateDocumentType)
    {
        $this->templateDocumentType = $templateDocumentType;
        return $this;
    }

    /**
     * Gets as templateDocumentNumber
     *
     * Номер документа
     *
     * @return string
     */
    public function getTemplateDocumentNumber()
    {
        return $this->templateDocumentNumber;
    }

    /**
     * Sets a new templateDocumentNumber
     *
     * Номер документа
     *
     * @param string $templateDocumentNumber
     * @return static
     */
    public function setTemplateDocumentNumber($templateDocumentNumber)
    {
        $this->templateDocumentNumber = $templateDocumentNumber;
        return $this;
    }

    /**
     * Gets as templateDocumentDate
     *
     * Дата документа
     *
     * @return \DateTime
     */
    public function getTemplateDocumentDate()
    {
        return $this->templateDocumentDate;
    }

    /**
     * Sets a new templateDocumentDate
     *
     * Дата документа
     *
     * @param \DateTime $templateDocumentDate
     * @return static
     */
    public function setTemplateDocumentDate(\DateTime $templateDocumentDate)
    {
        $this->templateDocumentDate = $templateDocumentDate;
        return $this;
    }

    /**
     * Gets as templateDocumentName
     *
     * Тип документа
     *
     * @return string
     */
    public function getTemplateDocumentName()
    {
        return $this->templateDocumentName;
    }

    /**
     * Sets a new templateDocumentName
     *
     * Тип документа
     *
     * @param string $templateDocumentName
     * @return static
     */
    public function setTemplateDocumentName($templateDocumentName)
    {
        $this->templateDocumentName = $templateDocumentName;
        return $this;
    }

    /**
     * Gets as linkedDocs
     *
     * Связанные документы, например, реестр пополнения корп. карт, зарплатные ведомости и т.п.
     *
     * @return \common\models\sbbolxml\request\LinkedDocsEMType
     */
    public function getLinkedDocs()
    {
        return $this->linkedDocs;
    }

    /**
     * Sets a new linkedDocs
     *
     * Связанные документы, например, реестр пополнения корп. карт, зарплатные ведомости и т.п.
     *
     * @param \common\models\sbbolxml\request\LinkedDocsEMType $linkedDocs
     * @return static
     */
    public function setLinkedDocs(\common\models\sbbolxml\request\LinkedDocsEMType $linkedDocs)
    {
        $this->linkedDocs = $linkedDocs;
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


}


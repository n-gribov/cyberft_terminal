<?php

namespace common\models\raiffeisenxml\request;

/**
 * Class representing LetterInBankType
 *
 *
 * XSD Type: LetterInBank
 */
class LetterInBankType
{

    /**
     * Идентификатор документа в УС
     *
     * @property string $docExtId
     */
    private $docExtId = null;

    /**
     * Идентификатор диалога
     *
     * @property string $conversationId
     */
    private $conversationId = null;

    /**
     * Идентификатор письма, на которое данное письмо является ответом
     *
     * @property string $replyForMessageExtId
     */
    private $replyForMessageExtId = null;

    /**
     * Общие реквизиты платёжного документа ДБО
     *
     * @property \common\models\raiffeisenxml\request\ComLettDataType $docData
     */
    private $docData = null;

    /**
     * Тип
     *
     * @property string $type
     */
    private $type = null;

    /**
     * Тема письма
     *
     * @property string $theme
     */
    private $theme = null;

    /**
     * Получатель письма
     *
     * @property string $receiver
     */
    private $receiver = null;

    /**
     * Текст сообщения
     *
     * @property string $addInfo
     */
    private $addInfo = null;

    /**
     * Реквизиты документа
     *
     * @property \common\models\raiffeisenxml\request\DocDataType $linkDocData
     */
    private $linkDocData = null;

    /**
     * Группа получателей
     *
     * @property \common\models\raiffeisenxml\request\RecipientGroupType $recipientGroup
     */
    private $recipientGroup = null;

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
     * Gets as conversationId
     *
     * Идентификатор диалога
     *
     * @return string
     */
    public function getConversationId()
    {
        return $this->conversationId;
    }

    /**
     * Sets a new conversationId
     *
     * Идентификатор диалога
     *
     * @param string $conversationId
     * @return static
     */
    public function setConversationId($conversationId)
    {
        $this->conversationId = $conversationId;
        return $this;
    }

    /**
     * Gets as replyForMessageExtId
     *
     * Идентификатор письма, на которое данное письмо является ответом
     *
     * @return string
     */
    public function getReplyForMessageExtId()
    {
        return $this->replyForMessageExtId;
    }

    /**
     * Sets a new replyForMessageExtId
     *
     * Идентификатор письма, на которое данное письмо является ответом
     *
     * @param string $replyForMessageExtId
     * @return static
     */
    public function setReplyForMessageExtId($replyForMessageExtId)
    {
        $this->replyForMessageExtId = $replyForMessageExtId;
        return $this;
    }

    /**
     * Gets as docData
     *
     * Общие реквизиты платёжного документа ДБО
     *
     * @return \common\models\raiffeisenxml\request\ComLettDataType
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
     * @param \common\models\raiffeisenxml\request\ComLettDataType $docData
     * @return static
     */
    public function setDocData(\common\models\raiffeisenxml\request\ComLettDataType $docData)
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
     * Тема письма
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
     * Тема письма
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
     * Gets as receiver
     *
     * Получатель письма
     *
     * @return string
     */
    public function getReceiver()
    {
        return $this->receiver;
    }

    /**
     * Sets a new receiver
     *
     * Получатель письма
     *
     * @param string $receiver
     * @return static
     */
    public function setReceiver($receiver)
    {
        $this->receiver = $receiver;
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
     * Gets as linkDocData
     *
     * Реквизиты документа
     *
     * @return \common\models\raiffeisenxml\request\DocDataType
     */
    public function getLinkDocData()
    {
        return $this->linkDocData;
    }

    /**
     * Sets a new linkDocData
     *
     * Реквизиты документа
     *
     * @param \common\models\raiffeisenxml\request\DocDataType $linkDocData
     * @return static
     */
    public function setLinkDocData(\common\models\raiffeisenxml\request\DocDataType $linkDocData)
    {
        $this->linkDocData = $linkDocData;
        return $this;
    }

    /**
     * Gets as recipientGroup
     *
     * Группа получателей
     *
     * @return \common\models\raiffeisenxml\request\RecipientGroupType
     */
    public function getRecipientGroup()
    {
        return $this->recipientGroup;
    }

    /**
     * Sets a new recipientGroup
     *
     * Группа получателей
     *
     * @param \common\models\raiffeisenxml\request\RecipientGroupType $recipientGroup
     * @return static
     */
    public function setRecipientGroup(\common\models\raiffeisenxml\request\RecipientGroupType $recipientGroup)
    {
        $this->recipientGroup = $recipientGroup;
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


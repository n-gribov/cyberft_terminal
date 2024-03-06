<?php

namespace common\models\raiffeisenxml\response;

/**
 * Class representing LetterFromBankType
 *
 *
 * XSD Type: LetterFromBank
 */
class LetterFromBankType
{

    /**
     * Идентификатор документа в системе ДБО
     *
     * @property string $docExtId
     */
    private $docExtId = null;

    /**
     * Общие реквизиты документа ДБО
     *
     * @property \common\models\raiffeisenxml\response\ComLettDataType $docData
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
     * Связанные документы, например, реестр пополнения корп. карт, зарплатные ведомости
     *  и т.п.
     *
     * @property \common\models\raiffeisenxml\response\LinkedDocsType\LDocAType[] $linkedDocs
     */
    private $linkedDocs = null;

    /**
     * Приложенные к документу отсканированные образы-вложения
     *
     * @property \common\models\raiffeisenxml\response\AttachmentsType\AttachmentAType[] $attachments
     */
    private $attachments = null;

    /**
     * Обязательно к прочтению
     *
     * @property bool $mustRead
     */
    private $mustRead = null;

    /**
     * Номер счета
     *
     * @property string $accNum
     */
    private $accNum = null;

    /**
     * @property \common\models\raiffeisenxml\response\DigitalSignType[] $signs
     */
    private $signs = null;

    /**
     * Gets as docExtId
     *
     * Идентификатор документа в системе ДБО
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
     * Идентификатор документа в системе ДБО
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
     * Общие реквизиты документа ДБО
     *
     * @return \common\models\raiffeisenxml\response\ComLettDataType
     */
    public function getDocData()
    {
        return $this->docData;
    }

    /**
     * Sets a new docData
     *
     * Общие реквизиты документа ДБО
     *
     * @param \common\models\raiffeisenxml\response\ComLettDataType $docData
     * @return static
     */
    public function setDocData(\common\models\raiffeisenxml\response\ComLettDataType $docData)
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
     * Adds as lDoc
     *
     * Связанные документы, например, реестр пополнения корп. карт, зарплатные ведомости
     *  и т.п.
     *
     * @return static
     * @param \common\models\raiffeisenxml\response\LinkedDocsType\LDocAType $lDoc
     */
    public function addToLinkedDocs(\common\models\raiffeisenxml\response\LinkedDocsType\LDocAType $lDoc)
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
     * @return \common\models\raiffeisenxml\response\LinkedDocsType\LDocAType[]
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
     * @param \common\models\raiffeisenxml\response\LinkedDocsType\LDocAType[] $linkedDocs
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
     * @param \common\models\raiffeisenxml\response\AttachmentsType\AttachmentAType $attachment
     */
    public function addToAttachments(\common\models\raiffeisenxml\response\AttachmentsType\AttachmentAType $attachment)
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
     * @return \common\models\raiffeisenxml\response\AttachmentsType\AttachmentAType[]
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
     * @param \common\models\raiffeisenxml\response\AttachmentsType\AttachmentAType[] $attachments
     * @return static
     */
    public function setAttachments(array $attachments)
    {
        $this->attachments = $attachments;
        return $this;
    }

    /**
     * Gets as mustRead
     *
     * Обязательно к прочтению
     *
     * @return bool
     */
    public function getMustRead()
    {
        return $this->mustRead;
    }

    /**
     * Sets a new mustRead
     *
     * Обязательно к прочтению
     *
     * @param bool $mustRead
     * @return static
     */
    public function setMustRead($mustRead)
    {
        $this->mustRead = $mustRead;
        return $this;
    }

    /**
     * Gets as accNum
     *
     * Номер счета
     *
     * @return string
     */
    public function getAccNum()
    {
        return $this->accNum;
    }

    /**
     * Sets a new accNum
     *
     * Номер счета
     *
     * @param string $accNum
     * @return static
     */
    public function setAccNum($accNum)
    {
        $this->accNum = $accNum;
        return $this;
    }

    /**
     * Adds as sign
     *
     * @return static
     * @param \common\models\raiffeisenxml\response\DigitalSignType $sign
     */
    public function addToSigns(\common\models\raiffeisenxml\response\DigitalSignType $sign)
    {
        $this->signs[] = $sign;
        return $this;
    }

    /**
     * isset signs
     *
     * @param int|string $index
     * @return bool
     */
    public function issetSigns($index)
    {
        return isset($this->signs[$index]);
    }

    /**
     * unset signs
     *
     * @param int|string $index
     * @return void
     */
    public function unsetSigns($index)
    {
        unset($this->signs[$index]);
    }

    /**
     * Gets as signs
     *
     * @return \common\models\raiffeisenxml\response\DigitalSignType[]
     */
    public function getSigns()
    {
        return $this->signs;
    }

    /**
     * Sets a new signs
     *
     * @param \common\models\raiffeisenxml\response\DigitalSignType[] $signs
     * @return static
     */
    public function setSigns(array $signs)
    {
        $this->signs = $signs;
        return $this;
    }


}


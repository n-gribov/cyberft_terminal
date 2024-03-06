<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing ExchangeMessagesFromBankType
 *
 *
 * XSD Type: ExchangeMessagesFromBank
 */
class ExchangeMessagesFromBankType
{

    /**
     * Идентификатор документа в СББ
     *
     * @property string $docExtId
     */
    private $docExtId = null;

    /**
     * Уникальный идентификатор документа в системе ВК Банка.
     *
     * @property string $docExtGuid
     */
    private $docExtGuid = null;

    /**
     * Идентификатор организации в СББОЛ
     *
     * @property string $orgId
     */
    private $orgId = null;

    /**
     * @property \common\models\sbbolxml\response\ComDocDataFromBankType $docData
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
     * История сообщений
     *
     * @property string $messageHistory
     */
    private $messageHistory = null;

    /**
     * Приложенные к документу отсканированные образы-вложения
     *
     * @property \common\models\sbbolxml\response\AttachmentType[] $attachments
     */
    private $attachments = null;

    /**
     * Связанные документы, например, реестр пополнения корп. карт, зарплатные ведомости и т.п.
     *
     * @property \common\models\sbbolxml\response\LinkedDocsType\LDocAType[] $linkedDocs
     */
    private $linkedDocs = null;

    /**
     * ЭП клиента
     *
     * @property \common\models\sbbolxml\response\DigitalSignType $sign
     */
    private $sign = null;

    /**
     * Gets as docExtId
     *
     * Идентификатор документа в СББ
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
     * Идентификатор документа в СББ
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
     * Gets as orgId
     *
     * Идентификатор организации в СББОЛ
     *
     * @return string
     */
    public function getOrgId()
    {
        return $this->orgId;
    }

    /**
     * Sets a new orgId
     *
     * Идентификатор организации в СББОЛ
     *
     * @param string $orgId
     * @return static
     */
    public function setOrgId($orgId)
    {
        $this->orgId = $orgId;
        return $this;
    }

    /**
     * Gets as docData
     *
     * @return \common\models\sbbolxml\response\ComDocDataFromBankType
     */
    public function getDocData()
    {
        return $this->docData;
    }

    /**
     * Sets a new docData
     *
     * @param \common\models\sbbolxml\response\ComDocDataFromBankType $docData
     * @return static
     */
    public function setDocData(\common\models\sbbolxml\response\ComDocDataFromBankType $docData)
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
     * Gets as messageHistory
     *
     * История сообщений
     *
     * @return string
     */
    public function getMessageHistory()
    {
        return $this->messageHistory;
    }

    /**
     * Sets a new messageHistory
     *
     * История сообщений
     *
     * @param string $messageHistory
     * @return static
     */
    public function setMessageHistory($messageHistory)
    {
        $this->messageHistory = $messageHistory;
        return $this;
    }

    /**
     * Adds as attachment
     *
     * Приложенные к документу отсканированные образы-вложения
     *
     * @return static
     * @param \common\models\sbbolxml\response\AttachmentType $attachment
     */
    public function addToAttachments(\common\models\sbbolxml\response\AttachmentType $attachment)
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
     * @return \common\models\sbbolxml\response\AttachmentType[]
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
     * @param \common\models\sbbolxml\response\AttachmentType[] $attachments
     * @return static
     */
    public function setAttachments(array $attachments)
    {
        $this->attachments = $attachments;
        return $this;
    }

    /**
     * Adds as lDoc
     *
     * Связанные документы, например, реестр пополнения корп. карт, зарплатные ведомости и т.п.
     *
     * @return static
     * @param \common\models\sbbolxml\response\LinkedDocsType\LDocAType $lDoc
     */
    public function addToLinkedDocs(\common\models\sbbolxml\response\LinkedDocsType\LDocAType $lDoc)
    {
        $this->linkedDocs[] = $lDoc;
        return $this;
    }

    /**
     * isset linkedDocs
     *
     * Связанные документы, например, реестр пополнения корп. карт, зарплатные ведомости и т.п.
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
     * Связанные документы, например, реестр пополнения корп. карт, зарплатные ведомости и т.п.
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
     * Связанные документы, например, реестр пополнения корп. карт, зарплатные ведомости и т.п.
     *
     * @return \common\models\sbbolxml\response\LinkedDocsType\LDocAType[]
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
     * @param \common\models\sbbolxml\response\LinkedDocsType\LDocAType[] $linkedDocs
     * @return static
     */
    public function setLinkedDocs(array $linkedDocs)
    {
        $this->linkedDocs = $linkedDocs;
        return $this;
    }

    /**
     * Gets as sign
     *
     * ЭП клиента
     *
     * @return \common\models\sbbolxml\response\DigitalSignType
     */
    public function getSign()
    {
        return $this->sign;
    }

    /**
     * Sets a new sign
     *
     * ЭП клиента
     *
     * @param \common\models\sbbolxml\response\DigitalSignType $sign
     * @return static
     */
    public function setSign(\common\models\sbbolxml\response\DigitalSignType $sign)
    {
        $this->sign = $sign;
        return $this;
    }


}


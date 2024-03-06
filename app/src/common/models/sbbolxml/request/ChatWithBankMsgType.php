<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing ChatWithBankMsgType
 *
 * Обмен сообщениями с банком (в Банк)
 * XSD Type: ChatWithBankMsg
 */
class ChatWithBankMsgType
{

    /**
     * Идентификатор сообщения чата в УС
     *
     * @property string $chatMsgExtId
     */
    private $chatMsgExtId = null;

    /**
     * Идентификатор документа, к которому относится сообщение чата, в СББОЛ
     *
     * @property string $docId
     */
    private $docId = null;

    /**
     * Номер сообщения чата
     *
     * @property integer $chatMsgNum
     */
    private $chatMsgNum = null;

    /**
     * Дата создания сообщения чата
     *
     * @property \DateTime $chatMsgDate
     */
    private $chatMsgDate = null;

    /**
     * Наименование подразделения банка
     *
     * @property string $bankName
     */
    private $bankName = null;

    /**
     * Уполномоченный сотрудник организации клиента
     *
     * @property \common\models\sbbolxml\request\AuthPers2Type $authPers
     */
    private $authPers = null;

    /**
     * Текст сообщения
     *
     * @property string $addInfo
     */
    private $addInfo = null;

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
     * Gets as chatMsgExtId
     *
     * Идентификатор сообщения чата в УС
     *
     * @return string
     */
    public function getChatMsgExtId()
    {
        return $this->chatMsgExtId;
    }

    /**
     * Sets a new chatMsgExtId
     *
     * Идентификатор сообщения чата в УС
     *
     * @param string $chatMsgExtId
     * @return static
     */
    public function setChatMsgExtId($chatMsgExtId)
    {
        $this->chatMsgExtId = $chatMsgExtId;
        return $this;
    }

    /**
     * Gets as docId
     *
     * Идентификатор документа, к которому относится сообщение чата, в СББОЛ
     *
     * @return string
     */
    public function getDocId()
    {
        return $this->docId;
    }

    /**
     * Sets a new docId
     *
     * Идентификатор документа, к которому относится сообщение чата, в СББОЛ
     *
     * @param string $docId
     * @return static
     */
    public function setDocId($docId)
    {
        $this->docId = $docId;
        return $this;
    }

    /**
     * Gets as chatMsgNum
     *
     * Номер сообщения чата
     *
     * @return integer
     */
    public function getChatMsgNum()
    {
        return $this->chatMsgNum;
    }

    /**
     * Sets a new chatMsgNum
     *
     * Номер сообщения чата
     *
     * @param integer $chatMsgNum
     * @return static
     */
    public function setChatMsgNum($chatMsgNum)
    {
        $this->chatMsgNum = $chatMsgNum;
        return $this;
    }

    /**
     * Gets as chatMsgDate
     *
     * Дата создания сообщения чата
     *
     * @return \DateTime
     */
    public function getChatMsgDate()
    {
        return $this->chatMsgDate;
    }

    /**
     * Sets a new chatMsgDate
     *
     * Дата создания сообщения чата
     *
     * @param \DateTime $chatMsgDate
     * @return static
     */
    public function setChatMsgDate(\DateTime $chatMsgDate)
    {
        $this->chatMsgDate = $chatMsgDate;
        return $this;
    }

    /**
     * Gets as bankName
     *
     * Наименование подразделения банка
     *
     * @return string
     */
    public function getBankName()
    {
        return $this->bankName;
    }

    /**
     * Sets a new bankName
     *
     * Наименование подразделения банка
     *
     * @param string $bankName
     * @return static
     */
    public function setBankName($bankName)
    {
        $this->bankName = $bankName;
        return $this;
    }

    /**
     * Gets as authPers
     *
     * Уполномоченный сотрудник организации клиента
     *
     * @return \common\models\sbbolxml\request\AuthPers2Type
     */
    public function getAuthPers()
    {
        return $this->authPers;
    }

    /**
     * Sets a new authPers
     *
     * Уполномоченный сотрудник организации клиента
     *
     * @param \common\models\sbbolxml\request\AuthPers2Type $authPers
     * @return static
     */
    public function setAuthPers(\common\models\sbbolxml\request\AuthPers2Type $authPers)
    {
        $this->authPers = $authPers;
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


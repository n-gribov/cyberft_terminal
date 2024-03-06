<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing ChatWithBankMsgType
 *
 * Обмен сообщениями с банком (из Банка)
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
     * Идентификатор документа, к оторому относится сообщение чата, в УС
     *
     * @property string $docExtId
     */
    private $docExtId = null;

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
     * Является ли создатель сообщения клиентом , в противном случае создал сообщение банк
     *
     * @property boolean $creatorClient
     */
    private $creatorClient = null;

    /**
     * Наименование подразделения банка
     *
     * @property string $bankName
     */
    private $bankName = null;

    /**
     * Уполномоченный сотрудник организации клиента
     *
     * @property \common\models\sbbolxml\response\AuthPersType $authPers
     */
    private $authPers = null;

    /**
     * Статус сообщения
     *
     * @property string $status
     */
    private $status = null;

    /**
     * Текст сообщения
     *
     * @property string $addInfo
     */
    private $addInfo = null;

    /**
     * Приложенные к документу отсканированные образы-вложения
     *
     * @property \common\models\sbbolxml\response\AttachmentType[] $attachments
     */
    private $attachments = null;

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
     * Gets as docExtId
     *
     * Идентификатор документа, к оторому относится сообщение чата, в УС
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
     * Идентификатор документа, к оторому относится сообщение чата, в УС
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
     * Gets as creatorClient
     *
     * Является ли создатель сообщения клиентом , в противном случае создал сообщение банк
     *
     * @return boolean
     */
    public function getCreatorClient()
    {
        return $this->creatorClient;
    }

    /**
     * Sets a new creatorClient
     *
     * Является ли создатель сообщения клиентом , в противном случае создал сообщение банк
     *
     * @param boolean $creatorClient
     * @return static
     */
    public function setCreatorClient($creatorClient)
    {
        $this->creatorClient = $creatorClient;
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
     * @return \common\models\sbbolxml\response\AuthPersType
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
     * @param \common\models\sbbolxml\response\AuthPersType $authPers
     * @return static
     */
    public function setAuthPers(\common\models\sbbolxml\response\AuthPersType $authPers)
    {
        $this->authPers = $authPers;
        return $this;
    }

    /**
     * Gets as status
     *
     * Статус сообщения
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Sets a new status
     *
     * Статус сообщения
     *
     * @param string $status
     * @return static
     */
    public function setStatus($status)
    {
        $this->status = $status;
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


}


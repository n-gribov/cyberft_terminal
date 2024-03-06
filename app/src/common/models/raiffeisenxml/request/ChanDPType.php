<?php

namespace common\models\raiffeisenxml\request;

/**
 * Class representing ChanDPType
 *
 *
 * XSD Type: ChanDP
 */
class ChanDPType
{

    /**
     * Идентификатор документа в УС
     *
     * @property string $docExtId
     */
    private $docExtId = null;

    /**
     * Общие реквизиты документа ВК ДБО
     *
     * @property \common\models\raiffeisenxml\request\DocDataCCType $docData
     */
    private $docData = null;

    /**
     * Информация по ПС
     *
     * @property \common\models\raiffeisenxml\request\ChanDPType\DpInfoAType\OperAType[] $dpInfo
     */
    private $dpInfo = null;

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
     * Общие реквизиты документа ВК ДБО
     *
     * @return \common\models\raiffeisenxml\request\DocDataCCType
     */
    public function getDocData()
    {
        return $this->docData;
    }

    /**
     * Sets a new docData
     *
     * Общие реквизиты документа ВК ДБО
     *
     * @param \common\models\raiffeisenxml\request\DocDataCCType $docData
     * @return static
     */
    public function setDocData(\common\models\raiffeisenxml\request\DocDataCCType $docData)
    {
        $this->docData = $docData;
        return $this;
    }

    /**
     * Adds as oper
     *
     * Информация по ПС
     *
     * @return static
     * @param \common\models\raiffeisenxml\request\ChanDPType\DpInfoAType\OperAType $oper
     */
    public function addToDpInfo(\common\models\raiffeisenxml\request\ChanDPType\DpInfoAType\OperAType $oper)
    {
        $this->dpInfo[] = $oper;
        return $this;
    }

    /**
     * isset dpInfo
     *
     * Информация по ПС
     *
     * @param int|string $index
     * @return bool
     */
    public function issetDpInfo($index)
    {
        return isset($this->dpInfo[$index]);
    }

    /**
     * unset dpInfo
     *
     * Информация по ПС
     *
     * @param int|string $index
     * @return void
     */
    public function unsetDpInfo($index)
    {
        unset($this->dpInfo[$index]);
    }

    /**
     * Gets as dpInfo
     *
     * Информация по ПС
     *
     * @return \common\models\raiffeisenxml\request\ChanDPType\DpInfoAType\OperAType[]
     */
    public function getDpInfo()
    {
        return $this->dpInfo;
    }

    /**
     * Sets a new dpInfo
     *
     * Информация по ПС
     *
     * @param \common\models\raiffeisenxml\request\ChanDPType\DpInfoAType\OperAType[] $dpInfo
     * @return static
     */
    public function setDpInfo(array $dpInfo)
    {
        $this->dpInfo = $dpInfo;
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


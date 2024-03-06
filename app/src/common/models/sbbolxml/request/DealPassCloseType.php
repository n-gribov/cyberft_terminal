<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing DealPassCloseType
 *
 *
 * XSD Type: DealPassClose
 */
class DealPassCloseType extends DocBaseType
{

    /**
     * Общие реквизиты документа ВК ДБО
     *
     * @property \common\models\sbbolxml\request\DocDataCCType $docData
     */
    private $docData = null;

    /**
     * Информация по ПС
     *
     * @property \common\models\sbbolxml\request\DpInfosType\DpInfoAType[] $dpInfos
     */
    private $dpInfos = null;

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
     * Gets as docData
     *
     * Общие реквизиты документа ВК ДБО
     *
     * @return \common\models\sbbolxml\request\DocDataCCType
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
     * @param \common\models\sbbolxml\request\DocDataCCType $docData
     * @return static
     */
    public function setDocData(\common\models\sbbolxml\request\DocDataCCType $docData)
    {
        $this->docData = $docData;
        return $this;
    }

    /**
     * Adds as dpInfo
     *
     * Информация по ПС
     *
     * @return static
     * @param \common\models\sbbolxml\request\DpInfosType\DpInfoAType $dpInfo
     */
    public function addToDpInfos(\common\models\sbbolxml\request\DpInfosType\DpInfoAType $dpInfo)
    {
        $this->dpInfos[] = $dpInfo;
        return $this;
    }

    /**
     * isset dpInfos
     *
     * Информация по ПС
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetDpInfos($index)
    {
        return isset($this->dpInfos[$index]);
    }

    /**
     * unset dpInfos
     *
     * Информация по ПС
     *
     * @param scalar $index
     * @return void
     */
    public function unsetDpInfos($index)
    {
        unset($this->dpInfos[$index]);
    }

    /**
     * Gets as dpInfos
     *
     * Информация по ПС
     *
     * @return \common\models\sbbolxml\request\DpInfosType\DpInfoAType[]
     */
    public function getDpInfos()
    {
        return $this->dpInfos;
    }

    /**
     * Sets a new dpInfos
     *
     * Информация по ПС
     *
     * @param \common\models\sbbolxml\request\DpInfosType\DpInfoAType[] $dpInfos
     * @return static
     */
    public function setDpInfos(array $dpInfos)
    {
        $this->dpInfos = $dpInfos;
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


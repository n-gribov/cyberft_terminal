<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing DealPassRestructType
 *
 * Заявление о переоформлении паспорта сделки 138И
 * XSD Type: DealPassRestruct
 */
class DealPassRestructType extends DocBaseType
{

    /**
     * Общие реквизиты документа ВК ДБО
     *
     * @property \common\models\sbbolxml\request\DocDataCCType $docData
     */
    private $docData = null;

    /**
     * Сведения о переоформляемых ПС
     *
     * @property \common\models\sbbolxml\request\DpDataType[] $dpRestruct
     */
    private $dpRestruct = null;

    /**
     * Приложения на x листах [не используется, оставлено для совместимости]
     *
     * @property string $sheetsNum
     */
    private $sheetsNum = null;

    /**
     * Приложения [не используется, оставлено для совместимости]
     *
     * @property string $attach
     */
    private $attach = null;

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
     * Adds as dpData
     *
     * Сведения о переоформляемых ПС
     *
     * @return static
     * @param \common\models\sbbolxml\request\DpDataType $dpData
     */
    public function addToDpRestruct(\common\models\sbbolxml\request\DpDataType $dpData)
    {
        $this->dpRestruct[] = $dpData;
        return $this;
    }

    /**
     * isset dpRestruct
     *
     * Сведения о переоформляемых ПС
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetDpRestruct($index)
    {
        return isset($this->dpRestruct[$index]);
    }

    /**
     * unset dpRestruct
     *
     * Сведения о переоформляемых ПС
     *
     * @param scalar $index
     * @return void
     */
    public function unsetDpRestruct($index)
    {
        unset($this->dpRestruct[$index]);
    }

    /**
     * Gets as dpRestruct
     *
     * Сведения о переоформляемых ПС
     *
     * @return \common\models\sbbolxml\request\DpDataType[]
     */
    public function getDpRestruct()
    {
        return $this->dpRestruct;
    }

    /**
     * Sets a new dpRestruct
     *
     * Сведения о переоформляемых ПС
     *
     * @param \common\models\sbbolxml\request\DpDataType[] $dpRestruct
     * @return static
     */
    public function setDpRestruct(array $dpRestruct)
    {
        $this->dpRestruct = $dpRestruct;
        return $this;
    }

    /**
     * Gets as sheetsNum
     *
     * Приложения на x листах [не используется, оставлено для совместимости]
     *
     * @return string
     */
    public function getSheetsNum()
    {
        return $this->sheetsNum;
    }

    /**
     * Sets a new sheetsNum
     *
     * Приложения на x листах [не используется, оставлено для совместимости]
     *
     * @param string $sheetsNum
     * @return static
     */
    public function setSheetsNum($sheetsNum)
    {
        $this->sheetsNum = $sheetsNum;
        return $this;
    }

    /**
     * Gets as attach
     *
     * Приложения [не используется, оставлено для совместимости]
     *
     * @return string
     */
    public function getAttach()
    {
        return $this->attach;
    }

    /**
     * Sets a new attach
     *
     * Приложения [не используется, оставлено для совместимости]
     *
     * @param string $attach
     * @return static
     */
    public function setAttach($attach)
    {
        $this->attach = $attach;
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


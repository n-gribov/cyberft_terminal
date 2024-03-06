<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing ConfDocCertificate181IType
 *
 *
 * XSD Type: ConfDocCertificate181I
 */
class ConfDocCertificate181IType extends DocBaseType
{

    /**
     * Общие реквизиты документа
     *
     * @property \common\models\sbbolxml\request\DocDataConf181IType $docData
     */
    private $docData = null;

    /**
     * Содержит данные табличных полей
     *
     * @property \common\models\sbbolxml\request\ConfDocCertificateDoc181IType[] $confDocCertificateDocs181I
     */
    private $confDocCertificateDocs181I = null;

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
     * Общие реквизиты документа
     *
     * @return \common\models\sbbolxml\request\DocDataConf181IType
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
     * @param \common\models\sbbolxml\request\DocDataConf181IType $docData
     * @return static
     */
    public function setDocData(\common\models\sbbolxml\request\DocDataConf181IType $docData)
    {
        $this->docData = $docData;
        return $this;
    }

    /**
     * Adds as confDocCertificateDoc181I
     *
     * Содержит данные табличных полей
     *
     * @return static
     * @param \common\models\sbbolxml\request\ConfDocCertificateDoc181IType $confDocCertificateDoc181I
     */
    public function addToConfDocCertificateDocs181I(\common\models\sbbolxml\request\ConfDocCertificateDoc181IType $confDocCertificateDoc181I)
    {
        $this->confDocCertificateDocs181I[] = $confDocCertificateDoc181I;
        return $this;
    }

    /**
     * isset confDocCertificateDocs181I
     *
     * Содержит данные табличных полей
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetConfDocCertificateDocs181I($index)
    {
        return isset($this->confDocCertificateDocs181I[$index]);
    }

    /**
     * unset confDocCertificateDocs181I
     *
     * Содержит данные табличных полей
     *
     * @param scalar $index
     * @return void
     */
    public function unsetConfDocCertificateDocs181I($index)
    {
        unset($this->confDocCertificateDocs181I[$index]);
    }

    /**
     * Gets as confDocCertificateDocs181I
     *
     * Содержит данные табличных полей
     *
     * @return \common\models\sbbolxml\request\ConfDocCertificateDoc181IType[]
     */
    public function getConfDocCertificateDocs181I()
    {
        return $this->confDocCertificateDocs181I;
    }

    /**
     * Sets a new confDocCertificateDocs181I
     *
     * Содержит данные табличных полей
     *
     * @param \common\models\sbbolxml\request\ConfDocCertificateDoc181IType[] $confDocCertificateDocs181I
     * @return static
     */
    public function setConfDocCertificateDocs181I(array $confDocCertificateDocs181I)
    {
        $this->confDocCertificateDocs181I = $confDocCertificateDocs181I;
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


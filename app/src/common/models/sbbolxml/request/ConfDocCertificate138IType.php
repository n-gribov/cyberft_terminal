<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing ConfDocCertificate138IType
 *
 *
 * XSD Type: ConfDocCertificate138I
 */
class ConfDocCertificate138IType extends DocBaseType
{

    /**
     * Общие реквизиты документа
     *
     * @property \common\models\sbbolxml\request\DocDataConf138IType $docData
     */
    private $docData = null;

    /**
     * Содержит данные табличных полей
     *
     * @property \common\models\sbbolxml\request\ConfDocCertificateDoc138IType[] $confDocCertificateDocs138I
     */
    private $confDocCertificateDocs138I = null;

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
     * @return \common\models\sbbolxml\request\DocDataConf138IType
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
     * @param \common\models\sbbolxml\request\DocDataConf138IType $docData
     * @return static
     */
    public function setDocData(\common\models\sbbolxml\request\DocDataConf138IType $docData)
    {
        $this->docData = $docData;
        return $this;
    }

    /**
     * Adds as confDocCertificateDoc138I
     *
     * Содержит данные табличных полей
     *
     * @return static
     * @param \common\models\sbbolxml\request\ConfDocCertificateDoc138IType $confDocCertificateDoc138I
     */
    public function addToConfDocCertificateDocs138I(\common\models\sbbolxml\request\ConfDocCertificateDoc138IType $confDocCertificateDoc138I)
    {
        $this->confDocCertificateDocs138I[] = $confDocCertificateDoc138I;
        return $this;
    }

    /**
     * isset confDocCertificateDocs138I
     *
     * Содержит данные табличных полей
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetConfDocCertificateDocs138I($index)
    {
        return isset($this->confDocCertificateDocs138I[$index]);
    }

    /**
     * unset confDocCertificateDocs138I
     *
     * Содержит данные табличных полей
     *
     * @param scalar $index
     * @return void
     */
    public function unsetConfDocCertificateDocs138I($index)
    {
        unset($this->confDocCertificateDocs138I[$index]);
    }

    /**
     * Gets as confDocCertificateDocs138I
     *
     * Содержит данные табличных полей
     *
     * @return \common\models\sbbolxml\request\ConfDocCertificateDoc138IType[]
     */
    public function getConfDocCertificateDocs138I()
    {
        return $this->confDocCertificateDocs138I;
    }

    /**
     * Sets a new confDocCertificateDocs138I
     *
     * Содержит данные табличных полей
     *
     * @param \common\models\sbbolxml\request\ConfDocCertificateDoc138IType[] $confDocCertificateDocs138I
     * @return static
     */
    public function setConfDocCertificateDocs138I(array $confDocCertificateDocs138I)
    {
        $this->confDocCertificateDocs138I = $confDocCertificateDocs138I;
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


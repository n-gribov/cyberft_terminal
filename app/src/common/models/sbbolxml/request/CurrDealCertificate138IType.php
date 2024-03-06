<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing CurrDealCertificate138IType
 *
 *
 * XSD Type: CurrDealCertificate138I
 */
class CurrDealCertificate138IType extends DocBaseType
{

    /**
     * Общие реквизиты документа ВК ДБО
     *
     * @property \common\models\sbbolxml\request\DocDataCC138IType $docData
     */
    private $docData = null;

    /**
     * Содержит данные табличных полей
     *
     * @property \common\models\sbbolxml\request\CurrDealCertificateDoc138IType[] $currDealCertificateDocs138I
     */
    private $currDealCertificateDocs138I = null;

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
     * @return \common\models\sbbolxml\request\DocDataCC138IType
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
     * @param \common\models\sbbolxml\request\DocDataCC138IType $docData
     * @return static
     */
    public function setDocData(\common\models\sbbolxml\request\DocDataCC138IType $docData)
    {
        $this->docData = $docData;
        return $this;
    }

    /**
     * Adds as currDealCertificateDoc138I
     *
     * Содержит данные табличных полей
     *
     * @return static
     * @param \common\models\sbbolxml\request\CurrDealCertificateDoc138IType $currDealCertificateDoc138I
     */
    public function addToCurrDealCertificateDocs138I(\common\models\sbbolxml\request\CurrDealCertificateDoc138IType $currDealCertificateDoc138I)
    {
        $this->currDealCertificateDocs138I[] = $currDealCertificateDoc138I;
        return $this;
    }

    /**
     * isset currDealCertificateDocs138I
     *
     * Содержит данные табличных полей
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetCurrDealCertificateDocs138I($index)
    {
        return isset($this->currDealCertificateDocs138I[$index]);
    }

    /**
     * unset currDealCertificateDocs138I
     *
     * Содержит данные табличных полей
     *
     * @param scalar $index
     * @return void
     */
    public function unsetCurrDealCertificateDocs138I($index)
    {
        unset($this->currDealCertificateDocs138I[$index]);
    }

    /**
     * Gets as currDealCertificateDocs138I
     *
     * Содержит данные табличных полей
     *
     * @return \common\models\sbbolxml\request\CurrDealCertificateDoc138IType[]
     */
    public function getCurrDealCertificateDocs138I()
    {
        return $this->currDealCertificateDocs138I;
    }

    /**
     * Sets a new currDealCertificateDocs138I
     *
     * Содержит данные табличных полей
     *
     * @param \common\models\sbbolxml\request\CurrDealCertificateDoc138IType[] $currDealCertificateDocs138I
     * @return static
     */
    public function setCurrDealCertificateDocs138I(array $currDealCertificateDocs138I)
    {
        $this->currDealCertificateDocs138I = $currDealCertificateDocs138I;
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


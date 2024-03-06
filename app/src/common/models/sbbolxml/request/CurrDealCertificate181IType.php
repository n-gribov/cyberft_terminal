<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing CurrDealCertificate181IType
 *
 *
 * XSD Type: CurrDealCertificate181I
 */
class CurrDealCertificate181IType
{

    /**
     * Уникальный* идентификатор документа в учетной системе (УС) Клиента.
     *  *Уникальность контролируется на стороне банка
     *
     * @property string $docExtId
     */
    private $docExtId = null;

    /**
     * Общие реквизиты документа
     *
     * @property \common\models\sbbolxml\request\DocDataCC181IType $docData
     */
    private $docData = null;

    /**
     * Содержит данные табличных полей
     *
     * @property \common\models\sbbolxml\request\CurrDealCertificateDoc181IType[] $currDealCertificateDocs181I
     */
    private $currDealCertificateDocs181I = null;

    /**
     * Содержит информацию о связном документе
     *  (РПП, валютный перевод, распоряжение об обязательной продаже, уведомление и т.п., доставленные по системе СББОЛ)
     *  по списанию или зачислению, корректируемое СВО
     *
     * @property \common\models\sbbolxml\request\LinkedDocsType\LDocAType[] $linkedDocs
     */
    private $linkedDocs = null;

    /**
     * Приложенные к документу отсканированные образы-вложения
     *
     * @property \common\models\sbbolxml\request\AttachmentsType\AttachmentAType[] $attachments
     */
    private $attachments = null;

    /**
     * Приложенные к документу отсканированные образы-вложения - для АС БФ
     *
     * @property \common\models\sbbolxml\request\BigFileAttachmentType[] $bigFileAttachments
     */
    private $bigFileAttachments = null;

    /**
     * Gets as docExtId
     *
     * Уникальный* идентификатор документа в учетной системе (УС) Клиента.
     *  *Уникальность контролируется на стороне банка
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
     * Уникальный* идентификатор документа в учетной системе (УС) Клиента.
     *  *Уникальность контролируется на стороне банка
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
     * Общие реквизиты документа
     *
     * @return \common\models\sbbolxml\request\DocDataCC181IType
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
     * @param \common\models\sbbolxml\request\DocDataCC181IType $docData
     * @return static
     */
    public function setDocData(\common\models\sbbolxml\request\DocDataCC181IType $docData)
    {
        $this->docData = $docData;
        return $this;
    }

    /**
     * Adds as currDealCertificateDoc181I
     *
     * Содержит данные табличных полей
     *
     * @return static
     * @param \common\models\sbbolxml\request\CurrDealCertificateDoc181IType $currDealCertificateDoc181I
     */
    public function addToCurrDealCertificateDocs181I(\common\models\sbbolxml\request\CurrDealCertificateDoc181IType $currDealCertificateDoc181I)
    {
        $this->currDealCertificateDocs181I[] = $currDealCertificateDoc181I;
        return $this;
    }

    /**
     * isset currDealCertificateDocs181I
     *
     * Содержит данные табличных полей
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetCurrDealCertificateDocs181I($index)
    {
        return isset($this->currDealCertificateDocs181I[$index]);
    }

    /**
     * unset currDealCertificateDocs181I
     *
     * Содержит данные табличных полей
     *
     * @param scalar $index
     * @return void
     */
    public function unsetCurrDealCertificateDocs181I($index)
    {
        unset($this->currDealCertificateDocs181I[$index]);
    }

    /**
     * Gets as currDealCertificateDocs181I
     *
     * Содержит данные табличных полей
     *
     * @return \common\models\sbbolxml\request\CurrDealCertificateDoc181IType[]
     */
    public function getCurrDealCertificateDocs181I()
    {
        return $this->currDealCertificateDocs181I;
    }

    /**
     * Sets a new currDealCertificateDocs181I
     *
     * Содержит данные табличных полей
     *
     * @param \common\models\sbbolxml\request\CurrDealCertificateDoc181IType[] $currDealCertificateDocs181I
     * @return static
     */
    public function setCurrDealCertificateDocs181I(array $currDealCertificateDocs181I)
    {
        $this->currDealCertificateDocs181I = $currDealCertificateDocs181I;
        return $this;
    }

    /**
     * Adds as lDoc
     *
     * Содержит информацию о связном документе
     *  (РПП, валютный перевод, распоряжение об обязательной продаже, уведомление и т.п., доставленные по системе СББОЛ)
     *  по списанию или зачислению, корректируемое СВО
     *
     * @return static
     * @param \common\models\sbbolxml\request\LinkedDocsType\LDocAType $lDoc
     */
    public function addToLinkedDocs(\common\models\sbbolxml\request\LinkedDocsType\LDocAType $lDoc)
    {
        $this->linkedDocs[] = $lDoc;
        return $this;
    }

    /**
     * isset linkedDocs
     *
     * Содержит информацию о связном документе
     *  (РПП, валютный перевод, распоряжение об обязательной продаже, уведомление и т.п., доставленные по системе СББОЛ)
     *  по списанию или зачислению, корректируемое СВО
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
     * Содержит информацию о связном документе
     *  (РПП, валютный перевод, распоряжение об обязательной продаже, уведомление и т.п., доставленные по системе СББОЛ)
     *  по списанию или зачислению, корректируемое СВО
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
     * Содержит информацию о связном документе
     *  (РПП, валютный перевод, распоряжение об обязательной продаже, уведомление и т.п., доставленные по системе СББОЛ)
     *  по списанию или зачислению, корректируемое СВО
     *
     * @return \common\models\sbbolxml\request\LinkedDocsType\LDocAType[]
     */
    public function getLinkedDocs()
    {
        return $this->linkedDocs;
    }

    /**
     * Sets a new linkedDocs
     *
     * Содержит информацию о связном документе
     *  (РПП, валютный перевод, распоряжение об обязательной продаже, уведомление и т.п., доставленные по системе СББОЛ)
     *  по списанию или зачислению, корректируемое СВО
     *
     * @param \common\models\sbbolxml\request\LinkedDocsType\LDocAType[] $linkedDocs
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
     * Приложенные к документу отсканированные образы-вложения - для АС БФ
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
     * Приложенные к документу отсканированные образы-вложения - для АС БФ
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
     * Приложенные к документу отсканированные образы-вложения - для АС БФ
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
     * Приложенные к документу отсканированные образы-вложения - для АС БФ
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
     * Приложенные к документу отсканированные образы-вложения - для АС БФ
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


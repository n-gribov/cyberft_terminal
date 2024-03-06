<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing ApplForContractType
 *
 *
 * XSD Type: ApplForContract
 */
class ApplForContractType extends DocBaseType
{

    /**
     * Общие реквизиты документа
     *
     * @property \common\models\sbbolxml\request\ApplForContractType\DocDataAType $docData
     */
    private $docData = null;

    /**
     * Данные клиента
     *
     * @property \common\models\sbbolxml\request\OrgDataType $orgData
     */
    private $orgData = null;

    /**
     * Осн. информация о картах
     *
     * @property \common\models\sbbolxml\request\ApplForContractType\CardsInfoAType $cardsInfo
     */
    private $cardsInfo = null;

    /**
     * Прочие данные
     *
     * @property \common\models\sbbolxml\request\ApplForContractType\OtherInfoAType $otherInfo
     */
    private $otherInfo = null;

    /**
     * Контактная информация
     *
     * @property string $contactInfo
     */
    private $contactInfo = null;

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
     * Связнные документы. Их наличие может влиять на особенности обработки документа в АБС
     *
     * @property \common\models\sbbolxml\request\LinkedDocsType\LDocAType[] $linkedDocs
     */
    private $linkedDocs = null;

    /**
     * Gets as docData
     *
     * Общие реквизиты документа
     *
     * @return \common\models\sbbolxml\request\ApplForContractType\DocDataAType
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
     * @param \common\models\sbbolxml\request\ApplForContractType\DocDataAType $docData
     * @return static
     */
    public function setDocData(\common\models\sbbolxml\request\ApplForContractType\DocDataAType $docData)
    {
        $this->docData = $docData;
        return $this;
    }

    /**
     * Gets as orgData
     *
     * Данные клиента
     *
     * @return \common\models\sbbolxml\request\OrgDataType
     */
    public function getOrgData()
    {
        return $this->orgData;
    }

    /**
     * Sets a new orgData
     *
     * Данные клиента
     *
     * @param \common\models\sbbolxml\request\OrgDataType $orgData
     * @return static
     */
    public function setOrgData(\common\models\sbbolxml\request\OrgDataType $orgData)
    {
        $this->orgData = $orgData;
        return $this;
    }

    /**
     * Gets as cardsInfo
     *
     * Осн. информация о картах
     *
     * @return \common\models\sbbolxml\request\ApplForContractType\CardsInfoAType
     */
    public function getCardsInfo()
    {
        return $this->cardsInfo;
    }

    /**
     * Sets a new cardsInfo
     *
     * Осн. информация о картах
     *
     * @param \common\models\sbbolxml\request\ApplForContractType\CardsInfoAType $cardsInfo
     * @return static
     */
    public function setCardsInfo(\common\models\sbbolxml\request\ApplForContractType\CardsInfoAType $cardsInfo)
    {
        $this->cardsInfo = $cardsInfo;
        return $this;
    }

    /**
     * Gets as otherInfo
     *
     * Прочие данные
     *
     * @return \common\models\sbbolxml\request\ApplForContractType\OtherInfoAType
     */
    public function getOtherInfo()
    {
        return $this->otherInfo;
    }

    /**
     * Sets a new otherInfo
     *
     * Прочие данные
     *
     * @param \common\models\sbbolxml\request\ApplForContractType\OtherInfoAType $otherInfo
     * @return static
     */
    public function setOtherInfo(\common\models\sbbolxml\request\ApplForContractType\OtherInfoAType $otherInfo)
    {
        $this->otherInfo = $otherInfo;
        return $this;
    }

    /**
     * Gets as contactInfo
     *
     * Контактная информация
     *
     * @return string
     */
    public function getContactInfo()
    {
        return $this->contactInfo;
    }

    /**
     * Sets a new contactInfo
     *
     * Контактная информация
     *
     * @param string $contactInfo
     * @return static
     */
    public function setContactInfo($contactInfo)
    {
        $this->contactInfo = $contactInfo;
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

    /**
     * Adds as lDoc
     *
     * Связнные документы. Их наличие может влиять на особенности обработки документа в АБС
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
     * Связнные документы. Их наличие может влиять на особенности обработки документа в АБС
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
     * Связнные документы. Их наличие может влиять на особенности обработки документа в АБС
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
     * Связнные документы. Их наличие может влиять на особенности обработки документа в АБС
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
     * Связнные документы. Их наличие может влиять на особенности обработки документа в АБС
     *
     * @param \common\models\sbbolxml\request\LinkedDocsType\LDocAType[] $linkedDocs
     * @return static
     */
    public function setLinkedDocs(array $linkedDocs)
    {
        $this->linkedDocs = $linkedDocs;
        return $this;
    }


}


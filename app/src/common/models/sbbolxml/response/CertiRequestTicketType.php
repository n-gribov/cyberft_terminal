<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing CertiRequestTicketType
 *
 *
 * XSD Type: CertiRequestTicket
 */
class CertiRequestTicketType
{

    /**
     * Информация о бумажных копиях КСКП ЭП
     *
     * @property \common\models\sbbolxml\response\CertiRequestTicketType\AttachmentsAType\AttachmentAType[] $attachments
     */
    private $attachments = null;

    /**
     * Adds as attachment
     *
     * Информация о бумажных копиях КСКП ЭП
     *
     * @return static
     * @param \common\models\sbbolxml\response\CertiRequestTicketType\AttachmentsAType\AttachmentAType $attachment
     */
    public function addToAttachments(\common\models\sbbolxml\response\CertiRequestTicketType\AttachmentsAType\AttachmentAType $attachment)
    {
        $this->attachments[] = $attachment;
        return $this;
    }

    /**
     * isset attachments
     *
     * Информация о бумажных копиях КСКП ЭП
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
     * Информация о бумажных копиях КСКП ЭП
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
     * Информация о бумажных копиях КСКП ЭП
     *
     * @return \common\models\sbbolxml\response\CertiRequestTicketType\AttachmentsAType\AttachmentAType[]
     */
    public function getAttachments()
    {
        return $this->attachments;
    }

    /**
     * Sets a new attachments
     *
     * Информация о бумажных копиях КСКП ЭП
     *
     * @param \common\models\sbbolxml\response\CertiRequestTicketType\AttachmentsAType\AttachmentAType[] $attachments
     * @return static
     */
    public function setAttachments(array $attachments)
    {
        $this->attachments = $attachments;
        return $this;
    }


}


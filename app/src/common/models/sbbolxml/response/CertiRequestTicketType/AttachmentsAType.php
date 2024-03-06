<?php

namespace common\models\sbbolxml\response\CertiRequestTicketType;

/**
 * Class representing AttachmentsAType
 */
class AttachmentsAType
{

    /**
     * Текстовое представление КСКП ЭП и руководство по безопасности
     *
     * @property \common\models\sbbolxml\response\CertiRequestTicketType\AttachmentsAType\AttachmentAType[] $attachment
     */
    private $attachment = array(
        
    );

    /**
     * Adds as attachment
     *
     * Текстовое представление КСКП ЭП и руководство по безопасности
     *
     * @return static
     * @param \common\models\sbbolxml\response\CertiRequestTicketType\AttachmentsAType\AttachmentAType $attachment
     */
    public function addToAttachment(\common\models\sbbolxml\response\CertiRequestTicketType\AttachmentsAType\AttachmentAType $attachment)
    {
        $this->attachment[] = $attachment;
        return $this;
    }

    /**
     * isset attachment
     *
     * Текстовое представление КСКП ЭП и руководство по безопасности
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetAttachment($index)
    {
        return isset($this->attachment[$index]);
    }

    /**
     * unset attachment
     *
     * Текстовое представление КСКП ЭП и руководство по безопасности
     *
     * @param scalar $index
     * @return void
     */
    public function unsetAttachment($index)
    {
        unset($this->attachment[$index]);
    }

    /**
     * Gets as attachment
     *
     * Текстовое представление КСКП ЭП и руководство по безопасности
     *
     * @return \common\models\sbbolxml\response\CertiRequestTicketType\AttachmentsAType\AttachmentAType[]
     */
    public function getAttachment()
    {
        return $this->attachment;
    }

    /**
     * Sets a new attachment
     *
     * Текстовое представление КСКП ЭП и руководство по безопасности
     *
     * @param \common\models\sbbolxml\response\CertiRequestTicketType\AttachmentsAType\AttachmentAType[] $attachment
     * @return static
     */
    public function setAttachment(array $attachment)
    {
        $this->attachment = $attachment;
        return $this;
    }


}


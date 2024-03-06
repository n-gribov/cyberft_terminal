<?php

namespace common\models\sbbolxml\response\CertiRequestTicketType\AttachmentsAType;

/**
 * Class representing AttachmentAType
 */
class AttachmentAType
{

    /**
     * Имя файла с текстовым представлением КСКП ЭП в формате PDF
     *
     * @property string $attachmentName
     */
    private $attachmentName = null;

    /**
     * Текстовое представление КСКП ЭП: PDF в формате base64
     *
     * @property string $body
     */
    private $body = null;

    /**
     * Описание файла
     *
     * @property string $description
     */
    private $description = null;

    /**
     * Gets as attachmentName
     *
     * Имя файла с текстовым представлением КСКП ЭП в формате PDF
     *
     * @return string
     */
    public function getAttachmentName()
    {
        return $this->attachmentName;
    }

    /**
     * Sets a new attachmentName
     *
     * Имя файла с текстовым представлением КСКП ЭП в формате PDF
     *
     * @param string $attachmentName
     * @return static
     */
    public function setAttachmentName($attachmentName)
    {
        $this->attachmentName = $attachmentName;
        return $this;
    }

    /**
     * Gets as body
     *
     * Текстовое представление КСКП ЭП: PDF в формате base64
     *
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Sets a new body
     *
     * Текстовое представление КСКП ЭП: PDF в формате base64
     *
     * @param string $body
     * @return static
     */
    public function setBody($body)
    {
        $this->body = $body;
        return $this;
    }

    /**
     * Gets as description
     *
     * Описание файла
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Sets a new description
     *
     * Описание файла
     *
     * @param string $description
     * @return static
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }


}


<?php

namespace common\models\raiffeisenxml\request\AttachmentsType;

/**
 * Class representing AttachmentAType
 */
class AttachmentAType
{

    /**
     * Имя файла вложения
     *
     * @property string $attachmentName
     */
    private $attachmentName = null;

    /**
     * Дата создания файла вложения
     *
     * @property \DateTime $createDate
     */
    private $createDate = null;

    /**
     * Пользовательское описание файла вложения
     *
     * @property string $description
     */
    private $description = null;

    /**
     * В бинарном представлении в сжатом и несжатом виде
     *
     * @property string $body
     */
    private $body = null;

    /**
     * Gets as attachmentName
     *
     * Имя файла вложения
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
     * Имя файла вложения
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
     * Gets as createDate
     *
     * Дата создания файла вложения
     *
     * @return \DateTime
     */
    public function getCreateDate()
    {
        return $this->createDate;
    }

    /**
     * Sets a new createDate
     *
     * Дата создания файла вложения
     *
     * @param \DateTime $createDate
     * @return static
     */
    public function setCreateDate(\DateTime $createDate)
    {
        $this->createDate = $createDate;
        return $this;
    }

    /**
     * Gets as description
     *
     * Пользовательское описание файла вложения
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
     * Пользовательское описание файла вложения
     *
     * @param string $description
     * @return static
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * Gets as body
     *
     * В бинарном представлении в сжатом и несжатом виде
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
     * В бинарном представлении в сжатом и несжатом виде
     *
     * @param string $body
     * @return static
     */
    public function setBody($body)
    {
        $this->body = $body;
        return $this;
    }


}


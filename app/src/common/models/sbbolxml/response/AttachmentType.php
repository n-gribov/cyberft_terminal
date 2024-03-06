<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing AttachmentType
 *
 * Отдельное вложение
 * XSD Type: Attachment
 */
class AttachmentType
{

    /**
     * Имя файла вложения
     *
     * @property string $attachmentName
     */
    private $attachmentName = null;

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
     * Размер вложенного файла в байтах
     *
     * @property integer $fileSize
     */
    private $fileSize = null;

    /**
     * Дата создания файла
     *
     * @property \DateTime $fileDate
     */
    private $fileDate = null;

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

    /**
     * Gets as fileSize
     *
     * Размер вложенного файла в байтах
     *
     * @return integer
     */
    public function getFileSize()
    {
        return $this->fileSize;
    }

    /**
     * Sets a new fileSize
     *
     * Размер вложенного файла в байтах
     *
     * @param integer $fileSize
     * @return static
     */
    public function setFileSize($fileSize)
    {
        $this->fileSize = $fileSize;
        return $this;
    }

    /**
     * Gets as fileDate
     *
     * Дата создания файла
     *
     * @return \DateTime
     */
    public function getFileDate()
    {
        return $this->fileDate;
    }

    /**
     * Sets a new fileDate
     *
     * Дата создания файла
     *
     * @param \DateTime $fileDate
     * @return static
     */
    public function setFileDate(\DateTime $fileDate)
    {
        $this->fileDate = $fileDate;
        return $this;
    }


}


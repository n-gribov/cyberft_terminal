<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing BFUploadAttachmentType
 *
 * Файл в системе БФ для запроса ссылки на загрузку вложения
 * XSD Type: BFUploadAttachment
 */
class BFUploadAttachmentType
{

    /**
     * Уникальный идентификатор запроса, используется клиентским приложением для связывания
     *  запроса ссылки и самой ссылки
     *
     * @property string $linkUUID
     */
    private $linkUUID = null;

    /**
     * @property string $fileName
     */
    private $fileName = null;

    /**
     * Размер файла. Не используется, оставлен для совместимости.
     *
     * @property integer $fileSize
     */
    private $fileSize = null;

    /**
     * Пользовательское описание файла вложения
     *
     * @property string $comment
     */
    private $comment = null;

    /**
     * Gets as linkUUID
     *
     * Уникальный идентификатор запроса, используется клиентским приложением для связывания
     *  запроса ссылки и самой ссылки
     *
     * @return string
     */
    public function getLinkUUID()
    {
        return $this->linkUUID;
    }

    /**
     * Sets a new linkUUID
     *
     * Уникальный идентификатор запроса, используется клиентским приложением для связывания
     *  запроса ссылки и самой ссылки
     *
     * @param string $linkUUID
     * @return static
     */
    public function setLinkUUID($linkUUID)
    {
        $this->linkUUID = $linkUUID;
        return $this;
    }

    /**
     * Gets as fileName
     *
     * @return string
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * Sets a new fileName
     *
     * @param string $fileName
     * @return static
     */
    public function setFileName($fileName)
    {
        $this->fileName = $fileName;
        return $this;
    }

    /**
     * Gets as fileSize
     *
     * Размер файла. Не используется, оставлен для совместимости.
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
     * Размер файла. Не используется, оставлен для совместимости.
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
     * Gets as comment
     *
     * Пользовательское описание файла вложения
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Sets a new comment
     *
     * Пользовательское описание файла вложения
     *
     * @param string $comment
     * @return static
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
        return $this;
    }


}


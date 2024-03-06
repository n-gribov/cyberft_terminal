<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing EssenceAttachmentType
 *
 * Данные о файле реестра задолженностей
 * XSD Type: EssenceAttachment
 */
class EssenceAttachmentType
{

    /**
     * Идентификатор задания на загрузку файла в БФ
     *
     * @property string $uploadJobId
     */
    private $uploadJobId = null;

    /**
     * Имя файла
     *
     * @property string $fileName
     */
    private $fileName = null;

    /**
     * Размер файла
     *
     * @property integer $fileSize
     */
    private $fileSize = null;

    /**
     * Gets as uploadJobId
     *
     * Идентификатор задания на загрузку файла в БФ
     *
     * @return string
     */
    public function getUploadJobId()
    {
        return $this->uploadJobId;
    }

    /**
     * Sets a new uploadJobId
     *
     * Идентификатор задания на загрузку файла в БФ
     *
     * @param string $uploadJobId
     * @return static
     */
    public function setUploadJobId($uploadJobId)
    {
        $this->uploadJobId = $uploadJobId;
        return $this;
    }

    /**
     * Gets as fileName
     *
     * Имя файла
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
     * Имя файла
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
     * Размер файла
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
     * Размер файла
     *
     * @param integer $fileSize
     * @return static
     */
    public function setFileSize($fileSize)
    {
        $this->fileSize = $fileSize;
        return $this;
    }


}


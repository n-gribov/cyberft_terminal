<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing BigFileAttachmentType
 *
 * Данные о большом файле, связанном с сущностью
 * XSD Type: BigFileAttachment
 */
class BigFileAttachmentType
{

    /**
     * Идентификатор задания на загрузку файла в БФ
     *
     * @property string $uploadJobId
     */
    private $uploadJobId = null;

    /**
     * Пользовательское описание файла вложения
     *
     * @property string $description
     */
    private $description = null;

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


}


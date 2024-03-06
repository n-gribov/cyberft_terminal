<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing UploadLinkType
 *
 * Ссылка на закачку в систему БФ
 * XSD Type: UploadLink
 */
class UploadLinkType
{

    /**
     * Уникальный идентификатор запроса
     *
     * @property string $linkUuid
     */
    private $linkUuid = null;

    /**
     * Идентификатор задачи на загрузку файла
     *
     * @property string $uploadJobId
     */
    private $uploadJobId = null;

    /**
     * Ссылка на загруженный файл
     *
     * @property string $webUploadLink
     */
    private $webUploadLink = null;

    /**
     * Ошибки вложенных файлов
     *
     * @property \common\models\sbbolxml\response\BFErrorType[] $errors
     */
    private $errors = null;

    /**
     * Gets as linkUuid
     *
     * Уникальный идентификатор запроса
     *
     * @return string
     */
    public function getLinkUuid()
    {
        return $this->linkUuid;
    }

    /**
     * Sets a new linkUuid
     *
     * Уникальный идентификатор запроса
     *
     * @param string $linkUuid
     * @return static
     */
    public function setLinkUuid($linkUuid)
    {
        $this->linkUuid = $linkUuid;
        return $this;
    }

    /**
     * Gets as uploadJobId
     *
     * Идентификатор задачи на загрузку файла
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
     * Идентификатор задачи на загрузку файла
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
     * Gets as webUploadLink
     *
     * Ссылка на загруженный файл
     *
     * @return string
     */
    public function getWebUploadLink()
    {
        return $this->webUploadLink;
    }

    /**
     * Sets a new webUploadLink
     *
     * Ссылка на загруженный файл
     *
     * @param string $webUploadLink
     * @return static
     */
    public function setWebUploadLink($webUploadLink)
    {
        $this->webUploadLink = $webUploadLink;
        return $this;
    }

    /**
     * Adds as error
     *
     * Ошибки вложенных файлов
     *
     * @return static
     * @param \common\models\sbbolxml\response\BFErrorType $error
     */
    public function addToErrors(\common\models\sbbolxml\response\BFErrorType $error)
    {
        $this->errors[] = $error;
        return $this;
    }

    /**
     * isset errors
     *
     * Ошибки вложенных файлов
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetErrors($index)
    {
        return isset($this->errors[$index]);
    }

    /**
     * unset errors
     *
     * Ошибки вложенных файлов
     *
     * @param scalar $index
     * @return void
     */
    public function unsetErrors($index)
    {
        unset($this->errors[$index]);
    }

    /**
     * Gets as errors
     *
     * Ошибки вложенных файлов
     *
     * @return \common\models\sbbolxml\response\BFErrorType[]
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Sets a new errors
     *
     * Ошибки вложенных файлов
     *
     * @param \common\models\sbbolxml\response\BFErrorType[] $errors
     * @return static
     */
    public function setErrors(array $errors)
    {
        $this->errors = $errors;
        return $this;
    }


}


<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing LinkToBigFileType
 *
 * Ссылка на закачку в систему БФ
 * XSD Type: LinkToBigFile
 */
class LinkToBigFileType
{

    /**
     * Уникальный идентификатор запроса, используется клиентским приложением для связывания запроса ссылки и самой ссылки
     *
     * @property string $linkUUID
     */
    private $linkUUID = null;

    /**
     * Ссылка на ресурс «БФ» для закачивания файла
     *
     * @property string $webUploadLink
     */
    private $webUploadLink = null;

    /**
     * Уникальный идентификатор задачи на закачку файла в БФ.
     *
     * @property string $transferJobGUID
     */
    private $transferJobGUID = null;

    /**
     * Ошибки вложенных файлов
     *
     * @property \common\models\sbbolxml\response\BFErrorType[] $errors
     */
    private $errors = null;

    /**
     * Gets as linkUUID
     *
     * Уникальный идентификатор запроса, используется клиентским приложением для связывания запроса ссылки и самой ссылки
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
     * Уникальный идентификатор запроса, используется клиентским приложением для связывания запроса ссылки и самой ссылки
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
     * Gets as webUploadLink
     *
     * Ссылка на ресурс «БФ» для закачивания файла
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
     * Ссылка на ресурс «БФ» для закачивания файла
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
     * Gets as transferJobGUID
     *
     * Уникальный идентификатор задачи на закачку файла в БФ.
     *
     * @return string
     */
    public function getTransferJobGUID()
    {
        return $this->transferJobGUID;
    }

    /**
     * Sets a new transferJobGUID
     *
     * Уникальный идентификатор задачи на закачку файла в БФ.
     *
     * @param string $transferJobGUID
     * @return static
     */
    public function setTransferJobGUID($transferJobGUID)
    {
        $this->transferJobGUID = $transferJobGUID;
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


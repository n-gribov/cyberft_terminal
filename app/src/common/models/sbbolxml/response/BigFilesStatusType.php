<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing BigFilesStatusType
 *
 * Статус закачки файлов в систему БФ
 * XSD Type: BigFilesStatus
 */
class BigFilesStatusType
{

    /**
     * Уникальный идентификатор задачи на закачку файла в БФ
     *
     * @property string $uploadJobId
     */
    private $uploadJobId = null;

    /**
     * Уникальный идентификатор задачи на скачивание файла из БФ
     *
     * @property string $downloadJobId
     */
    private $downloadJobId = null;

    /**
     * Уникальный идентификатор сохраненного файла в БФ
     *
     * @property string $fileNetId
     */
    private $fileNetId = null;

    /**
     * Статус файла в БФ:
     *  PROCESSING - Файл обрабатывается
     *  SENT - Файл перемещен в хранилище
     *  ACCEPTED - Файл принят
     *  ERROR - В процессе передачи файла в целевое хранилище произошла ошибка
     *
     * @property string $status
     */
    private $status = null;

    /**
     * Ошибки вложенных файлов
     *
     * @property \common\models\sbbolxml\response\BFErrorType[] $errors
     */
    private $errors = null;

    /**
     * Gets as uploadJobId
     *
     * Уникальный идентификатор задачи на закачку файла в БФ
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
     * Уникальный идентификатор задачи на закачку файла в БФ
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
     * Gets as downloadJobId
     *
     * Уникальный идентификатор задачи на скачивание файла из БФ
     *
     * @return string
     */
    public function getDownloadJobId()
    {
        return $this->downloadJobId;
    }

    /**
     * Sets a new downloadJobId
     *
     * Уникальный идентификатор задачи на скачивание файла из БФ
     *
     * @param string $downloadJobId
     * @return static
     */
    public function setDownloadJobId($downloadJobId)
    {
        $this->downloadJobId = $downloadJobId;
        return $this;
    }

    /**
     * Gets as fileNetId
     *
     * Уникальный идентификатор сохраненного файла в БФ
     *
     * @return string
     */
    public function getFileNetId()
    {
        return $this->fileNetId;
    }

    /**
     * Sets a new fileNetId
     *
     * Уникальный идентификатор сохраненного файла в БФ
     *
     * @param string $fileNetId
     * @return static
     */
    public function setFileNetId($fileNetId)
    {
        $this->fileNetId = $fileNetId;
        return $this;
    }

    /**
     * Gets as status
     *
     * Статус файла в БФ:
     *  PROCESSING - Файл обрабатывается
     *  SENT - Файл перемещен в хранилище
     *  ACCEPTED - Файл принят
     *  ERROR - В процессе передачи файла в целевое хранилище произошла ошибка
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Sets a new status
     *
     * Статус файла в БФ:
     *  PROCESSING - Файл обрабатывается
     *  SENT - Файл перемещен в хранилище
     *  ACCEPTED - Файл принят
     *  ERROR - В процессе передачи файла в целевое хранилище произошла ошибка
     *
     * @param string $status
     * @return static
     */
    public function setStatus($status)
    {
        $this->status = $status;
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


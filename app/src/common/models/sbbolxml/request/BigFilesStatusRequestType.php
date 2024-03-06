<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing BigFilesStatusRequestType
 *
 * Запрос статуса загруженных файлов
 * XSD Type: BigFilesStatusRequest
 */
class BigFilesStatusRequestType
{

    /**
     * Уникальный идентификатор задачи на закачку файла в БФ
     *
     * @property string[] $uploadJobId
     */
    private $uploadJobId = array(
        
    );

    /**
     * Уникальный идентификатор задачи на скачивание файла из БФ
     *
     * @property string[] $downloadJobId
     */
    private $downloadJobId = array(
        
    );

    /**
     * Adds as uploadJobId
     *
     * Уникальный идентификатор задачи на закачку файла в БФ
     *
     * @return static
     * @param string $uploadJobId
     */
    public function addToUploadJobId($uploadJobId)
    {
        $this->uploadJobId[] = $uploadJobId;
        return $this;
    }

    /**
     * isset uploadJobId
     *
     * Уникальный идентификатор задачи на закачку файла в БФ
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetUploadJobId($index)
    {
        return isset($this->uploadJobId[$index]);
    }

    /**
     * unset uploadJobId
     *
     * Уникальный идентификатор задачи на закачку файла в БФ
     *
     * @param scalar $index
     * @return void
     */
    public function unsetUploadJobId($index)
    {
        unset($this->uploadJobId[$index]);
    }

    /**
     * Gets as uploadJobId
     *
     * Уникальный идентификатор задачи на закачку файла в БФ
     *
     * @return string[]
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
    public function setUploadJobId(array $uploadJobId)
    {
        $this->uploadJobId = $uploadJobId;
        return $this;
    }

    /**
     * Adds as downloadJobId
     *
     * Уникальный идентификатор задачи на скачивание файла из БФ
     *
     * @return static
     * @param string $downloadJobId
     */
    public function addToDownloadJobId($downloadJobId)
    {
        $this->downloadJobId[] = $downloadJobId;
        return $this;
    }

    /**
     * isset downloadJobId
     *
     * Уникальный идентификатор задачи на скачивание файла из БФ
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetDownloadJobId($index)
    {
        return isset($this->downloadJobId[$index]);
    }

    /**
     * unset downloadJobId
     *
     * Уникальный идентификатор задачи на скачивание файла из БФ
     *
     * @param scalar $index
     * @return void
     */
    public function unsetDownloadJobId($index)
    {
        unset($this->downloadJobId[$index]);
    }

    /**
     * Gets as downloadJobId
     *
     * Уникальный идентификатор задачи на скачивание файла из БФ
     *
     * @return string[]
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
    public function setDownloadJobId(array $downloadJobId)
    {
        $this->downloadJobId = $downloadJobId;
        return $this;
    }


}


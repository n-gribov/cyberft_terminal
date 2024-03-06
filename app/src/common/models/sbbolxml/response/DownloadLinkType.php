<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing DownloadLinkType
 *
 * Ссылка на скачивание
 * XSD Type: DownloadLink
 */
class DownloadLinkType
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
     * @property string $downloadJobId
     */
    private $downloadJobId = null;

    /**
     * Ссылка на файл для загрузки
     *
     * @property string $webDownloadLink
     */
    private $webDownloadLink = null;

    /**
     * Статус файла в БФ
     *
     * @property string $state
     */
    private $state = null;

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
     * Gets as downloadJobId
     *
     * Идентификатор задачи на загрузку файла
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
     * Идентификатор задачи на загрузку файла
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
     * Gets as webDownloadLink
     *
     * Ссылка на файл для загрузки
     *
     * @return string
     */
    public function getWebDownloadLink()
    {
        return $this->webDownloadLink;
    }

    /**
     * Sets a new webDownloadLink
     *
     * Ссылка на файл для загрузки
     *
     * @param string $webDownloadLink
     * @return static
     */
    public function setWebDownloadLink($webDownloadLink)
    {
        $this->webDownloadLink = $webDownloadLink;
        return $this;
    }

    /**
     * Gets as state
     *
     * Статус файла в БФ
     *
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Sets a new state
     *
     * Статус файла в БФ
     *
     * @param string $state
     * @return static
     */
    public function setState($state)
    {
        $this->state = $state;
        return $this;
    }


}


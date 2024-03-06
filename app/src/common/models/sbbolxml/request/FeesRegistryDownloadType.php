<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing FeesRegistryDownloadType
 *
 * Запрос на скачивание реестра платежей
 * XSD Type: FeesRegistryDownload
 */
class FeesRegistryDownloadType
{

    /**
     * Уникальный идентификатор запроса, используется клиентским приложением для связывания запроса ссылки и самой ссылки
     *
     * @property string $linkUUID
     */
    private $linkUUID = null;

    /**
     * Идентификатор документа, файл которого необходимо скачать
     *
     * @property string $docId
     */
    private $docId = null;

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
     * Gets as docId
     *
     * Идентификатор документа, файл которого необходимо скачать
     *
     * @return string
     */
    public function getDocId()
    {
        return $this->docId;
    }

    /**
     * Sets a new docId
     *
     * Идентификатор документа, файл которого необходимо скачать
     *
     * @param string $docId
     * @return static
     */
    public function setDocId($docId)
    {
        $this->docId = $docId;
        return $this;
    }


}


<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing EssenceLinkType
 *
 * Запрос на получение ссылки для загрузки файла в систему 'Большие Файлы'
 * XSD Type: EssenceLink
 */
class EssenceLinkType
{

    /**
     * Тип загружаемой сущности: DOC - для документов, DICT - для справочников)
     *
     * @property string $bigFilesJobType
     */
    private $bigFilesJobType = null;

    /**
     * Тип передаваемого документа/справочника
     *
     * @property string $bigFilesJobSubType
     */
    private $bigFilesJobSubType = null;

    /**
     * Уникальный идентификатор запроса, используется клиентским приложением для
     *  связывания запроса ссылки и самой ссылки
     *
     * @property string $linkUUID
     */
    private $linkUUID = null;

    /**
     * Криптопрофиль подписанта вложения. Параметр заполняется
     *  если BF_TOKEN_SIGN_ENABLED = true
     *
     * @property string $cryptoProfileID
     */
    private $cryptoProfileID = null;

    /**
     * Подпись файла вложения. Параметр заполняется если BF_TOKEN_SIGN_ENABLED = true
     *
     * @property string $signAttachment
     */
    private $signAttachment = null;

    /**
     * Gets as bigFilesJobType
     *
     * Тип загружаемой сущности: DOC - для документов, DICT - для справочников)
     *
     * @return string
     */
    public function getBigFilesJobType()
    {
        return $this->bigFilesJobType;
    }

    /**
     * Sets a new bigFilesJobType
     *
     * Тип загружаемой сущности: DOC - для документов, DICT - для справочников)
     *
     * @param string $bigFilesJobType
     * @return static
     */
    public function setBigFilesJobType($bigFilesJobType)
    {
        $this->bigFilesJobType = $bigFilesJobType;
        return $this;
    }

    /**
     * Gets as bigFilesJobSubType
     *
     * Тип передаваемого документа/справочника
     *
     * @return string
     */
    public function getBigFilesJobSubType()
    {
        return $this->bigFilesJobSubType;
    }

    /**
     * Sets a new bigFilesJobSubType
     *
     * Тип передаваемого документа/справочника
     *
     * @param string $bigFilesJobSubType
     * @return static
     */
    public function setBigFilesJobSubType($bigFilesJobSubType)
    {
        $this->bigFilesJobSubType = $bigFilesJobSubType;
        return $this;
    }

    /**
     * Gets as linkUUID
     *
     * Уникальный идентификатор запроса, используется клиентским приложением для
     *  связывания запроса ссылки и самой ссылки
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
     * Уникальный идентификатор запроса, используется клиентским приложением для
     *  связывания запроса ссылки и самой ссылки
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
     * Gets as cryptoProfileID
     *
     * Криптопрофиль подписанта вложения. Параметр заполняется
     *  если BF_TOKEN_SIGN_ENABLED = true
     *
     * @return string
     */
    public function getCryptoProfileID()
    {
        return $this->cryptoProfileID;
    }

    /**
     * Sets a new cryptoProfileID
     *
     * Криптопрофиль подписанта вложения. Параметр заполняется
     *  если BF_TOKEN_SIGN_ENABLED = true
     *
     * @param string $cryptoProfileID
     * @return static
     */
    public function setCryptoProfileID($cryptoProfileID)
    {
        $this->cryptoProfileID = $cryptoProfileID;
        return $this;
    }

    /**
     * Gets as signAttachment
     *
     * Подпись файла вложения. Параметр заполняется если BF_TOKEN_SIGN_ENABLED = true
     *
     * @return string
     */
    public function getSignAttachment()
    {
        return $this->signAttachment;
    }

    /**
     * Sets a new signAttachment
     *
     * Подпись файла вложения. Параметр заполняется если BF_TOKEN_SIGN_ENABLED = true
     *
     * @param string $signAttachment
     * @return static
     */
    public function setSignAttachment($signAttachment)
    {
        $this->signAttachment = $signAttachment;
        return $this;
    }


}


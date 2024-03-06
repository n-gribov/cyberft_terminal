<?php

namespace common\models\sbbolxml\response\ResponseType\FirmwareUpdateAType;

/**
 * Class representing InternalLinkAType
 */
class InternalLinkAType
{

    /**
     * Временная ссылка
     *
     * @property string $intBFLink
     */
    private $intBFLink = null;

    /**
     * Описание ошибки при получении ссылки на скачивание
     *
     * @property string $errorMessage
     */
    private $errorMessage = null;

    /**
     * Внешняя ссылка на случай, если при работе с файлом возникла
     *  ошибка
     *
     * @property string $extLink
     */
    private $extLink = null;

    /**
     * Gets as intBFLink
     *
     * Временная ссылка
     *
     * @return string
     */
    public function getIntBFLink()
    {
        return $this->intBFLink;
    }

    /**
     * Sets a new intBFLink
     *
     * Временная ссылка
     *
     * @param string $intBFLink
     * @return static
     */
    public function setIntBFLink($intBFLink)
    {
        $this->intBFLink = $intBFLink;
        return $this;
    }

    /**
     * Gets as errorMessage
     *
     * Описание ошибки при получении ссылки на скачивание
     *
     * @return string
     */
    public function getErrorMessage()
    {
        return $this->errorMessage;
    }

    /**
     * Sets a new errorMessage
     *
     * Описание ошибки при получении ссылки на скачивание
     *
     * @param string $errorMessage
     * @return static
     */
    public function setErrorMessage($errorMessage)
    {
        $this->errorMessage = $errorMessage;
        return $this;
    }

    /**
     * Gets as extLink
     *
     * Внешняя ссылка на случай, если при работе с файлом возникла
     *  ошибка
     *
     * @return string
     */
    public function getExtLink()
    {
        return $this->extLink;
    }

    /**
     * Sets a new extLink
     *
     * Внешняя ссылка на случай, если при работе с файлом возникла
     *  ошибка
     *
     * @param string $extLink
     * @return static
     */
    public function setExtLink($extLink)
    {
        $this->extLink = $extLink;
        return $this;
    }


}


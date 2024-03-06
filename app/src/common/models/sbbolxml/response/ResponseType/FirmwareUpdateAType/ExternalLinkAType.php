<?php

namespace common\models\sbbolxml\response\ResponseType\FirmwareUpdateAType;

/**
 * Class representing ExternalLinkAType
 */
class ExternalLinkAType
{

    /**
     * Внешняя ссылка на случай, если при работе с файлом возникла
     *  ошибка
     *
     * @property string $extLink
     */
    private $extLink = null;

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


<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing DownloadLinksType
 *
 * Ссылки на скачивание
 * XSD Type: DownloadLinks
 */
class DownloadLinksType
{

    /**
     * Ссылка на скачивание
     *
     * @property \common\models\sbbolxml\response\DownloadLinkType[] $downloadLink
     */
    private $downloadLink = array(
        
    );

    /**
     * @property \common\models\sbbolxml\response\DigitalSignType $sign
     */
    private $sign = null;

    /**
     * Adds as downloadLink
     *
     * Ссылка на скачивание
     *
     * @return static
     * @param \common\models\sbbolxml\response\DownloadLinkType $downloadLink
     */
    public function addToDownloadLink(\common\models\sbbolxml\response\DownloadLinkType $downloadLink)
    {
        $this->downloadLink[] = $downloadLink;
        return $this;
    }

    /**
     * isset downloadLink
     *
     * Ссылка на скачивание
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetDownloadLink($index)
    {
        return isset($this->downloadLink[$index]);
    }

    /**
     * unset downloadLink
     *
     * Ссылка на скачивание
     *
     * @param scalar $index
     * @return void
     */
    public function unsetDownloadLink($index)
    {
        unset($this->downloadLink[$index]);
    }

    /**
     * Gets as downloadLink
     *
     * Ссылка на скачивание
     *
     * @return \common\models\sbbolxml\response\DownloadLinkType[]
     */
    public function getDownloadLink()
    {
        return $this->downloadLink;
    }

    /**
     * Sets a new downloadLink
     *
     * Ссылка на скачивание
     *
     * @param \common\models\sbbolxml\response\DownloadLinkType[] $downloadLink
     * @return static
     */
    public function setDownloadLink(array $downloadLink)
    {
        $this->downloadLink = $downloadLink;
        return $this;
    }

    /**
     * Gets as sign
     *
     * @return \common\models\sbbolxml\response\DigitalSignType
     */
    public function getSign()
    {
        return $this->sign;
    }

    /**
     * Sets a new sign
     *
     * @param \common\models\sbbolxml\response\DigitalSignType $sign
     * @return static
     */
    public function setSign(\common\models\sbbolxml\response\DigitalSignType $sign)
    {
        $this->sign = $sign;
        return $this;
    }


}


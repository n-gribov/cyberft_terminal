<?php

namespace common\models\sbbolxml\response\ResponseType;

/**
 * Class representing FirmwareUpdateAType
 */
class FirmwareUpdateAType
{

    /**
     * @property string $name
     */
    private $name = null;

    /**
     * @property string $app
     */
    private $app = null;

    /**
     * @property integer $version
     */
    private $version = null;

    /**
     * @property integer $build
     */
    private $build = null;

    /**
     * @property string $link
     */
    private $link = null;

    /**
     * @property string $versionString
     */
    private $versionString = null;

    /**
     * @property integer $typeRule
     */
    private $typeRule = null;

    /**
     * Доступно для прошивок
     *
     * @property string $availableVersion
     */
    private $availableVersion = null;

    /**
     * Файл обновления доступен по временной ссылке
     *
     * @property \common\models\sbbolxml\response\ResponseType\FirmwareUpdateAType\InternalLinkAType $internalLink
     */
    private $internalLink = null;

    /**
     * Ссылка на обновление
     *
     * @property \common\models\sbbolxml\response\ResponseType\FirmwareUpdateAType\ExternalLinkAType $externalLink
     */
    private $externalLink = null;

    /**
     * Gets as name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets a new name
     *
     * @param string $name
     * @return static
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Gets as app
     *
     * @return string
     */
    public function getApp()
    {
        return $this->app;
    }

    /**
     * Sets a new app
     *
     * @param string $app
     * @return static
     */
    public function setApp($app)
    {
        $this->app = $app;
        return $this;
    }

    /**
     * Gets as version
     *
     * @return integer
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Sets a new version
     *
     * @param integer $version
     * @return static
     */
    public function setVersion($version)
    {
        $this->version = $version;
        return $this;
    }

    /**
     * Gets as build
     *
     * @return integer
     */
    public function getBuild()
    {
        return $this->build;
    }

    /**
     * Sets a new build
     *
     * @param integer $build
     * @return static
     */
    public function setBuild($build)
    {
        $this->build = $build;
        return $this;
    }

    /**
     * Gets as link
     *
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * Sets a new link
     *
     * @param string $link
     * @return static
     */
    public function setLink($link)
    {
        $this->link = $link;
        return $this;
    }

    /**
     * Gets as versionString
     *
     * @return string
     */
    public function getVersionString()
    {
        return $this->versionString;
    }

    /**
     * Sets a new versionString
     *
     * @param string $versionString
     * @return static
     */
    public function setVersionString($versionString)
    {
        $this->versionString = $versionString;
        return $this;
    }

    /**
     * Gets as typeRule
     *
     * @return integer
     */
    public function getTypeRule()
    {
        return $this->typeRule;
    }

    /**
     * Sets a new typeRule
     *
     * @param integer $typeRule
     * @return static
     */
    public function setTypeRule($typeRule)
    {
        $this->typeRule = $typeRule;
        return $this;
    }

    /**
     * Gets as availableVersion
     *
     * Доступно для прошивок
     *
     * @return string
     */
    public function getAvailableVersion()
    {
        return $this->availableVersion;
    }

    /**
     * Sets a new availableVersion
     *
     * Доступно для прошивок
     *
     * @param string $availableVersion
     * @return static
     */
    public function setAvailableVersion($availableVersion)
    {
        $this->availableVersion = $availableVersion;
        return $this;
    }

    /**
     * Gets as internalLink
     *
     * Файл обновления доступен по временной ссылке
     *
     * @return \common\models\sbbolxml\response\ResponseType\FirmwareUpdateAType\InternalLinkAType
     */
    public function getInternalLink()
    {
        return $this->internalLink;
    }

    /**
     * Sets a new internalLink
     *
     * Файл обновления доступен по временной ссылке
     *
     * @param \common\models\sbbolxml\response\ResponseType\FirmwareUpdateAType\InternalLinkAType $internalLink
     * @return static
     */
    public function setInternalLink(\common\models\sbbolxml\response\ResponseType\FirmwareUpdateAType\InternalLinkAType $internalLink)
    {
        $this->internalLink = $internalLink;
        return $this;
    }

    /**
     * Gets as externalLink
     *
     * Ссылка на обновление
     *
     * @return \common\models\sbbolxml\response\ResponseType\FirmwareUpdateAType\ExternalLinkAType
     */
    public function getExternalLink()
    {
        return $this->externalLink;
    }

    /**
     * Sets a new externalLink
     *
     * Ссылка на обновление
     *
     * @param \common\models\sbbolxml\response\ResponseType\FirmwareUpdateAType\ExternalLinkAType $externalLink
     * @return static
     */
    public function setExternalLink(\common\models\sbbolxml\response\ResponseType\FirmwareUpdateAType\ExternalLinkAType $externalLink)
    {
        $this->externalLink = $externalLink;
        return $this;
    }


}


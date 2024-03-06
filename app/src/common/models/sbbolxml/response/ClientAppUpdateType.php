<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing ClientAppUpdateType
 *
 *
 * XSD Type: ClientAppUpdate
 */
class ClientAppUpdateType
{

    /**
     * @property string $postFix
     */
    private $postFix = null;

    /**
     * @property string $fileName
     */
    private $fileName = null;

    /**
     * Версия клиентского приложения
     *
     * @property string $clientAppVersion
     */
    private $clientAppVersion = null;

    /**
     * Тип клиентского приложения.
     *  Передаем константу:
     *  1- Однопользователькое приложение;
     *  2- Многопользовательское приложение
     *
     * @property string $clientAppType
     */
    private $clientAppType = null;

    /**
     * Тип ОС у клиента
     *
     * @property string $clientOS
     */
    private $clientOS = null;

    /**
     * Разрядность ОС
     *
     * @property string $clientOSArh
     */
    private $clientOSArh = null;

    /**
     * Версия ОС
     *
     * @property string $clientOSVersion
     */
    private $clientOSVersion = null;

    /**
     * Версия JVM
     *
     * @property string $jvmVersion
     */
    private $jvmVersion = null;

    /**
     * Региональные настройки операционной системы
     *
     * @property string $clientOSLocale
     */
    private $clientOSLocale = null;

    /**
     * @property \common\models\sbbolxml\response\DigitalSignType $sign
     */
    private $sign = null;

    /**
     * Gets as postFix
     *
     * @return string
     */
    public function getPostFix()
    {
        return $this->postFix;
    }

    /**
     * Sets a new postFix
     *
     * @param string $postFix
     * @return static
     */
    public function setPostFix($postFix)
    {
        $this->postFix = $postFix;
        return $this;
    }

    /**
     * Gets as fileName
     *
     * @return string
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * Sets a new fileName
     *
     * @param string $fileName
     * @return static
     */
    public function setFileName($fileName)
    {
        $this->fileName = $fileName;
        return $this;
    }

    /**
     * Gets as clientAppVersion
     *
     * Версия клиентского приложения
     *
     * @return string
     */
    public function getClientAppVersion()
    {
        return $this->clientAppVersion;
    }

    /**
     * Sets a new clientAppVersion
     *
     * Версия клиентского приложения
     *
     * @param string $clientAppVersion
     * @return static
     */
    public function setClientAppVersion($clientAppVersion)
    {
        $this->clientAppVersion = $clientAppVersion;
        return $this;
    }

    /**
     * Gets as clientAppType
     *
     * Тип клиентского приложения.
     *  Передаем константу:
     *  1- Однопользователькое приложение;
     *  2- Многопользовательское приложение
     *
     * @return string
     */
    public function getClientAppType()
    {
        return $this->clientAppType;
    }

    /**
     * Sets a new clientAppType
     *
     * Тип клиентского приложения.
     *  Передаем константу:
     *  1- Однопользователькое приложение;
     *  2- Многопользовательское приложение
     *
     * @param string $clientAppType
     * @return static
     */
    public function setClientAppType($clientAppType)
    {
        $this->clientAppType = $clientAppType;
        return $this;
    }

    /**
     * Gets as clientOS
     *
     * Тип ОС у клиента
     *
     * @return string
     */
    public function getClientOS()
    {
        return $this->clientOS;
    }

    /**
     * Sets a new clientOS
     *
     * Тип ОС у клиента
     *
     * @param string $clientOS
     * @return static
     */
    public function setClientOS($clientOS)
    {
        $this->clientOS = $clientOS;
        return $this;
    }

    /**
     * Gets as clientOSArh
     *
     * Разрядность ОС
     *
     * @return string
     */
    public function getClientOSArh()
    {
        return $this->clientOSArh;
    }

    /**
     * Sets a new clientOSArh
     *
     * Разрядность ОС
     *
     * @param string $clientOSArh
     * @return static
     */
    public function setClientOSArh($clientOSArh)
    {
        $this->clientOSArh = $clientOSArh;
        return $this;
    }

    /**
     * Gets as clientOSVersion
     *
     * Версия ОС
     *
     * @return string
     */
    public function getClientOSVersion()
    {
        return $this->clientOSVersion;
    }

    /**
     * Sets a new clientOSVersion
     *
     * Версия ОС
     *
     * @param string $clientOSVersion
     * @return static
     */
    public function setClientOSVersion($clientOSVersion)
    {
        $this->clientOSVersion = $clientOSVersion;
        return $this;
    }

    /**
     * Gets as jvmVersion
     *
     * Версия JVM
     *
     * @return string
     */
    public function getJvmVersion()
    {
        return $this->jvmVersion;
    }

    /**
     * Sets a new jvmVersion
     *
     * Версия JVM
     *
     * @param string $jvmVersion
     * @return static
     */
    public function setJvmVersion($jvmVersion)
    {
        $this->jvmVersion = $jvmVersion;
        return $this;
    }

    /**
     * Gets as clientOSLocale
     *
     * Региональные настройки операционной системы
     *
     * @return string
     */
    public function getClientOSLocale()
    {
        return $this->clientOSLocale;
    }

    /**
     * Sets a new clientOSLocale
     *
     * Региональные настройки операционной системы
     *
     * @param string $clientOSLocale
     * @return static
     */
    public function setClientOSLocale($clientOSLocale)
    {
        $this->clientOSLocale = $clientOSLocale;
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


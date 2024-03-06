<?php

namespace common\models\sbbolxml;

/**
 * Class representing FraudParams
 */
class FraudParams
{

    /**
     * Закодированное значение, полученное из скрипта "rsa.js" (Фрод)
     *
     * @property string $devicePrint
     */
    private $devicePrint = null;

    /**
     * Индикатор канала во фрод-мониторинге:
     *  1. SBB – Толстый клиент
     *  2. UPGCOMMON - клиент УПШ, у которого есть авторизация по логину/паролю
     *  3. UPGLIGHT - клиент УПШ, у которого нет авторизации по логину/паролю (в данный момент это 1С)
     *
     * @property string $channelIndicator
     */
    private $channelIndicator = null;

    /**
     * IP и Mac адреса компьютера устанавливающего связь с банком. Первым идет внешний ip-адрес
     *  Формат: «remoteIP;remoteMac;ip1;mac1;ip2;mac2»
     *
     * @property string $ipMACAddresses
     */
    private $ipMACAddresses = null;

    /**
     * Геопозиционирование компьютера устанавливающего связь с банком.
     *  Формат: «Longitude;Latitude;HorizontalAccuracy;Timestamp;Status»
     *
     * @property string $geolocationInfo
     */
    private $geolocationInfo = null;

    /**
     * Уникальные свойтсва компьютера устанавливающего связь с банком.
     *  Формат: «UUID;Идентификатор процессора;Серийный номер BIOS;Серийный номер жесткого диска».
     *
     * @property string $pcProp
     */
    private $pcProp = null;

    /**
     * Данные токена Отображение в запросе (разделитель «;»):
     *  TOKEN;IС1_A10D0010L_C1_VT01KA02;2012-04-13 09:44:59.003;TLS00041485B;100;1
     *  Отображение в интерфейсе:
     *  1. Тип токена: TOKEN
     *  2. Информация о конфигурации токена: IС1_A10D0010L_C1_VT01KA02
     *  3. Дата конфигурации: 2012-04-13 09:44:59.003
     *  4. Серийный номер токена: TLS00041485B
     *  5. Номер сборки токена: 100
     *  6. Текущая учетная запись (ПИН): 1
     *
     * @property string $tokenInfo
     */
    private $tokenInfo = null;

    /**
     * Список поддерживаемых естественных языков Требуется указать локализацию ОС
     *  компьютера, на котором установлен БК, Например, для английской локализации в качестве
     *  значения атрибута указывается - 'en-US', для русской локализации указывается - 'ru-RU'.
     *
     * @property string $httpAcceptLanguage
     */
    private $httpAcceptLanguage = null;

    /**
     * Gets as devicePrint
     *
     * Закодированное значение, полученное из скрипта "rsa.js" (Фрод)
     *
     * @return string
     */
    public function getDevicePrint()
    {
        return $this->devicePrint;
    }

    /**
     * Sets a new devicePrint
     *
     * Закодированное значение, полученное из скрипта "rsa.js" (Фрод)
     *
     * @param string $devicePrint
     * @return static
     */
    public function setDevicePrint($devicePrint)
    {
        $this->devicePrint = $devicePrint;
        return $this;
    }

    /**
     * Gets as channelIndicator
     *
     * Индикатор канала во фрод-мониторинге:
     *  1. SBB – Толстый клиент
     *  2. UPGCOMMON - клиент УПШ, у которого есть авторизация по логину/паролю
     *  3. UPGLIGHT - клиент УПШ, у которого нет авторизации по логину/паролю (в данный момент это 1С)
     *
     * @return string
     */
    public function getChannelIndicator()
    {
        return $this->channelIndicator;
    }

    /**
     * Sets a new channelIndicator
     *
     * Индикатор канала во фрод-мониторинге:
     *  1. SBB – Толстый клиент
     *  2. UPGCOMMON - клиент УПШ, у которого есть авторизация по логину/паролю
     *  3. UPGLIGHT - клиент УПШ, у которого нет авторизации по логину/паролю (в данный момент это 1С)
     *
     * @param string $channelIndicator
     * @return static
     */
    public function setChannelIndicator($channelIndicator)
    {
        $this->channelIndicator = $channelIndicator;
        return $this;
    }

    /**
     * Gets as ipMACAddresses
     *
     * IP и Mac адреса компьютера устанавливающего связь с банком. Первым идет внешний ip-адрес
     *  Формат: «remoteIP;remoteMac;ip1;mac1;ip2;mac2»
     *
     * @return string
     */
    public function getIpMACAddresses()
    {
        return $this->ipMACAddresses;
    }

    /**
     * Sets a new ipMACAddresses
     *
     * IP и Mac адреса компьютера устанавливающего связь с банком. Первым идет внешний ip-адрес
     *  Формат: «remoteIP;remoteMac;ip1;mac1;ip2;mac2»
     *
     * @param string $ipMACAddresses
     * @return static
     */
    public function setIpMACAddresses($ipMACAddresses)
    {
        $this->ipMACAddresses = $ipMACAddresses;
        return $this;
    }

    /**
     * Gets as geolocationInfo
     *
     * Геопозиционирование компьютера устанавливающего связь с банком.
     *  Формат: «Longitude;Latitude;HorizontalAccuracy;Timestamp;Status»
     *
     * @return string
     */
    public function getGeolocationInfo()
    {
        return $this->geolocationInfo;
    }

    /**
     * Sets a new geolocationInfo
     *
     * Геопозиционирование компьютера устанавливающего связь с банком.
     *  Формат: «Longitude;Latitude;HorizontalAccuracy;Timestamp;Status»
     *
     * @param string $geolocationInfo
     * @return static
     */
    public function setGeolocationInfo($geolocationInfo)
    {
        $this->geolocationInfo = $geolocationInfo;
        return $this;
    }

    /**
     * Gets as pcProp
     *
     * Уникальные свойтсва компьютера устанавливающего связь с банком.
     *  Формат: «UUID;Идентификатор процессора;Серийный номер BIOS;Серийный номер жесткого диска».
     *
     * @return string
     */
    public function getPcProp()
    {
        return $this->pcProp;
    }

    /**
     * Sets a new pcProp
     *
     * Уникальные свойтсва компьютера устанавливающего связь с банком.
     *  Формат: «UUID;Идентификатор процессора;Серийный номер BIOS;Серийный номер жесткого диска».
     *
     * @param string $pcProp
     * @return static
     */
    public function setPcProp($pcProp)
    {
        $this->pcProp = $pcProp;
        return $this;
    }

    /**
     * Gets as tokenInfo
     *
     * Данные токена Отображение в запросе (разделитель «;»):
     *  TOKEN;IС1_A10D0010L_C1_VT01KA02;2012-04-13 09:44:59.003;TLS00041485B;100;1
     *  Отображение в интерфейсе:
     *  1. Тип токена: TOKEN
     *  2. Информация о конфигурации токена: IС1_A10D0010L_C1_VT01KA02
     *  3. Дата конфигурации: 2012-04-13 09:44:59.003
     *  4. Серийный номер токена: TLS00041485B
     *  5. Номер сборки токена: 100
     *  6. Текущая учетная запись (ПИН): 1
     *
     * @return string
     */
    public function getTokenInfo()
    {
        return $this->tokenInfo;
    }

    /**
     * Sets a new tokenInfo
     *
     * Данные токена Отображение в запросе (разделитель «;»):
     *  TOKEN;IС1_A10D0010L_C1_VT01KA02;2012-04-13 09:44:59.003;TLS00041485B;100;1
     *  Отображение в интерфейсе:
     *  1. Тип токена: TOKEN
     *  2. Информация о конфигурации токена: IС1_A10D0010L_C1_VT01KA02
     *  3. Дата конфигурации: 2012-04-13 09:44:59.003
     *  4. Серийный номер токена: TLS00041485B
     *  5. Номер сборки токена: 100
     *  6. Текущая учетная запись (ПИН): 1
     *
     * @param string $tokenInfo
     * @return static
     */
    public function setTokenInfo($tokenInfo)
    {
        $this->tokenInfo = $tokenInfo;
        return $this;
    }

    /**
     * Gets as httpAcceptLanguage
     *
     * Список поддерживаемых естественных языков Требуется указать локализацию ОС
     *  компьютера, на котором установлен БК, Например, для английской локализации в качестве
     *  значения атрибута указывается - 'en-US', для русской локализации указывается - 'ru-RU'.
     *
     * @return string
     */
    public function getHttpAcceptLanguage()
    {
        return $this->httpAcceptLanguage;
    }

    /**
     * Sets a new httpAcceptLanguage
     *
     * Список поддерживаемых естественных языков Требуется указать локализацию ОС
     *  компьютера, на котором установлен БК, Например, для английской локализации в качестве
     *  значения атрибута указывается - 'en-US', для русской локализации указывается - 'ru-RU'.
     *
     * @param string $httpAcceptLanguage
     * @return static
     */
    public function setHttpAcceptLanguage($httpAcceptLanguage)
    {
        $this->httpAcceptLanguage = $httpAcceptLanguage;
        return $this;
    }


}


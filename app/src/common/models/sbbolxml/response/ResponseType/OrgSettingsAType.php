<?php

namespace common\models\sbbolxml\response\ResponseType;

/**
 * Class representing OrgSettingsAType
 */
class OrgSettingsAType
{

    /**
     * Контроль используемой архитектуры
     *
     * @property boolean $usedArchitectureControl
     */
    private $usedArchitectureControl = null;

    /**
     * Использовать подтвержденный справочник контрагентов
     *
     * @property boolean $useSignCorrDict
     */
    private $useSignCorrDict = null;

    /**
     * Контроль удаленного управлениия
     *
     * @property boolean $remoteAccessProtect
     */
    private $remoteAccessProtect = null;

    /**
     * Изменился ли справочник корреспондентов с момента последней загрузки
     *
     * @property boolean $correspondentDictChanged
     */
    private $correspondentDictChanged = null;

    /**
     * Изменился ли справочник БИК с момента последней загрузки
     *
     * @property boolean $bicDictChanged
     */
    private $bicDictChanged = null;

    /**
     * Передавать список отозванных сертификатов (CRL) на ТК
     *
     * @property boolean $sendCRL
     */
    private $sendCRL = null;

    /**
     * Верcия данных об организации
     *
     * @property integer $orgDataVersion
     */
    private $orgDataVersion = null;

    /**
     * @property \common\models\sbbolxml\response\DigitalSignType $sign
     */
    private $sign = null;

    /**
     * Изменился ли справочник бенефициаров с момента последней загрузки
     *
     * @property boolean $beneficiarDictChanged
     */
    private $beneficiarDictChanged = null;

    /**
     * Признак запроса на заполнение ИСК и формирование сообщений клиентам
     *
     * @property boolean $requestISK
     */
    private $requestISK = null;

    /**
     * Запросы логов с ТК
     *
     * @property \common\models\sbbolxml\response\ResponseType\OrgSettingsAType\OrgUPGLogsRequestsAType $orgUPGLogsRequests
     */
    private $orgUPGLogsRequests = null;

    /**
     * Изменился ли справочник актуальных ставок с момента последней загрузки
     *
     * @property boolean $dictsForDepositsDictChanged
     */
    private $dictsForDepositsDictChanged = null;

    /**
     * Изменился ли справочник карточек НСО с момента последней загрузки
     *
     * @property boolean $cardPermBalanceDictChanged
     */
    private $cardPermBalanceDictChanged = null;

    /**
     * Изменился ли справочник карточек депозита с момента последней загрузки
     *
     * @property boolean $cardDepositDictChanged
     */
    private $cardDepositDictChanged = null;

    /**
     * Переход на Инструкцию 181-И включен на банке: 1- Да, 0- Нет
     *
     * @property boolean $currencyControlStart181I
     */
    private $currencyControlStart181I = null;

    /**
     * Изменился ли системный справочник с момента последней загрузки
     *
     * @property boolean $commonSettingsDictChanged
     */
    private $commonSettingsDictChanged = null;

    /**
     * Максимально возможная дата и время запроса Incoming
     *
     * @property \DateTime $maxLastIncomingTime
     */
    private $maxLastIncomingTime = null;

    /**
     * Gets as usedArchitectureControl
     *
     * Контроль используемой архитектуры
     *
     * @return boolean
     */
    public function getUsedArchitectureControl()
    {
        return $this->usedArchitectureControl;
    }

    /**
     * Sets a new usedArchitectureControl
     *
     * Контроль используемой архитектуры
     *
     * @param boolean $usedArchitectureControl
     * @return static
     */
    public function setUsedArchitectureControl($usedArchitectureControl)
    {
        $this->usedArchitectureControl = $usedArchitectureControl;
        return $this;
    }

    /**
     * Gets as useSignCorrDict
     *
     * Использовать подтвержденный справочник контрагентов
     *
     * @return boolean
     */
    public function getUseSignCorrDict()
    {
        return $this->useSignCorrDict;
    }

    /**
     * Sets a new useSignCorrDict
     *
     * Использовать подтвержденный справочник контрагентов
     *
     * @param boolean $useSignCorrDict
     * @return static
     */
    public function setUseSignCorrDict($useSignCorrDict)
    {
        $this->useSignCorrDict = $useSignCorrDict;
        return $this;
    }

    /**
     * Gets as remoteAccessProtect
     *
     * Контроль удаленного управлениия
     *
     * @return boolean
     */
    public function getRemoteAccessProtect()
    {
        return $this->remoteAccessProtect;
    }

    /**
     * Sets a new remoteAccessProtect
     *
     * Контроль удаленного управлениия
     *
     * @param boolean $remoteAccessProtect
     * @return static
     */
    public function setRemoteAccessProtect($remoteAccessProtect)
    {
        $this->remoteAccessProtect = $remoteAccessProtect;
        return $this;
    }

    /**
     * Gets as correspondentDictChanged
     *
     * Изменился ли справочник корреспондентов с момента последней загрузки
     *
     * @return boolean
     */
    public function getCorrespondentDictChanged()
    {
        return $this->correspondentDictChanged;
    }

    /**
     * Sets a new correspondentDictChanged
     *
     * Изменился ли справочник корреспондентов с момента последней загрузки
     *
     * @param boolean $correspondentDictChanged
     * @return static
     */
    public function setCorrespondentDictChanged($correspondentDictChanged)
    {
        $this->correspondentDictChanged = $correspondentDictChanged;
        return $this;
    }

    /**
     * Gets as bicDictChanged
     *
     * Изменился ли справочник БИК с момента последней загрузки
     *
     * @return boolean
     */
    public function getBicDictChanged()
    {
        return $this->bicDictChanged;
    }

    /**
     * Sets a new bicDictChanged
     *
     * Изменился ли справочник БИК с момента последней загрузки
     *
     * @param boolean $bicDictChanged
     * @return static
     */
    public function setBicDictChanged($bicDictChanged)
    {
        $this->bicDictChanged = $bicDictChanged;
        return $this;
    }

    /**
     * Gets as sendCRL
     *
     * Передавать список отозванных сертификатов (CRL) на ТК
     *
     * @return boolean
     */
    public function getSendCRL()
    {
        return $this->sendCRL;
    }

    /**
     * Sets a new sendCRL
     *
     * Передавать список отозванных сертификатов (CRL) на ТК
     *
     * @param boolean $sendCRL
     * @return static
     */
    public function setSendCRL($sendCRL)
    {
        $this->sendCRL = $sendCRL;
        return $this;
    }

    /**
     * Gets as orgDataVersion
     *
     * Верcия данных об организации
     *
     * @return integer
     */
    public function getOrgDataVersion()
    {
        return $this->orgDataVersion;
    }

    /**
     * Sets a new orgDataVersion
     *
     * Верcия данных об организации
     *
     * @param integer $orgDataVersion
     * @return static
     */
    public function setOrgDataVersion($orgDataVersion)
    {
        $this->orgDataVersion = $orgDataVersion;
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

    /**
     * Gets as beneficiarDictChanged
     *
     * Изменился ли справочник бенефициаров с момента последней загрузки
     *
     * @return boolean
     */
    public function getBeneficiarDictChanged()
    {
        return $this->beneficiarDictChanged;
    }

    /**
     * Sets a new beneficiarDictChanged
     *
     * Изменился ли справочник бенефициаров с момента последней загрузки
     *
     * @param boolean $beneficiarDictChanged
     * @return static
     */
    public function setBeneficiarDictChanged($beneficiarDictChanged)
    {
        $this->beneficiarDictChanged = $beneficiarDictChanged;
        return $this;
    }

    /**
     * Gets as requestISK
     *
     * Признак запроса на заполнение ИСК и формирование сообщений клиентам
     *
     * @return boolean
     */
    public function getRequestISK()
    {
        return $this->requestISK;
    }

    /**
     * Sets a new requestISK
     *
     * Признак запроса на заполнение ИСК и формирование сообщений клиентам
     *
     * @param boolean $requestISK
     * @return static
     */
    public function setRequestISK($requestISK)
    {
        $this->requestISK = $requestISK;
        return $this;
    }

    /**
     * Gets as orgUPGLogsRequests
     *
     * Запросы логов с ТК
     *
     * @return \common\models\sbbolxml\response\ResponseType\OrgSettingsAType\OrgUPGLogsRequestsAType
     */
    public function getOrgUPGLogsRequests()
    {
        return $this->orgUPGLogsRequests;
    }

    /**
     * Sets a new orgUPGLogsRequests
     *
     * Запросы логов с ТК
     *
     * @param \common\models\sbbolxml\response\ResponseType\OrgSettingsAType\OrgUPGLogsRequestsAType $orgUPGLogsRequests
     * @return static
     */
    public function setOrgUPGLogsRequests(\common\models\sbbolxml\response\ResponseType\OrgSettingsAType\OrgUPGLogsRequestsAType $orgUPGLogsRequests)
    {
        $this->orgUPGLogsRequests = $orgUPGLogsRequests;
        return $this;
    }

    /**
     * Gets as dictsForDepositsDictChanged
     *
     * Изменился ли справочник актуальных ставок с момента последней загрузки
     *
     * @return boolean
     */
    public function getDictsForDepositsDictChanged()
    {
        return $this->dictsForDepositsDictChanged;
    }

    /**
     * Sets a new dictsForDepositsDictChanged
     *
     * Изменился ли справочник актуальных ставок с момента последней загрузки
     *
     * @param boolean $dictsForDepositsDictChanged
     * @return static
     */
    public function setDictsForDepositsDictChanged($dictsForDepositsDictChanged)
    {
        $this->dictsForDepositsDictChanged = $dictsForDepositsDictChanged;
        return $this;
    }

    /**
     * Gets as cardPermBalanceDictChanged
     *
     * Изменился ли справочник карточек НСО с момента последней загрузки
     *
     * @return boolean
     */
    public function getCardPermBalanceDictChanged()
    {
        return $this->cardPermBalanceDictChanged;
    }

    /**
     * Sets a new cardPermBalanceDictChanged
     *
     * Изменился ли справочник карточек НСО с момента последней загрузки
     *
     * @param boolean $cardPermBalanceDictChanged
     * @return static
     */
    public function setCardPermBalanceDictChanged($cardPermBalanceDictChanged)
    {
        $this->cardPermBalanceDictChanged = $cardPermBalanceDictChanged;
        return $this;
    }

    /**
     * Gets as cardDepositDictChanged
     *
     * Изменился ли справочник карточек депозита с момента последней загрузки
     *
     * @return boolean
     */
    public function getCardDepositDictChanged()
    {
        return $this->cardDepositDictChanged;
    }

    /**
     * Sets a new cardDepositDictChanged
     *
     * Изменился ли справочник карточек депозита с момента последней загрузки
     *
     * @param boolean $cardDepositDictChanged
     * @return static
     */
    public function setCardDepositDictChanged($cardDepositDictChanged)
    {
        $this->cardDepositDictChanged = $cardDepositDictChanged;
        return $this;
    }

    /**
     * Gets as currencyControlStart181I
     *
     * Переход на Инструкцию 181-И включен на банке: 1- Да, 0- Нет
     *
     * @return boolean
     */
    public function getCurrencyControlStart181I()
    {
        return $this->currencyControlStart181I;
    }

    /**
     * Sets a new currencyControlStart181I
     *
     * Переход на Инструкцию 181-И включен на банке: 1- Да, 0- Нет
     *
     * @param boolean $currencyControlStart181I
     * @return static
     */
    public function setCurrencyControlStart181I($currencyControlStart181I)
    {
        $this->currencyControlStart181I = $currencyControlStart181I;
        return $this;
    }

    /**
     * Gets as commonSettingsDictChanged
     *
     * Изменился ли системный справочник с момента последней загрузки
     *
     * @return boolean
     */
    public function getCommonSettingsDictChanged()
    {
        return $this->commonSettingsDictChanged;
    }

    /**
     * Sets a new commonSettingsDictChanged
     *
     * Изменился ли системный справочник с момента последней загрузки
     *
     * @param boolean $commonSettingsDictChanged
     * @return static
     */
    public function setCommonSettingsDictChanged($commonSettingsDictChanged)
    {
        $this->commonSettingsDictChanged = $commonSettingsDictChanged;
        return $this;
    }

    /**
     * Gets as maxLastIncomingTime
     *
     * Максимально возможная дата и время запроса Incoming
     *
     * @return \DateTime
     */
    public function getMaxLastIncomingTime()
    {
        return $this->maxLastIncomingTime;
    }

    /**
     * Sets a new maxLastIncomingTime
     *
     * Максимально возможная дата и время запроса Incoming
     *
     * @param \DateTime $maxLastIncomingTime
     * @return static
     */
    public function setMaxLastIncomingTime(\DateTime $maxLastIncomingTime)
    {
        $this->maxLastIncomingTime = $maxLastIncomingTime;
        return $this;
    }


}


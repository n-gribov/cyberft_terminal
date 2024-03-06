<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing AuditType
 *
 * Информация для фиксирования в журнале аудита СББОЛ
 * XSD Type: Audit
 */
class AuditType
{

    /**
     * Идентификатор типа события (таблица AUD_LOG_EVENT_TYPE. SYSTEMNAME)
     *
     * @property string $eventTypeSystemName
     */
    private $eventTypeSystemName = null;

    /**
     * Логин пользователя, который осуществил действие
     *
     * @property string $userLogin
     */
    private $userLogin = null;

    /**
     * IP-адрес
     *
     * @property string $ipAddress
     */
    private $ipAddress = null;

    /**
     * Gets as eventTypeSystemName
     *
     * Идентификатор типа события (таблица AUD_LOG_EVENT_TYPE. SYSTEMNAME)
     *
     * @return string
     */
    public function getEventTypeSystemName()
    {
        return $this->eventTypeSystemName;
    }

    /**
     * Sets a new eventTypeSystemName
     *
     * Идентификатор типа события (таблица AUD_LOG_EVENT_TYPE. SYSTEMNAME)
     *
     * @param string $eventTypeSystemName
     * @return static
     */
    public function setEventTypeSystemName($eventTypeSystemName)
    {
        $this->eventTypeSystemName = $eventTypeSystemName;
        return $this;
    }

    /**
     * Gets as userLogin
     *
     * Логин пользователя, который осуществил действие
     *
     * @return string
     */
    public function getUserLogin()
    {
        return $this->userLogin;
    }

    /**
     * Sets a new userLogin
     *
     * Логин пользователя, который осуществил действие
     *
     * @param string $userLogin
     * @return static
     */
    public function setUserLogin($userLogin)
    {
        $this->userLogin = $userLogin;
        return $this;
    }

    /**
     * Gets as ipAddress
     *
     * IP-адрес
     *
     * @return string
     */
    public function getIpAddress()
    {
        return $this->ipAddress;
    }

    /**
     * Sets a new ipAddress
     *
     * IP-адрес
     *
     * @param string $ipAddress
     * @return static
     */
    public function setIpAddress($ipAddress)
    {
        $this->ipAddress = $ipAddress;
        return $this;
    }


}


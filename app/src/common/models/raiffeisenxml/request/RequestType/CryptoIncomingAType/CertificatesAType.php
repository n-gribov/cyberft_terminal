<?php

namespace common\models\raiffeisenxml\request\RequestType\CryptoIncomingAType;

/**
 * Class representing CertificatesAType
 */
class CertificatesAType
{

    /**
     * Запрос клиентских сертификатов для данной организации
     *
     * @property bool $client
     */
    private $client = null;

    /**
     * Признак запроса последнего действительного банковского
     *  сертификата
     *
     * @property bool $bank
     */
    private $bank = null;

    /**
     * Gets as client
     *
     * Запрос клиентских сертификатов для данной организации
     *
     * @return bool
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Sets a new client
     *
     * Запрос клиентских сертификатов для данной организации
     *
     * @param bool $client
     * @return static
     */
    public function setClient($client)
    {
        $this->client = $client;
        return $this;
    }

    /**
     * Gets as bank
     *
     * Признак запроса последнего действительного банковского
     *  сертификата
     *
     * @return bool
     */
    public function getBank()
    {
        return $this->bank;
    }

    /**
     * Sets a new bank
     *
     * Признак запроса последнего действительного банковского
     *  сертификата
     *
     * @param bool $bank
     * @return static
     */
    public function setBank($bank)
    {
        $this->bank = $bank;
        return $this;
    }


}


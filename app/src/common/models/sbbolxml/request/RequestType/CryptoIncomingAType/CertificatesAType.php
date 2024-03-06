<?php

namespace common\models\sbbolxml\request\RequestType\CryptoIncomingAType;

/**
 * Class representing CertificatesAType
 */
class CertificatesAType
{

    /**
     * Запрос клиентских сертификатов для данной организации
     *
     * @property boolean $client
     */
    private $client = null;

    /**
     * Признак запроса последнего действительного банковского
     *  сертификата
     *
     * @property boolean $bank
     */
    private $bank = null;

    /**
     * Признак запроса сертификатов УЦ и корневых сертификатов
     *
     * @property boolean $root
     */
    private $root = null;

    /**
     * Gets as client
     *
     * Запрос клиентских сертификатов для данной организации
     *
     * @return boolean
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
     * @param boolean $client
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
     * @return boolean
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
     * @param boolean $bank
     * @return static
     */
    public function setBank($bank)
    {
        $this->bank = $bank;
        return $this;
    }

    /**
     * Gets as root
     *
     * Признак запроса сертификатов УЦ и корневых сертификатов
     *
     * @return boolean
     */
    public function getRoot()
    {
        return $this->root;
    }

    /**
     * Sets a new root
     *
     * Признак запроса сертификатов УЦ и корневых сертификатов
     *
     * @param boolean $root
     * @return static
     */
    public function setRoot($root)
    {
        $this->root = $root;
        return $this;
    }


}


<?php

namespace common\models\raiffeisenxml\request\RequestType;

/**
 * Class representing CryptoIncomingAType
 */
class CryptoIncomingAType
{

    /**
     * Запрос информации о новых криптопрофилях для данной организации.
     *  1- признак активен
     *  0- не активен
     *
     * @property bool $cryptopro
     */
    private $cryptopro = null;

    /**
     * Запрос информации о сертификатах.
     *  Если проставлен признак @client, передавать только клиентские сертификаты.
     *  Если стоит признак @bank, передавать только последний действительный банковский
     *  сертификат.
     *  Если стоят оба признака, передавать и клиенские сертификаты и банковский.
     *
     * @property \common\models\raiffeisenxml\request\RequestType\CryptoIncomingAType\CertificatesAType $certificates
     */
    private $certificates = null;

    /**
     * Gets as cryptopro
     *
     * Запрос информации о новых криптопрофилях для данной организации.
     *  1- признак активен
     *  0- не активен
     *
     * @return bool
     */
    public function getCryptopro()
    {
        return $this->cryptopro;
    }

    /**
     * Sets a new cryptopro
     *
     * Запрос информации о новых криптопрофилях для данной организации.
     *  1- признак активен
     *  0- не активен
     *
     * @param bool $cryptopro
     * @return static
     */
    public function setCryptopro($cryptopro)
    {
        $this->cryptopro = $cryptopro;
        return $this;
    }

    /**
     * Gets as certificates
     *
     * Запрос информации о сертификатах.
     *  Если проставлен признак @client, передавать только клиентские сертификаты.
     *  Если стоит признак @bank, передавать только последний действительный банковский
     *  сертификат.
     *  Если стоят оба признака, передавать и клиенские сертификаты и банковский.
     *
     * @return \common\models\raiffeisenxml\request\RequestType\CryptoIncomingAType\CertificatesAType
     */
    public function getCertificates()
    {
        return $this->certificates;
    }

    /**
     * Sets a new certificates
     *
     * Запрос информации о сертификатах.
     *  Если проставлен признак @client, передавать только клиентские сертификаты.
     *  Если стоит признак @bank, передавать только последний действительный банковский
     *  сертификат.
     *  Если стоят оба признака, передавать и клиенские сертификаты и банковский.
     *
     * @param \common\models\raiffeisenxml\request\RequestType\CryptoIncomingAType\CertificatesAType $certificates
     * @return static
     */
    public function setCertificates(\common\models\raiffeisenxml\request\RequestType\CryptoIncomingAType\CertificatesAType $certificates)
    {
        $this->certificates = $certificates;
        return $this;
    }


}


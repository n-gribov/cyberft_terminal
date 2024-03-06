<?php

namespace common\models\sbbolxml\request\RequestType;

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
     * @property boolean $cryptopro
     */
    private $cryptopro = null;

    /**
     * Запрос информации о сертификатах.
     *  Если проставлен признак @client, передавать только клиентские сертификаты.
     *  Если стоит признак @bank, передавать только последний действительный банковский
     *  сертификат.
     *  Если стоят оба признака, передавать и клиенские сертификаты и банковский.
     *
     * @property \common\models\sbbolxml\request\RequestType\CryptoIncomingAType\CertificatesAType $certificates
     */
    private $certificates = null;

    /**
     * ТОЛЬКО ДЛЯ ТК. Идентификатор, который используется для получения
     *  персональных данных.
     *  (DBOContractChannel.accessCode)
     *
     * @property string[] $contractAccessCodes
     */
    private $contractAccessCodes = null;

    /**
     * Признак запроса списка отозванных сертикатов
     *
     * @property \common\models\sbbolxml\request\RequestType\CryptoIncomingAType\RevocationCertificatesAType $revocationCertificates
     */
    private $revocationCertificates = null;

    /**
     * Gets as cryptopro
     *
     * Запрос информации о новых криптопрофилях для данной организации.
     *  1- признак активен
     *  0- не активен
     *
     * @return boolean
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
     * @param boolean $cryptopro
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
     * @return \common\models\sbbolxml\request\RequestType\CryptoIncomingAType\CertificatesAType
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
     * @param \common\models\sbbolxml\request\RequestType\CryptoIncomingAType\CertificatesAType $certificates
     * @return static
     */
    public function setCertificates(\common\models\sbbolxml\request\RequestType\CryptoIncomingAType\CertificatesAType $certificates)
    {
        $this->certificates = $certificates;
        return $this;
    }

    /**
     * Adds as contractAccessCode
     *
     * ТОЛЬКО ДЛЯ ТК. Идентификатор, который используется для получения
     *  персональных данных.
     *  (DBOContractChannel.accessCode)
     *
     * @return static
     * @param string $contractAccessCode
     */
    public function addToContractAccessCodes($contractAccessCode)
    {
        $this->contractAccessCodes[] = $contractAccessCode;
        return $this;
    }

    /**
     * isset contractAccessCodes
     *
     * ТОЛЬКО ДЛЯ ТК. Идентификатор, который используется для получения
     *  персональных данных.
     *  (DBOContractChannel.accessCode)
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetContractAccessCodes($index)
    {
        return isset($this->contractAccessCodes[$index]);
    }

    /**
     * unset contractAccessCodes
     *
     * ТОЛЬКО ДЛЯ ТК. Идентификатор, который используется для получения
     *  персональных данных.
     *  (DBOContractChannel.accessCode)
     *
     * @param scalar $index
     * @return void
     */
    public function unsetContractAccessCodes($index)
    {
        unset($this->contractAccessCodes[$index]);
    }

    /**
     * Gets as contractAccessCodes
     *
     * ТОЛЬКО ДЛЯ ТК. Идентификатор, который используется для получения
     *  персональных данных.
     *  (DBOContractChannel.accessCode)
     *
     * @return string[]
     */
    public function getContractAccessCodes()
    {
        return $this->contractAccessCodes;
    }

    /**
     * Sets a new contractAccessCodes
     *
     * ТОЛЬКО ДЛЯ ТК. Идентификатор, который используется для получения
     *  персональных данных.
     *  (DBOContractChannel.accessCode)
     *
     * @param string[] $contractAccessCodes
     * @return static
     */
    public function setContractAccessCodes(array $contractAccessCodes)
    {
        $this->contractAccessCodes = $contractAccessCodes;
        return $this;
    }

    /**
     * Gets as revocationCertificates
     *
     * Признак запроса списка отозванных сертикатов
     *
     * @return \common\models\sbbolxml\request\RequestType\CryptoIncomingAType\RevocationCertificatesAType
     */
    public function getRevocationCertificates()
    {
        return $this->revocationCertificates;
    }

    /**
     * Sets a new revocationCertificates
     *
     * Признак запроса списка отозванных сертикатов
     *
     * @param \common\models\sbbolxml\request\RequestType\CryptoIncomingAType\RevocationCertificatesAType $revocationCertificates
     * @return static
     */
    public function setRevocationCertificates(\common\models\sbbolxml\request\RequestType\CryptoIncomingAType\RevocationCertificatesAType $revocationCertificates)
    {
        $this->revocationCertificates = $revocationCertificates;
        return $this;
    }


}


<?php

namespace common\models\raiffeisenxml\response\OrgInfoType\SignDevicesAType\SignDeviceAType;

/**
 * Class representing CertificatesAType
 */
class CertificatesAType
{

    /**
     * Данные одного сертификата в бинарном представлении
     *
     * @property \common\models\raiffeisenxml\response\OrgInfoType\SignDevicesAType\SignDeviceAType\CertificatesAType\CertificateAType[] $certificate
     */
    private $certificate = [
        
    ];

    /**
     * Adds as certificate
     *
     * Данные одного сертификата в бинарном представлении
     *
     * @return static
     * @param \common\models\raiffeisenxml\response\OrgInfoType\SignDevicesAType\SignDeviceAType\CertificatesAType\CertificateAType $certificate
     */
    public function addToCertificate(\common\models\raiffeisenxml\response\OrgInfoType\SignDevicesAType\SignDeviceAType\CertificatesAType\CertificateAType $certificate)
    {
        $this->certificate[] = $certificate;
        return $this;
    }

    /**
     * isset certificate
     *
     * Данные одного сертификата в бинарном представлении
     *
     * @param int|string $index
     * @return bool
     */
    public function issetCertificate($index)
    {
        return isset($this->certificate[$index]);
    }

    /**
     * unset certificate
     *
     * Данные одного сертификата в бинарном представлении
     *
     * @param int|string $index
     * @return void
     */
    public function unsetCertificate($index)
    {
        unset($this->certificate[$index]);
    }

    /**
     * Gets as certificate
     *
     * Данные одного сертификата в бинарном представлении
     *
     * @return \common\models\raiffeisenxml\response\OrgInfoType\SignDevicesAType\SignDeviceAType\CertificatesAType\CertificateAType[]
     */
    public function getCertificate()
    {
        return $this->certificate;
    }

    /**
     * Sets a new certificate
     *
     * Данные одного сертификата в бинарном представлении
     *
     * @param \common\models\raiffeisenxml\response\OrgInfoType\SignDevicesAType\SignDeviceAType\CertificatesAType\CertificateAType[] $certificate
     * @return static
     */
    public function setCertificate(array $certificate)
    {
        $this->certificate = $certificate;
        return $this;
    }


}


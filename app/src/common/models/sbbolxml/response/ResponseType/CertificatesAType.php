<?php

namespace common\models\sbbolxml\response\ResponseType;

/**
 * Class representing CertificatesAType
 */
class CertificatesAType
{

    /**
     * @property \common\models\sbbolxml\response\CertificateType[] $certificate
     */
    private $certificate = array(
        
    );

    /**
     * Adds as certificate
     *
     * @return static
     * @param \common\models\sbbolxml\response\CertificateType $certificate
     */
    public function addToCertificate(\common\models\sbbolxml\response\CertificateType $certificate)
    {
        $this->certificate[] = $certificate;
        return $this;
    }

    /**
     * isset certificate
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetCertificate($index)
    {
        return isset($this->certificate[$index]);
    }

    /**
     * unset certificate
     *
     * @param scalar $index
     * @return void
     */
    public function unsetCertificate($index)
    {
        unset($this->certificate[$index]);
    }

    /**
     * Gets as certificate
     *
     * @return \common\models\sbbolxml\response\CertificateType[]
     */
    public function getCertificate()
    {
        return $this->certificate;
    }

    /**
     * Sets a new certificate
     *
     * @param \common\models\sbbolxml\response\CertificateType[] $certificate
     * @return static
     */
    public function setCertificate(array $certificate)
    {
        $this->certificate = $certificate;
        return $this;
    }


}


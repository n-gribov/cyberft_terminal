<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing ActivateCertType
 *
 *
 * XSD Type: ActivateCert
 */
class ActivateCertType
{

    /**
     * Идентификатор поставщика сертификата
     *  Например, " issuer="E=IdleCA, C=RU, S=Idle CA, L=Idle CA, O=Idle CA, OU=Idle CA, CN=Idle CA" "
     *
     * @property string $issuer
     */
    private $issuer = null;

    /**
     * Серийный номер сертификата
     *
     * @property string $sN
     */
    private $sN = null;

    /**
     * Gets as issuer
     *
     * Идентификатор поставщика сертификата
     *  Например, " issuer="E=IdleCA, C=RU, S=Idle CA, L=Idle CA, O=Idle CA, OU=Idle CA, CN=Idle CA" "
     *
     * @return string
     */
    public function getIssuer()
    {
        return $this->issuer;
    }

    /**
     * Sets a new issuer
     *
     * Идентификатор поставщика сертификата
     *  Например, " issuer="E=IdleCA, C=RU, S=Idle CA, L=Idle CA, O=Idle CA, OU=Idle CA, CN=Idle CA" "
     *
     * @param string $issuer
     * @return static
     */
    public function setIssuer($issuer)
    {
        $this->issuer = $issuer;
        return $this;
    }

    /**
     * Gets as sN
     *
     * Серийный номер сертификата
     *
     * @return string
     */
    public function getSN()
    {
        return $this->sN;
    }

    /**
     * Sets a new sN
     *
     * Серийный номер сертификата
     *
     * @param string $sN
     * @return static
     */
    public function setSN($sN)
    {
        $this->sN = $sN;
        return $this;
    }


}


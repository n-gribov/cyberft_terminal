<?php

namespace common\models\sbbolxml\request\ConfDocCertificate138IType;

/**
 * Class representing ConfDocCertificateDocs138IAType
 */
class ConfDocCertificateDocs138IAType
{

    /**
     * Информация о подтверждающем документе
     *
     * @property \common\models\sbbolxml\request\ConfDocCertificateDoc138IType[] $confDocCertificateDoc138I
     */
    private $confDocCertificateDoc138I = array(
        
    );

    /**
     * Adds as confDocCertificateDoc138I
     *
     * Информация о подтверждающем документе
     *
     * @return static
     * @param \common\models\sbbolxml\request\ConfDocCertificateDoc138IType $confDocCertificateDoc138I
     */
    public function addToConfDocCertificateDoc138I(\common\models\sbbolxml\request\ConfDocCertificateDoc138IType $confDocCertificateDoc138I)
    {
        $this->confDocCertificateDoc138I[] = $confDocCertificateDoc138I;
        return $this;
    }

    /**
     * isset confDocCertificateDoc138I
     *
     * Информация о подтверждающем документе
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetConfDocCertificateDoc138I($index)
    {
        return isset($this->confDocCertificateDoc138I[$index]);
    }

    /**
     * unset confDocCertificateDoc138I
     *
     * Информация о подтверждающем документе
     *
     * @param scalar $index
     * @return void
     */
    public function unsetConfDocCertificateDoc138I($index)
    {
        unset($this->confDocCertificateDoc138I[$index]);
    }

    /**
     * Gets as confDocCertificateDoc138I
     *
     * Информация о подтверждающем документе
     *
     * @return \common\models\sbbolxml\request\ConfDocCertificateDoc138IType[]
     */
    public function getConfDocCertificateDoc138I()
    {
        return $this->confDocCertificateDoc138I;
    }

    /**
     * Sets a new confDocCertificateDoc138I
     *
     * Информация о подтверждающем документе
     *
     * @param \common\models\sbbolxml\request\ConfDocCertificateDoc138IType[] $confDocCertificateDoc138I
     * @return static
     */
    public function setConfDocCertificateDoc138I(array $confDocCertificateDoc138I)
    {
        $this->confDocCertificateDoc138I = $confDocCertificateDoc138I;
        return $this;
    }


}


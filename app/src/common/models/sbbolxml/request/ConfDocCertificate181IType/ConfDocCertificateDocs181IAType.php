<?php

namespace common\models\sbbolxml\request\ConfDocCertificate181IType;

/**
 * Class representing ConfDocCertificateDocs181IAType
 */
class ConfDocCertificateDocs181IAType
{

    /**
     * Информация о подтверждающем документе
     *
     * @property \common\models\sbbolxml\request\ConfDocCertificateDoc181IType[] $confDocCertificateDoc181I
     */
    private $confDocCertificateDoc181I = array(
        
    );

    /**
     * Adds as confDocCertificateDoc181I
     *
     * Информация о подтверждающем документе
     *
     * @return static
     * @param \common\models\sbbolxml\request\ConfDocCertificateDoc181IType $confDocCertificateDoc181I
     */
    public function addToConfDocCertificateDoc181I(\common\models\sbbolxml\request\ConfDocCertificateDoc181IType $confDocCertificateDoc181I)
    {
        $this->confDocCertificateDoc181I[] = $confDocCertificateDoc181I;
        return $this;
    }

    /**
     * isset confDocCertificateDoc181I
     *
     * Информация о подтверждающем документе
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetConfDocCertificateDoc181I($index)
    {
        return isset($this->confDocCertificateDoc181I[$index]);
    }

    /**
     * unset confDocCertificateDoc181I
     *
     * Информация о подтверждающем документе
     *
     * @param scalar $index
     * @return void
     */
    public function unsetConfDocCertificateDoc181I($index)
    {
        unset($this->confDocCertificateDoc181I[$index]);
    }

    /**
     * Gets as confDocCertificateDoc181I
     *
     * Информация о подтверждающем документе
     *
     * @return \common\models\sbbolxml\request\ConfDocCertificateDoc181IType[]
     */
    public function getConfDocCertificateDoc181I()
    {
        return $this->confDocCertificateDoc181I;
    }

    /**
     * Sets a new confDocCertificateDoc181I
     *
     * Информация о подтверждающем документе
     *
     * @param \common\models\sbbolxml\request\ConfDocCertificateDoc181IType[] $confDocCertificateDoc181I
     * @return static
     */
    public function setConfDocCertificateDoc181I(array $confDocCertificateDoc181I)
    {
        $this->confDocCertificateDoc181I = $confDocCertificateDoc181I;
        return $this;
    }


}


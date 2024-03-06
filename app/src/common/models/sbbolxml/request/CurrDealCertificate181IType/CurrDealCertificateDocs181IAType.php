<?php

namespace common\models\sbbolxml\request\CurrDealCertificate181IType;

/**
 * Class representing CurrDealCertificateDocs181IAType
 */
class CurrDealCertificateDocs181IAType
{

    /**
     * Информация о валютных операциях
     *
     * @property \common\models\sbbolxml\request\CurrDealCertificateDoc181IType[] $currDealCertificateDoc181I
     */
    private $currDealCertificateDoc181I = array(
        
    );

    /**
     * Adds as currDealCertificateDoc181I
     *
     * Информация о валютных операциях
     *
     * @return static
     * @param \common\models\sbbolxml\request\CurrDealCertificateDoc181IType $currDealCertificateDoc181I
     */
    public function addToCurrDealCertificateDoc181I(\common\models\sbbolxml\request\CurrDealCertificateDoc181IType $currDealCertificateDoc181I)
    {
        $this->currDealCertificateDoc181I[] = $currDealCertificateDoc181I;
        return $this;
    }

    /**
     * isset currDealCertificateDoc181I
     *
     * Информация о валютных операциях
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetCurrDealCertificateDoc181I($index)
    {
        return isset($this->currDealCertificateDoc181I[$index]);
    }

    /**
     * unset currDealCertificateDoc181I
     *
     * Информация о валютных операциях
     *
     * @param scalar $index
     * @return void
     */
    public function unsetCurrDealCertificateDoc181I($index)
    {
        unset($this->currDealCertificateDoc181I[$index]);
    }

    /**
     * Gets as currDealCertificateDoc181I
     *
     * Информация о валютных операциях
     *
     * @return \common\models\sbbolxml\request\CurrDealCertificateDoc181IType[]
     */
    public function getCurrDealCertificateDoc181I()
    {
        return $this->currDealCertificateDoc181I;
    }

    /**
     * Sets a new currDealCertificateDoc181I
     *
     * Информация о валютных операциях
     *
     * @param \common\models\sbbolxml\request\CurrDealCertificateDoc181IType[] $currDealCertificateDoc181I
     * @return static
     */
    public function setCurrDealCertificateDoc181I(array $currDealCertificateDoc181I)
    {
        $this->currDealCertificateDoc181I = $currDealCertificateDoc181I;
        return $this;
    }


}


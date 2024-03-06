<?php

namespace common\models\sbbolxml\request\CurrDealCertificate138IType;

/**
 * Class representing CurrDealCertificateDocs138IAType
 */
class CurrDealCertificateDocs138IAType
{

    /**
     * Информация о валютных операциях
     *
     * @property \common\models\sbbolxml\request\CurrDealCertificateDoc138IType[] $currDealCertificateDoc138I
     */
    private $currDealCertificateDoc138I = array(
        
    );

    /**
     * Adds as currDealCertificateDoc138I
     *
     * Информация о валютных операциях
     *
     * @return static
     * @param \common\models\sbbolxml\request\CurrDealCertificateDoc138IType $currDealCertificateDoc138I
     */
    public function addToCurrDealCertificateDoc138I(\common\models\sbbolxml\request\CurrDealCertificateDoc138IType $currDealCertificateDoc138I)
    {
        $this->currDealCertificateDoc138I[] = $currDealCertificateDoc138I;
        return $this;
    }

    /**
     * isset currDealCertificateDoc138I
     *
     * Информация о валютных операциях
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetCurrDealCertificateDoc138I($index)
    {
        return isset($this->currDealCertificateDoc138I[$index]);
    }

    /**
     * unset currDealCertificateDoc138I
     *
     * Информация о валютных операциях
     *
     * @param scalar $index
     * @return void
     */
    public function unsetCurrDealCertificateDoc138I($index)
    {
        unset($this->currDealCertificateDoc138I[$index]);
    }

    /**
     * Gets as currDealCertificateDoc138I
     *
     * Информация о валютных операциях
     *
     * @return \common\models\sbbolxml\request\CurrDealCertificateDoc138IType[]
     */
    public function getCurrDealCertificateDoc138I()
    {
        return $this->currDealCertificateDoc138I;
    }

    /**
     * Sets a new currDealCertificateDoc138I
     *
     * Информация о валютных операциях
     *
     * @param \common\models\sbbolxml\request\CurrDealCertificateDoc138IType[] $currDealCertificateDoc138I
     * @return static
     */
    public function setCurrDealCertificateDoc138I(array $currDealCertificateDoc138I)
    {
        $this->currDealCertificateDoc138I = $currDealCertificateDoc138I;
        return $this;
    }


}


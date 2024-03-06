<?php

namespace common\models\sbbolxml\response\ResponseType;

/**
 * Class representing PayDocsRuAType
 */
class PayDocsRuAType
{

    /**
     * Платежное поручение
     *
     * @property \common\models\sbbolxml\response\RZKPayDocRuType[] $payDocRu
     */
    private $payDocRu = array(
        
    );

    /**
     * Adds as payDocRu
     *
     * Платежное поручение
     *
     * @return static
     * @param \common\models\sbbolxml\response\RZKPayDocRuType $payDocRu
     */
    public function addToPayDocRu(\common\models\sbbolxml\response\RZKPayDocRuType $payDocRu)
    {
        $this->payDocRu[] = $payDocRu;
        return $this;
    }

    /**
     * isset payDocRu
     *
     * Платежное поручение
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetPayDocRu($index)
    {
        return isset($this->payDocRu[$index]);
    }

    /**
     * unset payDocRu
     *
     * Платежное поручение
     *
     * @param scalar $index
     * @return void
     */
    public function unsetPayDocRu($index)
    {
        unset($this->payDocRu[$index]);
    }

    /**
     * Gets as payDocRu
     *
     * Платежное поручение
     *
     * @return \common\models\sbbolxml\response\RZKPayDocRuType[]
     */
    public function getPayDocRu()
    {
        return $this->payDocRu;
    }

    /**
     * Sets a new payDocRu
     *
     * Платежное поручение
     *
     * @param \common\models\sbbolxml\response\RZKPayDocRuType[] $payDocRu
     * @return static
     */
    public function setPayDocRu(array $payDocRu)
    {
        $this->payDocRu = $payDocRu;
        return $this;
    }


}


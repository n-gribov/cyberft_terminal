<?php

namespace common\models\sbbolxml\request\SalaryDocType;

/**
 * Class representing PayDocsAType
 */
class PayDocsAType
{

    /**
     * @property \common\models\sbbolxml\request\SalaryPayDocRuType[] $payDocRu
     */
    private $payDocRu = array(
        
    );

    /**
     * Adds as payDocRu
     *
     * @return static
     * @param \common\models\sbbolxml\request\SalaryPayDocRuType $payDocRu
     */
    public function addToPayDocRu(\common\models\sbbolxml\request\SalaryPayDocRuType $payDocRu)
    {
        $this->payDocRu[] = $payDocRu;
        return $this;
    }

    /**
     * isset payDocRu
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
     * @return \common\models\sbbolxml\request\SalaryPayDocRuType[]
     */
    public function getPayDocRu()
    {
        return $this->payDocRu;
    }

    /**
     * Sets a new payDocRu
     *
     * @param \common\models\sbbolxml\request\SalaryPayDocRuType[] $payDocRu
     * @return static
     */
    public function setPayDocRu(array $payDocRu)
    {
        $this->payDocRu = $payDocRu;
        return $this;
    }


}


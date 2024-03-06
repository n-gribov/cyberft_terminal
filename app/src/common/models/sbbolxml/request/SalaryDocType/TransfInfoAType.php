<?php

namespace common\models\sbbolxml\request\SalaryDocType;

/**
 * Class representing TransfInfoAType
 */
class TransfInfoAType
{

    /**
     * @property \common\models\sbbolxml\request\SalaryDocType\TransfInfoAType\TransfAType[] $transf
     */
    private $transf = array(
        
    );

    /**
     * Adds as transf
     *
     * @return static
     * @param \common\models\sbbolxml\request\SalaryDocType\TransfInfoAType\TransfAType $transf
     */
    public function addToTransf(\common\models\sbbolxml\request\SalaryDocType\TransfInfoAType\TransfAType $transf)
    {
        $this->transf[] = $transf;
        return $this;
    }

    /**
     * isset transf
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetTransf($index)
    {
        return isset($this->transf[$index]);
    }

    /**
     * unset transf
     *
     * @param scalar $index
     * @return void
     */
    public function unsetTransf($index)
    {
        unset($this->transf[$index]);
    }

    /**
     * Gets as transf
     *
     * @return \common\models\sbbolxml\request\SalaryDocType\TransfInfoAType\TransfAType[]
     */
    public function getTransf()
    {
        return $this->transf;
    }

    /**
     * Sets a new transf
     *
     * @param \common\models\sbbolxml\request\SalaryDocType\TransfInfoAType\TransfAType[] $transf
     * @return static
     */
    public function setTransf(array $transf)
    {
        $this->transf = $transf;
        return $this;
    }


}


<?php

namespace common\models\sbbolxml\request\BeneficiarType;

/**
 * Class representing BeneficiaryesAType
 */
class BeneficiaryesAType
{

    /**
     * @property \common\models\sbbolxml\request\BeneficiaryType[] $beneficiary
     */
    private $beneficiary = array(
        
    );

    /**
     * Adds as beneficiary
     *
     * @return static
     * @param \common\models\sbbolxml\request\BeneficiaryType $beneficiary
     */
    public function addToBeneficiary(\common\models\sbbolxml\request\BeneficiaryType $beneficiary)
    {
        $this->beneficiary[] = $beneficiary;
        return $this;
    }

    /**
     * isset beneficiary
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetBeneficiary($index)
    {
        return isset($this->beneficiary[$index]);
    }

    /**
     * unset beneficiary
     *
     * @param scalar $index
     * @return void
     */
    public function unsetBeneficiary($index)
    {
        unset($this->beneficiary[$index]);
    }

    /**
     * Gets as beneficiary
     *
     * @return \common\models\sbbolxml\request\BeneficiaryType[]
     */
    public function getBeneficiary()
    {
        return $this->beneficiary;
    }

    /**
     * Sets a new beneficiary
     *
     * @param \common\models\sbbolxml\request\BeneficiaryType[] $beneficiary
     * @return static
     */
    public function setBeneficiary(array $beneficiary)
    {
        $this->beneficiary = $beneficiary;
        return $this;
    }


}


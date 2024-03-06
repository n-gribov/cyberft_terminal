<?php

namespace common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType;

/**
 * Class representing BeneficiarsAType
 */
class BeneficiarsAType
{

    /**
     * Идентификатор последнего изменения в справочнике
     *
     * @property integer $beneficiarDictStepId
     */
    private $beneficiarDictStepId = null;

    /**
     * @property \common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\BeneficiarsAType\BeneficiarAType[] $beneficiar
     */
    private $beneficiar = array(
        
    );

    /**
     * Gets as beneficiarDictStepId
     *
     * Идентификатор последнего изменения в справочнике
     *
     * @return integer
     */
    public function getBeneficiarDictStepId()
    {
        return $this->beneficiarDictStepId;
    }

    /**
     * Sets a new beneficiarDictStepId
     *
     * Идентификатор последнего изменения в справочнике
     *
     * @param integer $beneficiarDictStepId
     * @return static
     */
    public function setBeneficiarDictStepId($beneficiarDictStepId)
    {
        $this->beneficiarDictStepId = $beneficiarDictStepId;
        return $this;
    }

    /**
     * Adds as beneficiar
     *
     * @return static
     * @param \common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\BeneficiarsAType\BeneficiarAType $beneficiar
     */
    public function addToBeneficiar(\common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\BeneficiarsAType\BeneficiarAType $beneficiar)
    {
        $this->beneficiar[] = $beneficiar;
        return $this;
    }

    /**
     * isset beneficiar
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetBeneficiar($index)
    {
        return isset($this->beneficiar[$index]);
    }

    /**
     * unset beneficiar
     *
     * @param scalar $index
     * @return void
     */
    public function unsetBeneficiar($index)
    {
        unset($this->beneficiar[$index]);
    }

    /**
     * Gets as beneficiar
     *
     * @return \common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\BeneficiarsAType\BeneficiarAType[]
     */
    public function getBeneficiar()
    {
        return $this->beneficiar;
    }

    /**
     * Sets a new beneficiar
     *
     * @param \common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\BeneficiarsAType\BeneficiarAType[] $beneficiar
     * @return static
     */
    public function setBeneficiar(array $beneficiar)
    {
        $this->beneficiar = $beneficiar;
        return $this;
    }


}


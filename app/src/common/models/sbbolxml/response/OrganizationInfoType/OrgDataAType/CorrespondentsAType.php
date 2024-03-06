<?php

namespace common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType;

/**
 * Class representing CorrespondentsAType
 */
class CorrespondentsAType
{

    /**
     * Идентификатор последнего изменения в справочнике
     *
     * @property integer $correspondentDictStepId
     */
    private $correspondentDictStepId = null;

    /**
     * @property \common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\CorrespondentsAType\CorrespondentAType[] $correspondent
     */
    private $correspondent = array(
        
    );

    /**
     * Gets as correspondentDictStepId
     *
     * Идентификатор последнего изменения в справочнике
     *
     * @return integer
     */
    public function getCorrespondentDictStepId()
    {
        return $this->correspondentDictStepId;
    }

    /**
     * Sets a new correspondentDictStepId
     *
     * Идентификатор последнего изменения в справочнике
     *
     * @param integer $correspondentDictStepId
     * @return static
     */
    public function setCorrespondentDictStepId($correspondentDictStepId)
    {
        $this->correspondentDictStepId = $correspondentDictStepId;
        return $this;
    }

    /**
     * Adds as correspondent
     *
     * @return static
     * @param \common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\CorrespondentsAType\CorrespondentAType $correspondent
     */
    public function addToCorrespondent(\common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\CorrespondentsAType\CorrespondentAType $correspondent)
    {
        $this->correspondent[] = $correspondent;
        return $this;
    }

    /**
     * isset correspondent
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetCorrespondent($index)
    {
        return isset($this->correspondent[$index]);
    }

    /**
     * unset correspondent
     *
     * @param scalar $index
     * @return void
     */
    public function unsetCorrespondent($index)
    {
        unset($this->correspondent[$index]);
    }

    /**
     * Gets as correspondent
     *
     * @return \common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\CorrespondentsAType\CorrespondentAType[]
     */
    public function getCorrespondent()
    {
        return $this->correspondent;
    }

    /**
     * Sets a new correspondent
     *
     * @param \common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\CorrespondentsAType\CorrespondentAType[] $correspondent
     * @return static
     */
    public function setCorrespondent(array $correspondent)
    {
        $this->correspondent = $correspondent;
        return $this;
    }


}


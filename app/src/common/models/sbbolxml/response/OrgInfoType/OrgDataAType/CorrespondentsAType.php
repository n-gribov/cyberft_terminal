<?php

namespace common\models\sbbolxml\response\OrgInfoType\OrgDataAType;

/**
 * Class representing CorrespondentsAType
 */
class CorrespondentsAType
{

    /**
     * Информация об одном корреспонденте
     *
     * @property \common\models\sbbolxml\response\OrgInfoType\OrgDataAType\CorrespondentsAType\CorrespondentAType[] $correspondent
     */
    private $correspondent = array(
        
    );

    /**
     * Adds as correspondent
     *
     * Информация об одном корреспонденте
     *
     * @return static
     * @param \common\models\sbbolxml\response\OrgInfoType\OrgDataAType\CorrespondentsAType\CorrespondentAType $correspondent
     */
    public function addToCorrespondent(\common\models\sbbolxml\response\OrgInfoType\OrgDataAType\CorrespondentsAType\CorrespondentAType $correspondent)
    {
        $this->correspondent[] = $correspondent;
        return $this;
    }

    /**
     * isset correspondent
     *
     * Информация об одном корреспонденте
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
     * Информация об одном корреспонденте
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
     * Информация об одном корреспонденте
     *
     * @return \common\models\sbbolxml\response\OrgInfoType\OrgDataAType\CorrespondentsAType\CorrespondentAType[]
     */
    public function getCorrespondent()
    {
        return $this->correspondent;
    }

    /**
     * Sets a new correspondent
     *
     * Информация об одном корреспонденте
     *
     * @param \common\models\sbbolxml\response\OrgInfoType\OrgDataAType\CorrespondentsAType\CorrespondentAType[] $correspondent
     * @return static
     */
    public function setCorrespondent(array $correspondent)
    {
        $this->correspondent = $correspondent;
        return $this;
    }


}


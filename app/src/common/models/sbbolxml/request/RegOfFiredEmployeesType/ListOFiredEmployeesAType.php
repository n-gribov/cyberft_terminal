<?php

namespace common\models\sbbolxml\request\RegOfFiredEmployeesType;

/**
 * Class representing ListOFiredEmployeesAType
 */
class ListOFiredEmployeesAType
{

    /**
     * Информацию по каждому сотруднику, статус которого требуется изменить
     *  на "уволен"
     *
     * @property \common\models\sbbolxml\request\RegOfFiredEmployeesType\ListOFiredEmployeesAType\FiredEmployeesInfoAType[] $firedEmployeesInfo
     */
    private $firedEmployeesInfo = array(
        
    );

    /**
     * Adds as firedEmployeesInfo
     *
     * Информацию по каждому сотруднику, статус которого требуется изменить
     *  на "уволен"
     *
     * @return static
     * @param \common\models\sbbolxml\request\RegOfFiredEmployeesType\ListOFiredEmployeesAType\FiredEmployeesInfoAType $firedEmployeesInfo
     */
    public function addToFiredEmployeesInfo(\common\models\sbbolxml\request\RegOfFiredEmployeesType\ListOFiredEmployeesAType\FiredEmployeesInfoAType $firedEmployeesInfo)
    {
        $this->firedEmployeesInfo[] = $firedEmployeesInfo;
        return $this;
    }

    /**
     * isset firedEmployeesInfo
     *
     * Информацию по каждому сотруднику, статус которого требуется изменить
     *  на "уволен"
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetFiredEmployeesInfo($index)
    {
        return isset($this->firedEmployeesInfo[$index]);
    }

    /**
     * unset firedEmployeesInfo
     *
     * Информацию по каждому сотруднику, статус которого требуется изменить
     *  на "уволен"
     *
     * @param scalar $index
     * @return void
     */
    public function unsetFiredEmployeesInfo($index)
    {
        unset($this->firedEmployeesInfo[$index]);
    }

    /**
     * Gets as firedEmployeesInfo
     *
     * Информацию по каждому сотруднику, статус которого требуется изменить
     *  на "уволен"
     *
     * @return \common\models\sbbolxml\request\RegOfFiredEmployeesType\ListOFiredEmployeesAType\FiredEmployeesInfoAType[]
     */
    public function getFiredEmployeesInfo()
    {
        return $this->firedEmployeesInfo;
    }

    /**
     * Sets a new firedEmployeesInfo
     *
     * Информацию по каждому сотруднику, статус которого требуется изменить
     *  на "уволен"
     *
     * @param \common\models\sbbolxml\request\RegOfFiredEmployeesType\ListOFiredEmployeesAType\FiredEmployeesInfoAType[] $firedEmployeesInfo
     * @return static
     */
    public function setFiredEmployeesInfo(array $firedEmployeesInfo)
    {
        $this->firedEmployeesInfo = $firedEmployeesInfo;
        return $this;
    }


}


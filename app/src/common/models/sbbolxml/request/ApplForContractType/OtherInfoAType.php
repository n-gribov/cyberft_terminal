<?php

namespace common\models\sbbolxml\request\ApplForContractType;

/**
 * Class representing OtherInfoAType
 */
class OtherInfoAType
{

    /**
     * Количество сотрудников предприятия
     *  Целое положительное число
     *
     * @property integer $total
     */
    private $total = null;

    /**
     * Количество зачислений в месяц
     *
     * @property integer $admissionsNumber
     */
    private $admissionsNumber = null;

    /**
     * Среднемесячный ФОТ, руб
     *
     * @property float $fOT
     */
    private $fOT = null;

    /**
     * Gets as total
     *
     * Количество сотрудников предприятия
     *  Целое положительное число
     *
     * @return integer
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * Sets a new total
     *
     * Количество сотрудников предприятия
     *  Целое положительное число
     *
     * @param integer $total
     * @return static
     */
    public function setTotal($total)
    {
        $this->total = $total;
        return $this;
    }

    /**
     * Gets as admissionsNumber
     *
     * Количество зачислений в месяц
     *
     * @return integer
     */
    public function getAdmissionsNumber()
    {
        return $this->admissionsNumber;
    }

    /**
     * Sets a new admissionsNumber
     *
     * Количество зачислений в месяц
     *
     * @param integer $admissionsNumber
     * @return static
     */
    public function setAdmissionsNumber($admissionsNumber)
    {
        $this->admissionsNumber = $admissionsNumber;
        return $this;
    }

    /**
     * Gets as fOT
     *
     * Среднемесячный ФОТ, руб
     *
     * @return float
     */
    public function getFOT()
    {
        return $this->fOT;
    }

    /**
     * Sets a new fOT
     *
     * Среднемесячный ФОТ, руб
     *
     * @param float $fOT
     * @return static
     */
    public function setFOT($fOT)
    {
        $this->fOT = $fOT;
        return $this;
    }


}


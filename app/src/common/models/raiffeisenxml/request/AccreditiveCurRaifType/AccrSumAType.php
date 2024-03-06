<?php

namespace common\models\raiffeisenxml\request\AccreditiveCurRaifType;

/**
 * Class representing AccrSumAType
 */
class AccrSumAType
{

    /**
     * Сумма с валютой
     *
     * @property \common\models\raiffeisenxml\request\AccreditiveCurRaifType\AccrSumAType\SumAType $sum
     */
    private $sum = null;

    /**
     * Наличие отклонения в сумме аккредитива. 0 - нет, 1 - есть.
     *
     * @property bool $sumDeviation
     */
    private $sumDeviation = null;

    /**
     * Отклонение в сумме аккредитива. Возможные значения: "Положительное отклонение / Tolerance plus", "Отрицательное отклонение / Tolerance minus", "Положительное и отрицательное отклонения/ Tolerance plus and minus".
     *
     * @property string $deviation
     */
    private $deviation = null;

    /**
     * Допустимые процентные отклонения в сумме аккредитива
     *
     * @property \common\models\raiffeisenxml\request\AccreditiveCurRaifType\AccrSumAType\ToleranceAType $tolerance
     */
    private $tolerance = null;

    /**
     * Gets as sum
     *
     * Сумма с валютой
     *
     * @return \common\models\raiffeisenxml\request\AccreditiveCurRaifType\AccrSumAType\SumAType
     */
    public function getSum()
    {
        return $this->sum;
    }

    /**
     * Sets a new sum
     *
     * Сумма с валютой
     *
     * @param \common\models\raiffeisenxml\request\AccreditiveCurRaifType\AccrSumAType\SumAType $sum
     * @return static
     */
    public function setSum(\common\models\raiffeisenxml\request\AccreditiveCurRaifType\AccrSumAType\SumAType $sum)
    {
        $this->sum = $sum;
        return $this;
    }

    /**
     * Gets as sumDeviation
     *
     * Наличие отклонения в сумме аккредитива. 0 - нет, 1 - есть.
     *
     * @return bool
     */
    public function getSumDeviation()
    {
        return $this->sumDeviation;
    }

    /**
     * Sets a new sumDeviation
     *
     * Наличие отклонения в сумме аккредитива. 0 - нет, 1 - есть.
     *
     * @param bool $sumDeviation
     * @return static
     */
    public function setSumDeviation($sumDeviation)
    {
        $this->sumDeviation = $sumDeviation;
        return $this;
    }

    /**
     * Gets as deviation
     *
     * Отклонение в сумме аккредитива. Возможные значения: "Положительное отклонение / Tolerance plus", "Отрицательное отклонение / Tolerance minus", "Положительное и отрицательное отклонения/ Tolerance plus and minus".
     *
     * @return string
     */
    public function getDeviation()
    {
        return $this->deviation;
    }

    /**
     * Sets a new deviation
     *
     * Отклонение в сумме аккредитива. Возможные значения: "Положительное отклонение / Tolerance plus", "Отрицательное отклонение / Tolerance minus", "Положительное и отрицательное отклонения/ Tolerance plus and minus".
     *
     * @param string $deviation
     * @return static
     */
    public function setDeviation($deviation)
    {
        $this->deviation = $deviation;
        return $this;
    }

    /**
     * Gets as tolerance
     *
     * Допустимые процентные отклонения в сумме аккредитива
     *
     * @return \common\models\raiffeisenxml\request\AccreditiveCurRaifType\AccrSumAType\ToleranceAType
     */
    public function getTolerance()
    {
        return $this->tolerance;
    }

    /**
     * Sets a new tolerance
     *
     * Допустимые процентные отклонения в сумме аккредитива
     *
     * @param \common\models\raiffeisenxml\request\AccreditiveCurRaifType\AccrSumAType\ToleranceAType $tolerance
     * @return static
     */
    public function setTolerance(\common\models\raiffeisenxml\request\AccreditiveCurRaifType\AccrSumAType\ToleranceAType $tolerance)
    {
        $this->tolerance = $tolerance;
        return $this;
    }


}


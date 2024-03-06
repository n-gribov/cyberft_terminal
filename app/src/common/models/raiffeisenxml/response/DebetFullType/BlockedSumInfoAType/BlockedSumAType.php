<?php

namespace common\models\raiffeisenxml\response\DebetFullType\BlockedSumInfoAType;

/**
 * Class representing BlockedSumAType
 */
class BlockedSumAType
{

    /**
     * Заблокированная (арестованная) сумма на счёте.
     *
     * @property float $sum
     */
    private $sum = null;

    /**
     * Основание ареста.
     *
     * @property string $annotation
     */
    private $annotation = null;

    /**
     * Наименование органа, наложившего арест
     *
     * @property string $arrestedBy
     */
    private $arrestedBy = null;

    /**
     * Код налогового органа, наложившего арест
     *
     * @property string $arestedByNum
     */
    private $arestedByNum = null;

    /**
     * Период органичения
     *
     * @property \common\models\raiffeisenxml\response\LimitationPeriodType $limitationPeriod
     */
    private $limitationPeriod = null;

    /**
     * Gets as sum
     *
     * Заблокированная (арестованная) сумма на счёте.
     *
     * @return float
     */
    public function getSum()
    {
        return $this->sum;
    }

    /**
     * Sets a new sum
     *
     * Заблокированная (арестованная) сумма на счёте.
     *
     * @param float $sum
     * @return static
     */
    public function setSum($sum)
    {
        $this->sum = $sum;
        return $this;
    }

    /**
     * Gets as annotation
     *
     * Основание ареста.
     *
     * @return string
     */
    public function getAnnotation()
    {
        return $this->annotation;
    }

    /**
     * Sets a new annotation
     *
     * Основание ареста.
     *
     * @param string $annotation
     * @return static
     */
    public function setAnnotation($annotation)
    {
        $this->annotation = $annotation;
        return $this;
    }

    /**
     * Gets as arrestedBy
     *
     * Наименование органа, наложившего арест
     *
     * @return string
     */
    public function getArrestedBy()
    {
        return $this->arrestedBy;
    }

    /**
     * Sets a new arrestedBy
     *
     * Наименование органа, наложившего арест
     *
     * @param string $arrestedBy
     * @return static
     */
    public function setArrestedBy($arrestedBy)
    {
        $this->arrestedBy = $arrestedBy;
        return $this;
    }

    /**
     * Gets as arestedByNum
     *
     * Код налогового органа, наложившего арест
     *
     * @return string
     */
    public function getArestedByNum()
    {
        return $this->arestedByNum;
    }

    /**
     * Sets a new arestedByNum
     *
     * Код налогового органа, наложившего арест
     *
     * @param string $arestedByNum
     * @return static
     */
    public function setArestedByNum($arestedByNum)
    {
        $this->arestedByNum = $arestedByNum;
        return $this;
    }

    /**
     * Gets as limitationPeriod
     *
     * Период органичения
     *
     * @return \common\models\raiffeisenxml\response\LimitationPeriodType
     */
    public function getLimitationPeriod()
    {
        return $this->limitationPeriod;
    }

    /**
     * Sets a new limitationPeriod
     *
     * Период органичения
     *
     * @param \common\models\raiffeisenxml\response\LimitationPeriodType $limitationPeriod
     * @return static
     */
    public function setLimitationPeriod(\common\models\raiffeisenxml\response\LimitationPeriodType $limitationPeriod)
    {
        $this->limitationPeriod = $limitationPeriod;
        return $this;
    }


}


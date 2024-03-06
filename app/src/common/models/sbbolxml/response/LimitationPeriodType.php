<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing LimitationPeriodType
 *
 *
 * XSD Type: LimitationPeriod
 */
class LimitationPeriodType
{

    /**
     * Дата начала действия ограничения
     *
     * @property \DateTime $beginDate
     */
    private $beginDate = null;

    /**
     * Дата снятия ограничения
     *
     * @property \DateTime $endDate
     */
    private $endDate = null;

    /**
     * Gets as beginDate
     *
     * Дата начала действия ограничения
     *
     * @return \DateTime
     */
    public function getBeginDate()
    {
        return $this->beginDate;
    }

    /**
     * Sets a new beginDate
     *
     * Дата начала действия ограничения
     *
     * @param \DateTime $beginDate
     * @return static
     */
    public function setBeginDate(\DateTime $beginDate)
    {
        $this->beginDate = $beginDate;
        return $this;
    }

    /**
     * Gets as endDate
     *
     * Дата снятия ограничения
     *
     * @return \DateTime
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * Sets a new endDate
     *
     * Дата снятия ограничения
     *
     * @param \DateTime $endDate
     * @return static
     */
    public function setEndDate(\DateTime $endDate)
    {
        $this->endDate = $endDate;
        return $this;
    }


}


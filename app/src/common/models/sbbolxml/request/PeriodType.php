<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing PeriodType
 *
 *
 * XSD Type: Period
 */
class PeriodType
{

    /**
     * Дата начала периода
     *
     * @property \DateTime $beginDate
     */
    private $beginDate = null;

    /**
     * Дата завершения периода
     *
     * @property \DateTime $endDate
     */
    private $endDate = null;

    /**
     * Gets as beginDate
     *
     * Дата начала периода
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
     * Дата начала периода
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
     * Дата завершения периода
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
     * Дата завершения периода
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


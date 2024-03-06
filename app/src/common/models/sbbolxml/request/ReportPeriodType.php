<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing ReportPeriodType
 *
 * Отчетный период
 * XSD Type: ReportPeriod
 */
class ReportPeriodType
{

    /**
     * @property integer $month
     */
    private $month = null;

    /**
     * @property string $year
     */
    private $year = null;

    /**
     * Gets as month
     *
     * @return integer
     */
    public function getMonth()
    {
        return $this->month;
    }

    /**
     * Sets a new month
     *
     * @param integer $month
     * @return static
     */
    public function setMonth($month)
    {
        $this->month = $month;
        return $this;
    }

    /**
     * Gets as year
     *
     * @return string
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * Sets a new year
     *
     * @param string $year
     * @return static
     */
    public function setYear($year)
    {
        $this->year = $year;
        return $this;
    }


}


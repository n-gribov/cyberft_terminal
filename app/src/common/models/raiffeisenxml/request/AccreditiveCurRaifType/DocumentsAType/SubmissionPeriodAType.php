<?php

namespace common\models\raiffeisenxml\request\AccreditiveCurRaifType\DocumentsAType;

/**
 * Class representing SubmissionPeriodAType
 */
class SubmissionPeriodAType
{

    /**
     * Количество дней с даты отгрузки.
     *
     * @property string $days
     */
    private $days = null;

    /**
     * Период предоставления документов. Возможные значения: "Документы должны быть предоставлены в течение _ дней с даты отгрузки, но в пределах срока действия аккредитива", "Документы должны быть предоставлены в течение срока действия аккредитива", "Другое".
     *
     * @property string $period
     */
    private $period = null;

    /**
     * Другое
     *
     * @property string $other
     */
    private $other = null;

    /**
     * Gets as days
     *
     * Количество дней с даты отгрузки.
     *
     * @return string
     */
    public function getDays()
    {
        return $this->days;
    }

    /**
     * Sets a new days
     *
     * Количество дней с даты отгрузки.
     *
     * @param string $days
     * @return static
     */
    public function setDays($days)
    {
        $this->days = $days;
        return $this;
    }

    /**
     * Gets as period
     *
     * Период предоставления документов. Возможные значения: "Документы должны быть предоставлены в течение _ дней с даты отгрузки, но в пределах срока действия аккредитива", "Документы должны быть предоставлены в течение срока действия аккредитива", "Другое".
     *
     * @return string
     */
    public function getPeriod()
    {
        return $this->period;
    }

    /**
     * Sets a new period
     *
     * Период предоставления документов. Возможные значения: "Документы должны быть предоставлены в течение _ дней с даты отгрузки, но в пределах срока действия аккредитива", "Документы должны быть предоставлены в течение срока действия аккредитива", "Другое".
     *
     * @param string $period
     * @return static
     */
    public function setPeriod($period)
    {
        $this->period = $period;
        return $this;
    }

    /**
     * Gets as other
     *
     * Другое
     *
     * @return string
     */
    public function getOther()
    {
        return $this->other;
    }

    /**
     * Sets a new other
     *
     * Другое
     *
     * @param string $other
     * @return static
     */
    public function setOther($other)
    {
        $this->other = $other;
        return $this;
    }


}


<?php

namespace common\models\raiffeisenxml\request\GuaranteeRaifType\AddInfoAType;

/**
 * Class representing SubmissionAType
 */
class SubmissionAType
{

    /**
     * Другое
     *
     * @property string $other
     */
    private $other = null;

    /**
     * Значение
     *
     * @property string $value
     */
    private $value = null;

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

    /**
     * Gets as value
     *
     * Значение
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Sets a new value
     *
     * Значение
     *
     * @param string $value
     * @return static
     */
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }


}


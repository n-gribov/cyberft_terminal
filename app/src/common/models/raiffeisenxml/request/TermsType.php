<?php

namespace common\models\raiffeisenxml\request;

/**
 * Class representing TermsType
 *
 *
 * XSD Type: Terms
 */
class TermsType
{

    /**
     * @property string $termsType
     */
    private $termsType = null;

    /**
     * @property \DateTime $date
     */
    private $date = null;

    /**
     * Gets as termsType
     *
     * @return string
     */
    public function getTermsType()
    {
        return $this->termsType;
    }

    /**
     * Sets a new termsType
     *
     * @param string $termsType
     * @return static
     */
    public function setTermsType($termsType)
    {
        $this->termsType = $termsType;
        return $this;
    }

    /**
     * Gets as date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Sets a new date
     *
     * @param \DateTime $date
     * @return static
     */
    public function setDate(\DateTime $date)
    {
        $this->date = $date;
        return $this;
    }


}


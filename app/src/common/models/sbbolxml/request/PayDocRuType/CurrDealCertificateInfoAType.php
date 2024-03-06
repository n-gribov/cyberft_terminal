<?php

namespace common\models\sbbolxml\request\PayDocRuType;

/**
 * Class representing CurrDealCertificateInfoAType
 */
class CurrDealCertificateInfoAType
{

    /**
     * Номер документа (клиентский)
     *
     * @property string $number
     */
    private $number = null;

    /**
     * @property \DateTime $date
     */
    private $date = null;

    /**
     * Gets as number
     *
     * Номер документа (клиентский)
     *
     * @return string
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Sets a new number
     *
     * Номер документа (клиентский)
     *
     * @param string $number
     * @return static
     */
    public function setNumber($number)
    {
        $this->number = $number;
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


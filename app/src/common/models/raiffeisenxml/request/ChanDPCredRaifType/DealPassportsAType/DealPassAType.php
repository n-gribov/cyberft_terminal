<?php

namespace common\models\raiffeisenxml\request\ChanDPCredRaifType\DealPassportsAType;

/**
 * Class representing DealPassAType
 */
class DealPassAType
{

    /**
     * Номер
     *
     * @property string $num
     */
    private $num = null;

    /**
     * Дата
     *
     * @property \DateTime $date
     */
    private $date = null;

    /**
     * Gets as num
     *
     * Номер
     *
     * @return string
     */
    public function getNum()
    {
        return $this->num;
    }

    /**
     * Sets a new num
     *
     * Номер
     *
     * @param string $num
     * @return static
     */
    public function setNum($num)
    {
        $this->num = $num;
        return $this;
    }

    /**
     * Gets as date
     *
     * Дата
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
     * Дата
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


<?php

namespace common\models\sbbolxml\request\TermDealTypeRequiredType;

/**
 * Class representing RateAType
 */
class RateAType
{

    /**
     * 0 - по курсу не более,
     *  1 - биржевой
     *
     * @property string $type
     */
    private $type = null;

    /**
     * курс
     *
     * @property float $rate
     */
    private $rate = null;

    /**
     * Gets as type
     *
     * 0 - по курсу не более,
     *  1 - биржевой
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Sets a new type
     *
     * 0 - по курсу не более,
     *  1 - биржевой
     *
     * @param string $type
     * @return static
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * Gets as rate
     *
     * курс
     *
     * @return float
     */
    public function getRate()
    {
        return $this->rate;
    }

    /**
     * Sets a new rate
     *
     * курс
     *
     * @param float $rate
     * @return static
     */
    public function setRate($rate)
    {
        $this->rate = $rate;
        return $this;
    }


}


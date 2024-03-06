<?php

namespace common\models\raiffeisenxml\request\DealPassCred138IRaifType\TrancheInfoAType;

/**
 * Class representing OperAType
 */
class OperAType
{

    /**
     * Сумма транша
     *
     * @property float $sum
     */
    private $sum = null;

    /**
     * Код срока привлечения транша
     *
     * @property string $code
     */
    private $code = null;

    /**
     * Ожидаемая дата поступления
     *
     * @property \DateTime $date
     */
    private $date = null;

    /**
     * Gets as sum
     *
     * Сумма транша
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
     * Сумма транша
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
     * Gets as code
     *
     * Код срока привлечения транша
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Sets a new code
     *
     * Код срока привлечения транша
     *
     * @param string $code
     * @return static
     */
    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    /**
     * Gets as date
     *
     * Ожидаемая дата поступления
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
     * Ожидаемая дата поступления
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


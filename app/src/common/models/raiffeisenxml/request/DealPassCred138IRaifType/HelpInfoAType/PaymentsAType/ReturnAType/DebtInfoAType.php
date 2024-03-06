<?php

namespace common\models\raiffeisenxml\request\DealPassCred138IRaifType\HelpInfoAType\PaymentsAType\ReturnAType;

/**
 * Class representing DebtInfoAType
 */
class DebtInfoAType
{

    /**
     * Дата платежа
     *
     * @property \DateTime $date
     */
    private $date = null;

    /**
     * Сумма платежа
     *
     * @property float $sum
     */
    private $sum = null;

    /**
     * Gets as date
     *
     * Дата платежа
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
     * Дата платежа
     *
     * @param \DateTime $date
     * @return static
     */
    public function setDate(\DateTime $date)
    {
        $this->date = $date;
        return $this;
    }

    /**
     * Gets as sum
     *
     * Сумма платежа
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
     * Сумма платежа
     *
     * @param float $sum
     * @return static
     */
    public function setSum($sum)
    {
        $this->sum = $sum;
        return $this;
    }


}


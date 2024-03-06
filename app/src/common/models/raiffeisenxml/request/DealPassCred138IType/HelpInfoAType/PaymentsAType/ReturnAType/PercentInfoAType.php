<?php

namespace common\models\raiffeisenxml\request\DealPassCred138IType\HelpInfoAType\PaymentsAType\ReturnAType;

/**
 * Class representing PercentInfoAType
 */
class PercentInfoAType
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
     * @property \common\models\raiffeisenxml\request\CurrAmountType $sum
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
     * @return \common\models\raiffeisenxml\request\CurrAmountType
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
     * @param \common\models\raiffeisenxml\request\CurrAmountType $sum
     * @return static
     */
    public function setSum(\common\models\raiffeisenxml\request\CurrAmountType $sum)
    {
        $this->sum = $sum;
        return $this;
    }


}


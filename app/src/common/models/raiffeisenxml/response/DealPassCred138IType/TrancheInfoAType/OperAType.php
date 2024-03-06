<?php

namespace common\models\raiffeisenxml\response\DealPassCred138IType\TrancheInfoAType;

/**
 * Class representing OperAType
 */
class OperAType
{

    /**
     * Сумма транша
     *
     * @property \common\models\raiffeisenxml\response\CurrAmountType $sum
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
     * @return \common\models\raiffeisenxml\response\CurrAmountType
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
     * @param \common\models\raiffeisenxml\response\CurrAmountType $sum
     * @return static
     */
    public function setSum(\common\models\raiffeisenxml\response\CurrAmountType $sum)
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


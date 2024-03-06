<?php

namespace common\models\raiffeisenxml\request;

/**
 * Class representing TrancheInfoType
 *
 *
 * XSD Type: TrancheInfo
 */
class TrancheInfoType
{

    /**
     * Сумма транша
     *
     * @property float $sum
     */
    private $sum = null;

    /**
     * Валюта цены контракта
     *
     * @property \common\models\raiffeisenxml\request\TrancheInfoType\CurrAType $curr
     */
    private $curr = null;

    /**
     * Ожидаемая дата поступления
     *
     * @property \DateTime $supplyDate
     */
    private $supplyDate = null;

    /**
     * Код срока привлечения транша
     *
     * @property string $attractTermCode
     */
    private $attractTermCode = null;

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
     * Gets as curr
     *
     * Валюта цены контракта
     *
     * @return \common\models\raiffeisenxml\request\TrancheInfoType\CurrAType
     */
    public function getCurr()
    {
        return $this->curr;
    }

    /**
     * Sets a new curr
     *
     * Валюта цены контракта
     *
     * @param \common\models\raiffeisenxml\request\TrancheInfoType\CurrAType $curr
     * @return static
     */
    public function setCurr(\common\models\raiffeisenxml\request\TrancheInfoType\CurrAType $curr)
    {
        $this->curr = $curr;
        return $this;
    }

    /**
     * Gets as supplyDate
     *
     * Ожидаемая дата поступления
     *
     * @return \DateTime
     */
    public function getSupplyDate()
    {
        return $this->supplyDate;
    }

    /**
     * Sets a new supplyDate
     *
     * Ожидаемая дата поступления
     *
     * @param \DateTime $supplyDate
     * @return static
     */
    public function setSupplyDate(\DateTime $supplyDate)
    {
        $this->supplyDate = $supplyDate;
        return $this;
    }

    /**
     * Gets as attractTermCode
     *
     * Код срока привлечения транша
     *
     * @return string
     */
    public function getAttractTermCode()
    {
        return $this->attractTermCode;
    }

    /**
     * Sets a new attractTermCode
     *
     * Код срока привлечения транша
     *
     * @param string $attractTermCode
     * @return static
     */
    public function setAttractTermCode($attractTermCode)
    {
        $this->attractTermCode = $attractTermCode;
        return $this;
    }


}


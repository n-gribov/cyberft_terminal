<?php

namespace common\models\raiffeisenxml\response\ContractType\ContractServicesAType\ContractServiceAType\AccountsAType\AccountAType;

/**
 * Class representing PaymentControlAType
 */
class PaymentControlAType
{

    /**
     * Сумма документа, начиная с которой требовать одну
     *  визирующую подпись
     *
     * @property float $sum1
     */
    private $sum1 = null;

    /**
     * Сумма документа, начиная с которой требовать две
     *  визирующие подписи
     *
     * @property float $sum2
     */
    private $sum2 = null;

    /**
     * Сумма документа, начиная с которой требовать три
     *  визирующие подписи
     *
     * @property float $sum3
     */
    private $sum3 = null;

    /**
     * Gets as sum1
     *
     * Сумма документа, начиная с которой требовать одну
     *  визирующую подпись
     *
     * @return float
     */
    public function getSum1()
    {
        return $this->sum1;
    }

    /**
     * Sets a new sum1
     *
     * Сумма документа, начиная с которой требовать одну
     *  визирующую подпись
     *
     * @param float $sum1
     * @return static
     */
    public function setSum1($sum1)
    {
        $this->sum1 = $sum1;
        return $this;
    }

    /**
     * Gets as sum2
     *
     * Сумма документа, начиная с которой требовать две
     *  визирующие подписи
     *
     * @return float
     */
    public function getSum2()
    {
        return $this->sum2;
    }

    /**
     * Sets a new sum2
     *
     * Сумма документа, начиная с которой требовать две
     *  визирующие подписи
     *
     * @param float $sum2
     * @return static
     */
    public function setSum2($sum2)
    {
        $this->sum2 = $sum2;
        return $this;
    }

    /**
     * Gets as sum3
     *
     * Сумма документа, начиная с которой требовать три
     *  визирующие подписи
     *
     * @return float
     */
    public function getSum3()
    {
        return $this->sum3;
    }

    /**
     * Sets a new sum3
     *
     * Сумма документа, начиная с которой требовать три
     *  визирующие подписи
     *
     * @param float $sum3
     * @return static
     */
    public function setSum3($sum3)
    {
        $this->sum3 = $sum3;
        return $this;
    }


}


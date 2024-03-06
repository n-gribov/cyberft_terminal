<?php

namespace common\models\raiffeisenxml\request\ChanDPCredRaifType;

/**
 * Class representing SpecTermsAType
 */
class SpecTermsAType
{

    /**
     * Сумма, подлежащая зачислению на счета за рубежом
     *
     * @property \common\models\raiffeisenxml\request\ChanDPCredRaifType\SpecTermsAType\EnrollAbroadSumAType $enrollAbroadSum
     */
    private $enrollAbroadSum = null;

    /**
     * Сумма, погашение которой предполагается за счет валютной выручки
     *
     * @property \common\models\raiffeisenxml\request\ChanDPCredRaifType\SpecTermsAType\RevenueWriteOffSumAType $revenueWriteOffSum
     */
    private $revenueWriteOffSum = null;

    /**
     * Код срока привлечения
     *
     * @property string $attractTermCode
     */
    private $attractTermCode = null;

    /**
     * Gets as enrollAbroadSum
     *
     * Сумма, подлежащая зачислению на счета за рубежом
     *
     * @return \common\models\raiffeisenxml\request\ChanDPCredRaifType\SpecTermsAType\EnrollAbroadSumAType
     */
    public function getEnrollAbroadSum()
    {
        return $this->enrollAbroadSum;
    }

    /**
     * Sets a new enrollAbroadSum
     *
     * Сумма, подлежащая зачислению на счета за рубежом
     *
     * @param \common\models\raiffeisenxml\request\ChanDPCredRaifType\SpecTermsAType\EnrollAbroadSumAType $enrollAbroadSum
     * @return static
     */
    public function setEnrollAbroadSum(\common\models\raiffeisenxml\request\ChanDPCredRaifType\SpecTermsAType\EnrollAbroadSumAType $enrollAbroadSum)
    {
        $this->enrollAbroadSum = $enrollAbroadSum;
        return $this;
    }

    /**
     * Gets as revenueWriteOffSum
     *
     * Сумма, погашение которой предполагается за счет валютной выручки
     *
     * @return \common\models\raiffeisenxml\request\ChanDPCredRaifType\SpecTermsAType\RevenueWriteOffSumAType
     */
    public function getRevenueWriteOffSum()
    {
        return $this->revenueWriteOffSum;
    }

    /**
     * Sets a new revenueWriteOffSum
     *
     * Сумма, погашение которой предполагается за счет валютной выручки
     *
     * @param \common\models\raiffeisenxml\request\ChanDPCredRaifType\SpecTermsAType\RevenueWriteOffSumAType $revenueWriteOffSum
     * @return static
     */
    public function setRevenueWriteOffSum(\common\models\raiffeisenxml\request\ChanDPCredRaifType\SpecTermsAType\RevenueWriteOffSumAType $revenueWriteOffSum)
    {
        $this->revenueWriteOffSum = $revenueWriteOffSum;
        return $this;
    }

    /**
     * Gets as attractTermCode
     *
     * Код срока привлечения
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
     * Код срока привлечения
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


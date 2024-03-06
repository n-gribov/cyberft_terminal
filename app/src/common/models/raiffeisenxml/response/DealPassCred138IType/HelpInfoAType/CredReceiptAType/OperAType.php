<?php

namespace common\models\raiffeisenxml\response\DealPassCred138IType\HelpInfoAType\CredReceiptAType;

/**
 * Class representing OperAType
 */
class OperAType
{

    /**
     * Реквизиты иностранного контрагента
     *
     * @property \common\models\raiffeisenxml\response\BeneficiarInfoType $beneficiarInfo
     */
    private $beneficiarInfo = null;

    /**
     * Сумма
     *
     * @property \common\models\raiffeisenxml\response\CurrAmountType $sum
     */
    private $sum = null;

    /**
     * Доля в общей сумме
     *
     * @property float $percent
     */
    private $percent = null;

    /**
     * Gets as beneficiarInfo
     *
     * Реквизиты иностранного контрагента
     *
     * @return \common\models\raiffeisenxml\response\BeneficiarInfoType
     */
    public function getBeneficiarInfo()
    {
        return $this->beneficiarInfo;
    }

    /**
     * Sets a new beneficiarInfo
     *
     * Реквизиты иностранного контрагента
     *
     * @param \common\models\raiffeisenxml\response\BeneficiarInfoType $beneficiarInfo
     * @return static
     */
    public function setBeneficiarInfo(\common\models\raiffeisenxml\response\BeneficiarInfoType $beneficiarInfo)
    {
        $this->beneficiarInfo = $beneficiarInfo;
        return $this;
    }

    /**
     * Gets as sum
     *
     * Сумма
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
     * Сумма
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
     * Gets as percent
     *
     * Доля в общей сумме
     *
     * @return float
     */
    public function getPercent()
    {
        return $this->percent;
    }

    /**
     * Sets a new percent
     *
     * Доля в общей сумме
     *
     * @param float $percent
     * @return static
     */
    public function setPercent($percent)
    {
        $this->percent = $percent;
        return $this;
    }


}


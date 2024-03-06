<?php

namespace common\models\raiffeisenxml\request\DealPassCred138IRaifType\HelpInfoAType\CredReceiptAType;

/**
 * Class representing OperAType
 */
class OperAType
{

    /**
     * Номер п/п
     *
     * @property string $strNum
     */
    private $strNum = null;

    /**
     * Реквизиты иностранного контрагента
     *
     * @property \common\models\raiffeisenxml\request\BeneficiarInfoCreditType $beneficiarInfo
     */
    private $beneficiarInfo = null;

    /**
     * Сумма
     *
     * @property float $sum
     */
    private $sum = null;

    /**
     * Доля в общей сумме
     *
     * @property float $percent
     */
    private $percent = null;

    /**
     * Gets as strNum
     *
     * Номер п/п
     *
     * @return string
     */
    public function getStrNum()
    {
        return $this->strNum;
    }

    /**
     * Sets a new strNum
     *
     * Номер п/п
     *
     * @param string $strNum
     * @return static
     */
    public function setStrNum($strNum)
    {
        $this->strNum = $strNum;
        return $this;
    }

    /**
     * Gets as beneficiarInfo
     *
     * Реквизиты иностранного контрагента
     *
     * @return \common\models\raiffeisenxml\request\BeneficiarInfoCreditType
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
     * @param \common\models\raiffeisenxml\request\BeneficiarInfoCreditType $beneficiarInfo
     * @return static
     */
    public function setBeneficiarInfo(\common\models\raiffeisenxml\request\BeneficiarInfoCreditType $beneficiarInfo)
    {
        $this->beneficiarInfo = $beneficiarInfo;
        return $this;
    }

    /**
     * Gets as sum
     *
     * Сумма
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
     * Сумма
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


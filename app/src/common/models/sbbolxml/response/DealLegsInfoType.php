<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing DealLegsInfoType
 *
 * Информация о ногах подтверждающей сделки
 * XSD Type: DealLegsInfo
 */
class DealLegsInfoType
{

    /**
     * Сумма и валюта, выплачиваемая стороной А
     *
     * @property string $curSumA
     */
    private $curSumA = null;

    /**
     * Сумма и валюта, выплачиваемая стороной Б
     *
     * @property string $curSumB
     */
    private $curSumB = null;

    /**
     * Дата валютирования для стороны А
     *
     * @property \DateTime $valueDateA
     */
    private $valueDateA = null;

    /**
     * Дата валютирования для стороны Б
     *
     * @property \DateTime $valueDateB
     */
    private $valueDateB = null;

    /**
     * Банковские реквизиты Стороны А
     *
     * @property string $bankDetailsA
     */
    private $bankDetailsA = null;

    /**
     * Банковские реквизиты Стороны Б
     *
     * @property string $bankDetailsB
     */
    private $bankDetailsB = null;

    /**
     * Обменный курс первоначального платежа
     *
     * @property string $firstPaymentRateSwap
     */
    private $firstPaymentRateSwap = null;

    /**
     * Обменный курс окончательного платежа
     *
     * @property string $lastPaymentRateSwap
     */
    private $lastPaymentRateSwap = null;

    /**
     * Сумма первоначального платежа и валюта для Стороны А
     *
     * @property string $curSumASwap
     */
    private $curSumASwap = null;

    /**
     * Сумма первоначального платежа и валюта для Стороны Б
     *
     * @property string $curSumBSwap
     */
    private $curSumBSwap = null;

    /**
     * Дата первоначального платежа для Стороны А
     *
     * @property \DateTime $fistPaymentDateASwap
     */
    private $fistPaymentDateASwap = null;

    /**
     * Дата первоначального платежа для Стороны Б
     *
     * @property \DateTime $fistPaymentDateBSwap
     */
    private $fistPaymentDateBSwap = null;

    /**
     * Сумма окончательного платежа и валюта для Стороны А
     *
     * @property string $paymentDetailsASwap
     */
    private $paymentDetailsASwap = null;

    /**
     * Сумма окончательного платежа и валюта для Стороны Б
     *
     * @property string $paymentDetailsBSwap
     */
    private $paymentDetailsBSwap = null;

    /**
     * Дата окончательного платежа для Стороны А
     *
     * @property \DateTime $lastPaymentDateASwap
     */
    private $lastPaymentDateASwap = null;

    /**
     * Дата окончательного платежа для Стороны Б
     *
     * @property \DateTime $lastPaymentDateBSwap
     */
    private $lastPaymentDateBSwap = null;

    /**
     * Первая валюта из пары
     *
     * @property string $currency1
     */
    private $currency1 = null;

    /**
     * Вторая валюта из пары (валюта расчетов)
     *
     * @property string $currency2
     */
    private $currency2 = null;

    /**
     * Валюты: cCY1/cCY2
     *
     * @property string $currencies
     */
    private $currencies = null;

    /**
     * Gets as curSumA
     *
     * Сумма и валюта, выплачиваемая стороной А
     *
     * @return string
     */
    public function getCurSumA()
    {
        return $this->curSumA;
    }

    /**
     * Sets a new curSumA
     *
     * Сумма и валюта, выплачиваемая стороной А
     *
     * @param string $curSumA
     * @return static
     */
    public function setCurSumA($curSumA)
    {
        $this->curSumA = $curSumA;
        return $this;
    }

    /**
     * Gets as curSumB
     *
     * Сумма и валюта, выплачиваемая стороной Б
     *
     * @return string
     */
    public function getCurSumB()
    {
        return $this->curSumB;
    }

    /**
     * Sets a new curSumB
     *
     * Сумма и валюта, выплачиваемая стороной Б
     *
     * @param string $curSumB
     * @return static
     */
    public function setCurSumB($curSumB)
    {
        $this->curSumB = $curSumB;
        return $this;
    }

    /**
     * Gets as valueDateA
     *
     * Дата валютирования для стороны А
     *
     * @return \DateTime
     */
    public function getValueDateA()
    {
        return $this->valueDateA;
    }

    /**
     * Sets a new valueDateA
     *
     * Дата валютирования для стороны А
     *
     * @param \DateTime $valueDateA
     * @return static
     */
    public function setValueDateA(\DateTime $valueDateA)
    {
        $this->valueDateA = $valueDateA;
        return $this;
    }

    /**
     * Gets as valueDateB
     *
     * Дата валютирования для стороны Б
     *
     * @return \DateTime
     */
    public function getValueDateB()
    {
        return $this->valueDateB;
    }

    /**
     * Sets a new valueDateB
     *
     * Дата валютирования для стороны Б
     *
     * @param \DateTime $valueDateB
     * @return static
     */
    public function setValueDateB(\DateTime $valueDateB)
    {
        $this->valueDateB = $valueDateB;
        return $this;
    }

    /**
     * Gets as bankDetailsA
     *
     * Банковские реквизиты Стороны А
     *
     * @return string
     */
    public function getBankDetailsA()
    {
        return $this->bankDetailsA;
    }

    /**
     * Sets a new bankDetailsA
     *
     * Банковские реквизиты Стороны А
     *
     * @param string $bankDetailsA
     * @return static
     */
    public function setBankDetailsA($bankDetailsA)
    {
        $this->bankDetailsA = $bankDetailsA;
        return $this;
    }

    /**
     * Gets as bankDetailsB
     *
     * Банковские реквизиты Стороны Б
     *
     * @return string
     */
    public function getBankDetailsB()
    {
        return $this->bankDetailsB;
    }

    /**
     * Sets a new bankDetailsB
     *
     * Банковские реквизиты Стороны Б
     *
     * @param string $bankDetailsB
     * @return static
     */
    public function setBankDetailsB($bankDetailsB)
    {
        $this->bankDetailsB = $bankDetailsB;
        return $this;
    }

    /**
     * Gets as firstPaymentRateSwap
     *
     * Обменный курс первоначального платежа
     *
     * @return string
     */
    public function getFirstPaymentRateSwap()
    {
        return $this->firstPaymentRateSwap;
    }

    /**
     * Sets a new firstPaymentRateSwap
     *
     * Обменный курс первоначального платежа
     *
     * @param string $firstPaymentRateSwap
     * @return static
     */
    public function setFirstPaymentRateSwap($firstPaymentRateSwap)
    {
        $this->firstPaymentRateSwap = $firstPaymentRateSwap;
        return $this;
    }

    /**
     * Gets as lastPaymentRateSwap
     *
     * Обменный курс окончательного платежа
     *
     * @return string
     */
    public function getLastPaymentRateSwap()
    {
        return $this->lastPaymentRateSwap;
    }

    /**
     * Sets a new lastPaymentRateSwap
     *
     * Обменный курс окончательного платежа
     *
     * @param string $lastPaymentRateSwap
     * @return static
     */
    public function setLastPaymentRateSwap($lastPaymentRateSwap)
    {
        $this->lastPaymentRateSwap = $lastPaymentRateSwap;
        return $this;
    }

    /**
     * Gets as curSumASwap
     *
     * Сумма первоначального платежа и валюта для Стороны А
     *
     * @return string
     */
    public function getCurSumASwap()
    {
        return $this->curSumASwap;
    }

    /**
     * Sets a new curSumASwap
     *
     * Сумма первоначального платежа и валюта для Стороны А
     *
     * @param string $curSumASwap
     * @return static
     */
    public function setCurSumASwap($curSumASwap)
    {
        $this->curSumASwap = $curSumASwap;
        return $this;
    }

    /**
     * Gets as curSumBSwap
     *
     * Сумма первоначального платежа и валюта для Стороны Б
     *
     * @return string
     */
    public function getCurSumBSwap()
    {
        return $this->curSumBSwap;
    }

    /**
     * Sets a new curSumBSwap
     *
     * Сумма первоначального платежа и валюта для Стороны Б
     *
     * @param string $curSumBSwap
     * @return static
     */
    public function setCurSumBSwap($curSumBSwap)
    {
        $this->curSumBSwap = $curSumBSwap;
        return $this;
    }

    /**
     * Gets as fistPaymentDateASwap
     *
     * Дата первоначального платежа для Стороны А
     *
     * @return \DateTime
     */
    public function getFistPaymentDateASwap()
    {
        return $this->fistPaymentDateASwap;
    }

    /**
     * Sets a new fistPaymentDateASwap
     *
     * Дата первоначального платежа для Стороны А
     *
     * @param \DateTime $fistPaymentDateASwap
     * @return static
     */
    public function setFistPaymentDateASwap(\DateTime $fistPaymentDateASwap)
    {
        $this->fistPaymentDateASwap = $fistPaymentDateASwap;
        return $this;
    }

    /**
     * Gets as fistPaymentDateBSwap
     *
     * Дата первоначального платежа для Стороны Б
     *
     * @return \DateTime
     */
    public function getFistPaymentDateBSwap()
    {
        return $this->fistPaymentDateBSwap;
    }

    /**
     * Sets a new fistPaymentDateBSwap
     *
     * Дата первоначального платежа для Стороны Б
     *
     * @param \DateTime $fistPaymentDateBSwap
     * @return static
     */
    public function setFistPaymentDateBSwap(\DateTime $fistPaymentDateBSwap)
    {
        $this->fistPaymentDateBSwap = $fistPaymentDateBSwap;
        return $this;
    }

    /**
     * Gets as paymentDetailsASwap
     *
     * Сумма окончательного платежа и валюта для Стороны А
     *
     * @return string
     */
    public function getPaymentDetailsASwap()
    {
        return $this->paymentDetailsASwap;
    }

    /**
     * Sets a new paymentDetailsASwap
     *
     * Сумма окончательного платежа и валюта для Стороны А
     *
     * @param string $paymentDetailsASwap
     * @return static
     */
    public function setPaymentDetailsASwap($paymentDetailsASwap)
    {
        $this->paymentDetailsASwap = $paymentDetailsASwap;
        return $this;
    }

    /**
     * Gets as paymentDetailsBSwap
     *
     * Сумма окончательного платежа и валюта для Стороны Б
     *
     * @return string
     */
    public function getPaymentDetailsBSwap()
    {
        return $this->paymentDetailsBSwap;
    }

    /**
     * Sets a new paymentDetailsBSwap
     *
     * Сумма окончательного платежа и валюта для Стороны Б
     *
     * @param string $paymentDetailsBSwap
     * @return static
     */
    public function setPaymentDetailsBSwap($paymentDetailsBSwap)
    {
        $this->paymentDetailsBSwap = $paymentDetailsBSwap;
        return $this;
    }

    /**
     * Gets as lastPaymentDateASwap
     *
     * Дата окончательного платежа для Стороны А
     *
     * @return \DateTime
     */
    public function getLastPaymentDateASwap()
    {
        return $this->lastPaymentDateASwap;
    }

    /**
     * Sets a new lastPaymentDateASwap
     *
     * Дата окончательного платежа для Стороны А
     *
     * @param \DateTime $lastPaymentDateASwap
     * @return static
     */
    public function setLastPaymentDateASwap(\DateTime $lastPaymentDateASwap)
    {
        $this->lastPaymentDateASwap = $lastPaymentDateASwap;
        return $this;
    }

    /**
     * Gets as lastPaymentDateBSwap
     *
     * Дата окончательного платежа для Стороны Б
     *
     * @return \DateTime
     */
    public function getLastPaymentDateBSwap()
    {
        return $this->lastPaymentDateBSwap;
    }

    /**
     * Sets a new lastPaymentDateBSwap
     *
     * Дата окончательного платежа для Стороны Б
     *
     * @param \DateTime $lastPaymentDateBSwap
     * @return static
     */
    public function setLastPaymentDateBSwap(\DateTime $lastPaymentDateBSwap)
    {
        $this->lastPaymentDateBSwap = $lastPaymentDateBSwap;
        return $this;
    }

    /**
     * Gets as currency1
     *
     * Первая валюта из пары
     *
     * @return string
     */
    public function getCurrency1()
    {
        return $this->currency1;
    }

    /**
     * Sets a new currency1
     *
     * Первая валюта из пары
     *
     * @param string $currency1
     * @return static
     */
    public function setCurrency1($currency1)
    {
        $this->currency1 = $currency1;
        return $this;
    }

    /**
     * Gets as currency2
     *
     * Вторая валюта из пары (валюта расчетов)
     *
     * @return string
     */
    public function getCurrency2()
    {
        return $this->currency2;
    }

    /**
     * Sets a new currency2
     *
     * Вторая валюта из пары (валюта расчетов)
     *
     * @param string $currency2
     * @return static
     */
    public function setCurrency2($currency2)
    {
        $this->currency2 = $currency2;
        return $this;
    }

    /**
     * Gets as currencies
     *
     * Валюты: cCY1/cCY2
     *
     * @return string
     */
    public function getCurrencies()
    {
        return $this->currencies;
    }

    /**
     * Sets a new currencies
     *
     * Валюты: cCY1/cCY2
     *
     * @param string $currencies
     * @return static
     */
    public function setCurrencies($currencies)
    {
        $this->currencies = $currencies;
        return $this;
    }


}


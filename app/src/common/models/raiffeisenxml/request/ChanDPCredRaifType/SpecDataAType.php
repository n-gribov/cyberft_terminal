<?php

namespace common\models\raiffeisenxml\request\ChanDPCredRaifType;

/**
 * Class representing SpecDataAType
 */
class SpecDataAType
{

    /**
     * Фикс. размер процентной ставки (% годовых)
     *
     * @property float $intRate
     */
    private $intRate = null;

    /**
     * Код ставки ЛИБОР
     *
     * @property \common\models\raiffeisenxml\request\ChanDPCredRaifType\SpecDataAType\LiborRateCodeAType $liborRateCode
     */
    private $liborRateCode = null;

    /**
     * Другой метод определения ставки
     *
     * @property string $otherMethod
     */
    private $otherMethod = null;

    /**
     * Размер процентной надбавки
     *
     * @property float $percAllowance
     */
    private $percAllowance = null;

    /**
     * 8.2. Иные платежи, предусмотренные кредитным договором
     *
     * @property string $otherPayments
     */
    private $otherPayments = null;

    /**
     * Основания для заполнения
     *
     * @property string $fillingBase
     */
    private $fillingBase = null;

    /**
     * Описание графика платежей по возврату основного долга
     *
     * @property \common\models\raiffeisenxml\request\PaymentType[] $paymentSchedule
     */
    private $paymentSchedule = null;

    /**
     * Наличие прямого инвестирования
     *
     * @property bool $directInv
     */
    private $directInv = null;

    /**
     * Сумма залогового или другого обеспечения
     *
     * @property float $secSum
     */
    private $secSum = null;

    /**
     * Информация о привлечении резидентом кредита, предоставленного нерезидентом на синдицированной основе
     *
     * @property \common\models\raiffeisenxml\request\ChanDPCredRaifType\SpecDataAType\ResCredInfoAType[] $resCredInfo
     */
    private $resCredInfo = [
        
    ];

    /**
     * Gets as intRate
     *
     * Фикс. размер процентной ставки (% годовых)
     *
     * @return float
     */
    public function getIntRate()
    {
        return $this->intRate;
    }

    /**
     * Sets a new intRate
     *
     * Фикс. размер процентной ставки (% годовых)
     *
     * @param float $intRate
     * @return static
     */
    public function setIntRate($intRate)
    {
        $this->intRate = $intRate;
        return $this;
    }

    /**
     * Gets as liborRateCode
     *
     * Код ставки ЛИБОР
     *
     * @return \common\models\raiffeisenxml\request\ChanDPCredRaifType\SpecDataAType\LiborRateCodeAType
     */
    public function getLiborRateCode()
    {
        return $this->liborRateCode;
    }

    /**
     * Sets a new liborRateCode
     *
     * Код ставки ЛИБОР
     *
     * @param \common\models\raiffeisenxml\request\ChanDPCredRaifType\SpecDataAType\LiborRateCodeAType $liborRateCode
     * @return static
     */
    public function setLiborRateCode(\common\models\raiffeisenxml\request\ChanDPCredRaifType\SpecDataAType\LiborRateCodeAType $liborRateCode)
    {
        $this->liborRateCode = $liborRateCode;
        return $this;
    }

    /**
     * Gets as otherMethod
     *
     * Другой метод определения ставки
     *
     * @return string
     */
    public function getOtherMethod()
    {
        return $this->otherMethod;
    }

    /**
     * Sets a new otherMethod
     *
     * Другой метод определения ставки
     *
     * @param string $otherMethod
     * @return static
     */
    public function setOtherMethod($otherMethod)
    {
        $this->otherMethod = $otherMethod;
        return $this;
    }

    /**
     * Gets as percAllowance
     *
     * Размер процентной надбавки
     *
     * @return float
     */
    public function getPercAllowance()
    {
        return $this->percAllowance;
    }

    /**
     * Sets a new percAllowance
     *
     * Размер процентной надбавки
     *
     * @param float $percAllowance
     * @return static
     */
    public function setPercAllowance($percAllowance)
    {
        $this->percAllowance = $percAllowance;
        return $this;
    }

    /**
     * Gets as otherPayments
     *
     * 8.2. Иные платежи, предусмотренные кредитным договором
     *
     * @return string
     */
    public function getOtherPayments()
    {
        return $this->otherPayments;
    }

    /**
     * Sets a new otherPayments
     *
     * 8.2. Иные платежи, предусмотренные кредитным договором
     *
     * @param string $otherPayments
     * @return static
     */
    public function setOtherPayments($otherPayments)
    {
        $this->otherPayments = $otherPayments;
        return $this;
    }

    /**
     * Gets as fillingBase
     *
     * Основания для заполнения
     *
     * @return string
     */
    public function getFillingBase()
    {
        return $this->fillingBase;
    }

    /**
     * Sets a new fillingBase
     *
     * Основания для заполнения
     *
     * @param string $fillingBase
     * @return static
     */
    public function setFillingBase($fillingBase)
    {
        $this->fillingBase = $fillingBase;
        return $this;
    }

    /**
     * Adds as payment
     *
     * Описание графика платежей по возврату основного долга
     *
     * @return static
     * @param \common\models\raiffeisenxml\request\PaymentType $payment
     */
    public function addToPaymentSchedule(\common\models\raiffeisenxml\request\PaymentType $payment)
    {
        $this->paymentSchedule[] = $payment;
        return $this;
    }

    /**
     * isset paymentSchedule
     *
     * Описание графика платежей по возврату основного долга
     *
     * @param int|string $index
     * @return bool
     */
    public function issetPaymentSchedule($index)
    {
        return isset($this->paymentSchedule[$index]);
    }

    /**
     * unset paymentSchedule
     *
     * Описание графика платежей по возврату основного долга
     *
     * @param int|string $index
     * @return void
     */
    public function unsetPaymentSchedule($index)
    {
        unset($this->paymentSchedule[$index]);
    }

    /**
     * Gets as paymentSchedule
     *
     * Описание графика платежей по возврату основного долга
     *
     * @return \common\models\raiffeisenxml\request\PaymentType[]
     */
    public function getPaymentSchedule()
    {
        return $this->paymentSchedule;
    }

    /**
     * Sets a new paymentSchedule
     *
     * Описание графика платежей по возврату основного долга
     *
     * @param \common\models\raiffeisenxml\request\PaymentType[] $paymentSchedule
     * @return static
     */
    public function setPaymentSchedule(array $paymentSchedule)
    {
        $this->paymentSchedule = $paymentSchedule;
        return $this;
    }

    /**
     * Gets as directInv
     *
     * Наличие прямого инвестирования
     *
     * @return bool
     */
    public function getDirectInv()
    {
        return $this->directInv;
    }

    /**
     * Sets a new directInv
     *
     * Наличие прямого инвестирования
     *
     * @param bool $directInv
     * @return static
     */
    public function setDirectInv($directInv)
    {
        $this->directInv = $directInv;
        return $this;
    }

    /**
     * Gets as secSum
     *
     * Сумма залогового или другого обеспечения
     *
     * @return float
     */
    public function getSecSum()
    {
        return $this->secSum;
    }

    /**
     * Sets a new secSum
     *
     * Сумма залогового или другого обеспечения
     *
     * @param float $secSum
     * @return static
     */
    public function setSecSum($secSum)
    {
        $this->secSum = $secSum;
        return $this;
    }

    /**
     * Adds as resCredInfo
     *
     * Информация о привлечении резидентом кредита, предоставленного нерезидентом на синдицированной основе
     *
     * @return static
     * @param \common\models\raiffeisenxml\request\ChanDPCredRaifType\SpecDataAType\ResCredInfoAType $resCredInfo
     */
    public function addToResCredInfo(\common\models\raiffeisenxml\request\ChanDPCredRaifType\SpecDataAType\ResCredInfoAType $resCredInfo)
    {
        $this->resCredInfo[] = $resCredInfo;
        return $this;
    }

    /**
     * isset resCredInfo
     *
     * Информация о привлечении резидентом кредита, предоставленного нерезидентом на синдицированной основе
     *
     * @param int|string $index
     * @return bool
     */
    public function issetResCredInfo($index)
    {
        return isset($this->resCredInfo[$index]);
    }

    /**
     * unset resCredInfo
     *
     * Информация о привлечении резидентом кредита, предоставленного нерезидентом на синдицированной основе
     *
     * @param int|string $index
     * @return void
     */
    public function unsetResCredInfo($index)
    {
        unset($this->resCredInfo[$index]);
    }

    /**
     * Gets as resCredInfo
     *
     * Информация о привлечении резидентом кредита, предоставленного нерезидентом на синдицированной основе
     *
     * @return \common\models\raiffeisenxml\request\ChanDPCredRaifType\SpecDataAType\ResCredInfoAType[]
     */
    public function getResCredInfo()
    {
        return $this->resCredInfo;
    }

    /**
     * Sets a new resCredInfo
     *
     * Информация о привлечении резидентом кредита, предоставленного нерезидентом на синдицированной основе
     *
     * @param \common\models\raiffeisenxml\request\ChanDPCredRaifType\SpecDataAType\ResCredInfoAType[] $resCredInfo
     * @return static
     */
    public function setResCredInfo(array $resCredInfo)
    {
        $this->resCredInfo = $resCredInfo;
        return $this;
    }


}


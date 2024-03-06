<?php

namespace common\models\sbbolxml\request\SalaryDocType;

/**
 * Class representing SalContractAType
 */
class SalContractAType
{

    /**
     * Вид зачисления (цифровое значение вида зачисления)
     *  Возможные значения: заработная плата - 01, стипендия учащимся - 02, пенсия
     *  социальная - 03, пенсия негосударственных пенсионных фондов (кроме НПФ
     *  Сбербанка) - 04, пособия и другие выплаты по безработице - 05, пособия на
     *  детей - 06, прочие выплаты - 07.
     *
     * @property string $admissionValue
     */
    private $admissionValue = null;

    /**
     * Номер договора
     *
     * @property string $contrNum
     */
    private $contrNum = null;

    /**
     * Дата договора
     *
     * @property \DateTime $contrDate
     */
    private $contrDate = null;

    /**
     * Наименование филиала СБРФ, заключившего с клиентом договор о
     *  зачислении денежных средств на счета физических лиц
     *
     * @property string $filialBankName
     */
    private $filialBankName = null;

    /**
     * Номер филиала СБРФ, заключившего с клиентом договор о зачислении
     *  денежных средств на счета физических лиц
     *
     * @property string $filialBankNum
     */
    private $filialBankNum = null;

    /**
     * ИНН банка
     *
     * @property string $bankINN
     */
    private $bankINN = null;

    /**
     * Номер кор. счета
     *
     * @property string $corAcc
     */
    private $corAcc = null;

    /**
     * Gets as admissionValue
     *
     * Вид зачисления (цифровое значение вида зачисления)
     *  Возможные значения: заработная плата - 01, стипендия учащимся - 02, пенсия
     *  социальная - 03, пенсия негосударственных пенсионных фондов (кроме НПФ
     *  Сбербанка) - 04, пособия и другие выплаты по безработице - 05, пособия на
     *  детей - 06, прочие выплаты - 07.
     *
     * @return string
     */
    public function getAdmissionValue()
    {
        return $this->admissionValue;
    }

    /**
     * Sets a new admissionValue
     *
     * Вид зачисления (цифровое значение вида зачисления)
     *  Возможные значения: заработная плата - 01, стипендия учащимся - 02, пенсия
     *  социальная - 03, пенсия негосударственных пенсионных фондов (кроме НПФ
     *  Сбербанка) - 04, пособия и другие выплаты по безработице - 05, пособия на
     *  детей - 06, прочие выплаты - 07.
     *
     * @param string $admissionValue
     * @return static
     */
    public function setAdmissionValue($admissionValue)
    {
        $this->admissionValue = $admissionValue;
        return $this;
    }

    /**
     * Gets as contrNum
     *
     * Номер договора
     *
     * @return string
     */
    public function getContrNum()
    {
        return $this->contrNum;
    }

    /**
     * Sets a new contrNum
     *
     * Номер договора
     *
     * @param string $contrNum
     * @return static
     */
    public function setContrNum($contrNum)
    {
        $this->contrNum = $contrNum;
        return $this;
    }

    /**
     * Gets as contrDate
     *
     * Дата договора
     *
     * @return \DateTime
     */
    public function getContrDate()
    {
        return $this->contrDate;
    }

    /**
     * Sets a new contrDate
     *
     * Дата договора
     *
     * @param \DateTime $contrDate
     * @return static
     */
    public function setContrDate(\DateTime $contrDate)
    {
        $this->contrDate = $contrDate;
        return $this;
    }

    /**
     * Gets as filialBankName
     *
     * Наименование филиала СБРФ, заключившего с клиентом договор о
     *  зачислении денежных средств на счета физических лиц
     *
     * @return string
     */
    public function getFilialBankName()
    {
        return $this->filialBankName;
    }

    /**
     * Sets a new filialBankName
     *
     * Наименование филиала СБРФ, заключившего с клиентом договор о
     *  зачислении денежных средств на счета физических лиц
     *
     * @param string $filialBankName
     * @return static
     */
    public function setFilialBankName($filialBankName)
    {
        $this->filialBankName = $filialBankName;
        return $this;
    }

    /**
     * Gets as filialBankNum
     *
     * Номер филиала СБРФ, заключившего с клиентом договор о зачислении
     *  денежных средств на счета физических лиц
     *
     * @return string
     */
    public function getFilialBankNum()
    {
        return $this->filialBankNum;
    }

    /**
     * Sets a new filialBankNum
     *
     * Номер филиала СБРФ, заключившего с клиентом договор о зачислении
     *  денежных средств на счета физических лиц
     *
     * @param string $filialBankNum
     * @return static
     */
    public function setFilialBankNum($filialBankNum)
    {
        $this->filialBankNum = $filialBankNum;
        return $this;
    }

    /**
     * Gets as bankINN
     *
     * ИНН банка
     *
     * @return string
     */
    public function getBankINN()
    {
        return $this->bankINN;
    }

    /**
     * Sets a new bankINN
     *
     * ИНН банка
     *
     * @param string $bankINN
     * @return static
     */
    public function setBankINN($bankINN)
    {
        $this->bankINN = $bankINN;
        return $this;
    }

    /**
     * Gets as corAcc
     *
     * Номер кор. счета
     *
     * @return string
     */
    public function getCorAcc()
    {
        return $this->corAcc;
    }

    /**
     * Sets a new corAcc
     *
     * Номер кор. счета
     *
     * @param string $corAcc
     * @return static
     */
    public function setCorAcc($corAcc)
    {
        $this->corAcc = $corAcc;
        return $this;
    }


}


<?php

namespace common\models\raiffeisenxml\request\AccreditiveRubRaifType;

/**
 * Class representing ExecutiveBankAType
 */
class ExecutiveBankAType
{

    /**
     * Возможные значения: "ЗАО Райффайзен банк, Москва", "другое"
     *
     * @property string $executiveBank
     */
    private $executiveBank = null;

    /**
     * Возможные значения: "Комиссии банка Плательщика оплачиваются Плательщиком, комиссии банка Получателя оплачиваются Получателем", "другое".
     *
     * @property string $commission
     */
    private $commission = null;

    /**
     * Допустимые значения:
     *  "запрещены", "разрешены"
     *
     * @property string $partialPayments
     */
    private $partialPayments = null;

    /**
     * Возможные значения: "не требуется", "требуется"
     *
     * @property string $confirmation
     */
    private $confirmation = null;

    /**
     * Наименование другого исполняющего Банка.
     *
     * @property string $anotherExBank
     */
    private $anotherExBank = null;

    /**
     * Другой вариант оплаты комиссии
     *
     * @property string $anotherComission
     */
    private $anotherComission = null;

    /**
     * Способ исполнения
     *
     * @property \common\models\raiffeisenxml\request\AccreditiveRubRaifType\ExecutiveBankAType\ExecutionAType $execution
     */
    private $execution = null;

    /**
     * Gets as executiveBank
     *
     * Возможные значения: "ЗАО Райффайзен банк, Москва", "другое"
     *
     * @return string
     */
    public function getExecutiveBank()
    {
        return $this->executiveBank;
    }

    /**
     * Sets a new executiveBank
     *
     * Возможные значения: "ЗАО Райффайзен банк, Москва", "другое"
     *
     * @param string $executiveBank
     * @return static
     */
    public function setExecutiveBank($executiveBank)
    {
        $this->executiveBank = $executiveBank;
        return $this;
    }

    /**
     * Gets as commission
     *
     * Возможные значения: "Комиссии банка Плательщика оплачиваются Плательщиком, комиссии банка Получателя оплачиваются Получателем", "другое".
     *
     * @return string
     */
    public function getCommission()
    {
        return $this->commission;
    }

    /**
     * Sets a new commission
     *
     * Возможные значения: "Комиссии банка Плательщика оплачиваются Плательщиком, комиссии банка Получателя оплачиваются Получателем", "другое".
     *
     * @param string $commission
     * @return static
     */
    public function setCommission($commission)
    {
        $this->commission = $commission;
        return $this;
    }

    /**
     * Gets as partialPayments
     *
     * Допустимые значения:
     *  "запрещены", "разрешены"
     *
     * @return string
     */
    public function getPartialPayments()
    {
        return $this->partialPayments;
    }

    /**
     * Sets a new partialPayments
     *
     * Допустимые значения:
     *  "запрещены", "разрешены"
     *
     * @param string $partialPayments
     * @return static
     */
    public function setPartialPayments($partialPayments)
    {
        $this->partialPayments = $partialPayments;
        return $this;
    }

    /**
     * Gets as confirmation
     *
     * Возможные значения: "не требуется", "требуется"
     *
     * @return string
     */
    public function getConfirmation()
    {
        return $this->confirmation;
    }

    /**
     * Sets a new confirmation
     *
     * Возможные значения: "не требуется", "требуется"
     *
     * @param string $confirmation
     * @return static
     */
    public function setConfirmation($confirmation)
    {
        $this->confirmation = $confirmation;
        return $this;
    }

    /**
     * Gets as anotherExBank
     *
     * Наименование другого исполняющего Банка.
     *
     * @return string
     */
    public function getAnotherExBank()
    {
        return $this->anotherExBank;
    }

    /**
     * Sets a new anotherExBank
     *
     * Наименование другого исполняющего Банка.
     *
     * @param string $anotherExBank
     * @return static
     */
    public function setAnotherExBank($anotherExBank)
    {
        $this->anotherExBank = $anotherExBank;
        return $this;
    }

    /**
     * Gets as anotherComission
     *
     * Другой вариант оплаты комиссии
     *
     * @return string
     */
    public function getAnotherComission()
    {
        return $this->anotherComission;
    }

    /**
     * Sets a new anotherComission
     *
     * Другой вариант оплаты комиссии
     *
     * @param string $anotherComission
     * @return static
     */
    public function setAnotherComission($anotherComission)
    {
        $this->anotherComission = $anotherComission;
        return $this;
    }

    /**
     * Gets as execution
     *
     * Способ исполнения
     *
     * @return \common\models\raiffeisenxml\request\AccreditiveRubRaifType\ExecutiveBankAType\ExecutionAType
     */
    public function getExecution()
    {
        return $this->execution;
    }

    /**
     * Sets a new execution
     *
     * Способ исполнения
     *
     * @param \common\models\raiffeisenxml\request\AccreditiveRubRaifType\ExecutiveBankAType\ExecutionAType $execution
     * @return static
     */
    public function setExecution(\common\models\raiffeisenxml\request\AccreditiveRubRaifType\ExecutiveBankAType\ExecutionAType $execution)
    {
        $this->execution = $execution;
        return $this;
    }


}


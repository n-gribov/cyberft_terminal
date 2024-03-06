<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing DepositType
 *
 * Данные по депозиту
 * XSD Type: DepositType
 */
class DepositType
{

    /**
     * Вид вклада (депозита), из карточки депозита
     *
     * @property string $depositType
     */
    private $depositType = null;

    /**
     * Специальные условия. Возможные значения:
     *  «Нет»
     *  «5%»
     *  «10%»
     *
     * @property string $depositConditions
     */
    private $depositConditions = null;

    /**
     * Сумма вклада (депозита)
     *
     * @property float $depositSum
     */
    private $depositSum = null;

    /**
     * Начало диапазона сумм
     *
     * @property float $depositSumFrom
     */
    private $depositSumFrom = null;

    /**
     * Конец диапазона сумм по депозиту
     *
     * @property float $depositSumTo
     */
    private $depositSumTo = null;

    /**
     * Цифровой код валюты суммы вклада, из карточки депозита
     *
     * @property string $depositSumCurrCode
     */
    private $depositSumCurrCode = null;

    /**
     * Буквенный код валюты суммы вклада, из карточки депозита
     *
     * @property string $depositSumCurrCodeISO
     */
    private $depositSumCurrCodeISO = null;

    /**
     * Дата начала срока вклада (депозита)
     *
     * @property \DateTime $depositStartDate
     */
    private $depositStartDate = null;

    /**
     * Дата окончания депозита/НСО
     *
     * @property \DateTime $depositEndDate
     */
    private $depositEndDate = null;

    /**
     * Срок вклада/депозита/НСО
     *
     * @property integer $depositTerm
     */
    private $depositTerm = null;

    /**
     * Начало диапазона срока вклада
     *
     * @property integer $depositTermFrom
     */
    private $depositTermFrom = null;

    /**
     * Окончание диапазона срока вклада
     *
     * @property integer $depositTermTo
     */
    private $depositTermTo = null;

    /**
     * Срок перечисления вклада
     *
     * @property \DateTime $depositTermDateReq
     */
    private $depositTermDateReq = null;

    /**
     * Процентная ставка по договору
     *
     * @property float $interestRate
     */
    private $interestRate = null;

    /**
     * Дата отзыва вклада (депозита)
     *
     * @property \DateTime $depositRecallDate
     */
    private $depositRecallDate = null;

    /**
     * Процентная ставка при досрочном расторжении, из карточки депозита
     *
     * @property float $interestRateOnClose
     */
    private $interestRateOnClose = null;

    /**
     * Gets as depositType
     *
     * Вид вклада (депозита), из карточки депозита
     *
     * @return string
     */
    public function getDepositType()
    {
        return $this->depositType;
    }

    /**
     * Sets a new depositType
     *
     * Вид вклада (депозита), из карточки депозита
     *
     * @param string $depositType
     * @return static
     */
    public function setDepositType($depositType)
    {
        $this->depositType = $depositType;
        return $this;
    }

    /**
     * Gets as depositConditions
     *
     * Специальные условия. Возможные значения:
     *  «Нет»
     *  «5%»
     *  «10%»
     *
     * @return string
     */
    public function getDepositConditions()
    {
        return $this->depositConditions;
    }

    /**
     * Sets a new depositConditions
     *
     * Специальные условия. Возможные значения:
     *  «Нет»
     *  «5%»
     *  «10%»
     *
     * @param string $depositConditions
     * @return static
     */
    public function setDepositConditions($depositConditions)
    {
        $this->depositConditions = $depositConditions;
        return $this;
    }

    /**
     * Gets as depositSum
     *
     * Сумма вклада (депозита)
     *
     * @return float
     */
    public function getDepositSum()
    {
        return $this->depositSum;
    }

    /**
     * Sets a new depositSum
     *
     * Сумма вклада (депозита)
     *
     * @param float $depositSum
     * @return static
     */
    public function setDepositSum($depositSum)
    {
        $this->depositSum = $depositSum;
        return $this;
    }

    /**
     * Gets as depositSumFrom
     *
     * Начало диапазона сумм
     *
     * @return float
     */
    public function getDepositSumFrom()
    {
        return $this->depositSumFrom;
    }

    /**
     * Sets a new depositSumFrom
     *
     * Начало диапазона сумм
     *
     * @param float $depositSumFrom
     * @return static
     */
    public function setDepositSumFrom($depositSumFrom)
    {
        $this->depositSumFrom = $depositSumFrom;
        return $this;
    }

    /**
     * Gets as depositSumTo
     *
     * Конец диапазона сумм по депозиту
     *
     * @return float
     */
    public function getDepositSumTo()
    {
        return $this->depositSumTo;
    }

    /**
     * Sets a new depositSumTo
     *
     * Конец диапазона сумм по депозиту
     *
     * @param float $depositSumTo
     * @return static
     */
    public function setDepositSumTo($depositSumTo)
    {
        $this->depositSumTo = $depositSumTo;
        return $this;
    }

    /**
     * Gets as depositSumCurrCode
     *
     * Цифровой код валюты суммы вклада, из карточки депозита
     *
     * @return string
     */
    public function getDepositSumCurrCode()
    {
        return $this->depositSumCurrCode;
    }

    /**
     * Sets a new depositSumCurrCode
     *
     * Цифровой код валюты суммы вклада, из карточки депозита
     *
     * @param string $depositSumCurrCode
     * @return static
     */
    public function setDepositSumCurrCode($depositSumCurrCode)
    {
        $this->depositSumCurrCode = $depositSumCurrCode;
        return $this;
    }

    /**
     * Gets as depositSumCurrCodeISO
     *
     * Буквенный код валюты суммы вклада, из карточки депозита
     *
     * @return string
     */
    public function getDepositSumCurrCodeISO()
    {
        return $this->depositSumCurrCodeISO;
    }

    /**
     * Sets a new depositSumCurrCodeISO
     *
     * Буквенный код валюты суммы вклада, из карточки депозита
     *
     * @param string $depositSumCurrCodeISO
     * @return static
     */
    public function setDepositSumCurrCodeISO($depositSumCurrCodeISO)
    {
        $this->depositSumCurrCodeISO = $depositSumCurrCodeISO;
        return $this;
    }

    /**
     * Gets as depositStartDate
     *
     * Дата начала срока вклада (депозита)
     *
     * @return \DateTime
     */
    public function getDepositStartDate()
    {
        return $this->depositStartDate;
    }

    /**
     * Sets a new depositStartDate
     *
     * Дата начала срока вклада (депозита)
     *
     * @param \DateTime $depositStartDate
     * @return static
     */
    public function setDepositStartDate(\DateTime $depositStartDate)
    {
        $this->depositStartDate = $depositStartDate;
        return $this;
    }

    /**
     * Gets as depositEndDate
     *
     * Дата окончания депозита/НСО
     *
     * @return \DateTime
     */
    public function getDepositEndDate()
    {
        return $this->depositEndDate;
    }

    /**
     * Sets a new depositEndDate
     *
     * Дата окончания депозита/НСО
     *
     * @param \DateTime $depositEndDate
     * @return static
     */
    public function setDepositEndDate(\DateTime $depositEndDate)
    {
        $this->depositEndDate = $depositEndDate;
        return $this;
    }

    /**
     * Gets as depositTerm
     *
     * Срок вклада/депозита/НСО
     *
     * @return integer
     */
    public function getDepositTerm()
    {
        return $this->depositTerm;
    }

    /**
     * Sets a new depositTerm
     *
     * Срок вклада/депозита/НСО
     *
     * @param integer $depositTerm
     * @return static
     */
    public function setDepositTerm($depositTerm)
    {
        $this->depositTerm = $depositTerm;
        return $this;
    }

    /**
     * Gets as depositTermFrom
     *
     * Начало диапазона срока вклада
     *
     * @return integer
     */
    public function getDepositTermFrom()
    {
        return $this->depositTermFrom;
    }

    /**
     * Sets a new depositTermFrom
     *
     * Начало диапазона срока вклада
     *
     * @param integer $depositTermFrom
     * @return static
     */
    public function setDepositTermFrom($depositTermFrom)
    {
        $this->depositTermFrom = $depositTermFrom;
        return $this;
    }

    /**
     * Gets as depositTermTo
     *
     * Окончание диапазона срока вклада
     *
     * @return integer
     */
    public function getDepositTermTo()
    {
        return $this->depositTermTo;
    }

    /**
     * Sets a new depositTermTo
     *
     * Окончание диапазона срока вклада
     *
     * @param integer $depositTermTo
     * @return static
     */
    public function setDepositTermTo($depositTermTo)
    {
        $this->depositTermTo = $depositTermTo;
        return $this;
    }

    /**
     * Gets as depositTermDateReq
     *
     * Срок перечисления вклада
     *
     * @return \DateTime
     */
    public function getDepositTermDateReq()
    {
        return $this->depositTermDateReq;
    }

    /**
     * Sets a new depositTermDateReq
     *
     * Срок перечисления вклада
     *
     * @param \DateTime $depositTermDateReq
     * @return static
     */
    public function setDepositTermDateReq(\DateTime $depositTermDateReq)
    {
        $this->depositTermDateReq = $depositTermDateReq;
        return $this;
    }

    /**
     * Gets as interestRate
     *
     * Процентная ставка по договору
     *
     * @return float
     */
    public function getInterestRate()
    {
        return $this->interestRate;
    }

    /**
     * Sets a new interestRate
     *
     * Процентная ставка по договору
     *
     * @param float $interestRate
     * @return static
     */
    public function setInterestRate($interestRate)
    {
        $this->interestRate = $interestRate;
        return $this;
    }

    /**
     * Gets as depositRecallDate
     *
     * Дата отзыва вклада (депозита)
     *
     * @return \DateTime
     */
    public function getDepositRecallDate()
    {
        return $this->depositRecallDate;
    }

    /**
     * Sets a new depositRecallDate
     *
     * Дата отзыва вклада (депозита)
     *
     * @param \DateTime $depositRecallDate
     * @return static
     */
    public function setDepositRecallDate(\DateTime $depositRecallDate)
    {
        $this->depositRecallDate = $depositRecallDate;
        return $this;
    }

    /**
     * Gets as interestRateOnClose
     *
     * Процентная ставка при досрочном расторжении, из карточки депозита
     *
     * @return float
     */
    public function getInterestRateOnClose()
    {
        return $this->interestRateOnClose;
    }

    /**
     * Sets a new interestRateOnClose
     *
     * Процентная ставка при досрочном расторжении, из карточки депозита
     *
     * @param float $interestRateOnClose
     * @return static
     */
    public function setInterestRateOnClose($interestRateOnClose)
    {
        $this->interestRateOnClose = $interestRateOnClose;
        return $this;
    }


}


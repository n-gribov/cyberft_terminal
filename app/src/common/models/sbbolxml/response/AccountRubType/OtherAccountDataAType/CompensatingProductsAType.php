<?php

namespace common\models\sbbolxml\response\AccountRubType\OtherAccountDataAType;

/**
 * Class representing CompensatingProductsAType
 */
class CompensatingProductsAType
{

    /**
     * Подключение счета к договору компенсационного продукта
     *
     * @property boolean $accContract
     */
    private $accContract = null;

    /**
     * Залючено допоонительное соглашение по компенсационному продукту "Начисление процентов на сумму кредитного остатка"
     *
     * @property boolean $creditLimitSum
     */
    private $creditLimitSum = null;

    /**
     * Залючено допоонительное соглашение по компенсационному продукту "Начисление процентов на совокупный (среднехронологический) остаток"
     *
     * @property boolean $middleLimit
     */
    private $middleLimit = null;

    /**
     * Залючено допоонительное соглашение по компенсационному продукту "Начисление процентов на совокупный неснижаемый остаток"
     *
     * @property boolean $minimumBalance
     */
    private $minimumBalance = null;

    /**
     * Залючено допоонительное соглашение по компенсационному продукту "Начисление процентов на сумму остатка овердрафтного кредита"
     *
     * @property boolean $overdraft
     */
    private $overdraft = null;

    /**
     * Залючено допоонительное соглашение по компенсационному продукту "Начисление процентов на сумму кредитного остатка и остатка овердрафтного кредита"
     *
     * @property boolean $creditLimit
     */
    private $creditLimit = null;

    /**
     * Gets as accContract
     *
     * Подключение счета к договору компенсационного продукта
     *
     * @return boolean
     */
    public function getAccContract()
    {
        return $this->accContract;
    }

    /**
     * Sets a new accContract
     *
     * Подключение счета к договору компенсационного продукта
     *
     * @param boolean $accContract
     * @return static
     */
    public function setAccContract($accContract)
    {
        $this->accContract = $accContract;
        return $this;
    }

    /**
     * Gets as creditLimitSum
     *
     * Залючено допоонительное соглашение по компенсационному продукту "Начисление процентов на сумму кредитного остатка"
     *
     * @return boolean
     */
    public function getCreditLimitSum()
    {
        return $this->creditLimitSum;
    }

    /**
     * Sets a new creditLimitSum
     *
     * Залючено допоонительное соглашение по компенсационному продукту "Начисление процентов на сумму кредитного остатка"
     *
     * @param boolean $creditLimitSum
     * @return static
     */
    public function setCreditLimitSum($creditLimitSum)
    {
        $this->creditLimitSum = $creditLimitSum;
        return $this;
    }

    /**
     * Gets as middleLimit
     *
     * Залючено допоонительное соглашение по компенсационному продукту "Начисление процентов на совокупный (среднехронологический) остаток"
     *
     * @return boolean
     */
    public function getMiddleLimit()
    {
        return $this->middleLimit;
    }

    /**
     * Sets a new middleLimit
     *
     * Залючено допоонительное соглашение по компенсационному продукту "Начисление процентов на совокупный (среднехронологический) остаток"
     *
     * @param boolean $middleLimit
     * @return static
     */
    public function setMiddleLimit($middleLimit)
    {
        $this->middleLimit = $middleLimit;
        return $this;
    }

    /**
     * Gets as minimumBalance
     *
     * Залючено допоонительное соглашение по компенсационному продукту "Начисление процентов на совокупный неснижаемый остаток"
     *
     * @return boolean
     */
    public function getMinimumBalance()
    {
        return $this->minimumBalance;
    }

    /**
     * Sets a new minimumBalance
     *
     * Залючено допоонительное соглашение по компенсационному продукту "Начисление процентов на совокупный неснижаемый остаток"
     *
     * @param boolean $minimumBalance
     * @return static
     */
    public function setMinimumBalance($minimumBalance)
    {
        $this->minimumBalance = $minimumBalance;
        return $this;
    }

    /**
     * Gets as overdraft
     *
     * Залючено допоонительное соглашение по компенсационному продукту "Начисление процентов на сумму остатка овердрафтного кредита"
     *
     * @return boolean
     */
    public function getOverdraft()
    {
        return $this->overdraft;
    }

    /**
     * Sets a new overdraft
     *
     * Залючено допоонительное соглашение по компенсационному продукту "Начисление процентов на сумму остатка овердрафтного кредита"
     *
     * @param boolean $overdraft
     * @return static
     */
    public function setOverdraft($overdraft)
    {
        $this->overdraft = $overdraft;
        return $this;
    }

    /**
     * Gets as creditLimit
     *
     * Залючено допоонительное соглашение по компенсационному продукту "Начисление процентов на сумму кредитного остатка и остатка овердрафтного кредита"
     *
     * @return boolean
     */
    public function getCreditLimit()
    {
        return $this->creditLimit;
    }

    /**
     * Sets a new creditLimit
     *
     * Залючено допоонительное соглашение по компенсационному продукту "Начисление процентов на сумму кредитного остатка и остатка овердрафтного кредита"
     *
     * @param boolean $creditLimit
     * @return static
     */
    public function setCreditLimit($creditLimit)
    {
        $this->creditLimit = $creditLimit;
        return $this;
    }


}


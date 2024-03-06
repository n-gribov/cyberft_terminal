<?php

namespace common\models\sbbolxml\request\AdmPaymentTemplateType;

/**
 * Class representing BudgetAType
 */
class BudgetAType
{

    /**
     * Лицевой счет бюджетополучателя
     *
     * @property string $budgetAccount
     */
    private $budgetAccount = null;

    /**
     * ИНН бюджетополучателя
     *
     * @property string $budgetInn
     */
    private $budgetInn = null;

    /**
     * КБК бюджетополучателя
     *
     * @property string $budgetKbk
     */
    private $budgetKbk = null;

    /**
     * КПП бюджетополучателя
     *
     * @property string $budgetKpp
     */
    private $budgetKpp = null;

    /**
     * Наименование бюджетополучателя
     *
     * @property string $budgetName
     */
    private $budgetName = null;

    /**
     * ОКТМО бюджетополучателя
     *
     * @property string $budgetOktmo
     */
    private $budgetOktmo = null;

    /**
     * Gets as budgetAccount
     *
     * Лицевой счет бюджетополучателя
     *
     * @return string
     */
    public function getBudgetAccount()
    {
        return $this->budgetAccount;
    }

    /**
     * Sets a new budgetAccount
     *
     * Лицевой счет бюджетополучателя
     *
     * @param string $budgetAccount
     * @return static
     */
    public function setBudgetAccount($budgetAccount)
    {
        $this->budgetAccount = $budgetAccount;
        return $this;
    }

    /**
     * Gets as budgetInn
     *
     * ИНН бюджетополучателя
     *
     * @return string
     */
    public function getBudgetInn()
    {
        return $this->budgetInn;
    }

    /**
     * Sets a new budgetInn
     *
     * ИНН бюджетополучателя
     *
     * @param string $budgetInn
     * @return static
     */
    public function setBudgetInn($budgetInn)
    {
        $this->budgetInn = $budgetInn;
        return $this;
    }

    /**
     * Gets as budgetKbk
     *
     * КБК бюджетополучателя
     *
     * @return string
     */
    public function getBudgetKbk()
    {
        return $this->budgetKbk;
    }

    /**
     * Sets a new budgetKbk
     *
     * КБК бюджетополучателя
     *
     * @param string $budgetKbk
     * @return static
     */
    public function setBudgetKbk($budgetKbk)
    {
        $this->budgetKbk = $budgetKbk;
        return $this;
    }

    /**
     * Gets as budgetKpp
     *
     * КПП бюджетополучателя
     *
     * @return string
     */
    public function getBudgetKpp()
    {
        return $this->budgetKpp;
    }

    /**
     * Sets a new budgetKpp
     *
     * КПП бюджетополучателя
     *
     * @param string $budgetKpp
     * @return static
     */
    public function setBudgetKpp($budgetKpp)
    {
        $this->budgetKpp = $budgetKpp;
        return $this;
    }

    /**
     * Gets as budgetName
     *
     * Наименование бюджетополучателя
     *
     * @return string
     */
    public function getBudgetName()
    {
        return $this->budgetName;
    }

    /**
     * Sets a new budgetName
     *
     * Наименование бюджетополучателя
     *
     * @param string $budgetName
     * @return static
     */
    public function setBudgetName($budgetName)
    {
        $this->budgetName = $budgetName;
        return $this;
    }

    /**
     * Gets as budgetOktmo
     *
     * ОКТМО бюджетополучателя
     *
     * @return string
     */
    public function getBudgetOktmo()
    {
        return $this->budgetOktmo;
    }

    /**
     * Sets a new budgetOktmo
     *
     * ОКТМО бюджетополучателя
     *
     * @param string $budgetOktmo
     * @return static
     */
    public function setBudgetOktmo($budgetOktmo)
    {
        $this->budgetOktmo = $budgetOktmo;
        return $this;
    }


}


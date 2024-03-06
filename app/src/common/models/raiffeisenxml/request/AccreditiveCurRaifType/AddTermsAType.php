<?php

namespace common\models\raiffeisenxml\request\AccreditiveCurRaifType;

/**
 * Class representing AddTermsAType
 */
class AddTermsAType
{

    /**
     * Дополнительные условия
     *
     * @property string $addInfo
     */
    private $addInfo = null;

    /**
     * Расходы по аккредитиву просим списать с нашего счета у Вас
     *
     * @property \common\models\raiffeisenxml\request\AccreditiveCurRaifType\AddTermsAType\AccrCostsAType $accrCosts
     */
    private $accrCosts = null;

    /**
     * Сумму платежа по аккредитиву просим списать с нашего счета у Вас
     *
     * @property \common\models\raiffeisenxml\request\AccountType $accrPaymentAcc
     */
    private $accrPaymentAcc = null;

    /**
     * Покрытие по аккредитиву просим списать с нашего счета у Вас
     *
     * @property \common\models\raiffeisenxml\request\AccountType $accrCoverAcc
     */
    private $accrCoverAcc = null;

    /**
     * Комментарии
     *
     * @property string $comments
     */
    private $comments = null;

    /**
     * Gets as addInfo
     *
     * Дополнительные условия
     *
     * @return string
     */
    public function getAddInfo()
    {
        return $this->addInfo;
    }

    /**
     * Sets a new addInfo
     *
     * Дополнительные условия
     *
     * @param string $addInfo
     * @return static
     */
    public function setAddInfo($addInfo)
    {
        $this->addInfo = $addInfo;
        return $this;
    }

    /**
     * Gets as accrCosts
     *
     * Расходы по аккредитиву просим списать с нашего счета у Вас
     *
     * @return \common\models\raiffeisenxml\request\AccreditiveCurRaifType\AddTermsAType\AccrCostsAType
     */
    public function getAccrCosts()
    {
        return $this->accrCosts;
    }

    /**
     * Sets a new accrCosts
     *
     * Расходы по аккредитиву просим списать с нашего счета у Вас
     *
     * @param \common\models\raiffeisenxml\request\AccreditiveCurRaifType\AddTermsAType\AccrCostsAType $accrCosts
     * @return static
     */
    public function setAccrCosts(\common\models\raiffeisenxml\request\AccreditiveCurRaifType\AddTermsAType\AccrCostsAType $accrCosts)
    {
        $this->accrCosts = $accrCosts;
        return $this;
    }

    /**
     * Gets as accrPaymentAcc
     *
     * Сумму платежа по аккредитиву просим списать с нашего счета у Вас
     *
     * @return \common\models\raiffeisenxml\request\AccountType
     */
    public function getAccrPaymentAcc()
    {
        return $this->accrPaymentAcc;
    }

    /**
     * Sets a new accrPaymentAcc
     *
     * Сумму платежа по аккредитиву просим списать с нашего счета у Вас
     *
     * @param \common\models\raiffeisenxml\request\AccountType $accrPaymentAcc
     * @return static
     */
    public function setAccrPaymentAcc(\common\models\raiffeisenxml\request\AccountType $accrPaymentAcc)
    {
        $this->accrPaymentAcc = $accrPaymentAcc;
        return $this;
    }

    /**
     * Gets as accrCoverAcc
     *
     * Покрытие по аккредитиву просим списать с нашего счета у Вас
     *
     * @return \common\models\raiffeisenxml\request\AccountType
     */
    public function getAccrCoverAcc()
    {
        return $this->accrCoverAcc;
    }

    /**
     * Sets a new accrCoverAcc
     *
     * Покрытие по аккредитиву просим списать с нашего счета у Вас
     *
     * @param \common\models\raiffeisenxml\request\AccountType $accrCoverAcc
     * @return static
     */
    public function setAccrCoverAcc(\common\models\raiffeisenxml\request\AccountType $accrCoverAcc)
    {
        $this->accrCoverAcc = $accrCoverAcc;
        return $this;
    }

    /**
     * Gets as comments
     *
     * Комментарии
     *
     * @return string
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Sets a new comments
     *
     * Комментарии
     *
     * @param string $comments
     * @return static
     */
    public function setComments($comments)
    {
        $this->comments = $comments;
        return $this;
    }


}


<?php

namespace common\models\raiffeisenxml\request\AccreditiveCurRaifType\AddTermsAType;

/**
 * Class representing AccrCostsAType
 */
class AccrCostsAType
{

    /**
     * Счет1
     *
     * @property \common\models\raiffeisenxml\request\AccountType $acc1
     */
    private $acc1 = null;

    /**
     * Счет2
     *
     * @property \common\models\raiffeisenxml\request\AccountType $acc2
     */
    private $acc2 = null;

    /**
     * Счет3
     *
     * @property \common\models\raiffeisenxml\request\AccountType $acc3
     */
    private $acc3 = null;

    /**
     * Gets as acc1
     *
     * Счет1
     *
     * @return \common\models\raiffeisenxml\request\AccountType
     */
    public function getAcc1()
    {
        return $this->acc1;
    }

    /**
     * Sets a new acc1
     *
     * Счет1
     *
     * @param \common\models\raiffeisenxml\request\AccountType $acc1
     * @return static
     */
    public function setAcc1(\common\models\raiffeisenxml\request\AccountType $acc1)
    {
        $this->acc1 = $acc1;
        return $this;
    }

    /**
     * Gets as acc2
     *
     * Счет2
     *
     * @return \common\models\raiffeisenxml\request\AccountType
     */
    public function getAcc2()
    {
        return $this->acc2;
    }

    /**
     * Sets a new acc2
     *
     * Счет2
     *
     * @param \common\models\raiffeisenxml\request\AccountType $acc2
     * @return static
     */
    public function setAcc2(\common\models\raiffeisenxml\request\AccountType $acc2)
    {
        $this->acc2 = $acc2;
        return $this;
    }

    /**
     * Gets as acc3
     *
     * Счет3
     *
     * @return \common\models\raiffeisenxml\request\AccountType
     */
    public function getAcc3()
    {
        return $this->acc3;
    }

    /**
     * Sets a new acc3
     *
     * Счет3
     *
     * @param \common\models\raiffeisenxml\request\AccountType $acc3
     * @return static
     */
    public function setAcc3(\common\models\raiffeisenxml\request\AccountType $acc3)
    {
        $this->acc3 = $acc3;
        return $this;
    }


}


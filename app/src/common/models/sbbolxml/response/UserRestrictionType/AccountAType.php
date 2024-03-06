<?php

namespace common\models\sbbolxml\response\UserRestrictionType;

/**
 * Class representing AccountAType
 */
class AccountAType
{

    /**
     * Идентификатор счета
     *
     * @property string $accountId
     */
    private $accountId = null;

    /**
     * Номер счёта зачисления
     *
     * @property string $accNum
     */
    private $accNum = null;

    /**
     * Идентификатор счета
     *
     * @property string $restrictionAccountId
     */
    private $restrictionAccountId = null;

    /**
     * Gets as accountId
     *
     * Идентификатор счета
     *
     * @return string
     */
    public function getAccountId()
    {
        return $this->accountId;
    }

    /**
     * Sets a new accountId
     *
     * Идентификатор счета
     *
     * @param string $accountId
     * @return static
     */
    public function setAccountId($accountId)
    {
        $this->accountId = $accountId;
        return $this;
    }

    /**
     * Gets as accNum
     *
     * Номер счёта зачисления
     *
     * @return string
     */
    public function getAccNum()
    {
        return $this->accNum;
    }

    /**
     * Sets a new accNum
     *
     * Номер счёта зачисления
     *
     * @param string $accNum
     * @return static
     */
    public function setAccNum($accNum)
    {
        $this->accNum = $accNum;
        return $this;
    }

    /**
     * Gets as restrictionAccountId
     *
     * Идентификатор счета
     *
     * @return string
     */
    public function getRestrictionAccountId()
    {
        return $this->restrictionAccountId;
    }

    /**
     * Sets a new restrictionAccountId
     *
     * Идентификатор счета
     *
     * @param string $restrictionAccountId
     * @return static
     */
    public function setRestrictionAccountId($restrictionAccountId)
    {
        $this->restrictionAccountId = $restrictionAccountId;
        return $this;
    }


}


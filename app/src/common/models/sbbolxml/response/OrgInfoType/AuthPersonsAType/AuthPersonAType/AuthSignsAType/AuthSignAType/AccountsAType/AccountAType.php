<?php

namespace common\models\sbbolxml\response\OrgInfoType\AuthPersonsAType\AuthPersonAType\AuthSignsAType\AuthSignAType\AccountsAType;

/**
 * Class representing AccountAType
 */
class AccountAType
{

    /**
     * Идентификатор
     *  счета
     *
     * @property string $accountId
     */
    private $accountId = null;

    /**
     * Сумма
     *  ограничения по
     *  счету для
     *  данного уполномоченного
     *  лица
     *
     * @property \common\models\sbbolxml\response\OrgInfoType\AuthPersonsAType\AuthPersonAType\AuthSignsAType\AuthSignAType\AccountsAType\AccountAType\AmmountAType $ammount
     */
    private $ammount = null;

    /**
     * Gets as accountId
     *
     * Идентификатор
     *  счета
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
     * Идентификатор
     *  счета
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
     * Gets as ammount
     *
     * Сумма
     *  ограничения по
     *  счету для
     *  данного уполномоченного
     *  лица
     *
     * @return \common\models\sbbolxml\response\OrgInfoType\AuthPersonsAType\AuthPersonAType\AuthSignsAType\AuthSignAType\AccountsAType\AccountAType\AmmountAType
     */
    public function getAmmount()
    {
        return $this->ammount;
    }

    /**
     * Sets a new ammount
     *
     * Сумма
     *  ограничения по
     *  счету для
     *  данного уполномоченного
     *  лица
     *
     * @param \common\models\sbbolxml\response\OrgInfoType\AuthPersonsAType\AuthPersonAType\AuthSignsAType\AuthSignAType\AccountsAType\AccountAType\AmmountAType $ammount
     * @return static
     */
    public function setAmmount(\common\models\sbbolxml\response\OrgInfoType\AuthPersonsAType\AuthPersonAType\AuthSignsAType\AuthSignAType\AccountsAType\AccountAType\AmmountAType $ammount)
    {
        $this->ammount = $ammount;
        return $this;
    }


}


<?php

namespace common\models\sbbolxml\response\CardPermBalanceType;

/**
 * Class representing CardPermBalanceInfoAType
 */
class CardPermBalanceInfoAType
{

    /**
     * Номер договора
     *
     * @property string $docNum
     */
    private $docNum = null;

    /**
     * Дата заключения договора
     *
     * @property \DateTime $docDate
     */
    private $docDate = null;

    /**
     * Статус НСО
     *
     * @property string $status
     */
    private $status = null;

    /**
     * Статус договора
     *
     * @property string $contractStatus
     */
    private $contractStatus = null;

    /**
     * Идентификатор заявления на открытие
     *
     * @property string $depositId
     */
    private $depositId = null;

    /**
     * Идентификатор первоначальной карточки договора НСО
     *
     * @property string $initialCardGuid
     */
    private $initialCardGuid = null;

    /**
     * Счет поддержания НСО
     *
     * @property string $pbAccount
     */
    private $pbAccount = null;

    /**
     * Признак актуальности договора
     *
     * @property boolean $actual
     */
    private $actual = null;

    /**
     * Комментарий
     *
     * @property string $commentary
     */
    private $commentary = null;

    /**
     * Данные по продукту
     *
     * @property \common\models\sbbolxml\response\PermBalanceType $permBalance
     */
    private $permBalance = null;

    /**
     * Счет возврата вклада и %%
     *
     * @property \common\models\sbbolxml\response\AccountReturnType $reqAccount
     */
    private $reqAccount = null;

    /**
     * Gets as docNum
     *
     * Номер договора
     *
     * @return string
     */
    public function getDocNum()
    {
        return $this->docNum;
    }

    /**
     * Sets a new docNum
     *
     * Номер договора
     *
     * @param string $docNum
     * @return static
     */
    public function setDocNum($docNum)
    {
        $this->docNum = $docNum;
        return $this;
    }

    /**
     * Gets as docDate
     *
     * Дата заключения договора
     *
     * @return \DateTime
     */
    public function getDocDate()
    {
        return $this->docDate;
    }

    /**
     * Sets a new docDate
     *
     * Дата заключения договора
     *
     * @param \DateTime $docDate
     * @return static
     */
    public function setDocDate(\DateTime $docDate)
    {
        $this->docDate = $docDate;
        return $this;
    }

    /**
     * Gets as status
     *
     * Статус НСО
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Sets a new status
     *
     * Статус НСО
     *
     * @param string $status
     * @return static
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * Gets as contractStatus
     *
     * Статус договора
     *
     * @return string
     */
    public function getContractStatus()
    {
        return $this->contractStatus;
    }

    /**
     * Sets a new contractStatus
     *
     * Статус договора
     *
     * @param string $contractStatus
     * @return static
     */
    public function setContractStatus($contractStatus)
    {
        $this->contractStatus = $contractStatus;
        return $this;
    }

    /**
     * Gets as depositId
     *
     * Идентификатор заявления на открытие
     *
     * @return string
     */
    public function getDepositId()
    {
        return $this->depositId;
    }

    /**
     * Sets a new depositId
     *
     * Идентификатор заявления на открытие
     *
     * @param string $depositId
     * @return static
     */
    public function setDepositId($depositId)
    {
        $this->depositId = $depositId;
        return $this;
    }

    /**
     * Gets as initialCardGuid
     *
     * Идентификатор первоначальной карточки договора НСО
     *
     * @return string
     */
    public function getInitialCardGuid()
    {
        return $this->initialCardGuid;
    }

    /**
     * Sets a new initialCardGuid
     *
     * Идентификатор первоначальной карточки договора НСО
     *
     * @param string $initialCardGuid
     * @return static
     */
    public function setInitialCardGuid($initialCardGuid)
    {
        $this->initialCardGuid = $initialCardGuid;
        return $this;
    }

    /**
     * Gets as pbAccount
     *
     * Счет поддержания НСО
     *
     * @return string
     */
    public function getPbAccount()
    {
        return $this->pbAccount;
    }

    /**
     * Sets a new pbAccount
     *
     * Счет поддержания НСО
     *
     * @param string $pbAccount
     * @return static
     */
    public function setPbAccount($pbAccount)
    {
        $this->pbAccount = $pbAccount;
        return $this;
    }

    /**
     * Gets as actual
     *
     * Признак актуальности договора
     *
     * @return boolean
     */
    public function getActual()
    {
        return $this->actual;
    }

    /**
     * Sets a new actual
     *
     * Признак актуальности договора
     *
     * @param boolean $actual
     * @return static
     */
    public function setActual($actual)
    {
        $this->actual = $actual;
        return $this;
    }

    /**
     * Gets as commentary
     *
     * Комментарий
     *
     * @return string
     */
    public function getCommentary()
    {
        return $this->commentary;
    }

    /**
     * Sets a new commentary
     *
     * Комментарий
     *
     * @param string $commentary
     * @return static
     */
    public function setCommentary($commentary)
    {
        $this->commentary = $commentary;
        return $this;
    }

    /**
     * Gets as permBalance
     *
     * Данные по продукту
     *
     * @return \common\models\sbbolxml\response\PermBalanceType
     */
    public function getPermBalance()
    {
        return $this->permBalance;
    }

    /**
     * Sets a new permBalance
     *
     * Данные по продукту
     *
     * @param \common\models\sbbolxml\response\PermBalanceType $permBalance
     * @return static
     */
    public function setPermBalance(\common\models\sbbolxml\response\PermBalanceType $permBalance)
    {
        $this->permBalance = $permBalance;
        return $this;
    }

    /**
     * Gets as reqAccount
     *
     * Счет возврата вклада и %%
     *
     * @return \common\models\sbbolxml\response\AccountReturnType
     */
    public function getReqAccount()
    {
        return $this->reqAccount;
    }

    /**
     * Sets a new reqAccount
     *
     * Счет возврата вклада и %%
     *
     * @param \common\models\sbbolxml\response\AccountReturnType $reqAccount
     * @return static
     */
    public function setReqAccount(\common\models\sbbolxml\response\AccountReturnType $reqAccount)
    {
        $this->reqAccount = $reqAccount;
        return $this;
    }


}


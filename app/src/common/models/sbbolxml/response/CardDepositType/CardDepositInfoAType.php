<?php

namespace common\models\sbbolxml\response\CardDepositType;

/**
 * Class representing CardDepositInfoAType
 */
class CardDepositInfoAType
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
     * Статус депозита
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
     * Идентификатор заявления на открытие / пролонгацию
     *
     * @property string $depositId
     */
    private $depositId = null;

    /**
     * Идентификатор первоначальной карточки договора депозита
     *
     * @property string $initialCardGuid
     */
    private $initialCardGuid = null;

    /**
     * Номер депозитного счета
     *
     * @property string $depositAccount
     */
    private $depositAccount = null;

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
     * Данные по депозиту
     *
     * @property \common\models\sbbolxml\response\DepositType $deposit
     */
    private $deposit = null;

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
     * Статус депозита
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
     * Статус депозита
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
     * Идентификатор заявления на открытие / пролонгацию
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
     * Идентификатор заявления на открытие / пролонгацию
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
     * Идентификатор первоначальной карточки договора депозита
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
     * Идентификатор первоначальной карточки договора депозита
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
     * Gets as depositAccount
     *
     * Номер депозитного счета
     *
     * @return string
     */
    public function getDepositAccount()
    {
        return $this->depositAccount;
    }

    /**
     * Sets a new depositAccount
     *
     * Номер депозитного счета
     *
     * @param string $depositAccount
     * @return static
     */
    public function setDepositAccount($depositAccount)
    {
        $this->depositAccount = $depositAccount;
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
     * Gets as deposit
     *
     * Данные по депозиту
     *
     * @return \common\models\sbbolxml\response\DepositType
     */
    public function getDeposit()
    {
        return $this->deposit;
    }

    /**
     * Sets a new deposit
     *
     * Данные по депозиту
     *
     * @param \common\models\sbbolxml\response\DepositType $deposit
     * @return static
     */
    public function setDeposit(\common\models\sbbolxml\response\DepositType $deposit)
    {
        $this->deposit = $deposit;
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


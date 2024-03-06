<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing StmtReqType
 *
 *
 * XSD Type: StmtReqType
 */
class StmtReqType extends DocBaseType
{

    /**
     * Дата и время создания запроса (с час. поясами)
     *
     * @property \DateTime $createTime
     */
    private $createTime = null;

    /**
     * Дата начала периода
     *
     * @property \DateTime $beginDate
     */
    private $beginDate = null;

    /**
     * Дата окончания периода
     *
     * @property \DateTime $endDate
     */
    private $endDate = null;

    /**
     * Тип запроса выписки: 101 - текущая выписка
     *
     * @property string $stmtType
     */
    private $stmtType = null;

    /**
     * Передается платежное наименование организации.
     *  СББОЛ при приеме должен проверить на соотв. справочнику и вернуть предупреждение о несоответствии
     *
     * @property string $orgName
     */
    private $orgName = null;

    /**
     * @property \common\models\sbbolxml\request\AccType[] $accounts
     */
    private $accounts = null;

    /**
     * Gets as createTime
     *
     * Дата и время создания запроса (с час. поясами)
     *
     * @return \DateTime
     */
    public function getCreateTime()
    {
        return $this->createTime;
    }

    /**
     * Sets a new createTime
     *
     * Дата и время создания запроса (с час. поясами)
     *
     * @param \DateTime $createTime
     * @return static
     */
    public function setCreateTime(\DateTime $createTime)
    {
        $this->createTime = $createTime;
        return $this;
    }

    /**
     * Gets as beginDate
     *
     * Дата начала периода
     *
     * @return \DateTime
     */
    public function getBeginDate()
    {
        return $this->beginDate;
    }

    /**
     * Sets a new beginDate
     *
     * Дата начала периода
     *
     * @param \DateTime $beginDate
     * @return static
     */
    public function setBeginDate(\DateTime $beginDate)
    {
        $this->beginDate = $beginDate;
        return $this;
    }

    /**
     * Gets as endDate
     *
     * Дата окончания периода
     *
     * @return \DateTime
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * Sets a new endDate
     *
     * Дата окончания периода
     *
     * @param \DateTime $endDate
     * @return static
     */
    public function setEndDate(\DateTime $endDate)
    {
        $this->endDate = $endDate;
        return $this;
    }

    /**
     * Gets as stmtType
     *
     * Тип запроса выписки: 101 - текущая выписка
     *
     * @return string
     */
    public function getStmtType()
    {
        return $this->stmtType;
    }

    /**
     * Sets a new stmtType
     *
     * Тип запроса выписки: 101 - текущая выписка
     *
     * @param string $stmtType
     * @return static
     */
    public function setStmtType($stmtType)
    {
        $this->stmtType = $stmtType;
        return $this;
    }

    /**
     * Gets as orgName
     *
     * Передается платежное наименование организации.
     *  СББОЛ при приеме должен проверить на соотв. справочнику и вернуть предупреждение о несоответствии
     *
     * @return string
     */
    public function getOrgName()
    {
        return $this->orgName;
    }

    /**
     * Sets a new orgName
     *
     * Передается платежное наименование организации.
     *  СББОЛ при приеме должен проверить на соотв. справочнику и вернуть предупреждение о несоответствии
     *
     * @param string $orgName
     * @return static
     */
    public function setOrgName($orgName)
    {
        $this->orgName = $orgName;
        return $this;
    }

    /**
     * Adds as account
     *
     * @return static
     * @param \common\models\sbbolxml\request\AccType $account
     */
    public function addToAccounts(\common\models\sbbolxml\request\AccType $account)
    {
        $this->accounts[] = $account;
        return $this;
    }

    /**
     * isset accounts
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetAccounts($index)
    {
        return isset($this->accounts[$index]);
    }

    /**
     * unset accounts
     *
     * @param scalar $index
     * @return void
     */
    public function unsetAccounts($index)
    {
        unset($this->accounts[$index]);
    }

    /**
     * Gets as accounts
     *
     * @return \common\models\sbbolxml\request\AccType[]
     */
    public function getAccounts()
    {
        return $this->accounts;
    }

    /**
     * Sets a new accounts
     *
     * @param \common\models\sbbolxml\request\AccType[] $accounts
     * @return static
     */
    public function setAccounts(array $accounts)
    {
        $this->accounts = $accounts;
        return $this;
    }


}


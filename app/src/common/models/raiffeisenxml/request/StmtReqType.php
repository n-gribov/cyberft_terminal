<?php

namespace common\models\raiffeisenxml\request;

/**
 * Class representing StmtReqType
 *
 *
 * XSD Type: StmtReqType
 */
class StmtReqType
{

    /**
     * Номер документа
     *
     * @property string $docNumber
     */
    private $docNumber = null;

    /**
     * Дата составления документа
     *
     * @property \DateTime $docDate
     */
    private $docDate = null;

    /**
     * Идентификатор документа в УС, если в УС запрос оформляется документом как в системе
     *  ДБО
     *  В системе ДБО сохраняется как документ. По нему можно получить квитанцию
     *
     * @property string $docExtId
     */
    private $docExtId = null;

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
     * Тип запроса выписки: 6 - только итоговая, 101 - только плановая, 106 - итоговая +
     *  плановая, 1 - не итоговая, не
     *  плановая (если в банке используется один вид), 0 - остатки
     *  На первом этапе: 6 или 0
     *
     * @property string $stmtType
     */
    private $stmtType = null;

    /**
     * Передается платежное наименование организации.
     *
     * @property string $orgName
     */
    private $orgName = null;

    /**
     * @property \common\models\raiffeisenxml\request\AccountType[] $accounts
     */
    private $accounts = null;

    /**
     * Gets as docNumber
     *
     * Номер документа
     *
     * @return string
     */
    public function getDocNumber()
    {
        return $this->docNumber;
    }

    /**
     * Sets a new docNumber
     *
     * Номер документа
     *
     * @param string $docNumber
     * @return static
     */
    public function setDocNumber($docNumber)
    {
        $this->docNumber = $docNumber;
        return $this;
    }

    /**
     * Gets as docDate
     *
     * Дата составления документа
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
     * Дата составления документа
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
     * Gets as docExtId
     *
     * Идентификатор документа в УС, если в УС запрос оформляется документом как в системе
     *  ДБО
     *  В системе ДБО сохраняется как документ. По нему можно получить квитанцию
     *
     * @return string
     */
    public function getDocExtId()
    {
        return $this->docExtId;
    }

    /**
     * Sets a new docExtId
     *
     * Идентификатор документа в УС, если в УС запрос оформляется документом как в системе
     *  ДБО
     *  В системе ДБО сохраняется как документ. По нему можно получить квитанцию
     *
     * @param string $docExtId
     * @return static
     */
    public function setDocExtId($docExtId)
    {
        $this->docExtId = $docExtId;
        return $this;
    }

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
     * Тип запроса выписки: 6 - только итоговая, 101 - только плановая, 106 - итоговая +
     *  плановая, 1 - не итоговая, не
     *  плановая (если в банке используется один вид), 0 - остатки
     *  На первом этапе: 6 или 0
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
     * Тип запроса выписки: 6 - только итоговая, 101 - только плановая, 106 - итоговая +
     *  плановая, 1 - не итоговая, не
     *  плановая (если в банке используется один вид), 0 - остатки
     *  На первом этапе: 6 или 0
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
     * @param \common\models\raiffeisenxml\request\AccountType $account
     */
    public function addToAccounts(\common\models\raiffeisenxml\request\AccountType $account)
    {
        $this->accounts[] = $account;
        return $this;
    }

    /**
     * isset accounts
     *
     * @param int|string $index
     * @return bool
     */
    public function issetAccounts($index)
    {
        return isset($this->accounts[$index]);
    }

    /**
     * unset accounts
     *
     * @param int|string $index
     * @return void
     */
    public function unsetAccounts($index)
    {
        unset($this->accounts[$index]);
    }

    /**
     * Gets as accounts
     *
     * @return \common\models\raiffeisenxml\request\AccountType[]
     */
    public function getAccounts()
    {
        return $this->accounts;
    }

    /**
     * Sets a new accounts
     *
     * @param \common\models\raiffeisenxml\request\AccountType[] $accounts
     * @return static
     */
    public function setAccounts(array $accounts)
    {
        $this->accounts = $accounts;
        return $this;
    }


}


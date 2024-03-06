<?php

namespace common\models\raiffeisenxml\request;

/**
 * Class representing StmtReqTypeRaifType
 *
 *
 * XSD Type: StmtReqTypeRaif
 */
class StmtReqTypeRaifType
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
     * Идентификатор документа в УС, если в УС запрос оформляется документом как в системе ДБО. В системе ДБО сохраняется как документ. По нему можно получить квитанцию.
     *
     * @property string $docExtId
     */
    private $docExtId = null;

    /**
     * Дата за которую требуется выписка
     *
     * @property \DateTime $date
     */
    private $date = null;

    /**
     * Тип запроса выписки: 6 - только итоговая, 1 - не итоговая
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
     * ИНН (до 12)
     *
     * @property string $inn
     */
    private $inn = null;

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
     * Идентификатор документа в УС, если в УС запрос оформляется документом как в системе ДБО. В системе ДБО сохраняется как документ. По нему можно получить квитанцию.
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
     * Идентификатор документа в УС, если в УС запрос оформляется документом как в системе ДБО. В системе ДБО сохраняется как документ. По нему можно получить квитанцию.
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
     * Gets as date
     *
     * Дата за которую требуется выписка
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Sets a new date
     *
     * Дата за которую требуется выписка
     *
     * @param \DateTime $date
     * @return static
     */
    public function setDate(\DateTime $date)
    {
        $this->date = $date;
        return $this;
    }

    /**
     * Gets as stmtType
     *
     * Тип запроса выписки: 6 - только итоговая, 1 - не итоговая
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
     * Тип запроса выписки: 6 - только итоговая, 1 - не итоговая
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
     * Gets as inn
     *
     * ИНН (до 12)
     *
     * @return string
     */
    public function getInn()
    {
        return $this->inn;
    }

    /**
     * Sets a new inn
     *
     * ИНН (до 12)
     *
     * @param string $inn
     * @return static
     */
    public function setInn($inn)
    {
        $this->inn = $inn;
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


<?php

namespace common\models\sbbolxml\request\RequestType;

/**
 * Class representing IncomingAType
 */
class IncomingAType
{

    /**
     * Идентификатор последнего обновления справочника корреспондентов
     *
     * @property integer $correspondentDictStepId
     */
    private $correspondentDictStepId = null;

    /**
     * Идентификатор последнего обновления справочника БИК
     *
     * @property integer $bicDictStepId
     */
    private $bicDictStepId = null;

    /**
     * Дата и время последнего запроса Incoming(с час. поясами)
     *
     * @property \DateTime $lastIncomingTime
     */
    private $lastIncomingTime = null;

    /**
     * Идентификатор последнего обновления справочника бенефициаров
     *
     * @property integer $beneficiarDictStepId
     */
    private $beneficiarDictStepId = null;

    /**
     * Признак того, что необходимо выгружать рублевые платежные поручения
     *
     * @property boolean $includeRZKPayDocsRu
     */
    private $includeRZKPayDocsRu = null;

    /**
     * Признак того, что необходимо выгружать валютные платежные поручения
     *
     * @property boolean $includeRZKPayDocsCurr
     */
    private $includeRZKPayDocsCurr = null;

    /**
     * Признак того, что необходимо выгружать зарплатную ведомость
     *
     * @property boolean $includeRZKSalaryDocs
     */
    private $includeRZKSalaryDocs = null;

    /**
     * Признак того, что необходимо выгружать выписки
     *
     * @property boolean $includeStatements
     */
    private $includeStatements = null;

    /**
     * Признак того, что необходимо выгружать сообщения из банка
     *
     * @property boolean $includeExchangeMessageFromBank
     */
    private $includeExchangeMessageFromBank = null;

    /**
     * Признак того, что необходимо выгружать новости
     *
     * @property boolean $includeNews
     */
    private $includeNews = null;

    /**
     * Признак того, что необходимо выгружать сообщения из банка
     *
     * @property boolean $includeLetterFromBanks
     */
    private $includeLetterFromBanks = null;

    /**
     * Признак того, что необходимо выгружать платежные требования
     *
     * @property boolean $includePayRequests
     */
    private $includePayRequests = null;

    /**
     * Признак того, что необходимо выгружать информацию об измененных документах
     *
     * @property boolean $includeChangedDocs
     */
    private $includeChangedDocs = null;

    /**
     * Признак того, что необходимо выгружать информацию об организации
     *
     * @property boolean $includeOrgSettings
     */
    private $includeOrgSettings = null;

    /**
     * Признак того, что необходимо выгружать ведомости банковского контроля
     *  по контрактам, кредитным договорам
     *
     * @property boolean $includeIntCtrlStatement
     */
    private $includeIntCtrlStatement = null;

    /**
     * Признак того, что необходимо выгружать паспорта сделок по контракту
     *
     * @property boolean $includeDealPassCon138I
     */
    private $includeDealPassCon138I = null;

    /**
     * Признак того, что необходимо выгружать паспорта сделок по кредиту
     *
     * @property boolean $includeDealPassCred138I
     */
    private $includeDealPassCred138I = null;

    /**
     * Признак того, что необходимо выгружать уведомления о поступлении денежных средств на транзитный валютный счет
     *
     * @property boolean $includeCurrencyNotice
     */
    private $includeCurrencyNotice = null;

    /**
     * Признак того, что необходимо выгружать письма свободного формата из банка
     *
     * @property boolean $includeGenericLettersFromBank
     */
    private $includeGenericLettersFromBank = null;

    /**
     * Идентификатор последнего обновления справочника актуальных ставок
     *
     * @property integer $dictsForDepositsDictStepId
     */
    private $dictsForDepositsDictStepId = null;

    /**
     * Идентификатор последнего обновления справочника карточек депозита
     *
     * @property integer $cardDepositDictStepId
     */
    private $cardDepositDictStepId = null;

    /**
     * Идентификатор последнего обновления справочника карточек НСО
     *
     * @property integer $cardPermBalanceDictStepId
     */
    private $cardPermBalanceDictStepId = null;

    /**
     * Идентификатор последнего обновления системного справочника
     *
     * @property integer $commonSettingsDictStepId
     */
    private $commonSettingsDictStepId = null;

    /**
     * Счёта, подготовленные выписки по котором нужно выгрузить. Для счетов не вошедших в список
     *  предоставлять выписки по запросу с данным элементом не требуется
     *
     * @property \common\models\sbbolxml\request\AccountType[] $accounts
     */
    private $accounts = null;

    /**
     * Организации, письма по которым нужно выгрузить.
     *  Если список пуст, возвращаются только письма по организации, указанной в верхнем элементе
     *
     * @property string[] $orgs
     */
    private $orgs = null;

    /**
     * Gets as correspondentDictStepId
     *
     * Идентификатор последнего обновления справочника корреспондентов
     *
     * @return integer
     */
    public function getCorrespondentDictStepId()
    {
        return $this->correspondentDictStepId;
    }

    /**
     * Sets a new correspondentDictStepId
     *
     * Идентификатор последнего обновления справочника корреспондентов
     *
     * @param integer $correspondentDictStepId
     * @return static
     */
    public function setCorrespondentDictStepId($correspondentDictStepId)
    {
        $this->correspondentDictStepId = $correspondentDictStepId;
        return $this;
    }

    /**
     * Gets as bicDictStepId
     *
     * Идентификатор последнего обновления справочника БИК
     *
     * @return integer
     */
    public function getBicDictStepId()
    {
        return $this->bicDictStepId;
    }

    /**
     * Sets a new bicDictStepId
     *
     * Идентификатор последнего обновления справочника БИК
     *
     * @param integer $bicDictStepId
     * @return static
     */
    public function setBicDictStepId($bicDictStepId)
    {
        $this->bicDictStepId = $bicDictStepId;
        return $this;
    }

    /**
     * Gets as lastIncomingTime
     *
     * Дата и время последнего запроса Incoming(с час. поясами)
     *
     * @return \DateTime
     */
    public function getLastIncomingTime()
    {
        return $this->lastIncomingTime;
    }

    /**
     * Sets a new lastIncomingTime
     *
     * Дата и время последнего запроса Incoming(с час. поясами)
     *
     * @param \DateTime $lastIncomingTime
     * @return static
     */
    public function setLastIncomingTime(\DateTime $lastIncomingTime)
    {
        $this->lastIncomingTime = $lastIncomingTime;
        return $this;
    }

    /**
     * Gets as beneficiarDictStepId
     *
     * Идентификатор последнего обновления справочника бенефициаров
     *
     * @return integer
     */
    public function getBeneficiarDictStepId()
    {
        return $this->beneficiarDictStepId;
    }

    /**
     * Sets a new beneficiarDictStepId
     *
     * Идентификатор последнего обновления справочника бенефициаров
     *
     * @param integer $beneficiarDictStepId
     * @return static
     */
    public function setBeneficiarDictStepId($beneficiarDictStepId)
    {
        $this->beneficiarDictStepId = $beneficiarDictStepId;
        return $this;
    }

    /**
     * Gets as includeRZKPayDocsRu
     *
     * Признак того, что необходимо выгружать рублевые платежные поручения
     *
     * @return boolean
     */
    public function getIncludeRZKPayDocsRu()
    {
        return $this->includeRZKPayDocsRu;
    }

    /**
     * Sets a new includeRZKPayDocsRu
     *
     * Признак того, что необходимо выгружать рублевые платежные поручения
     *
     * @param boolean $includeRZKPayDocsRu
     * @return static
     */
    public function setIncludeRZKPayDocsRu($includeRZKPayDocsRu)
    {
        $this->includeRZKPayDocsRu = $includeRZKPayDocsRu;
        return $this;
    }

    /**
     * Gets as includeRZKPayDocsCurr
     *
     * Признак того, что необходимо выгружать валютные платежные поручения
     *
     * @return boolean
     */
    public function getIncludeRZKPayDocsCurr()
    {
        return $this->includeRZKPayDocsCurr;
    }

    /**
     * Sets a new includeRZKPayDocsCurr
     *
     * Признак того, что необходимо выгружать валютные платежные поручения
     *
     * @param boolean $includeRZKPayDocsCurr
     * @return static
     */
    public function setIncludeRZKPayDocsCurr($includeRZKPayDocsCurr)
    {
        $this->includeRZKPayDocsCurr = $includeRZKPayDocsCurr;
        return $this;
    }

    /**
     * Gets as includeRZKSalaryDocs
     *
     * Признак того, что необходимо выгружать зарплатную ведомость
     *
     * @return boolean
     */
    public function getIncludeRZKSalaryDocs()
    {
        return $this->includeRZKSalaryDocs;
    }

    /**
     * Sets a new includeRZKSalaryDocs
     *
     * Признак того, что необходимо выгружать зарплатную ведомость
     *
     * @param boolean $includeRZKSalaryDocs
     * @return static
     */
    public function setIncludeRZKSalaryDocs($includeRZKSalaryDocs)
    {
        $this->includeRZKSalaryDocs = $includeRZKSalaryDocs;
        return $this;
    }

    /**
     * Gets as includeStatements
     *
     * Признак того, что необходимо выгружать выписки
     *
     * @return boolean
     */
    public function getIncludeStatements()
    {
        return $this->includeStatements;
    }

    /**
     * Sets a new includeStatements
     *
     * Признак того, что необходимо выгружать выписки
     *
     * @param boolean $includeStatements
     * @return static
     */
    public function setIncludeStatements($includeStatements)
    {
        $this->includeStatements = $includeStatements;
        return $this;
    }

    /**
     * Gets as includeExchangeMessageFromBank
     *
     * Признак того, что необходимо выгружать сообщения из банка
     *
     * @return boolean
     */
    public function getIncludeExchangeMessageFromBank()
    {
        return $this->includeExchangeMessageFromBank;
    }

    /**
     * Sets a new includeExchangeMessageFromBank
     *
     * Признак того, что необходимо выгружать сообщения из банка
     *
     * @param boolean $includeExchangeMessageFromBank
     * @return static
     */
    public function setIncludeExchangeMessageFromBank($includeExchangeMessageFromBank)
    {
        $this->includeExchangeMessageFromBank = $includeExchangeMessageFromBank;
        return $this;
    }

    /**
     * Gets as includeNews
     *
     * Признак того, что необходимо выгружать новости
     *
     * @return boolean
     */
    public function getIncludeNews()
    {
        return $this->includeNews;
    }

    /**
     * Sets a new includeNews
     *
     * Признак того, что необходимо выгружать новости
     *
     * @param boolean $includeNews
     * @return static
     */
    public function setIncludeNews($includeNews)
    {
        $this->includeNews = $includeNews;
        return $this;
    }

    /**
     * Gets as includeLetterFromBanks
     *
     * Признак того, что необходимо выгружать сообщения из банка
     *
     * @return boolean
     */
    public function getIncludeLetterFromBanks()
    {
        return $this->includeLetterFromBanks;
    }

    /**
     * Sets a new includeLetterFromBanks
     *
     * Признак того, что необходимо выгружать сообщения из банка
     *
     * @param boolean $includeLetterFromBanks
     * @return static
     */
    public function setIncludeLetterFromBanks($includeLetterFromBanks)
    {
        $this->includeLetterFromBanks = $includeLetterFromBanks;
        return $this;
    }

    /**
     * Gets as includePayRequests
     *
     * Признак того, что необходимо выгружать платежные требования
     *
     * @return boolean
     */
    public function getIncludePayRequests()
    {
        return $this->includePayRequests;
    }

    /**
     * Sets a new includePayRequests
     *
     * Признак того, что необходимо выгружать платежные требования
     *
     * @param boolean $includePayRequests
     * @return static
     */
    public function setIncludePayRequests($includePayRequests)
    {
        $this->includePayRequests = $includePayRequests;
        return $this;
    }

    /**
     * Gets as includeChangedDocs
     *
     * Признак того, что необходимо выгружать информацию об измененных документах
     *
     * @return boolean
     */
    public function getIncludeChangedDocs()
    {
        return $this->includeChangedDocs;
    }

    /**
     * Sets a new includeChangedDocs
     *
     * Признак того, что необходимо выгружать информацию об измененных документах
     *
     * @param boolean $includeChangedDocs
     * @return static
     */
    public function setIncludeChangedDocs($includeChangedDocs)
    {
        $this->includeChangedDocs = $includeChangedDocs;
        return $this;
    }

    /**
     * Gets as includeOrgSettings
     *
     * Признак того, что необходимо выгружать информацию об организации
     *
     * @return boolean
     */
    public function getIncludeOrgSettings()
    {
        return $this->includeOrgSettings;
    }

    /**
     * Sets a new includeOrgSettings
     *
     * Признак того, что необходимо выгружать информацию об организации
     *
     * @param boolean $includeOrgSettings
     * @return static
     */
    public function setIncludeOrgSettings($includeOrgSettings)
    {
        $this->includeOrgSettings = $includeOrgSettings;
        return $this;
    }

    /**
     * Gets as includeIntCtrlStatement
     *
     * Признак того, что необходимо выгружать ведомости банковского контроля
     *  по контрактам, кредитным договорам
     *
     * @return boolean
     */
    public function getIncludeIntCtrlStatement()
    {
        return $this->includeIntCtrlStatement;
    }

    /**
     * Sets a new includeIntCtrlStatement
     *
     * Признак того, что необходимо выгружать ведомости банковского контроля
     *  по контрактам, кредитным договорам
     *
     * @param boolean $includeIntCtrlStatement
     * @return static
     */
    public function setIncludeIntCtrlStatement($includeIntCtrlStatement)
    {
        $this->includeIntCtrlStatement = $includeIntCtrlStatement;
        return $this;
    }

    /**
     * Gets as includeDealPassCon138I
     *
     * Признак того, что необходимо выгружать паспорта сделок по контракту
     *
     * @return boolean
     */
    public function getIncludeDealPassCon138I()
    {
        return $this->includeDealPassCon138I;
    }

    /**
     * Sets a new includeDealPassCon138I
     *
     * Признак того, что необходимо выгружать паспорта сделок по контракту
     *
     * @param boolean $includeDealPassCon138I
     * @return static
     */
    public function setIncludeDealPassCon138I($includeDealPassCon138I)
    {
        $this->includeDealPassCon138I = $includeDealPassCon138I;
        return $this;
    }

    /**
     * Gets as includeDealPassCred138I
     *
     * Признак того, что необходимо выгружать паспорта сделок по кредиту
     *
     * @return boolean
     */
    public function getIncludeDealPassCred138I()
    {
        return $this->includeDealPassCred138I;
    }

    /**
     * Sets a new includeDealPassCred138I
     *
     * Признак того, что необходимо выгружать паспорта сделок по кредиту
     *
     * @param boolean $includeDealPassCred138I
     * @return static
     */
    public function setIncludeDealPassCred138I($includeDealPassCred138I)
    {
        $this->includeDealPassCred138I = $includeDealPassCred138I;
        return $this;
    }

    /**
     * Gets as includeCurrencyNotice
     *
     * Признак того, что необходимо выгружать уведомления о поступлении денежных средств на транзитный валютный счет
     *
     * @return boolean
     */
    public function getIncludeCurrencyNotice()
    {
        return $this->includeCurrencyNotice;
    }

    /**
     * Sets a new includeCurrencyNotice
     *
     * Признак того, что необходимо выгружать уведомления о поступлении денежных средств на транзитный валютный счет
     *
     * @param boolean $includeCurrencyNotice
     * @return static
     */
    public function setIncludeCurrencyNotice($includeCurrencyNotice)
    {
        $this->includeCurrencyNotice = $includeCurrencyNotice;
        return $this;
    }

    /**
     * Gets as includeGenericLettersFromBank
     *
     * Признак того, что необходимо выгружать письма свободного формата из банка
     *
     * @return boolean
     */
    public function getIncludeGenericLettersFromBank()
    {
        return $this->includeGenericLettersFromBank;
    }

    /**
     * Sets a new includeGenericLettersFromBank
     *
     * Признак того, что необходимо выгружать письма свободного формата из банка
     *
     * @param boolean $includeGenericLettersFromBank
     * @return static
     */
    public function setIncludeGenericLettersFromBank($includeGenericLettersFromBank)
    {
        $this->includeGenericLettersFromBank = $includeGenericLettersFromBank;
        return $this;
    }

    /**
     * Gets as dictsForDepositsDictStepId
     *
     * Идентификатор последнего обновления справочника актуальных ставок
     *
     * @return integer
     */
    public function getDictsForDepositsDictStepId()
    {
        return $this->dictsForDepositsDictStepId;
    }

    /**
     * Sets a new dictsForDepositsDictStepId
     *
     * Идентификатор последнего обновления справочника актуальных ставок
     *
     * @param integer $dictsForDepositsDictStepId
     * @return static
     */
    public function setDictsForDepositsDictStepId($dictsForDepositsDictStepId)
    {
        $this->dictsForDepositsDictStepId = $dictsForDepositsDictStepId;
        return $this;
    }

    /**
     * Gets as cardDepositDictStepId
     *
     * Идентификатор последнего обновления справочника карточек депозита
     *
     * @return integer
     */
    public function getCardDepositDictStepId()
    {
        return $this->cardDepositDictStepId;
    }

    /**
     * Sets a new cardDepositDictStepId
     *
     * Идентификатор последнего обновления справочника карточек депозита
     *
     * @param integer $cardDepositDictStepId
     * @return static
     */
    public function setCardDepositDictStepId($cardDepositDictStepId)
    {
        $this->cardDepositDictStepId = $cardDepositDictStepId;
        return $this;
    }

    /**
     * Gets as cardPermBalanceDictStepId
     *
     * Идентификатор последнего обновления справочника карточек НСО
     *
     * @return integer
     */
    public function getCardPermBalanceDictStepId()
    {
        return $this->cardPermBalanceDictStepId;
    }

    /**
     * Sets a new cardPermBalanceDictStepId
     *
     * Идентификатор последнего обновления справочника карточек НСО
     *
     * @param integer $cardPermBalanceDictStepId
     * @return static
     */
    public function setCardPermBalanceDictStepId($cardPermBalanceDictStepId)
    {
        $this->cardPermBalanceDictStepId = $cardPermBalanceDictStepId;
        return $this;
    }

    /**
     * Gets as commonSettingsDictStepId
     *
     * Идентификатор последнего обновления системного справочника
     *
     * @return integer
     */
    public function getCommonSettingsDictStepId()
    {
        return $this->commonSettingsDictStepId;
    }

    /**
     * Sets a new commonSettingsDictStepId
     *
     * Идентификатор последнего обновления системного справочника
     *
     * @param integer $commonSettingsDictStepId
     * @return static
     */
    public function setCommonSettingsDictStepId($commonSettingsDictStepId)
    {
        $this->commonSettingsDictStepId = $commonSettingsDictStepId;
        return $this;
    }

    /**
     * Adds as account
     *
     * Счёта, подготовленные выписки по котором нужно выгрузить. Для счетов не вошедших в список
     *  предоставлять выписки по запросу с данным элементом не требуется
     *
     * @return static
     * @param \common\models\sbbolxml\request\AccountType $account
     */
    public function addToAccounts(\common\models\sbbolxml\request\AccountType $account)
    {
        $this->accounts[] = $account;
        return $this;
    }

    /**
     * isset accounts
     *
     * Счёта, подготовленные выписки по котором нужно выгрузить. Для счетов не вошедших в список
     *  предоставлять выписки по запросу с данным элементом не требуется
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
     * Счёта, подготовленные выписки по котором нужно выгрузить. Для счетов не вошедших в список
     *  предоставлять выписки по запросу с данным элементом не требуется
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
     * Счёта, подготовленные выписки по котором нужно выгрузить. Для счетов не вошедших в список
     *  предоставлять выписки по запросу с данным элементом не требуется
     *
     * @return \common\models\sbbolxml\request\AccountType[]
     */
    public function getAccounts()
    {
        return $this->accounts;
    }

    /**
     * Sets a new accounts
     *
     * Счёта, подготовленные выписки по котором нужно выгрузить. Для счетов не вошедших в список
     *  предоставлять выписки по запросу с данным элементом не требуется
     *
     * @param \common\models\sbbolxml\request\AccountType[] $accounts
     * @return static
     */
    public function setAccounts(array $accounts)
    {
        $this->accounts = $accounts;
        return $this;
    }

    /**
     * Adds as org
     *
     * Организации, письма по которым нужно выгрузить.
     *  Если список пуст, возвращаются только письма по организации, указанной в верхнем элементе
     *
     * @return static
     * @param string $org
     */
    public function addToOrgs($org)
    {
        $this->orgs[] = $org;
        return $this;
    }

    /**
     * isset orgs
     *
     * Организации, письма по которым нужно выгрузить.
     *  Если список пуст, возвращаются только письма по организации, указанной в верхнем элементе
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetOrgs($index)
    {
        return isset($this->orgs[$index]);
    }

    /**
     * unset orgs
     *
     * Организации, письма по которым нужно выгрузить.
     *  Если список пуст, возвращаются только письма по организации, указанной в верхнем элементе
     *
     * @param scalar $index
     * @return void
     */
    public function unsetOrgs($index)
    {
        unset($this->orgs[$index]);
    }

    /**
     * Gets as orgs
     *
     * Организации, письма по которым нужно выгрузить.
     *  Если список пуст, возвращаются только письма по организации, указанной в верхнем элементе
     *
     * @return string[]
     */
    public function getOrgs()
    {
        return $this->orgs;
    }

    /**
     * Sets a new orgs
     *
     * Организации, письма по которым нужно выгрузить.
     *  Если список пуст, возвращаются только письма по организации, указанной в верхнем элементе
     *
     * @param string $orgs
     * @return static
     */
    public function setOrgs(array $orgs)
    {
        $this->orgs = $orgs;
        return $this;
    }


}


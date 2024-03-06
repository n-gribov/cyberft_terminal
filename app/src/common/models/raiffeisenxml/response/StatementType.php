<?php

namespace common\models\raiffeisenxml\response;

/**
 * Class representing StatementType
 *
 *
 * XSD Type: StatementType
 */
class StatementType
{

    /**
     * Идентификатор выписки в ДБО (UUID документа)
     *
     * @property string $docId
     */
    private $docId = null;

    /**
     * Внешний идентификатор документа в АБС.
     *  Может быть присвоен документу по результатам выгрузки в АБС
     *
     * @property string $docExtId
     */
    private $docExtId = null;

    /**
     * Дата и время, на которые была сформирована выписка
     *
     * @property \DateTime $stmtDateTime
     */
    private $stmtDateTime = null;

    /**
     * @property string $bic
     */
    private $bic = null;

    /**
     * Дата последней операции по счету за запрошеный период
     *
     * @property \DateTime $lastMovetDate
     */
    private $lastMovetDate = null;

    /**
     * Дата предыдущей операции за запрошеный период
     *
     * @property \DateTime $datePLast
     */
    private $datePLast = null;

    /**
     * Номер счета
     *
     * @property string $acc
     */
    private $acc = null;

    /**
     * Курс ЦБ за 1 ед. валюты на начало периода.
     *  Заполняется только для счетов и ин. валюте
     *
     * @property float $rateIn
     */
    private $rateIn = null;

    /**
     * Курс ЦБ за 1 ед. валюты на конец периода.
     *  Заполняется только для счетов и ин. валюте
     *
     * @property float $rateOut
     */
    private $rateOut = null;

    /**
     * Входящий остаток в валюте счета
     *  Если запрашивались остатки, то на начало опер. дня
     *
     * @property float $enterBal
     */
    private $enterBal = null;

    /**
     * Входящий остаток в нац. валюте
     *  Если запрашивались остатки, то на начало опер. дня
     *
     * @property float $enterBalNat
     */
    private $enterBalNat = null;

    /**
     * Исходящий остаток в валюте счета
     *
     * @property float $outBal
     */
    private $outBal = null;

    /**
     * Исходящий остаток в нац. валюте
     *
     * @property float $outBalNat
     */
    private $outBalNat = null;

    /**
     * Плановый в валюте счета
     *
     * @property float $planOutBal
     */
    private $planOutBal = null;

    /**
     * Плановый в нац. валюте
     *
     * @property float $planOutBalNat
     */
    private $planOutBalNat = null;

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
     *  плановая (если в банке используется один вид) 0 - остатки
     *
     * @property string $stmtType
     */
    private $stmtType = null;

    /**
     * Дата запроса выписки
     *
     * @property \DateTime $requestDate
     */
    private $requestDate = null;

    /**
     * Номер выписки в ДБО (при получении остатков не заполняется)
     *
     * @property string $docNum
     */
    private $docNum = null;

    /**
     * Соответствует наименованию в выписке Сбербанка
     *
     * @property string $accountName
     */
    private $accountName = null;

    /**
     * Передается платежное наименование организации.
     *  СББОЛ при приеме должен проверить на соотв. справочнику и вернуть предупреждение о несоответствии
     *
     * @property string $orgName
     */
    private $orgName = null;

    /**
     * Итоговая сумма по дебету в валюте счета
     *  Для остатков не передается
     *
     * @property float $debetSum
     */
    private $debetSum = null;

    /**
     * Итоговая сумма по кредиту в валюте счета
     *  Для остатков не передается
     *
     * @property float $creditSum
     */
    private $creditSum = null;

    /**
     * Итоговая сумма по дебету в нац. валюте
     *  Для остатков не передается
     *
     * @property float $debetSumNat
     */
    private $debetSumNat = null;

    /**
     * Итоговая сумма по кредиту в нац. валюте
     *  Для остатков не передается
     *
     * @property float $creditSumNat
     */
    private $creditSumNat = null;

    /**
     * Номер ответственного исполнителя
     *
     * @property string $author
     */
    private $author = null;

    /**
     * @property string $docComment
     */
    private $docComment = null;

    /**
     * Доступный остаток
     *
     * @property float $ldgBal
     */
    private $ldgBal = null;

    /**
     * Доступный остаток в нац. валюте
     *
     * @property float $ldgBalNat
     */
    private $ldgBalNat = null;

    /**
     * @property \common\models\raiffeisenxml\response\TransInfoType[] $docs
     */
    private $docs = null;

    /**
     * @property \common\models\raiffeisenxml\response\ParamsType\ParamAType[] $params
     */
    private $params = null;

    /**
     * @property \common\models\raiffeisenxml\response\DigitalSignType[] $signs
     */
    private $signs = null;

    /**
     * Параметры запроса выписки
     *
     * @property \common\models\raiffeisenxml\response\InitialDocType $initialDoc
     */
    private $initialDoc = null;

    /**
     * Gets as docId
     *
     * Идентификатор выписки в ДБО (UUID документа)
     *
     * @return string
     */
    public function getDocId()
    {
        return $this->docId;
    }

    /**
     * Sets a new docId
     *
     * Идентификатор выписки в ДБО (UUID документа)
     *
     * @param string $docId
     * @return static
     */
    public function setDocId($docId)
    {
        $this->docId = $docId;
        return $this;
    }

    /**
     * Gets as docExtId
     *
     * Внешний идентификатор документа в АБС.
     *  Может быть присвоен документу по результатам выгрузки в АБС
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
     * Внешний идентификатор документа в АБС.
     *  Может быть присвоен документу по результатам выгрузки в АБС
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
     * Gets as stmtDateTime
     *
     * Дата и время, на которые была сформирована выписка
     *
     * @return \DateTime
     */
    public function getStmtDateTime()
    {
        return $this->stmtDateTime;
    }

    /**
     * Sets a new stmtDateTime
     *
     * Дата и время, на которые была сформирована выписка
     *
     * @param \DateTime $stmtDateTime
     * @return static
     */
    public function setStmtDateTime(\DateTime $stmtDateTime)
    {
        $this->stmtDateTime = $stmtDateTime;
        return $this;
    }

    /**
     * Gets as bic
     *
     * @return string
     */
    public function getBic()
    {
        return $this->bic;
    }

    /**
     * Sets a new bic
     *
     * @param string $bic
     * @return static
     */
    public function setBic($bic)
    {
        $this->bic = $bic;
        return $this;
    }

    /**
     * Gets as lastMovetDate
     *
     * Дата последней операции по счету за запрошеный период
     *
     * @return \DateTime
     */
    public function getLastMovetDate()
    {
        return $this->lastMovetDate;
    }

    /**
     * Sets a new lastMovetDate
     *
     * Дата последней операции по счету за запрошеный период
     *
     * @param \DateTime $lastMovetDate
     * @return static
     */
    public function setLastMovetDate(\DateTime $lastMovetDate)
    {
        $this->lastMovetDate = $lastMovetDate;
        return $this;
    }

    /**
     * Gets as datePLast
     *
     * Дата предыдущей операции за запрошеный период
     *
     * @return \DateTime
     */
    public function getDatePLast()
    {
        return $this->datePLast;
    }

    /**
     * Sets a new datePLast
     *
     * Дата предыдущей операции за запрошеный период
     *
     * @param \DateTime $datePLast
     * @return static
     */
    public function setDatePLast(\DateTime $datePLast)
    {
        $this->datePLast = $datePLast;
        return $this;
    }

    /**
     * Gets as acc
     *
     * Номер счета
     *
     * @return string
     */
    public function getAcc()
    {
        return $this->acc;
    }

    /**
     * Sets a new acc
     *
     * Номер счета
     *
     * @param string $acc
     * @return static
     */
    public function setAcc($acc)
    {
        $this->acc = $acc;
        return $this;
    }

    /**
     * Gets as rateIn
     *
     * Курс ЦБ за 1 ед. валюты на начало периода.
     *  Заполняется только для счетов и ин. валюте
     *
     * @return float
     */
    public function getRateIn()
    {
        return $this->rateIn;
    }

    /**
     * Sets a new rateIn
     *
     * Курс ЦБ за 1 ед. валюты на начало периода.
     *  Заполняется только для счетов и ин. валюте
     *
     * @param float $rateIn
     * @return static
     */
    public function setRateIn($rateIn)
    {
        $this->rateIn = $rateIn;
        return $this;
    }

    /**
     * Gets as rateOut
     *
     * Курс ЦБ за 1 ед. валюты на конец периода.
     *  Заполняется только для счетов и ин. валюте
     *
     * @return float
     */
    public function getRateOut()
    {
        return $this->rateOut;
    }

    /**
     * Sets a new rateOut
     *
     * Курс ЦБ за 1 ед. валюты на конец периода.
     *  Заполняется только для счетов и ин. валюте
     *
     * @param float $rateOut
     * @return static
     */
    public function setRateOut($rateOut)
    {
        $this->rateOut = $rateOut;
        return $this;
    }

    /**
     * Gets as enterBal
     *
     * Входящий остаток в валюте счета
     *  Если запрашивались остатки, то на начало опер. дня
     *
     * @return float
     */
    public function getEnterBal()
    {
        return $this->enterBal;
    }

    /**
     * Sets a new enterBal
     *
     * Входящий остаток в валюте счета
     *  Если запрашивались остатки, то на начало опер. дня
     *
     * @param float $enterBal
     * @return static
     */
    public function setEnterBal($enterBal)
    {
        $this->enterBal = $enterBal;
        return $this;
    }

    /**
     * Gets as enterBalNat
     *
     * Входящий остаток в нац. валюте
     *  Если запрашивались остатки, то на начало опер. дня
     *
     * @return float
     */
    public function getEnterBalNat()
    {
        return $this->enterBalNat;
    }

    /**
     * Sets a new enterBalNat
     *
     * Входящий остаток в нац. валюте
     *  Если запрашивались остатки, то на начало опер. дня
     *
     * @param float $enterBalNat
     * @return static
     */
    public function setEnterBalNat($enterBalNat)
    {
        $this->enterBalNat = $enterBalNat;
        return $this;
    }

    /**
     * Gets as outBal
     *
     * Исходящий остаток в валюте счета
     *
     * @return float
     */
    public function getOutBal()
    {
        return $this->outBal;
    }

    /**
     * Sets a new outBal
     *
     * Исходящий остаток в валюте счета
     *
     * @param float $outBal
     * @return static
     */
    public function setOutBal($outBal)
    {
        $this->outBal = $outBal;
        return $this;
    }

    /**
     * Gets as outBalNat
     *
     * Исходящий остаток в нац. валюте
     *
     * @return float
     */
    public function getOutBalNat()
    {
        return $this->outBalNat;
    }

    /**
     * Sets a new outBalNat
     *
     * Исходящий остаток в нац. валюте
     *
     * @param float $outBalNat
     * @return static
     */
    public function setOutBalNat($outBalNat)
    {
        $this->outBalNat = $outBalNat;
        return $this;
    }

    /**
     * Gets as planOutBal
     *
     * Плановый в валюте счета
     *
     * @return float
     */
    public function getPlanOutBal()
    {
        return $this->planOutBal;
    }

    /**
     * Sets a new planOutBal
     *
     * Плановый в валюте счета
     *
     * @param float $planOutBal
     * @return static
     */
    public function setPlanOutBal($planOutBal)
    {
        $this->planOutBal = $planOutBal;
        return $this;
    }

    /**
     * Gets as planOutBalNat
     *
     * Плановый в нац. валюте
     *
     * @return float
     */
    public function getPlanOutBalNat()
    {
        return $this->planOutBalNat;
    }

    /**
     * Sets a new planOutBalNat
     *
     * Плановый в нац. валюте
     *
     * @param float $planOutBalNat
     * @return static
     */
    public function setPlanOutBalNat($planOutBalNat)
    {
        $this->planOutBalNat = $planOutBalNat;
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
     *  плановая (если в банке используется один вид) 0 - остатки
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
     *  плановая (если в банке используется один вид) 0 - остатки
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
     * Gets as requestDate
     *
     * Дата запроса выписки
     *
     * @return \DateTime
     */
    public function getRequestDate()
    {
        return $this->requestDate;
    }

    /**
     * Sets a new requestDate
     *
     * Дата запроса выписки
     *
     * @param \DateTime $requestDate
     * @return static
     */
    public function setRequestDate(\DateTime $requestDate)
    {
        $this->requestDate = $requestDate;
        return $this;
    }

    /**
     * Gets as docNum
     *
     * Номер выписки в ДБО (при получении остатков не заполняется)
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
     * Номер выписки в ДБО (при получении остатков не заполняется)
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
     * Gets as accountName
     *
     * Соответствует наименованию в выписке Сбербанка
     *
     * @return string
     */
    public function getAccountName()
    {
        return $this->accountName;
    }

    /**
     * Sets a new accountName
     *
     * Соответствует наименованию в выписке Сбербанка
     *
     * @param string $accountName
     * @return static
     */
    public function setAccountName($accountName)
    {
        $this->accountName = $accountName;
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
     * Gets as debetSum
     *
     * Итоговая сумма по дебету в валюте счета
     *  Для остатков не передается
     *
     * @return float
     */
    public function getDebetSum()
    {
        return $this->debetSum;
    }

    /**
     * Sets a new debetSum
     *
     * Итоговая сумма по дебету в валюте счета
     *  Для остатков не передается
     *
     * @param float $debetSum
     * @return static
     */
    public function setDebetSum($debetSum)
    {
        $this->debetSum = $debetSum;
        return $this;
    }

    /**
     * Gets as creditSum
     *
     * Итоговая сумма по кредиту в валюте счета
     *  Для остатков не передается
     *
     * @return float
     */
    public function getCreditSum()
    {
        return $this->creditSum;
    }

    /**
     * Sets a new creditSum
     *
     * Итоговая сумма по кредиту в валюте счета
     *  Для остатков не передается
     *
     * @param float $creditSum
     * @return static
     */
    public function setCreditSum($creditSum)
    {
        $this->creditSum = $creditSum;
        return $this;
    }

    /**
     * Gets as debetSumNat
     *
     * Итоговая сумма по дебету в нац. валюте
     *  Для остатков не передается
     *
     * @return float
     */
    public function getDebetSumNat()
    {
        return $this->debetSumNat;
    }

    /**
     * Sets a new debetSumNat
     *
     * Итоговая сумма по дебету в нац. валюте
     *  Для остатков не передается
     *
     * @param float $debetSumNat
     * @return static
     */
    public function setDebetSumNat($debetSumNat)
    {
        $this->debetSumNat = $debetSumNat;
        return $this;
    }

    /**
     * Gets as creditSumNat
     *
     * Итоговая сумма по кредиту в нац. валюте
     *  Для остатков не передается
     *
     * @return float
     */
    public function getCreditSumNat()
    {
        return $this->creditSumNat;
    }

    /**
     * Sets a new creditSumNat
     *
     * Итоговая сумма по кредиту в нац. валюте
     *  Для остатков не передается
     *
     * @param float $creditSumNat
     * @return static
     */
    public function setCreditSumNat($creditSumNat)
    {
        $this->creditSumNat = $creditSumNat;
        return $this;
    }

    /**
     * Gets as author
     *
     * Номер ответственного исполнителя
     *
     * @return string
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Sets a new author
     *
     * Номер ответственного исполнителя
     *
     * @param string $author
     * @return static
     */
    public function setAuthor($author)
    {
        $this->author = $author;
        return $this;
    }

    /**
     * Gets as docComment
     *
     * @return string
     */
    public function getDocComment()
    {
        return $this->docComment;
    }

    /**
     * Sets a new docComment
     *
     * @param string $docComment
     * @return static
     */
    public function setDocComment($docComment)
    {
        $this->docComment = $docComment;
        return $this;
    }

    /**
     * Gets as ldgBal
     *
     * Доступный остаток
     *
     * @return float
     */
    public function getLdgBal()
    {
        return $this->ldgBal;
    }

    /**
     * Sets a new ldgBal
     *
     * Доступный остаток
     *
     * @param float $ldgBal
     * @return static
     */
    public function setLdgBal($ldgBal)
    {
        $this->ldgBal = $ldgBal;
        return $this;
    }

    /**
     * Gets as ldgBalNat
     *
     * Доступный остаток в нац. валюте
     *
     * @return float
     */
    public function getLdgBalNat()
    {
        return $this->ldgBalNat;
    }

    /**
     * Sets a new ldgBalNat
     *
     * Доступный остаток в нац. валюте
     *
     * @param float $ldgBalNat
     * @return static
     */
    public function setLdgBalNat($ldgBalNat)
    {
        $this->ldgBalNat = $ldgBalNat;
        return $this;
    }

    /**
     * Adds as transInfo
     *
     * @return static
     * @param \common\models\raiffeisenxml\response\TransInfoType $transInfo
     */
    public function addToDocs(\common\models\raiffeisenxml\response\TransInfoType $transInfo)
    {
        $this->docs[] = $transInfo;
        return $this;
    }

    /**
     * isset docs
     *
     * @param int|string $index
     * @return bool
     */
    public function issetDocs($index)
    {
        return isset($this->docs[$index]);
    }

    /**
     * unset docs
     *
     * @param int|string $index
     * @return void
     */
    public function unsetDocs($index)
    {
        unset($this->docs[$index]);
    }

    /**
     * Gets as docs
     *
     * @return \common\models\raiffeisenxml\response\TransInfoType[]
     */
    public function getDocs()
    {
        return $this->docs;
    }

    /**
     * Sets a new docs
     *
     * @param \common\models\raiffeisenxml\response\TransInfoType[] $docs
     * @return static
     */
    public function setDocs(array $docs)
    {
        $this->docs = $docs;
        return $this;
    }

    /**
     * Adds as param
     *
     * @return static
     * @param \common\models\raiffeisenxml\response\ParamsType\ParamAType $param
     */
    public function addToParams(\common\models\raiffeisenxml\response\ParamsType\ParamAType $param)
    {
        $this->params[] = $param;
        return $this;
    }

    /**
     * isset params
     *
     * @param int|string $index
     * @return bool
     */
    public function issetParams($index)
    {
        return isset($this->params[$index]);
    }

    /**
     * unset params
     *
     * @param int|string $index
     * @return void
     */
    public function unsetParams($index)
    {
        unset($this->params[$index]);
    }

    /**
     * Gets as params
     *
     * @return \common\models\raiffeisenxml\response\ParamsType\ParamAType[]
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * Sets a new params
     *
     * @param \common\models\raiffeisenxml\response\ParamsType\ParamAType[] $params
     * @return static
     */
    public function setParams(array $params)
    {
        $this->params = $params;
        return $this;
    }

    /**
     * Adds as sign
     *
     * @return static
     * @param \common\models\raiffeisenxml\response\DigitalSignType $sign
     */
    public function addToSigns(\common\models\raiffeisenxml\response\DigitalSignType $sign)
    {
        $this->signs[] = $sign;
        return $this;
    }

    /**
     * isset signs
     *
     * @param int|string $index
     * @return bool
     */
    public function issetSigns($index)
    {
        return isset($this->signs[$index]);
    }

    /**
     * unset signs
     *
     * @param int|string $index
     * @return void
     */
    public function unsetSigns($index)
    {
        unset($this->signs[$index]);
    }

    /**
     * Gets as signs
     *
     * @return \common\models\raiffeisenxml\response\DigitalSignType[]
     */
    public function getSigns()
    {
        return $this->signs;
    }

    /**
     * Sets a new signs
     *
     * @param \common\models\raiffeisenxml\response\DigitalSignType[] $signs
     * @return static
     */
    public function setSigns(array $signs)
    {
        $this->signs = $signs;
        return $this;
    }

    /**
     * Gets as initialDoc
     *
     * Параметры запроса выписки
     *
     * @return \common\models\raiffeisenxml\response\InitialDocType
     */
    public function getInitialDoc()
    {
        return $this->initialDoc;
    }

    /**
     * Sets a new initialDoc
     *
     * Параметры запроса выписки
     *
     * @param \common\models\raiffeisenxml\response\InitialDocType $initialDoc
     * @return static
     */
    public function setInitialDoc(\common\models\raiffeisenxml\response\InitialDocType $initialDoc)
    {
        $this->initialDoc = $initialDoc;
        return $this;
    }


}


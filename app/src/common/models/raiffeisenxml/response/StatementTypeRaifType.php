<?php

namespace common\models\raiffeisenxml\response;

/**
 * Class representing StatementTypeRaifType
 *
 *
 * XSD Type: StatementTypeRaif
 */
class StatementTypeRaifType
{

    /**
     * Идентификатор выписки в ELBRUS
     *
     * @property string $extId
     */
    private $extId = null;

    /**
     * Счет клиента
     *
     * @property string $acc
     */
    private $acc = null;

    /**
     * БИК банка клиента
     *
     * @property string $bic
     */
    private $bic = null;

    /**
     * Итого оборотов по кредиту
     *
     * @property float $creditSum
     */
    private $creditSum = null;

    /**
     * Код валюты выписки. Передается цифровой код валюты.
     *
     * @property string $currCode
     */
    private $currCode = null;

    /**
     * Итого оборотов по дебету
     *
     * @property float $debetSum
     */
    private $debetSum = null;

    /**
     * Данные актуальны на (дата)
     *
     * @property \DateTime $docDate
     */
    private $docDate = null;

    /**
     * Данные актуальны на (время)
     *
     * @property \DateTime $docTime
     */
    private $docTime = null;

    /**
     * Дата приема к исполнению
     *
     * @property \DateTime $acceptDate
     */
    private $acceptDate = null;

    /**
     * Исходящий остаток
     *
     * @property float $outBal
     */
    private $outBal = null;

    /**
     * Дата выписки
     *
     * @property \DateTime $stmtDate
     */
    private $stmtDate = null;

    /**
     * Тип выписки. Допустимые значения:
     *  1 – для итоговых выписок
     *  0 – для промежуточных.
     *
     * @property int $stmtType
     */
    private $stmtType = null;

    /**
     * Доступный остаток
     *
     * @property float $ldgBalance
     */
    private $ldgBalance = null;

    /**
     * Сообщение из банка
     *
     * @property string $docComment
     */
    private $docComment = null;

    /**
     * Ответственный исполнитель из банка
     *
     * @property string $author
     */
    private $author = null;

    /**
     * Дата начала периода выписки
     *
     * @property \DateTime $beginDate
     */
    private $beginDate = null;

    /**
     * Дата окончания периода выписки
     *
     * @property \DateTime $endDate
     */
    private $endDate = null;

    /**
     * Дата последней операции по счету
     *
     * @property \DateTime $lastMovetDate
     */
    private $lastMovetDate = null;

    /**
     * Дата предыдущей операции по счету
     *
     * @property \DateTime $lastStmtDate
     */
    private $lastStmtDate = null;

    /**
     * Входящий остаток
     *
     * @property float $enterBal
     */
    private $enterBal = null;

    /**
     * Входящий остаток в национальной валюте
     *
     * @property float $enterBalNat
     */
    private $enterBalNat = null;

    /**
     * Сумма по дебету в национальной валюте
     *
     * @property float $debetSumNat
     */
    private $debetSumNat = null;

    /**
     * Сумма по кредиту в национальной валюте
     *
     * @property float $creditSumNat
     */
    private $creditSumNat = null;

    /**
     * Сумма по картотеке 1
     *
     * @property float $card1Sum
     */
    private $card1Sum = null;

    /**
     * Сумма по картотеке 2
     *
     * @property float $card2Sum
     */
    private $card2Sum = null;

    /**
     * Курс ЦБ за 1 ед. валюты на начало периода
     *
     * @property float $rateIn
     */
    private $rateIn = null;

    /**
     * Курс за 1 ед. валюты на конец выписки
     *
     * @property float $rateOut
     */
    private $rateOut = null;

    /**
     * Исходящий остаток в нац. валюте
     *
     * @property float $outBalNat
     */
    private $outBalNat = null;

    /**
     * Номер документа
     *
     * @property string $docNum
     */
    private $docNum = null;

    /**
     * @property \common\models\raiffeisenxml\response\TransInfoRaifType[] $docs
     */
    private $docs = null;

    /**
     * @property \common\models\raiffeisenxml\response\DigitalSignType[] $signs
     */
    private $signs = null;

    /**
     * Gets as extId
     *
     * Идентификатор выписки в ELBRUS
     *
     * @return string
     */
    public function getExtId()
    {
        return $this->extId;
    }

    /**
     * Sets a new extId
     *
     * Идентификатор выписки в ELBRUS
     *
     * @param string $extId
     * @return static
     */
    public function setExtId($extId)
    {
        $this->extId = $extId;
        return $this;
    }

    /**
     * Gets as acc
     *
     * Счет клиента
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
     * Счет клиента
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
     * Gets as bic
     *
     * БИК банка клиента
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
     * БИК банка клиента
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
     * Gets as creditSum
     *
     * Итого оборотов по кредиту
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
     * Итого оборотов по кредиту
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
     * Gets as currCode
     *
     * Код валюты выписки. Передается цифровой код валюты.
     *
     * @return string
     */
    public function getCurrCode()
    {
        return $this->currCode;
    }

    /**
     * Sets a new currCode
     *
     * Код валюты выписки. Передается цифровой код валюты.
     *
     * @param string $currCode
     * @return static
     */
    public function setCurrCode($currCode)
    {
        $this->currCode = $currCode;
        return $this;
    }

    /**
     * Gets as debetSum
     *
     * Итого оборотов по дебету
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
     * Итого оборотов по дебету
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
     * Gets as docDate
     *
     * Данные актуальны на (дата)
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
     * Данные актуальны на (дата)
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
     * Gets as docTime
     *
     * Данные актуальны на (время)
     *
     * @return \DateTime
     */
    public function getDocTime()
    {
        return $this->docTime;
    }

    /**
     * Sets a new docTime
     *
     * Данные актуальны на (время)
     *
     * @param \DateTime $docTime
     * @return static
     */
    public function setDocTime(\DateTime $docTime)
    {
        $this->docTime = $docTime;
        return $this;
    }

    /**
     * Gets as acceptDate
     *
     * Дата приема к исполнению
     *
     * @return \DateTime
     */
    public function getAcceptDate()
    {
        return $this->acceptDate;
    }

    /**
     * Sets a new acceptDate
     *
     * Дата приема к исполнению
     *
     * @param \DateTime $acceptDate
     * @return static
     */
    public function setAcceptDate(\DateTime $acceptDate)
    {
        $this->acceptDate = $acceptDate;
        return $this;
    }

    /**
     * Gets as outBal
     *
     * Исходящий остаток
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
     * Исходящий остаток
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
     * Gets as stmtDate
     *
     * Дата выписки
     *
     * @return \DateTime
     */
    public function getStmtDate()
    {
        return $this->stmtDate;
    }

    /**
     * Sets a new stmtDate
     *
     * Дата выписки
     *
     * @param \DateTime $stmtDate
     * @return static
     */
    public function setStmtDate(\DateTime $stmtDate)
    {
        $this->stmtDate = $stmtDate;
        return $this;
    }

    /**
     * Gets as stmtType
     *
     * Тип выписки. Допустимые значения:
     *  1 – для итоговых выписок
     *  0 – для промежуточных.
     *
     * @return int
     */
    public function getStmtType()
    {
        return $this->stmtType;
    }

    /**
     * Sets a new stmtType
     *
     * Тип выписки. Допустимые значения:
     *  1 – для итоговых выписок
     *  0 – для промежуточных.
     *
     * @param int $stmtType
     * @return static
     */
    public function setStmtType($stmtType)
    {
        $this->stmtType = $stmtType;
        return $this;
    }

    /**
     * Gets as ldgBalance
     *
     * Доступный остаток
     *
     * @return float
     */
    public function getLdgBalance()
    {
        return $this->ldgBalance;
    }

    /**
     * Sets a new ldgBalance
     *
     * Доступный остаток
     *
     * @param float $ldgBalance
     * @return static
     */
    public function setLdgBalance($ldgBalance)
    {
        $this->ldgBalance = $ldgBalance;
        return $this;
    }

    /**
     * Gets as docComment
     *
     * Сообщение из банка
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
     * Сообщение из банка
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
     * Gets as author
     *
     * Ответственный исполнитель из банка
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
     * Ответственный исполнитель из банка
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
     * Gets as beginDate
     *
     * Дата начала периода выписки
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
     * Дата начала периода выписки
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
     * Дата окончания периода выписки
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
     * Дата окончания периода выписки
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
     * Gets as lastMovetDate
     *
     * Дата последней операции по счету
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
     * Дата последней операции по счету
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
     * Gets as lastStmtDate
     *
     * Дата предыдущей операции по счету
     *
     * @return \DateTime
     */
    public function getLastStmtDate()
    {
        return $this->lastStmtDate;
    }

    /**
     * Sets a new lastStmtDate
     *
     * Дата предыдущей операции по счету
     *
     * @param \DateTime $lastStmtDate
     * @return static
     */
    public function setLastStmtDate(\DateTime $lastStmtDate)
    {
        $this->lastStmtDate = $lastStmtDate;
        return $this;
    }

    /**
     * Gets as enterBal
     *
     * Входящий остаток
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
     * Входящий остаток
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
     * Входящий остаток в национальной валюте
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
     * Входящий остаток в национальной валюте
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
     * Gets as debetSumNat
     *
     * Сумма по дебету в национальной валюте
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
     * Сумма по дебету в национальной валюте
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
     * Сумма по кредиту в национальной валюте
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
     * Сумма по кредиту в национальной валюте
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
     * Gets as card1Sum
     *
     * Сумма по картотеке 1
     *
     * @return float
     */
    public function getCard1Sum()
    {
        return $this->card1Sum;
    }

    /**
     * Sets a new card1Sum
     *
     * Сумма по картотеке 1
     *
     * @param float $card1Sum
     * @return static
     */
    public function setCard1Sum($card1Sum)
    {
        $this->card1Sum = $card1Sum;
        return $this;
    }

    /**
     * Gets as card2Sum
     *
     * Сумма по картотеке 2
     *
     * @return float
     */
    public function getCard2Sum()
    {
        return $this->card2Sum;
    }

    /**
     * Sets a new card2Sum
     *
     * Сумма по картотеке 2
     *
     * @param float $card2Sum
     * @return static
     */
    public function setCard2Sum($card2Sum)
    {
        $this->card2Sum = $card2Sum;
        return $this;
    }

    /**
     * Gets as rateIn
     *
     * Курс ЦБ за 1 ед. валюты на начало периода
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
     * Курс ЦБ за 1 ед. валюты на начало периода
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
     * Курс за 1 ед. валюты на конец выписки
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
     * Курс за 1 ед. валюты на конец выписки
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
     * Gets as docNum
     *
     * Номер документа
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
     * Номер документа
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
     * Adds as transInfo
     *
     * @return static
     * @param \common\models\raiffeisenxml\response\TransInfoRaifType $transInfo
     */
    public function addToDocs(\common\models\raiffeisenxml\response\TransInfoRaifType $transInfo)
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
     * @return \common\models\raiffeisenxml\response\TransInfoRaifType[]
     */
    public function getDocs()
    {
        return $this->docs;
    }

    /**
     * Sets a new docs
     *
     * @param \common\models\raiffeisenxml\response\TransInfoRaifType[] $docs
     * @return static
     */
    public function setDocs(array $docs)
    {
        $this->docs = $docs;
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


}


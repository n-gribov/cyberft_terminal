<?php

namespace common\models\sbbolxml\response\PayRequestType;

/**
 * Class representing PayReqInfoAType
 */
class PayReqInfoAType
{

    /**
     * Условие оплаты (поле 35) Однозначный код значения реквизита. [Кодовые
     *  значения реквизитов]. Цифровой, 1 значный:
     *  1- с заранее данным акцептом
     *  2- требуется получение акцепта
     *
     * @property integer $paytCondition
     */
    private $paytCondition = null;

    /**
     * Срок для акцепта (поле 36). Количество дней для акцепта,
     *  установленного договором, в случае, если платеж производится при условии акцепта
     *  платежного требования плательщиком. Заполняется только для Платежного Требования
     *
     * @property integer $acptTerm
     */
    private $acptTerm = null;

    /**
     * Дата отсылки (вручения) плательщику предусмотренных договором
     *  документов (поле 37). Проставляется в случае, если эти документы были отосланы
     *  (вручены) получателем средств плательщику. Заполняется только для Платежного
     *  Требования
     *
     * @property \DateTime $docDispatchDate
     */
    private $docDispatchDate = null;

    /**
     * Дата представления документов в банк (поле 48). Дата представления
     *  документов получателем в обслуживающий его банк
     *
     * @property \DateTime $receiptDateCollectBank
     */
    private $receiptDateCollectBank = null;

    /**
     * Поступило в банк плательщика (поле 62). Дата поступления расчетного
     *  документа в банк плательщика.
     *
     * @property \DateTime $receiptDate
     */
    private $receiptDate = null;

    /**
     * Дата помещения в картотеку (поле 63).
     *
     * @property \DateTime $fileDate
     */
    private $fileDate = null;

    /**
     * Списано со счета плательщика (поле 71).
     *
     * @property \DateTime $chargeOffDate
     */
    private $chargeOffDate = null;

    /**
     * Окончание срока акцепта (поле 72).
     *
     * @property \DateTime $maturityDate
     */
    private $maturityDate = null;

    /**
     * Сумма исходного расчетного документа, предъявленного к акцепту (поле
     *  150). Пока не используется.
     *
     * @property integer $acptSum
     */
    private $acptSum = null;

    /**
     * Условие оплаты (продолжение поля 35). При списании средств без акцепта
     *  плательщика – ссылка на номер, дату принятия и статью закона или номер, дату и пункт
     *  договора о праве безакцептного списания.
     *
     * @property string $paytConditionRef
     */
    private $paytConditionRef = null;

    /**
     * Номер частичного платежа.
     *
     * @property string $numPartPay
     */
    private $numPartPay = null;

    /**
     * Номер платежного ордера. Указывается номер платежного ордера
     *
     * @property string $numPayOrder
     */
    private $numPayOrder = null;

    /**
     * Дата платежного ордера. Указывается дата платежного ордера в порядке,
     *  установленном для реквизита "Дата"
     *
     * @property \DateTime $payOrderDate
     */
    private $payOrderDate = null;

    /**
     * Указывается сумма частичного платежа цифрами в порядке, установленном
     *  для реквизита "Сумма"
     *
     * @property float $sumPartPay
     */
    private $sumPartPay = null;

    /**
     * Указывается сумма остатка платежа цифрами в порядке, установленном для
     *  реквизита "Сумма"
     *
     * @property float $balancePayDoc
     */
    private $balancePayDoc = null;

    /**
     * @property \common\models\sbbolxml\response\ParamsType\ParamAType[] $params
     */
    private $params = null;

    /**
     * Gets as paytCondition
     *
     * Условие оплаты (поле 35) Однозначный код значения реквизита. [Кодовые
     *  значения реквизитов]. Цифровой, 1 значный:
     *  1- с заранее данным акцептом
     *  2- требуется получение акцепта
     *
     * @return integer
     */
    public function getPaytCondition()
    {
        return $this->paytCondition;
    }

    /**
     * Sets a new paytCondition
     *
     * Условие оплаты (поле 35) Однозначный код значения реквизита. [Кодовые
     *  значения реквизитов]. Цифровой, 1 значный:
     *  1- с заранее данным акцептом
     *  2- требуется получение акцепта
     *
     * @param integer $paytCondition
     * @return static
     */
    public function setPaytCondition($paytCondition)
    {
        $this->paytCondition = $paytCondition;
        return $this;
    }

    /**
     * Gets as acptTerm
     *
     * Срок для акцепта (поле 36). Количество дней для акцепта,
     *  установленного договором, в случае, если платеж производится при условии акцепта
     *  платежного требования плательщиком. Заполняется только для Платежного Требования
     *
     * @return integer
     */
    public function getAcptTerm()
    {
        return $this->acptTerm;
    }

    /**
     * Sets a new acptTerm
     *
     * Срок для акцепта (поле 36). Количество дней для акцепта,
     *  установленного договором, в случае, если платеж производится при условии акцепта
     *  платежного требования плательщиком. Заполняется только для Платежного Требования
     *
     * @param integer $acptTerm
     * @return static
     */
    public function setAcptTerm($acptTerm)
    {
        $this->acptTerm = $acptTerm;
        return $this;
    }

    /**
     * Gets as docDispatchDate
     *
     * Дата отсылки (вручения) плательщику предусмотренных договором
     *  документов (поле 37). Проставляется в случае, если эти документы были отосланы
     *  (вручены) получателем средств плательщику. Заполняется только для Платежного
     *  Требования
     *
     * @return \DateTime
     */
    public function getDocDispatchDate()
    {
        return $this->docDispatchDate;
    }

    /**
     * Sets a new docDispatchDate
     *
     * Дата отсылки (вручения) плательщику предусмотренных договором
     *  документов (поле 37). Проставляется в случае, если эти документы были отосланы
     *  (вручены) получателем средств плательщику. Заполняется только для Платежного
     *  Требования
     *
     * @param \DateTime $docDispatchDate
     * @return static
     */
    public function setDocDispatchDate(\DateTime $docDispatchDate)
    {
        $this->docDispatchDate = $docDispatchDate;
        return $this;
    }

    /**
     * Gets as receiptDateCollectBank
     *
     * Дата представления документов в банк (поле 48). Дата представления
     *  документов получателем в обслуживающий его банк
     *
     * @return \DateTime
     */
    public function getReceiptDateCollectBank()
    {
        return $this->receiptDateCollectBank;
    }

    /**
     * Sets a new receiptDateCollectBank
     *
     * Дата представления документов в банк (поле 48). Дата представления
     *  документов получателем в обслуживающий его банк
     *
     * @param \DateTime $receiptDateCollectBank
     * @return static
     */
    public function setReceiptDateCollectBank(\DateTime $receiptDateCollectBank)
    {
        $this->receiptDateCollectBank = $receiptDateCollectBank;
        return $this;
    }

    /**
     * Gets as receiptDate
     *
     * Поступило в банк плательщика (поле 62). Дата поступления расчетного
     *  документа в банк плательщика.
     *
     * @return \DateTime
     */
    public function getReceiptDate()
    {
        return $this->receiptDate;
    }

    /**
     * Sets a new receiptDate
     *
     * Поступило в банк плательщика (поле 62). Дата поступления расчетного
     *  документа в банк плательщика.
     *
     * @param \DateTime $receiptDate
     * @return static
     */
    public function setReceiptDate(\DateTime $receiptDate)
    {
        $this->receiptDate = $receiptDate;
        return $this;
    }

    /**
     * Gets as fileDate
     *
     * Дата помещения в картотеку (поле 63).
     *
     * @return \DateTime
     */
    public function getFileDate()
    {
        return $this->fileDate;
    }

    /**
     * Sets a new fileDate
     *
     * Дата помещения в картотеку (поле 63).
     *
     * @param \DateTime $fileDate
     * @return static
     */
    public function setFileDate(\DateTime $fileDate)
    {
        $this->fileDate = $fileDate;
        return $this;
    }

    /**
     * Gets as chargeOffDate
     *
     * Списано со счета плательщика (поле 71).
     *
     * @return \DateTime
     */
    public function getChargeOffDate()
    {
        return $this->chargeOffDate;
    }

    /**
     * Sets a new chargeOffDate
     *
     * Списано со счета плательщика (поле 71).
     *
     * @param \DateTime $chargeOffDate
     * @return static
     */
    public function setChargeOffDate(\DateTime $chargeOffDate)
    {
        $this->chargeOffDate = $chargeOffDate;
        return $this;
    }

    /**
     * Gets as maturityDate
     *
     * Окончание срока акцепта (поле 72).
     *
     * @return \DateTime
     */
    public function getMaturityDate()
    {
        return $this->maturityDate;
    }

    /**
     * Sets a new maturityDate
     *
     * Окончание срока акцепта (поле 72).
     *
     * @param \DateTime $maturityDate
     * @return static
     */
    public function setMaturityDate(\DateTime $maturityDate)
    {
        $this->maturityDate = $maturityDate;
        return $this;
    }

    /**
     * Gets as acptSum
     *
     * Сумма исходного расчетного документа, предъявленного к акцепту (поле
     *  150). Пока не используется.
     *
     * @return integer
     */
    public function getAcptSum()
    {
        return $this->acptSum;
    }

    /**
     * Sets a new acptSum
     *
     * Сумма исходного расчетного документа, предъявленного к акцепту (поле
     *  150). Пока не используется.
     *
     * @param integer $acptSum
     * @return static
     */
    public function setAcptSum($acptSum)
    {
        $this->acptSum = $acptSum;
        return $this;
    }

    /**
     * Gets as paytConditionRef
     *
     * Условие оплаты (продолжение поля 35). При списании средств без акцепта
     *  плательщика – ссылка на номер, дату принятия и статью закона или номер, дату и пункт
     *  договора о праве безакцептного списания.
     *
     * @return string
     */
    public function getPaytConditionRef()
    {
        return $this->paytConditionRef;
    }

    /**
     * Sets a new paytConditionRef
     *
     * Условие оплаты (продолжение поля 35). При списании средств без акцепта
     *  плательщика – ссылка на номер, дату принятия и статью закона или номер, дату и пункт
     *  договора о праве безакцептного списания.
     *
     * @param string $paytConditionRef
     * @return static
     */
    public function setPaytConditionRef($paytConditionRef)
    {
        $this->paytConditionRef = $paytConditionRef;
        return $this;
    }

    /**
     * Gets as numPartPay
     *
     * Номер частичного платежа.
     *
     * @return string
     */
    public function getNumPartPay()
    {
        return $this->numPartPay;
    }

    /**
     * Sets a new numPartPay
     *
     * Номер частичного платежа.
     *
     * @param string $numPartPay
     * @return static
     */
    public function setNumPartPay($numPartPay)
    {
        $this->numPartPay = $numPartPay;
        return $this;
    }

    /**
     * Gets as numPayOrder
     *
     * Номер платежного ордера. Указывается номер платежного ордера
     *
     * @return string
     */
    public function getNumPayOrder()
    {
        return $this->numPayOrder;
    }

    /**
     * Sets a new numPayOrder
     *
     * Номер платежного ордера. Указывается номер платежного ордера
     *
     * @param string $numPayOrder
     * @return static
     */
    public function setNumPayOrder($numPayOrder)
    {
        $this->numPayOrder = $numPayOrder;
        return $this;
    }

    /**
     * Gets as payOrderDate
     *
     * Дата платежного ордера. Указывается дата платежного ордера в порядке,
     *  установленном для реквизита "Дата"
     *
     * @return \DateTime
     */
    public function getPayOrderDate()
    {
        return $this->payOrderDate;
    }

    /**
     * Sets a new payOrderDate
     *
     * Дата платежного ордера. Указывается дата платежного ордера в порядке,
     *  установленном для реквизита "Дата"
     *
     * @param \DateTime $payOrderDate
     * @return static
     */
    public function setPayOrderDate(\DateTime $payOrderDate)
    {
        $this->payOrderDate = $payOrderDate;
        return $this;
    }

    /**
     * Gets as sumPartPay
     *
     * Указывается сумма частичного платежа цифрами в порядке, установленном
     *  для реквизита "Сумма"
     *
     * @return float
     */
    public function getSumPartPay()
    {
        return $this->sumPartPay;
    }

    /**
     * Sets a new sumPartPay
     *
     * Указывается сумма частичного платежа цифрами в порядке, установленном
     *  для реквизита "Сумма"
     *
     * @param float $sumPartPay
     * @return static
     */
    public function setSumPartPay($sumPartPay)
    {
        $this->sumPartPay = $sumPartPay;
        return $this;
    }

    /**
     * Gets as balancePayDoc
     *
     * Указывается сумма остатка платежа цифрами в порядке, установленном для
     *  реквизита "Сумма"
     *
     * @return float
     */
    public function getBalancePayDoc()
    {
        return $this->balancePayDoc;
    }

    /**
     * Sets a new balancePayDoc
     *
     * Указывается сумма остатка платежа цифрами в порядке, установленном для
     *  реквизита "Сумма"
     *
     * @param float $balancePayDoc
     * @return static
     */
    public function setBalancePayDoc($balancePayDoc)
    {
        $this->balancePayDoc = $balancePayDoc;
        return $this;
    }

    /**
     * Adds as param
     *
     * @return static
     * @param \common\models\sbbolxml\response\ParamsType\ParamAType $param
     */
    public function addToParams(\common\models\sbbolxml\response\ParamsType\ParamAType $param)
    {
        $this->params[] = $param;
        return $this;
    }

    /**
     * isset params
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetParams($index)
    {
        return isset($this->params[$index]);
    }

    /**
     * unset params
     *
     * @param scalar $index
     * @return void
     */
    public function unsetParams($index)
    {
        unset($this->params[$index]);
    }

    /**
     * Gets as params
     *
     * @return \common\models\sbbolxml\response\ParamsType\ParamAType[]
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * Sets a new params
     *
     * @param \common\models\sbbolxml\response\ParamsType\ParamAType[] $params
     * @return static
     */
    public function setParams(array $params)
    {
        $this->params = $params;
        return $this;
    }


}


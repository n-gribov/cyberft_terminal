<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing CurrDealCertificateDoc181IType
 *
 *
 * XSD Type: CurrDealCertificateDoc181I
 */
class CurrDealCertificateDoc181IType
{

    /**
     * Порядковый номер строки в справке
     *
     * @property integer $lineNumber
     */
    private $lineNumber = null;

    /**
     * Вид валютной операции
     *
     * @property \common\models\sbbolxml\request\OperInfoType $operInfo
     */
    private $operInfo = null;

    /**
     * Сумма платежа
     *
     * @property \common\models\sbbolxml\request\CurrAmountType $paymentSum
     */
    private $paymentSum = null;

    /**
     * Информация о контракте
     *
     * @property \common\models\sbbolxml\request\ContractDateOptionalType $contract
     */
    private $contract = null;

    /**
     * Уникальный номер контракта (строка со слешами)
     *
     * @property string $dealPassNum
     */
    private $dealPassNum = null;

    /**
     * Сумма платежа в валюте цены контракта (кредитного договора)
     *
     * @property \common\models\sbbolxml\request\CurrAmountType $contractSum
     */
    private $contractSum = null;

    /**
     * Ожидаемый срок исполнения обязательств по контракту
     *
     * @property \DateTime $expectedTerm
     */
    private $expectedTerm = null;

    /**
     * Примечания по данной строке
     *
     * @property string $addInfo
     */
    private $addInfo = null;

    /**
     * Код основания проведения операции
     *  1 - Контракт (кредитный договор) с нерезидентом, сумма которого не превышает 200 000 рублей РФ
     *  2 - Контракт (кредитный договор) с нерезидентом, не требующий постановки на учет, сумма которого превышает 200 000 рублей РФ
     *  3 - Контракт (кредитный договор), поставленный на учет в банке
     *  4 - Иные
     *
     * @property string $operationBase
     */
    private $operationBase = null;

    /**
     * Код перечисления возможных составов предоставляемой информации:
     *  1 - Информация об уникальном номере контракта (кредитного договора)
     *  2 - Документы, связанные с проведением операции
     *  3 - Информация о коде вида операции
     *  4 - Информация об уникальном номере контракта и коде вида операции
     *  5 - Информация об уникальном номере контракта и документы, связанные с проведением операции
     *  6 - Документы, связанные с проведением операции, представлены ранее
     *  8 - Сведения уполномоченного банка о проведении операции с указанием уникального номера контракта (кредитного договора)
     *
     * @property string $dataComposition
     */
    private $dataComposition = null;

    /**
     * Условия расчетов
     *  "0"- Аванс
     *  "1"- По факту
     *
     * @property string $termOfPayment
     */
    private $termOfPayment = null;

    /**
     * Gets as lineNumber
     *
     * Порядковый номер строки в справке
     *
     * @return integer
     */
    public function getLineNumber()
    {
        return $this->lineNumber;
    }

    /**
     * Sets a new lineNumber
     *
     * Порядковый номер строки в справке
     *
     * @param integer $lineNumber
     * @return static
     */
    public function setLineNumber($lineNumber)
    {
        $this->lineNumber = $lineNumber;
        return $this;
    }

    /**
     * Gets as operInfo
     *
     * Вид валютной операции
     *
     * @return \common\models\sbbolxml\request\OperInfoType
     */
    public function getOperInfo()
    {
        return $this->operInfo;
    }

    /**
     * Sets a new operInfo
     *
     * Вид валютной операции
     *
     * @param \common\models\sbbolxml\request\OperInfoType $operInfo
     * @return static
     */
    public function setOperInfo(\common\models\sbbolxml\request\OperInfoType $operInfo)
    {
        $this->operInfo = $operInfo;
        return $this;
    }

    /**
     * Gets as paymentSum
     *
     * Сумма платежа
     *
     * @return \common\models\sbbolxml\request\CurrAmountType
     */
    public function getPaymentSum()
    {
        return $this->paymentSum;
    }

    /**
     * Sets a new paymentSum
     *
     * Сумма платежа
     *
     * @param \common\models\sbbolxml\request\CurrAmountType $paymentSum
     * @return static
     */
    public function setPaymentSum(\common\models\sbbolxml\request\CurrAmountType $paymentSum)
    {
        $this->paymentSum = $paymentSum;
        return $this;
    }

    /**
     * Gets as contract
     *
     * Информация о контракте
     *
     * @return \common\models\sbbolxml\request\ContractDateOptionalType
     */
    public function getContract()
    {
        return $this->contract;
    }

    /**
     * Sets a new contract
     *
     * Информация о контракте
     *
     * @param \common\models\sbbolxml\request\ContractDateOptionalType $contract
     * @return static
     */
    public function setContract(\common\models\sbbolxml\request\ContractDateOptionalType $contract)
    {
        $this->contract = $contract;
        return $this;
    }

    /**
     * Gets as dealPassNum
     *
     * Уникальный номер контракта (строка со слешами)
     *
     * @return string
     */
    public function getDealPassNum()
    {
        return $this->dealPassNum;
    }

    /**
     * Sets a new dealPassNum
     *
     * Уникальный номер контракта (строка со слешами)
     *
     * @param string $dealPassNum
     * @return static
     */
    public function setDealPassNum($dealPassNum)
    {
        $this->dealPassNum = $dealPassNum;
        return $this;
    }

    /**
     * Gets as contractSum
     *
     * Сумма платежа в валюте цены контракта (кредитного договора)
     *
     * @return \common\models\sbbolxml\request\CurrAmountType
     */
    public function getContractSum()
    {
        return $this->contractSum;
    }

    /**
     * Sets a new contractSum
     *
     * Сумма платежа в валюте цены контракта (кредитного договора)
     *
     * @param \common\models\sbbolxml\request\CurrAmountType $contractSum
     * @return static
     */
    public function setContractSum(\common\models\sbbolxml\request\CurrAmountType $contractSum)
    {
        $this->contractSum = $contractSum;
        return $this;
    }

    /**
     * Gets as expectedTerm
     *
     * Ожидаемый срок исполнения обязательств по контракту
     *
     * @return \DateTime
     */
    public function getExpectedTerm()
    {
        return $this->expectedTerm;
    }

    /**
     * Sets a new expectedTerm
     *
     * Ожидаемый срок исполнения обязательств по контракту
     *
     * @param \DateTime $expectedTerm
     * @return static
     */
    public function setExpectedTerm(\DateTime $expectedTerm)
    {
        $this->expectedTerm = $expectedTerm;
        return $this;
    }

    /**
     * Gets as addInfo
     *
     * Примечания по данной строке
     *
     * @return string
     */
    public function getAddInfo()
    {
        return $this->addInfo;
    }

    /**
     * Sets a new addInfo
     *
     * Примечания по данной строке
     *
     * @param string $addInfo
     * @return static
     */
    public function setAddInfo($addInfo)
    {
        $this->addInfo = $addInfo;
        return $this;
    }

    /**
     * Gets as operationBase
     *
     * Код основания проведения операции
     *  1 - Контракт (кредитный договор) с нерезидентом, сумма которого не превышает 200 000 рублей РФ
     *  2 - Контракт (кредитный договор) с нерезидентом, не требующий постановки на учет, сумма которого превышает 200 000 рублей РФ
     *  3 - Контракт (кредитный договор), поставленный на учет в банке
     *  4 - Иные
     *
     * @return string
     */
    public function getOperationBase()
    {
        return $this->operationBase;
    }

    /**
     * Sets a new operationBase
     *
     * Код основания проведения операции
     *  1 - Контракт (кредитный договор) с нерезидентом, сумма которого не превышает 200 000 рублей РФ
     *  2 - Контракт (кредитный договор) с нерезидентом, не требующий постановки на учет, сумма которого превышает 200 000 рублей РФ
     *  3 - Контракт (кредитный договор), поставленный на учет в банке
     *  4 - Иные
     *
     * @param string $operationBase
     * @return static
     */
    public function setOperationBase($operationBase)
    {
        $this->operationBase = $operationBase;
        return $this;
    }

    /**
     * Gets as dataComposition
     *
     * Код перечисления возможных составов предоставляемой информации:
     *  1 - Информация об уникальном номере контракта (кредитного договора)
     *  2 - Документы, связанные с проведением операции
     *  3 - Информация о коде вида операции
     *  4 - Информация об уникальном номере контракта и коде вида операции
     *  5 - Информация об уникальном номере контракта и документы, связанные с проведением операции
     *  6 - Документы, связанные с проведением операции, представлены ранее
     *  8 - Сведения уполномоченного банка о проведении операции с указанием уникального номера контракта (кредитного договора)
     *
     * @return string
     */
    public function getDataComposition()
    {
        return $this->dataComposition;
    }

    /**
     * Sets a new dataComposition
     *
     * Код перечисления возможных составов предоставляемой информации:
     *  1 - Информация об уникальном номере контракта (кредитного договора)
     *  2 - Документы, связанные с проведением операции
     *  3 - Информация о коде вида операции
     *  4 - Информация об уникальном номере контракта и коде вида операции
     *  5 - Информация об уникальном номере контракта и документы, связанные с проведением операции
     *  6 - Документы, связанные с проведением операции, представлены ранее
     *  8 - Сведения уполномоченного банка о проведении операции с указанием уникального номера контракта (кредитного договора)
     *
     * @param string $dataComposition
     * @return static
     */
    public function setDataComposition($dataComposition)
    {
        $this->dataComposition = $dataComposition;
        return $this;
    }

    /**
     * Gets as termOfPayment
     *
     * Условия расчетов
     *  "0"- Аванс
     *  "1"- По факту
     *
     * @return string
     */
    public function getTermOfPayment()
    {
        return $this->termOfPayment;
    }

    /**
     * Sets a new termOfPayment
     *
     * Условия расчетов
     *  "0"- Аванс
     *  "1"- По факту
     *
     * @param string $termOfPayment
     * @return static
     */
    public function setTermOfPayment($termOfPayment)
    {
        $this->termOfPayment = $termOfPayment;
        return $this;
    }


}


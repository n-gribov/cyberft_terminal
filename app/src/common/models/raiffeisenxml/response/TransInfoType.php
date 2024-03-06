<?php

namespace common\models\raiffeisenxml\response;

/**
 * Class representing TransInfoType
 *
 *
 * XSD Type: TransInfo
 */
class TransInfoType
{

    /**
     * @property string $payeeINN
     */
    private $payeeINN = null;

    /**
     * @property string $payeeAcc
     */
    private $payeeAcc = null;

    /**
     * @property string $payeeName
     */
    private $payeeName = null;

    /**
     * КПП получателя.
     *
     * @property string $payeeKPP
     */
    private $payeeKPP = null;

    /**
     * ИНН плательщика (до 12)
     *
     * @property string $payerINN
     */
    private $payerINN = null;

    /**
     * Номер счета плательщика
     *
     * @property string $payerAcc
     */
    private $payerAcc = null;

    /**
     * Наименование плательщика
     *
     * @property string $payerName
     */
    private $payerName = null;

    /**
     * КПП плательщика.
     *
     * @property string $payerKPP
     */
    private $payerKPP = null;

    /**
     * @property string $payeeBankName
     */
    private $payeeBankName = null;

    /**
     * @property string $payeeBankBic
     */
    private $payeeBankBic = null;

    /**
     * @property string $payeeBankCorrAcc
     */
    private $payeeBankCorrAcc = null;

    /**
     * @property string $payerBankName
     */
    private $payerBankName = null;

    /**
     * @property string $payerBankCorrAcc
     */
    private $payerBankCorrAcc = null;

    /**
     * @property string $payerBankBic
     */
    private $payerBankBic = null;

    /**
     * Списания со счета Плательщика
     *
     * @property \DateTime $chargeOffDate
     */
    private $chargeOffDate = null;

    /**
     * Постановки в картотеку
     *
     * @property \DateTime $fileDate
     */
    private $fileDate = null;

    /**
     * Отметки банком Плательщика
     *
     * @property \DateTime $signDate
     */
    private $signDate = null;

    /**
     * Поступило в банк Плательщика
     *
     * @property \DateTime $receiptDate
     */
    private $receiptDate = null;

    /**
     * Дата перечисления платежа
     *
     * @property \DateTime $dpp
     */
    private $dpp = null;

    /**
     * Дата отметки банка Получателя
     *
     * @property \DateTime $recDate
     */
    private $recDate = null;

    /**
     * Признак дебетового/кредитового приложения
     *
     * @property bool $dc
     */
    private $dc = null;

    /**
     * Номер расчетного документа
     *
     * @property string $docNum
     */
    private $docNum = null;

    /**
     * Дата расчетного документа
     *
     * @property \DateTime $docDate
     */
    private $docDate = null;

    /**
     * Валюта платежа
     *
     * @property string $docCurr
     */
    private $docCurr = null;

    /**
     * Сумма документа в валюте платежа
     *
     * @property float $docSum
     */
    private $docSum = null;

    /**
     * Сумма документа в рублях
     *
     * @property float $docSumNat
     */
    private $docSumNat = null;

    /**
     * Назначение платежа
     *
     * @property string $purpose
     */
    private $purpose = null;

    /**
     * Код подразделения Сбербанка
     *
     * @property string $branchCode
     */
    private $branchCode = null;

    /**
     * Очередность платежа
     *
     * @property int $paymentOrder
     */
    private $paymentOrder = null;

    /**
     * Вид платежа
     *  Возможные значения: «почтой», «электронно», «телеграфом» («срочно» временно недоступно).
     *  "0" - есои не указано
     *
     * @property string $paytKind
     */
    private $paytKind = null;

    /**
     * Вид операции
     *
     * @property string $transKind
     */
    private $transKind = null;

    /**
     * Номер документа банка (до 20 символов)
     *
     * @property string $bankNumDoc
     */
    private $bankNumDoc = null;

    /**
     * Дата проводки
     *
     * @property \DateTime $carryDate
     */
    private $carryDate = null;

    /**
     * Дата списания со счета плательщика
     *
     * @property \DateTime $writeOffDate
     */
    private $writeOffDate = null;

    /**
     * Переоценка, дооценка (при получении выписки по валютным счетам)
     *
     * @property string $sTI
     */
    private $sTI = null;

    /**
     * Дата валютирования
     *
     * @property \DateTime $valueDate
     */
    private $valueDate = null;

    /**
     * Доп. тип (авизо)
     *
     * @property string $avisType
     */
    private $avisType = null;

    /**
     * @property \common\models\raiffeisenxml\response\DepartmentalInfoExtType $departmentalInfo
     */
    private $departmentalInfo = null;

    /**
     * @property \common\models\raiffeisenxml\response\DiffDocType $diffDoc
     */
    private $diffDoc = null;

    /**
     * @property \common\models\raiffeisenxml\response\CurType $cur
     */
    private $cur = null;

    /**
     * @property string $info
     */
    private $info = null;

    /**
     * @property \common\models\raiffeisenxml\response\DigitalSignType $sign
     */
    private $sign = null;

    /**
     * @property \common\models\raiffeisenxml\response\ParamsType\ParamAType[] $params
     */
    private $params = null;

    /**
     * Gets as payeeINN
     *
     * @return string
     */
    public function getPayeeINN()
    {
        return $this->payeeINN;
    }

    /**
     * Sets a new payeeINN
     *
     * @param string $payeeINN
     * @return static
     */
    public function setPayeeINN($payeeINN)
    {
        $this->payeeINN = $payeeINN;
        return $this;
    }

    /**
     * Gets as payeeAcc
     *
     * @return string
     */
    public function getPayeeAcc()
    {
        return $this->payeeAcc;
    }

    /**
     * Sets a new payeeAcc
     *
     * @param string $payeeAcc
     * @return static
     */
    public function setPayeeAcc($payeeAcc)
    {
        $this->payeeAcc = $payeeAcc;
        return $this;
    }

    /**
     * Gets as payeeName
     *
     * @return string
     */
    public function getPayeeName()
    {
        return $this->payeeName;
    }

    /**
     * Sets a new payeeName
     *
     * @param string $payeeName
     * @return static
     */
    public function setPayeeName($payeeName)
    {
        $this->payeeName = $payeeName;
        return $this;
    }

    /**
     * Gets as payeeKPP
     *
     * КПП получателя.
     *
     * @return string
     */
    public function getPayeeKPP()
    {
        return $this->payeeKPP;
    }

    /**
     * Sets a new payeeKPP
     *
     * КПП получателя.
     *
     * @param string $payeeKPP
     * @return static
     */
    public function setPayeeKPP($payeeKPP)
    {
        $this->payeeKPP = $payeeKPP;
        return $this;
    }

    /**
     * Gets as payerINN
     *
     * ИНН плательщика (до 12)
     *
     * @return string
     */
    public function getPayerINN()
    {
        return $this->payerINN;
    }

    /**
     * Sets a new payerINN
     *
     * ИНН плательщика (до 12)
     *
     * @param string $payerINN
     * @return static
     */
    public function setPayerINN($payerINN)
    {
        $this->payerINN = $payerINN;
        return $this;
    }

    /**
     * Gets as payerAcc
     *
     * Номер счета плательщика
     *
     * @return string
     */
    public function getPayerAcc()
    {
        return $this->payerAcc;
    }

    /**
     * Sets a new payerAcc
     *
     * Номер счета плательщика
     *
     * @param string $payerAcc
     * @return static
     */
    public function setPayerAcc($payerAcc)
    {
        $this->payerAcc = $payerAcc;
        return $this;
    }

    /**
     * Gets as payerName
     *
     * Наименование плательщика
     *
     * @return string
     */
    public function getPayerName()
    {
        return $this->payerName;
    }

    /**
     * Sets a new payerName
     *
     * Наименование плательщика
     *
     * @param string $payerName
     * @return static
     */
    public function setPayerName($payerName)
    {
        $this->payerName = $payerName;
        return $this;
    }

    /**
     * Gets as payerKPP
     *
     * КПП плательщика.
     *
     * @return string
     */
    public function getPayerKPP()
    {
        return $this->payerKPP;
    }

    /**
     * Sets a new payerKPP
     *
     * КПП плательщика.
     *
     * @param string $payerKPP
     * @return static
     */
    public function setPayerKPP($payerKPP)
    {
        $this->payerKPP = $payerKPP;
        return $this;
    }

    /**
     * Gets as payeeBankName
     *
     * @return string
     */
    public function getPayeeBankName()
    {
        return $this->payeeBankName;
    }

    /**
     * Sets a new payeeBankName
     *
     * @param string $payeeBankName
     * @return static
     */
    public function setPayeeBankName($payeeBankName)
    {
        $this->payeeBankName = $payeeBankName;
        return $this;
    }

    /**
     * Gets as payeeBankBic
     *
     * @return string
     */
    public function getPayeeBankBic()
    {
        return $this->payeeBankBic;
    }

    /**
     * Sets a new payeeBankBic
     *
     * @param string $payeeBankBic
     * @return static
     */
    public function setPayeeBankBic($payeeBankBic)
    {
        $this->payeeBankBic = $payeeBankBic;
        return $this;
    }

    /**
     * Gets as payeeBankCorrAcc
     *
     * @return string
     */
    public function getPayeeBankCorrAcc()
    {
        return $this->payeeBankCorrAcc;
    }

    /**
     * Sets a new payeeBankCorrAcc
     *
     * @param string $payeeBankCorrAcc
     * @return static
     */
    public function setPayeeBankCorrAcc($payeeBankCorrAcc)
    {
        $this->payeeBankCorrAcc = $payeeBankCorrAcc;
        return $this;
    }

    /**
     * Gets as payerBankName
     *
     * @return string
     */
    public function getPayerBankName()
    {
        return $this->payerBankName;
    }

    /**
     * Sets a new payerBankName
     *
     * @param string $payerBankName
     * @return static
     */
    public function setPayerBankName($payerBankName)
    {
        $this->payerBankName = $payerBankName;
        return $this;
    }

    /**
     * Gets as payerBankCorrAcc
     *
     * @return string
     */
    public function getPayerBankCorrAcc()
    {
        return $this->payerBankCorrAcc;
    }

    /**
     * Sets a new payerBankCorrAcc
     *
     * @param string $payerBankCorrAcc
     * @return static
     */
    public function setPayerBankCorrAcc($payerBankCorrAcc)
    {
        $this->payerBankCorrAcc = $payerBankCorrAcc;
        return $this;
    }

    /**
     * Gets as payerBankBic
     *
     * @return string
     */
    public function getPayerBankBic()
    {
        return $this->payerBankBic;
    }

    /**
     * Sets a new payerBankBic
     *
     * @param string $payerBankBic
     * @return static
     */
    public function setPayerBankBic($payerBankBic)
    {
        $this->payerBankBic = $payerBankBic;
        return $this;
    }

    /**
     * Gets as chargeOffDate
     *
     * Списания со счета Плательщика
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
     * Списания со счета Плательщика
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
     * Gets as fileDate
     *
     * Постановки в картотеку
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
     * Постановки в картотеку
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
     * Gets as signDate
     *
     * Отметки банком Плательщика
     *
     * @return \DateTime
     */
    public function getSignDate()
    {
        return $this->signDate;
    }

    /**
     * Sets a new signDate
     *
     * Отметки банком Плательщика
     *
     * @param \DateTime $signDate
     * @return static
     */
    public function setSignDate(\DateTime $signDate)
    {
        $this->signDate = $signDate;
        return $this;
    }

    /**
     * Gets as receiptDate
     *
     * Поступило в банк Плательщика
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
     * Поступило в банк Плательщика
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
     * Gets as dpp
     *
     * Дата перечисления платежа
     *
     * @return \DateTime
     */
    public function getDpp()
    {
        return $this->dpp;
    }

    /**
     * Sets a new dpp
     *
     * Дата перечисления платежа
     *
     * @param \DateTime $dpp
     * @return static
     */
    public function setDpp(\DateTime $dpp)
    {
        $this->dpp = $dpp;
        return $this;
    }

    /**
     * Gets as recDate
     *
     * Дата отметки банка Получателя
     *
     * @return \DateTime
     */
    public function getRecDate()
    {
        return $this->recDate;
    }

    /**
     * Sets a new recDate
     *
     * Дата отметки банка Получателя
     *
     * @param \DateTime $recDate
     * @return static
     */
    public function setRecDate(\DateTime $recDate)
    {
        $this->recDate = $recDate;
        return $this;
    }

    /**
     * Gets as dc
     *
     * Признак дебетового/кредитового приложения
     *
     * @return bool
     */
    public function getDc()
    {
        return $this->dc;
    }

    /**
     * Sets a new dc
     *
     * Признак дебетового/кредитового приложения
     *
     * @param bool $dc
     * @return static
     */
    public function setDc($dc)
    {
        $this->dc = $dc;
        return $this;
    }

    /**
     * Gets as docNum
     *
     * Номер расчетного документа
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
     * Номер расчетного документа
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
     * Дата расчетного документа
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
     * Дата расчетного документа
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
     * Gets as docCurr
     *
     * Валюта платежа
     *
     * @return string
     */
    public function getDocCurr()
    {
        return $this->docCurr;
    }

    /**
     * Sets a new docCurr
     *
     * Валюта платежа
     *
     * @param string $docCurr
     * @return static
     */
    public function setDocCurr($docCurr)
    {
        $this->docCurr = $docCurr;
        return $this;
    }

    /**
     * Gets as docSum
     *
     * Сумма документа в валюте платежа
     *
     * @return float
     */
    public function getDocSum()
    {
        return $this->docSum;
    }

    /**
     * Sets a new docSum
     *
     * Сумма документа в валюте платежа
     *
     * @param float $docSum
     * @return static
     */
    public function setDocSum($docSum)
    {
        $this->docSum = $docSum;
        return $this;
    }

    /**
     * Gets as docSumNat
     *
     * Сумма документа в рублях
     *
     * @return float
     */
    public function getDocSumNat()
    {
        return $this->docSumNat;
    }

    /**
     * Sets a new docSumNat
     *
     * Сумма документа в рублях
     *
     * @param float $docSumNat
     * @return static
     */
    public function setDocSumNat($docSumNat)
    {
        $this->docSumNat = $docSumNat;
        return $this;
    }

    /**
     * Gets as purpose
     *
     * Назначение платежа
     *
     * @return string
     */
    public function getPurpose()
    {
        return $this->purpose;
    }

    /**
     * Sets a new purpose
     *
     * Назначение платежа
     *
     * @param string $purpose
     * @return static
     */
    public function setPurpose($purpose)
    {
        $this->purpose = $purpose;
        return $this;
    }

    /**
     * Gets as branchCode
     *
     * Код подразделения Сбербанка
     *
     * @return string
     */
    public function getBranchCode()
    {
        return $this->branchCode;
    }

    /**
     * Sets a new branchCode
     *
     * Код подразделения Сбербанка
     *
     * @param string $branchCode
     * @return static
     */
    public function setBranchCode($branchCode)
    {
        $this->branchCode = $branchCode;
        return $this;
    }

    /**
     * Gets as paymentOrder
     *
     * Очередность платежа
     *
     * @return int
     */
    public function getPaymentOrder()
    {
        return $this->paymentOrder;
    }

    /**
     * Sets a new paymentOrder
     *
     * Очередность платежа
     *
     * @param int $paymentOrder
     * @return static
     */
    public function setPaymentOrder($paymentOrder)
    {
        $this->paymentOrder = $paymentOrder;
        return $this;
    }

    /**
     * Gets as paytKind
     *
     * Вид платежа
     *  Возможные значения: «почтой», «электронно», «телеграфом» («срочно» временно недоступно).
     *  "0" - есои не указано
     *
     * @return string
     */
    public function getPaytKind()
    {
        return $this->paytKind;
    }

    /**
     * Sets a new paytKind
     *
     * Вид платежа
     *  Возможные значения: «почтой», «электронно», «телеграфом» («срочно» временно недоступно).
     *  "0" - есои не указано
     *
     * @param string $paytKind
     * @return static
     */
    public function setPaytKind($paytKind)
    {
        $this->paytKind = $paytKind;
        return $this;
    }

    /**
     * Gets as transKind
     *
     * Вид операции
     *
     * @return string
     */
    public function getTransKind()
    {
        return $this->transKind;
    }

    /**
     * Sets a new transKind
     *
     * Вид операции
     *
     * @param string $transKind
     * @return static
     */
    public function setTransKind($transKind)
    {
        $this->transKind = $transKind;
        return $this;
    }

    /**
     * Gets as bankNumDoc
     *
     * Номер документа банка (до 20 символов)
     *
     * @return string
     */
    public function getBankNumDoc()
    {
        return $this->bankNumDoc;
    }

    /**
     * Sets a new bankNumDoc
     *
     * Номер документа банка (до 20 символов)
     *
     * @param string $bankNumDoc
     * @return static
     */
    public function setBankNumDoc($bankNumDoc)
    {
        $this->bankNumDoc = $bankNumDoc;
        return $this;
    }

    /**
     * Gets as carryDate
     *
     * Дата проводки
     *
     * @return \DateTime
     */
    public function getCarryDate()
    {
        return $this->carryDate;
    }

    /**
     * Sets a new carryDate
     *
     * Дата проводки
     *
     * @param \DateTime $carryDate
     * @return static
     */
    public function setCarryDate(\DateTime $carryDate)
    {
        $this->carryDate = $carryDate;
        return $this;
    }

    /**
     * Gets as writeOffDate
     *
     * Дата списания со счета плательщика
     *
     * @return \DateTime
     */
    public function getWriteOffDate()
    {
        return $this->writeOffDate;
    }

    /**
     * Sets a new writeOffDate
     *
     * Дата списания со счета плательщика
     *
     * @param \DateTime $writeOffDate
     * @return static
     */
    public function setWriteOffDate(\DateTime $writeOffDate)
    {
        $this->writeOffDate = $writeOffDate;
        return $this;
    }

    /**
     * Gets as sTI
     *
     * Переоценка, дооценка (при получении выписки по валютным счетам)
     *
     * @return string
     */
    public function getSTI()
    {
        return $this->sTI;
    }

    /**
     * Sets a new sTI
     *
     * Переоценка, дооценка (при получении выписки по валютным счетам)
     *
     * @param string $sTI
     * @return static
     */
    public function setSTI($sTI)
    {
        $this->sTI = $sTI;
        return $this;
    }

    /**
     * Gets as valueDate
     *
     * Дата валютирования
     *
     * @return \DateTime
     */
    public function getValueDate()
    {
        return $this->valueDate;
    }

    /**
     * Sets a new valueDate
     *
     * Дата валютирования
     *
     * @param \DateTime $valueDate
     * @return static
     */
    public function setValueDate(\DateTime $valueDate)
    {
        $this->valueDate = $valueDate;
        return $this;
    }

    /**
     * Gets as avisType
     *
     * Доп. тип (авизо)
     *
     * @return string
     */
    public function getAvisType()
    {
        return $this->avisType;
    }

    /**
     * Sets a new avisType
     *
     * Доп. тип (авизо)
     *
     * @param string $avisType
     * @return static
     */
    public function setAvisType($avisType)
    {
        $this->avisType = $avisType;
        return $this;
    }

    /**
     * Gets as departmentalInfo
     *
     * @return \common\models\raiffeisenxml\response\DepartmentalInfoExtType
     */
    public function getDepartmentalInfo()
    {
        return $this->departmentalInfo;
    }

    /**
     * Sets a new departmentalInfo
     *
     * @param \common\models\raiffeisenxml\response\DepartmentalInfoExtType $departmentalInfo
     * @return static
     */
    public function setDepartmentalInfo(\common\models\raiffeisenxml\response\DepartmentalInfoExtType $departmentalInfo)
    {
        $this->departmentalInfo = $departmentalInfo;
        return $this;
    }

    /**
     * Gets as diffDoc
     *
     * @return \common\models\raiffeisenxml\response\DiffDocType
     */
    public function getDiffDoc()
    {
        return $this->diffDoc;
    }

    /**
     * Sets a new diffDoc
     *
     * @param \common\models\raiffeisenxml\response\DiffDocType $diffDoc
     * @return static
     */
    public function setDiffDoc(\common\models\raiffeisenxml\response\DiffDocType $diffDoc)
    {
        $this->diffDoc = $diffDoc;
        return $this;
    }

    /**
     * Gets as cur
     *
     * @return \common\models\raiffeisenxml\response\CurType
     */
    public function getCur()
    {
        return $this->cur;
    }

    /**
     * Sets a new cur
     *
     * @param \common\models\raiffeisenxml\response\CurType $cur
     * @return static
     */
    public function setCur(\common\models\raiffeisenxml\response\CurType $cur)
    {
        $this->cur = $cur;
        return $this;
    }

    /**
     * Gets as info
     *
     * @return string
     */
    public function getInfo()
    {
        return $this->info;
    }

    /**
     * Sets a new info
     *
     * @param string $info
     * @return static
     */
    public function setInfo($info)
    {
        $this->info = $info;
        return $this;
    }

    /**
     * Gets as sign
     *
     * @return \common\models\raiffeisenxml\response\DigitalSignType
     */
    public function getSign()
    {
        return $this->sign;
    }

    /**
     * Sets a new sign
     *
     * @param \common\models\raiffeisenxml\response\DigitalSignType $sign
     * @return static
     */
    public function setSign(\common\models\raiffeisenxml\response\DigitalSignType $sign)
    {
        $this->sign = $sign;
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


}


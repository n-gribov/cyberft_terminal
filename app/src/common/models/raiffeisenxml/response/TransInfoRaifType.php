<?php

namespace common\models\raiffeisenxml\response;

/**
 * Class representing TransInfoRaifType
 *
 *
 * XSD Type: TransInfoRaif
 */
class TransInfoRaifType
{

    /**
     * Идентификатор операции в ELBRUS
     *
     * @property string $extId
     */
    private $extId = null;

    /**
     * Код авизо
     *
     * @property string $avisType
     */
    private $avisType = null;

    /**
     * Банк плательщика/получателя
     *
     * @property string $bank
     */
    private $bank = null;

    /**
     * Банк плательщика/получателя
     *
     * @property string $receiverBankName
     */
    private $receiverBankName = null;

    /**
     * Корр. счет банка плательщика
     *
     * @property string $payerBankCorrAccount
     */
    private $payerBankCorrAccount = null;

    /**
     * Корр. счет банка получателя
     *
     * @property string $receiverBankCorrAccount
     */
    private $receiverBankCorrAccount = null;

    /**
     * Код вида валютной операции
     *
     * @property int $codeVO
     */
    private $codeVO = null;

    /**
     * Счет плательщика
     *
     * @property string $corrAcc
     */
    private $corrAcc = null;

    /**
     * БИК банка получателя
     *
     * @property string $corrBIC
     */
    private $corrBIC = null;

    /**
     * Код валюты счета плательщика. Передается цифровой код валюты.
     *
     * @property string $payerCurrCode
     */
    private $payerCurrCode = null;

    /**
     * Код валюты счета получателя. Передается цифровой код валюты.
     *
     * @property string $receiverCurrCode
     */
    private $receiverCurrCode = null;

    /**
     * признак дебета/кредита. Если 1-дебет, 2-кредит
     *
     * @property int $dc
     */
    private $dc = null;

    /**
     * Дата документа
     *
     * @property \DateTime $docDate
     */
    private $docDate = null;

    /**
     * Номер документа
     *
     * @property string $docNum
     */
    private $docNum = null;

    /**
     * Сумма документа
     *
     * @property float $docSum
     */
    private $docSum = null;

    /**
     * Сумма документа в национальной валюте
     *
     * @property float $docSumNat
     */
    private $docSumNat = null;

    /**
     * Дата операции
     *
     * @property \DateTime $operDate
     */
    private $operDate = null;

    /**
     * Очередность платежа
     *
     * @property string $paymentOrder
     */
    private $paymentOrder = null;

    /**
     * Вид платежа
     *
     * @property string $paytKind
     */
    private $paytKind = null;

    /**
     * Код вида платежа
     *
     * @property string $paytCode
     */
    private $paytCode = null;

    /**
     * Счет плательщика
     *
     * @property string $personalAcc
     */
    private $personalAcc = null;

    /**
     * ИНН плательщика
     *
     * @property string $personalINN
     */
    private $personalINN = null;

    /**
     * ИНН получателя
     *
     * @property string $receiverINN
     */
    private $receiverINN = null;

    /**
     * КПП плательщика
     *
     * @property string $personalKPP
     */
    private $personalKPP = null;

    /**
     * Не используется.
     *
     * @property int $priority
     */
    private $priority = null;

    /**
     * Номер паспорта сделки
     *
     * @property int $psNum
     */
    private $psNum = null;

    /**
     * Дата приема
     *
     * @property \DateTime $receiptDate
     */
    private $receiptDate = null;

    /**
     * КПП получателя
     *
     * @property string $receiverKPP
     */
    private $receiverKPP = null;

    /**
     * Тип операции
     *
     * @property string $transKind
     */
    private $transKind = null;

    /**
     * не используется
     *
     * @property int $turnoverKind
     */
    private $turnoverKind = null;

    /**
     * Дата валютирования
     *
     * @property \DateTime $valueDate
     */
    private $valueDate = null;

    /**
     * Дата списания
     *
     * @property \DateTime $writeOffDate
     */
    private $writeOffDate = null;

    /**
     * Наименование счета получателя
     *
     * @property string $receiverAccountName
     */
    private $receiverAccountName = null;

    /**
     * Комиссия
     *
     * @property string $chargeDetails
     */
    private $chargeDetails = null;

    /**
     * Референс банковкой проводки
     *
     * @property string $intRef
     */
    private $intRef = null;

    /**
     * название системы из которй передана выписка
     *
     * @property string $extRef
     */
    private $extRef = null;

    /**
     * Дата помещения в картотеку
     *
     * @property \DateTime $filingDate
     */
    private $filingDate = null;

    /**
     * номер части платежа
     *
     * @property string $partPayNum
     */
    private $partPayNum = null;

    /**
     * Номер банковской проводки
     *
     * @property string $bankRef
     */
    private $bankRef = null;

    /**
     * Банк посредник
     *
     * @property string $midlBank
     */
    private $midlBank = null;

    /**
     * Комиссии списаны со счета
     *
     * @property string $comissionWrittenOff
     */
    private $comissionWrittenOff = null;

    /**
     * Комиссия за перевод
     *
     * @property float $transferComission
     */
    private $transferComission = null;

    /**
     * Комиссии третьих банков
     *
     * @property float $thirdBankFee
     */
    private $thirdBankFee = null;

    /**
     * Комиссия за SWIFT
     *
     * @property float $commissionForSWIFT
     */
    private $commissionForSWIFT = null;

    /**
     * Комиссия НОСТРО-банка за доп. Услугу
     *
     * @property float $nOSTROBankComission
     */
    private $nOSTROBankComission = null;

    /**
     * Комиссия РБ за доп. Услугу
     *
     * @property float $rBComission
     */
    private $rBComission = null;

    /**
     * Сумма зачисления на кор.счет банка
     *
     * @property float $setAmt
     */
    private $setAmt = null;

    /**
     * Цифровой код валюты зачисления на кор.счет банка
     *
     * @property string $setCur
     */
    private $setCur = null;

    /**
     * Сумма к зачислению на счет
     *
     * @property float $payAmt
     */
    private $payAmt = null;

    /**
     * Валюта к зачислению на счет. Передается цифровой код валюты.
     *
     * @property string $payCur
     */
    private $payCur = null;

    /**
     * SWIFT-код отправителя
     *
     * @property string $sender
     */
    private $sender = null;

    /**
     * SWIFT-код получателя
     *
     * @property string $receiver
     */
    private $receiver = null;

    /**
     * Название получателя
     *
     * @property string $receiptName
     */
    private $receiptName = null;

    /**
     * Комиссии, удержанные отправителем
     *
     * @property string $senderCharges
     */
    private $senderCharges = null;

    /**
     * Курс конвертации
     *
     * @property float $currRate
     */
    private $currRate = null;

    /**
     * БИК банка плательщика
     *
     * @property string $payerBankBic
     */
    private $payerBankBic = null;

    /**
     * Назначение платежа в печатной форме
     *
     * @property string $payDtls
     */
    private $payDtls = null;

    /**
     * Шифр документа (картотека)
     *
     * @property string $docShifr
     */
    private $docShifr = null;

    /**
     * Номер документа (картотека)
     *
     * @property string $payNum
     */
    private $payNum = null;

    /**
     * Дата документа (картотека)
     *
     * @property string $payDate
     */
    private $payDate = null;

    /**
     * Сумма остатка платежа (картотека)
     *
     * @property float $sumRest
     */
    private $sumRest = null;

    /**
     * Сумма документа, которым было сформирована данная операция
     *
     * @property float $notDelAmt
     */
    private $notDelAmt = null;

    /**
     * Цифровой код валюты документа, которым была сформирована данная операция
     *
     * @property string $notDelCur
     */
    private $notDelCur = null;

    /**
     * в mt940 Дополнительная информация в поле 61 Подполе8
     *
     * @property string $pnar
     */
    private $pnar = null;

    /**
     * GVC код (для mt940)
     *
     * @property string $mtGVC
     */
    private $mtGVC = null;

    /**
     * 4х значный код SpeedUp Collect (для mt940)
     *
     * @property string $speedUPC
     */
    private $speedUPC = null;

    /**
     * Коды типов операций, используемые банком и удовлетворяющие описанным в стандарте SWIFT (для mt940 в поле 61 Подполе5)
     *
     * @property string $mtTratCode
     */
    private $mtTratCode = null;

    /**
     * УИП - уникальный идентификатор платежа
     *
     * @property string $uip
     */
    private $uip = null;

    /**
     * Наименование организации клиента
     *
     * @property string $personalName
     */
    private $personalName = null;

    /**
     * Назначение платежа
     *
     * @property string $purpose
     */
    private $purpose = null;

    /**
     * Налоговые реквизиты
     *
     * @property \common\models\raiffeisenxml\response\DepartmentalInfoRaifType $departmentalInfo
     */
    private $departmentalInfo = null;

    /**
     * Дополнительные параметры
     *
     * @property \common\models\raiffeisenxml\response\ParamsType\ParamAType[] $params
     */
    private $params = null;

    /**
     * Gets as extId
     *
     * Идентификатор операции в ELBRUS
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
     * Идентификатор операции в ELBRUS
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
     * Gets as avisType
     *
     * Код авизо
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
     * Код авизо
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
     * Gets as bank
     *
     * Банк плательщика/получателя
     *
     * @return string
     */
    public function getBank()
    {
        return $this->bank;
    }

    /**
     * Sets a new bank
     *
     * Банк плательщика/получателя
     *
     * @param string $bank
     * @return static
     */
    public function setBank($bank)
    {
        $this->bank = $bank;
        return $this;
    }

    /**
     * Gets as receiverBankName
     *
     * Банк плательщика/получателя
     *
     * @return string
     */
    public function getReceiverBankName()
    {
        return $this->receiverBankName;
    }

    /**
     * Sets a new receiverBankName
     *
     * Банк плательщика/получателя
     *
     * @param string $receiverBankName
     * @return static
     */
    public function setReceiverBankName($receiverBankName)
    {
        $this->receiverBankName = $receiverBankName;
        return $this;
    }

    /**
     * Gets as payerBankCorrAccount
     *
     * Корр. счет банка плательщика
     *
     * @return string
     */
    public function getPayerBankCorrAccount()
    {
        return $this->payerBankCorrAccount;
    }

    /**
     * Sets a new payerBankCorrAccount
     *
     * Корр. счет банка плательщика
     *
     * @param string $payerBankCorrAccount
     * @return static
     */
    public function setPayerBankCorrAccount($payerBankCorrAccount)
    {
        $this->payerBankCorrAccount = $payerBankCorrAccount;
        return $this;
    }

    /**
     * Gets as receiverBankCorrAccount
     *
     * Корр. счет банка получателя
     *
     * @return string
     */
    public function getReceiverBankCorrAccount()
    {
        return $this->receiverBankCorrAccount;
    }

    /**
     * Sets a new receiverBankCorrAccount
     *
     * Корр. счет банка получателя
     *
     * @param string $receiverBankCorrAccount
     * @return static
     */
    public function setReceiverBankCorrAccount($receiverBankCorrAccount)
    {
        $this->receiverBankCorrAccount = $receiverBankCorrAccount;
        return $this;
    }

    /**
     * Gets as codeVO
     *
     * Код вида валютной операции
     *
     * @return int
     */
    public function getCodeVO()
    {
        return $this->codeVO;
    }

    /**
     * Sets a new codeVO
     *
     * Код вида валютной операции
     *
     * @param int $codeVO
     * @return static
     */
    public function setCodeVO($codeVO)
    {
        $this->codeVO = $codeVO;
        return $this;
    }

    /**
     * Gets as corrAcc
     *
     * Счет плательщика
     *
     * @return string
     */
    public function getCorrAcc()
    {
        return $this->corrAcc;
    }

    /**
     * Sets a new corrAcc
     *
     * Счет плательщика
     *
     * @param string $corrAcc
     * @return static
     */
    public function setCorrAcc($corrAcc)
    {
        $this->corrAcc = $corrAcc;
        return $this;
    }

    /**
     * Gets as corrBIC
     *
     * БИК банка получателя
     *
     * @return string
     */
    public function getCorrBIC()
    {
        return $this->corrBIC;
    }

    /**
     * Sets a new corrBIC
     *
     * БИК банка получателя
     *
     * @param string $corrBIC
     * @return static
     */
    public function setCorrBIC($corrBIC)
    {
        $this->corrBIC = $corrBIC;
        return $this;
    }

    /**
     * Gets as payerCurrCode
     *
     * Код валюты счета плательщика. Передается цифровой код валюты.
     *
     * @return string
     */
    public function getPayerCurrCode()
    {
        return $this->payerCurrCode;
    }

    /**
     * Sets a new payerCurrCode
     *
     * Код валюты счета плательщика. Передается цифровой код валюты.
     *
     * @param string $payerCurrCode
     * @return static
     */
    public function setPayerCurrCode($payerCurrCode)
    {
        $this->payerCurrCode = $payerCurrCode;
        return $this;
    }

    /**
     * Gets as receiverCurrCode
     *
     * Код валюты счета получателя. Передается цифровой код валюты.
     *
     * @return string
     */
    public function getReceiverCurrCode()
    {
        return $this->receiverCurrCode;
    }

    /**
     * Sets a new receiverCurrCode
     *
     * Код валюты счета получателя. Передается цифровой код валюты.
     *
     * @param string $receiverCurrCode
     * @return static
     */
    public function setReceiverCurrCode($receiverCurrCode)
    {
        $this->receiverCurrCode = $receiverCurrCode;
        return $this;
    }

    /**
     * Gets as dc
     *
     * признак дебета/кредита. Если 1-дебет, 2-кредит
     *
     * @return int
     */
    public function getDc()
    {
        return $this->dc;
    }

    /**
     * Sets a new dc
     *
     * признак дебета/кредита. Если 1-дебет, 2-кредит
     *
     * @param int $dc
     * @return static
     */
    public function setDc($dc)
    {
        $this->dc = $dc;
        return $this;
    }

    /**
     * Gets as docDate
     *
     * Дата документа
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
     * Дата документа
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
     * Gets as docSum
     *
     * Сумма документа
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
     * Сумма документа
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
     * Сумма документа в национальной валюте
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
     * Сумма документа в национальной валюте
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
     * Gets as operDate
     *
     * Дата операции
     *
     * @return \DateTime
     */
    public function getOperDate()
    {
        return $this->operDate;
    }

    /**
     * Sets a new operDate
     *
     * Дата операции
     *
     * @param \DateTime $operDate
     * @return static
     */
    public function setOperDate(\DateTime $operDate)
    {
        $this->operDate = $operDate;
        return $this;
    }

    /**
     * Gets as paymentOrder
     *
     * Очередность платежа
     *
     * @return string
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
     * @param string $paymentOrder
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
     * Gets as paytCode
     *
     * Код вида платежа
     *
     * @return string
     */
    public function getPaytCode()
    {
        return $this->paytCode;
    }

    /**
     * Sets a new paytCode
     *
     * Код вида платежа
     *
     * @param string $paytCode
     * @return static
     */
    public function setPaytCode($paytCode)
    {
        $this->paytCode = $paytCode;
        return $this;
    }

    /**
     * Gets as personalAcc
     *
     * Счет плательщика
     *
     * @return string
     */
    public function getPersonalAcc()
    {
        return $this->personalAcc;
    }

    /**
     * Sets a new personalAcc
     *
     * Счет плательщика
     *
     * @param string $personalAcc
     * @return static
     */
    public function setPersonalAcc($personalAcc)
    {
        $this->personalAcc = $personalAcc;
        return $this;
    }

    /**
     * Gets as personalINN
     *
     * ИНН плательщика
     *
     * @return string
     */
    public function getPersonalINN()
    {
        return $this->personalINN;
    }

    /**
     * Sets a new personalINN
     *
     * ИНН плательщика
     *
     * @param string $personalINN
     * @return static
     */
    public function setPersonalINN($personalINN)
    {
        $this->personalINN = $personalINN;
        return $this;
    }

    /**
     * Gets as receiverINN
     *
     * ИНН получателя
     *
     * @return string
     */
    public function getReceiverINN()
    {
        return $this->receiverINN;
    }

    /**
     * Sets a new receiverINN
     *
     * ИНН получателя
     *
     * @param string $receiverINN
     * @return static
     */
    public function setReceiverINN($receiverINN)
    {
        $this->receiverINN = $receiverINN;
        return $this;
    }

    /**
     * Gets as personalKPP
     *
     * КПП плательщика
     *
     * @return string
     */
    public function getPersonalKPP()
    {
        return $this->personalKPP;
    }

    /**
     * Sets a new personalKPP
     *
     * КПП плательщика
     *
     * @param string $personalKPP
     * @return static
     */
    public function setPersonalKPP($personalKPP)
    {
        $this->personalKPP = $personalKPP;
        return $this;
    }

    /**
     * Gets as priority
     *
     * Не используется.
     *
     * @return int
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * Sets a new priority
     *
     * Не используется.
     *
     * @param int $priority
     * @return static
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;
        return $this;
    }

    /**
     * Gets as psNum
     *
     * Номер паспорта сделки
     *
     * @return int
     */
    public function getPsNum()
    {
        return $this->psNum;
    }

    /**
     * Sets a new psNum
     *
     * Номер паспорта сделки
     *
     * @param int $psNum
     * @return static
     */
    public function setPsNum($psNum)
    {
        $this->psNum = $psNum;
        return $this;
    }

    /**
     * Gets as receiptDate
     *
     * Дата приема
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
     * Дата приема
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
     * Gets as receiverKPP
     *
     * КПП получателя
     *
     * @return string
     */
    public function getReceiverKPP()
    {
        return $this->receiverKPP;
    }

    /**
     * Sets a new receiverKPP
     *
     * КПП получателя
     *
     * @param string $receiverKPP
     * @return static
     */
    public function setReceiverKPP($receiverKPP)
    {
        $this->receiverKPP = $receiverKPP;
        return $this;
    }

    /**
     * Gets as transKind
     *
     * Тип операции
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
     * Тип операции
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
     * Gets as turnoverKind
     *
     * не используется
     *
     * @return int
     */
    public function getTurnoverKind()
    {
        return $this->turnoverKind;
    }

    /**
     * Sets a new turnoverKind
     *
     * не используется
     *
     * @param int $turnoverKind
     * @return static
     */
    public function setTurnoverKind($turnoverKind)
    {
        $this->turnoverKind = $turnoverKind;
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
     * Gets as writeOffDate
     *
     * Дата списания
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
     * Дата списания
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
     * Gets as receiverAccountName
     *
     * Наименование счета получателя
     *
     * @return string
     */
    public function getReceiverAccountName()
    {
        return $this->receiverAccountName;
    }

    /**
     * Sets a new receiverAccountName
     *
     * Наименование счета получателя
     *
     * @param string $receiverAccountName
     * @return static
     */
    public function setReceiverAccountName($receiverAccountName)
    {
        $this->receiverAccountName = $receiverAccountName;
        return $this;
    }

    /**
     * Gets as chargeDetails
     *
     * Комиссия
     *
     * @return string
     */
    public function getChargeDetails()
    {
        return $this->chargeDetails;
    }

    /**
     * Sets a new chargeDetails
     *
     * Комиссия
     *
     * @param string $chargeDetails
     * @return static
     */
    public function setChargeDetails($chargeDetails)
    {
        $this->chargeDetails = $chargeDetails;
        return $this;
    }

    /**
     * Gets as intRef
     *
     * Референс банковкой проводки
     *
     * @return string
     */
    public function getIntRef()
    {
        return $this->intRef;
    }

    /**
     * Sets a new intRef
     *
     * Референс банковкой проводки
     *
     * @param string $intRef
     * @return static
     */
    public function setIntRef($intRef)
    {
        $this->intRef = $intRef;
        return $this;
    }

    /**
     * Gets as extRef
     *
     * название системы из которй передана выписка
     *
     * @return string
     */
    public function getExtRef()
    {
        return $this->extRef;
    }

    /**
     * Sets a new extRef
     *
     * название системы из которй передана выписка
     *
     * @param string $extRef
     * @return static
     */
    public function setExtRef($extRef)
    {
        $this->extRef = $extRef;
        return $this;
    }

    /**
     * Gets as filingDate
     *
     * Дата помещения в картотеку
     *
     * @return \DateTime
     */
    public function getFilingDate()
    {
        return $this->filingDate;
    }

    /**
     * Sets a new filingDate
     *
     * Дата помещения в картотеку
     *
     * @param \DateTime $filingDate
     * @return static
     */
    public function setFilingDate(\DateTime $filingDate)
    {
        $this->filingDate = $filingDate;
        return $this;
    }

    /**
     * Gets as partPayNum
     *
     * номер части платежа
     *
     * @return string
     */
    public function getPartPayNum()
    {
        return $this->partPayNum;
    }

    /**
     * Sets a new partPayNum
     *
     * номер части платежа
     *
     * @param string $partPayNum
     * @return static
     */
    public function setPartPayNum($partPayNum)
    {
        $this->partPayNum = $partPayNum;
        return $this;
    }

    /**
     * Gets as bankRef
     *
     * Номер банковской проводки
     *
     * @return string
     */
    public function getBankRef()
    {
        return $this->bankRef;
    }

    /**
     * Sets a new bankRef
     *
     * Номер банковской проводки
     *
     * @param string $bankRef
     * @return static
     */
    public function setBankRef($bankRef)
    {
        $this->bankRef = $bankRef;
        return $this;
    }

    /**
     * Gets as midlBank
     *
     * Банк посредник
     *
     * @return string
     */
    public function getMidlBank()
    {
        return $this->midlBank;
    }

    /**
     * Sets a new midlBank
     *
     * Банк посредник
     *
     * @param string $midlBank
     * @return static
     */
    public function setMidlBank($midlBank)
    {
        $this->midlBank = $midlBank;
        return $this;
    }

    /**
     * Gets as comissionWrittenOff
     *
     * Комиссии списаны со счета
     *
     * @return string
     */
    public function getComissionWrittenOff()
    {
        return $this->comissionWrittenOff;
    }

    /**
     * Sets a new comissionWrittenOff
     *
     * Комиссии списаны со счета
     *
     * @param string $comissionWrittenOff
     * @return static
     */
    public function setComissionWrittenOff($comissionWrittenOff)
    {
        $this->comissionWrittenOff = $comissionWrittenOff;
        return $this;
    }

    /**
     * Gets as transferComission
     *
     * Комиссия за перевод
     *
     * @return float
     */
    public function getTransferComission()
    {
        return $this->transferComission;
    }

    /**
     * Sets a new transferComission
     *
     * Комиссия за перевод
     *
     * @param float $transferComission
     * @return static
     */
    public function setTransferComission($transferComission)
    {
        $this->transferComission = $transferComission;
        return $this;
    }

    /**
     * Gets as thirdBankFee
     *
     * Комиссии третьих банков
     *
     * @return float
     */
    public function getThirdBankFee()
    {
        return $this->thirdBankFee;
    }

    /**
     * Sets a new thirdBankFee
     *
     * Комиссии третьих банков
     *
     * @param float $thirdBankFee
     * @return static
     */
    public function setThirdBankFee($thirdBankFee)
    {
        $this->thirdBankFee = $thirdBankFee;
        return $this;
    }

    /**
     * Gets as commissionForSWIFT
     *
     * Комиссия за SWIFT
     *
     * @return float
     */
    public function getCommissionForSWIFT()
    {
        return $this->commissionForSWIFT;
    }

    /**
     * Sets a new commissionForSWIFT
     *
     * Комиссия за SWIFT
     *
     * @param float $commissionForSWIFT
     * @return static
     */
    public function setCommissionForSWIFT($commissionForSWIFT)
    {
        $this->commissionForSWIFT = $commissionForSWIFT;
        return $this;
    }

    /**
     * Gets as nOSTROBankComission
     *
     * Комиссия НОСТРО-банка за доп. Услугу
     *
     * @return float
     */
    public function getNOSTROBankComission()
    {
        return $this->nOSTROBankComission;
    }

    /**
     * Sets a new nOSTROBankComission
     *
     * Комиссия НОСТРО-банка за доп. Услугу
     *
     * @param float $nOSTROBankComission
     * @return static
     */
    public function setNOSTROBankComission($nOSTROBankComission)
    {
        $this->nOSTROBankComission = $nOSTROBankComission;
        return $this;
    }

    /**
     * Gets as rBComission
     *
     * Комиссия РБ за доп. Услугу
     *
     * @return float
     */
    public function getRBComission()
    {
        return $this->rBComission;
    }

    /**
     * Sets a new rBComission
     *
     * Комиссия РБ за доп. Услугу
     *
     * @param float $rBComission
     * @return static
     */
    public function setRBComission($rBComission)
    {
        $this->rBComission = $rBComission;
        return $this;
    }

    /**
     * Gets as setAmt
     *
     * Сумма зачисления на кор.счет банка
     *
     * @return float
     */
    public function getSetAmt()
    {
        return $this->setAmt;
    }

    /**
     * Sets a new setAmt
     *
     * Сумма зачисления на кор.счет банка
     *
     * @param float $setAmt
     * @return static
     */
    public function setSetAmt($setAmt)
    {
        $this->setAmt = $setAmt;
        return $this;
    }

    /**
     * Gets as setCur
     *
     * Цифровой код валюты зачисления на кор.счет банка
     *
     * @return string
     */
    public function getSetCur()
    {
        return $this->setCur;
    }

    /**
     * Sets a new setCur
     *
     * Цифровой код валюты зачисления на кор.счет банка
     *
     * @param string $setCur
     * @return static
     */
    public function setSetCur($setCur)
    {
        $this->setCur = $setCur;
        return $this;
    }

    /**
     * Gets as payAmt
     *
     * Сумма к зачислению на счет
     *
     * @return float
     */
    public function getPayAmt()
    {
        return $this->payAmt;
    }

    /**
     * Sets a new payAmt
     *
     * Сумма к зачислению на счет
     *
     * @param float $payAmt
     * @return static
     */
    public function setPayAmt($payAmt)
    {
        $this->payAmt = $payAmt;
        return $this;
    }

    /**
     * Gets as payCur
     *
     * Валюта к зачислению на счет. Передается цифровой код валюты.
     *
     * @return string
     */
    public function getPayCur()
    {
        return $this->payCur;
    }

    /**
     * Sets a new payCur
     *
     * Валюта к зачислению на счет. Передается цифровой код валюты.
     *
     * @param string $payCur
     * @return static
     */
    public function setPayCur($payCur)
    {
        $this->payCur = $payCur;
        return $this;
    }

    /**
     * Gets as sender
     *
     * SWIFT-код отправителя
     *
     * @return string
     */
    public function getSender()
    {
        return $this->sender;
    }

    /**
     * Sets a new sender
     *
     * SWIFT-код отправителя
     *
     * @param string $sender
     * @return static
     */
    public function setSender($sender)
    {
        $this->sender = $sender;
        return $this;
    }

    /**
     * Gets as receiver
     *
     * SWIFT-код получателя
     *
     * @return string
     */
    public function getReceiver()
    {
        return $this->receiver;
    }

    /**
     * Sets a new receiver
     *
     * SWIFT-код получателя
     *
     * @param string $receiver
     * @return static
     */
    public function setReceiver($receiver)
    {
        $this->receiver = $receiver;
        return $this;
    }

    /**
     * Gets as receiptName
     *
     * Название получателя
     *
     * @return string
     */
    public function getReceiptName()
    {
        return $this->receiptName;
    }

    /**
     * Sets a new receiptName
     *
     * Название получателя
     *
     * @param string $receiptName
     * @return static
     */
    public function setReceiptName($receiptName)
    {
        $this->receiptName = $receiptName;
        return $this;
    }

    /**
     * Gets as senderCharges
     *
     * Комиссии, удержанные отправителем
     *
     * @return string
     */
    public function getSenderCharges()
    {
        return $this->senderCharges;
    }

    /**
     * Sets a new senderCharges
     *
     * Комиссии, удержанные отправителем
     *
     * @param string $senderCharges
     * @return static
     */
    public function setSenderCharges($senderCharges)
    {
        $this->senderCharges = $senderCharges;
        return $this;
    }

    /**
     * Gets as currRate
     *
     * Курс конвертации
     *
     * @return float
     */
    public function getCurrRate()
    {
        return $this->currRate;
    }

    /**
     * Sets a new currRate
     *
     * Курс конвертации
     *
     * @param float $currRate
     * @return static
     */
    public function setCurrRate($currRate)
    {
        $this->currRate = $currRate;
        return $this;
    }

    /**
     * Gets as payerBankBic
     *
     * БИК банка плательщика
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
     * БИК банка плательщика
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
     * Gets as payDtls
     *
     * Назначение платежа в печатной форме
     *
     * @return string
     */
    public function getPayDtls()
    {
        return $this->payDtls;
    }

    /**
     * Sets a new payDtls
     *
     * Назначение платежа в печатной форме
     *
     * @param string $payDtls
     * @return static
     */
    public function setPayDtls($payDtls)
    {
        $this->payDtls = $payDtls;
        return $this;
    }

    /**
     * Gets as docShifr
     *
     * Шифр документа (картотека)
     *
     * @return string
     */
    public function getDocShifr()
    {
        return $this->docShifr;
    }

    /**
     * Sets a new docShifr
     *
     * Шифр документа (картотека)
     *
     * @param string $docShifr
     * @return static
     */
    public function setDocShifr($docShifr)
    {
        $this->docShifr = $docShifr;
        return $this;
    }

    /**
     * Gets as payNum
     *
     * Номер документа (картотека)
     *
     * @return string
     */
    public function getPayNum()
    {
        return $this->payNum;
    }

    /**
     * Sets a new payNum
     *
     * Номер документа (картотека)
     *
     * @param string $payNum
     * @return static
     */
    public function setPayNum($payNum)
    {
        $this->payNum = $payNum;
        return $this;
    }

    /**
     * Gets as payDate
     *
     * Дата документа (картотека)
     *
     * @return string
     */
    public function getPayDate()
    {
        return $this->payDate;
    }

    /**
     * Sets a new payDate
     *
     * Дата документа (картотека)
     *
     * @param string $payDate
     * @return static
     */
    public function setPayDate($payDate)
    {
        $this->payDate = $payDate;
        return $this;
    }

    /**
     * Gets as sumRest
     *
     * Сумма остатка платежа (картотека)
     *
     * @return float
     */
    public function getSumRest()
    {
        return $this->sumRest;
    }

    /**
     * Sets a new sumRest
     *
     * Сумма остатка платежа (картотека)
     *
     * @param float $sumRest
     * @return static
     */
    public function setSumRest($sumRest)
    {
        $this->sumRest = $sumRest;
        return $this;
    }

    /**
     * Gets as notDelAmt
     *
     * Сумма документа, которым было сформирована данная операция
     *
     * @return float
     */
    public function getNotDelAmt()
    {
        return $this->notDelAmt;
    }

    /**
     * Sets a new notDelAmt
     *
     * Сумма документа, которым было сформирована данная операция
     *
     * @param float $notDelAmt
     * @return static
     */
    public function setNotDelAmt($notDelAmt)
    {
        $this->notDelAmt = $notDelAmt;
        return $this;
    }

    /**
     * Gets as notDelCur
     *
     * Цифровой код валюты документа, которым была сформирована данная операция
     *
     * @return string
     */
    public function getNotDelCur()
    {
        return $this->notDelCur;
    }

    /**
     * Sets a new notDelCur
     *
     * Цифровой код валюты документа, которым была сформирована данная операция
     *
     * @param string $notDelCur
     * @return static
     */
    public function setNotDelCur($notDelCur)
    {
        $this->notDelCur = $notDelCur;
        return $this;
    }

    /**
     * Gets as pnar
     *
     * в mt940 Дополнительная информация в поле 61 Подполе8
     *
     * @return string
     */
    public function getPnar()
    {
        return $this->pnar;
    }

    /**
     * Sets a new pnar
     *
     * в mt940 Дополнительная информация в поле 61 Подполе8
     *
     * @param string $pnar
     * @return static
     */
    public function setPnar($pnar)
    {
        $this->pnar = $pnar;
        return $this;
    }

    /**
     * Gets as mtGVC
     *
     * GVC код (для mt940)
     *
     * @return string
     */
    public function getMtGVC()
    {
        return $this->mtGVC;
    }

    /**
     * Sets a new mtGVC
     *
     * GVC код (для mt940)
     *
     * @param string $mtGVC
     * @return static
     */
    public function setMtGVC($mtGVC)
    {
        $this->mtGVC = $mtGVC;
        return $this;
    }

    /**
     * Gets as speedUPC
     *
     * 4х значный код SpeedUp Collect (для mt940)
     *
     * @return string
     */
    public function getSpeedUPC()
    {
        return $this->speedUPC;
    }

    /**
     * Sets a new speedUPC
     *
     * 4х значный код SpeedUp Collect (для mt940)
     *
     * @param string $speedUPC
     * @return static
     */
    public function setSpeedUPC($speedUPC)
    {
        $this->speedUPC = $speedUPC;
        return $this;
    }

    /**
     * Gets as mtTratCode
     *
     * Коды типов операций, используемые банком и удовлетворяющие описанным в стандарте SWIFT (для mt940 в поле 61 Подполе5)
     *
     * @return string
     */
    public function getMtTratCode()
    {
        return $this->mtTratCode;
    }

    /**
     * Sets a new mtTratCode
     *
     * Коды типов операций, используемые банком и удовлетворяющие описанным в стандарте SWIFT (для mt940 в поле 61 Подполе5)
     *
     * @param string $mtTratCode
     * @return static
     */
    public function setMtTratCode($mtTratCode)
    {
        $this->mtTratCode = $mtTratCode;
        return $this;
    }

    /**
     * Gets as uip
     *
     * УИП - уникальный идентификатор платежа
     *
     * @return string
     */
    public function getUip()
    {
        return $this->uip;
    }

    /**
     * Sets a new uip
     *
     * УИП - уникальный идентификатор платежа
     *
     * @param string $uip
     * @return static
     */
    public function setUip($uip)
    {
        $this->uip = $uip;
        return $this;
    }

    /**
     * Gets as personalName
     *
     * Наименование организации клиента
     *
     * @return string
     */
    public function getPersonalName()
    {
        return $this->personalName;
    }

    /**
     * Sets a new personalName
     *
     * Наименование организации клиента
     *
     * @param string $personalName
     * @return static
     */
    public function setPersonalName($personalName)
    {
        $this->personalName = $personalName;
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
     * Gets as departmentalInfo
     *
     * Налоговые реквизиты
     *
     * @return \common\models\raiffeisenxml\response\DepartmentalInfoRaifType
     */
    public function getDepartmentalInfo()
    {
        return $this->departmentalInfo;
    }

    /**
     * Sets a new departmentalInfo
     *
     * Налоговые реквизиты
     *
     * @param \common\models\raiffeisenxml\response\DepartmentalInfoRaifType $departmentalInfo
     * @return static
     */
    public function setDepartmentalInfo(\common\models\raiffeisenxml\response\DepartmentalInfoRaifType $departmentalInfo)
    {
        $this->departmentalInfo = $departmentalInfo;
        return $this;
    }

    /**
     * Adds as param
     *
     * Дополнительные параметры
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
     * Дополнительные параметры
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
     * Дополнительные параметры
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
     * Дополнительные параметры
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
     * Дополнительные параметры
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


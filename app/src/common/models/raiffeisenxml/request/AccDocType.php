<?php

namespace common\models\raiffeisenxml\request;

/**
 * Class representing AccDocType
 *
 *
 * XSD Type: AccDoc
 */
class AccDocType
{

    /**
     * Назначение платежа
     *
     * @property string $purpose
     */
    private $purpose = null;

    /**
     * Дата составления документа
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
     * Сумма платежа
     *
     * @property float $docSum
     */
    private $docSum = null;

    /**
     * Сумма НДС
     *
     * @property float $vatSum
     */
    private $vatSum = null;

    /**
     * % НДС
     *
     * @property float $vatRate
     */
    private $vatRate = null;

    /**
     * Указывается способ расчета НДС согласно справочнику расчета НДС в CORREQTS:
     *  Vat1 - расчет по % (1)
     *  Vat2 - расчет по % (2)
     *  VatAdd - Расчет по % (+)
     *  VatZero - "НДС не облаг."
     *  VatManualRate - "Ввод НДС"
     *  VatManualAll - "Ручной ввод"
     *
     * @property string $vat
     */
    private $vat = null;

    /**
     * Вид операции
     *
     * @property string $transKind
     */
    private $transKind = null;

    /**
     * Вид платежа
     *  Возможные значения: «почтой», «электронно», «телеграфом» («срочно» временно недоступно).
     *  Если не указано, заполняется: «0»
     *
     * @property string $paytKind
     */
    private $paytKind = null;

    /**
     * Код вида платежа
     *  Возможные значения: «0», «1», «2», «3», «4».
     *
     * @property string $paytCode
     */
    private $paytCode = null;

    /**
     * Очерёдность платежа: число от 1 до 6
     *
     * @property int $priority
     */
    private $priority = null;

    /**
     * Код вида валютной операции
     *
     * @property string $codeVO
     */
    private $codeVO = null;

    /**
     * Документы не требуются (0 или 1)
     *
     * @property bool $nodocs
     */
    private $nodocs = null;

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
     * Сумма платежа
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
     * Сумма платежа
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
     * Gets as vatSum
     *
     * Сумма НДС
     *
     * @return float
     */
    public function getVatSum()
    {
        return $this->vatSum;
    }

    /**
     * Sets a new vatSum
     *
     * Сумма НДС
     *
     * @param float $vatSum
     * @return static
     */
    public function setVatSum($vatSum)
    {
        $this->vatSum = $vatSum;
        return $this;
    }

    /**
     * Gets as vatRate
     *
     * % НДС
     *
     * @return float
     */
    public function getVatRate()
    {
        return $this->vatRate;
    }

    /**
     * Sets a new vatRate
     *
     * % НДС
     *
     * @param float $vatRate
     * @return static
     */
    public function setVatRate($vatRate)
    {
        $this->vatRate = $vatRate;
        return $this;
    }

    /**
     * Gets as vat
     *
     * Указывается способ расчета НДС согласно справочнику расчета НДС в CORREQTS:
     *  Vat1 - расчет по % (1)
     *  Vat2 - расчет по % (2)
     *  VatAdd - Расчет по % (+)
     *  VatZero - "НДС не облаг."
     *  VatManualRate - "Ввод НДС"
     *  VatManualAll - "Ручной ввод"
     *
     * @return string
     */
    public function getVat()
    {
        return $this->vat;
    }

    /**
     * Sets a new vat
     *
     * Указывается способ расчета НДС согласно справочнику расчета НДС в CORREQTS:
     *  Vat1 - расчет по % (1)
     *  Vat2 - расчет по % (2)
     *  VatAdd - Расчет по % (+)
     *  VatZero - "НДС не облаг."
     *  VatManualRate - "Ввод НДС"
     *  VatManualAll - "Ручной ввод"
     *
     * @param string $vat
     * @return static
     */
    public function setVat($vat)
    {
        $this->vat = $vat;
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
     * Gets as paytKind
     *
     * Вид платежа
     *  Возможные значения: «почтой», «электронно», «телеграфом» («срочно» временно недоступно).
     *  Если не указано, заполняется: «0»
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
     *  Если не указано, заполняется: «0»
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
     *  Возможные значения: «0», «1», «2», «3», «4».
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
     *  Возможные значения: «0», «1», «2», «3», «4».
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
     * Gets as priority
     *
     * Очерёдность платежа: число от 1 до 6
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
     * Очерёдность платежа: число от 1 до 6
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
     * Gets as codeVO
     *
     * Код вида валютной операции
     *
     * @return string
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
     * @param string $codeVO
     * @return static
     */
    public function setCodeVO($codeVO)
    {
        $this->codeVO = $codeVO;
        return $this;
    }

    /**
     * Gets as nodocs
     *
     * Документы не требуются (0 или 1)
     *
     * @return bool
     */
    public function getNodocs()
    {
        return $this->nodocs;
    }

    /**
     * Sets a new nodocs
     *
     * Документы не требуются (0 или 1)
     *
     * @param bool $nodocs
     * @return static
     */
    public function setNodocs($nodocs)
    {
        $this->nodocs = $nodocs;
        return $this;
    }


}


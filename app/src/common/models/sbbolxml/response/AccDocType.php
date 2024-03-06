<?php

namespace common\models\sbbolxml\response;

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
     * Номер расчетного документа
     *
     * @property string $accDocNo
     */
    private $accDocNo = null;

    /**
     * Дата создания документа
     *
     * @property \DateTime $docDate
     */
    private $docDate = null;

    /**
     * Сумма платежа
     *
     * @property float $docSum
     */
    private $docSum = null;

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
     * Очерёдность списания денежных средств со счёта: число от 1 до 6.
     *  (действующий диапазон - от 1 до 5; значение 6 оставлено для совместимости со старыми поручениями)
     *
     * @property integer $priority
     */
    private $priority = null;

    /**
     * Код вида валютной операции
     *
     * @property string $codeVO
     */
    private $codeVO = null;

    /**
     * Номер паспорта сделки
     *
     * @property string $psNum
     */
    private $psNum = null;

    /**
     * Дополнительная услуга СБРФ.
     *  Срочность: 0 - срочный, 1 - срочный с УВ, 2 - неотложный
     *
     * @property string $urgentSBRF
     */
    private $urgentSBRF = null;

    /**
     * Указывается способ расчета НДС согласно справочнику расчета НДС в CORREQTS:
     *  1 - расчет по % (1)
     *  2 - расчет по % (2)
     *  3 - Расчет по % (+)
     *  4 - НДС не облаг.
     *  5 - Ввод НДС
     *  6 - Ручной ввод
     *
     * @property string $vat
     */
    private $vat = null;

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
     * УИП
     *
     * @property string $uip
     */
    private $uip = null;

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
     * Gets as accDocNo
     *
     * Номер расчетного документа
     *
     * @return string
     */
    public function getAccDocNo()
    {
        return $this->accDocNo;
    }

    /**
     * Sets a new accDocNo
     *
     * Номер расчетного документа
     *
     * @param string $accDocNo
     * @return static
     */
    public function setAccDocNo($accDocNo)
    {
        $this->accDocNo = $accDocNo;
        return $this;
    }

    /**
     * Gets as docDate
     *
     * Дата создания документа
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
     * Дата создания документа
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
     * Gets as priority
     *
     * Очерёдность списания денежных средств со счёта: число от 1 до 6.
     *  (действующий диапазон - от 1 до 5; значение 6 оставлено для совместимости со старыми поручениями)
     *
     * @return integer
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * Sets a new priority
     *
     * Очерёдность списания денежных средств со счёта: число от 1 до 6.
     *  (действующий диапазон - от 1 до 5; значение 6 оставлено для совместимости со старыми поручениями)
     *
     * @param integer $priority
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
     * Gets as psNum
     *
     * Номер паспорта сделки
     *
     * @return string
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
     * @param string $psNum
     * @return static
     */
    public function setPsNum($psNum)
    {
        $this->psNum = $psNum;
        return $this;
    }

    /**
     * Gets as urgentSBRF
     *
     * Дополнительная услуга СБРФ.
     *  Срочность: 0 - срочный, 1 - срочный с УВ, 2 - неотложный
     *
     * @return string
     */
    public function getUrgentSBRF()
    {
        return $this->urgentSBRF;
    }

    /**
     * Sets a new urgentSBRF
     *
     * Дополнительная услуга СБРФ.
     *  Срочность: 0 - срочный, 1 - срочный с УВ, 2 - неотложный
     *
     * @param string $urgentSBRF
     * @return static
     */
    public function setUrgentSBRF($urgentSBRF)
    {
        $this->urgentSBRF = $urgentSBRF;
        return $this;
    }

    /**
     * Gets as vat
     *
     * Указывается способ расчета НДС согласно справочнику расчета НДС в CORREQTS:
     *  1 - расчет по % (1)
     *  2 - расчет по % (2)
     *  3 - Расчет по % (+)
     *  4 - НДС не облаг.
     *  5 - Ввод НДС
     *  6 - Ручной ввод
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
     *  1 - расчет по % (1)
     *  2 - расчет по % (2)
     *  3 - Расчет по % (+)
     *  4 - НДС не облаг.
     *  5 - Ввод НДС
     *  6 - Ручной ввод
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
     * Gets as uip
     *
     * УИП
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
     * УИП
     *
     * @param string $uip
     * @return static
     */
    public function setUip($uip)
    {
        $this->uip = $uip;
        return $this;
    }


}


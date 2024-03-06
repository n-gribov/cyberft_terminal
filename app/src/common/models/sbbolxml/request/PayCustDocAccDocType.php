<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing PayCustDocAccDocType
 *
 *
 * XSD Type: PayCustDocAccDoc
 */
class PayCustDocAccDocType
{

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
     * Вид платежа Возможные значения: «почтой», «электронно», «телеграфом» («срочно» временно недоступно). Если не указано, заполняется: «0»
     *
     * @property string $paytKind
     */
    private $paytKind = null;

    /**
     * Очерёдность списания денежных средств со счёта: число от 1 до 5
     *
     * @property integer $priority
     */
    private $priority = null;

    /**
     * Дополнительная услуга СБРФ. Срочность: 0 - срочный, 1 - срочный с УВ, 2 - неотложный
     *
     * @property string $urgentSBRF
     */
    private $urgentSBRF = null;

    /**
     * Назначение платежа
     *
     * @property string $purpose
     */
    private $purpose = null;

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
     * Вид платежа Возможные значения: «почтой», «электронно», «телеграфом» («срочно» временно недоступно). Если не указано, заполняется: «0»
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
     * Вид платежа Возможные значения: «почтой», «электронно», «телеграфом» («срочно» временно недоступно). Если не указано, заполняется: «0»
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
     * Очерёдность списания денежных средств со счёта: число от 1 до 5
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
     * Очерёдность списания денежных средств со счёта: число от 1 до 5
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
     * Gets as urgentSBRF
     *
     * Дополнительная услуга СБРФ. Срочность: 0 - срочный, 1 - срочный с УВ, 2 - неотложный
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
     * Дополнительная услуга СБРФ. Срочность: 0 - срочный, 1 - срочный с УВ, 2 - неотложный
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


}


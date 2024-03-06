<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing PaymentType
 *
 *
 * XSD Type: Payment
 */
class PaymentType
{

    /**
     * Признак отсутствия номера валютного документа
     *
     * @property boolean $isDocumentNumber
     */
    private $isDocumentNumber = null;

    /**
     * Номер документа по валютной операции
     *
     * @property string $docNumber
     */
    private $docNumber = null;

    /**
     * Дата валютного документа
     *
     * @property \DateTime $docDate
     */
    private $docDate = null;

    /**
     * Сумма платежа
     *
     * @property float $paymentAmount
     */
    private $paymentAmount = null;

    /**
     * Цифровой код валюты операции
     *
     * @property string $currCode
     */
    private $currCode = null;

    /**
     * ISO-код валюты валюты (3-х буквенный код валюты)
     *
     * @property string $currNameOper
     */
    private $currNameOper = null;

    /**
     * Направление платежа
     *  Признак платежа
     *  1 - зачисление резиденту, в т.ч. оформившему ПС
     *  2 - списание резидента, в т.ч. оформившего ПС
     *
     * @property string $payDirection
     */
    private $payDirection = null;

    /**
     * Дата операции
     *
     * @property \DateTime $operDate
     */
    private $operDate = null;

    /**
     * Тип валютного документа:
     *  MandatorySale - Распоряжение об осуществлении обязательной продажи;
     *  PayDocCur - Поручение на перевод валюты;
     *  PayDocRu - Рублевое платежное поручение.
     *  CurrencyNotices - Уведомлений о зачислении (поступлении) иностранной валюты на транзитный
     *  валютный счет
     *
     * @property string $currDocType
     */
    private $currDocType = null;

    /**
     * Gets as isDocumentNumber
     *
     * Признак отсутствия номера валютного документа
     *
     * @return boolean
     */
    public function getIsDocumentNumber()
    {
        return $this->isDocumentNumber;
    }

    /**
     * Sets a new isDocumentNumber
     *
     * Признак отсутствия номера валютного документа
     *
     * @param boolean $isDocumentNumber
     * @return static
     */
    public function setIsDocumentNumber($isDocumentNumber)
    {
        $this->isDocumentNumber = $isDocumentNumber;
        return $this;
    }

    /**
     * Gets as docNumber
     *
     * Номер документа по валютной операции
     *
     * @return string
     */
    public function getDocNumber()
    {
        return $this->docNumber;
    }

    /**
     * Sets a new docNumber
     *
     * Номер документа по валютной операции
     *
     * @param string $docNumber
     * @return static
     */
    public function setDocNumber($docNumber)
    {
        $this->docNumber = $docNumber;
        return $this;
    }

    /**
     * Gets as docDate
     *
     * Дата валютного документа
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
     * Дата валютного документа
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
     * Gets as paymentAmount
     *
     * Сумма платежа
     *
     * @return float
     */
    public function getPaymentAmount()
    {
        return $this->paymentAmount;
    }

    /**
     * Sets a new paymentAmount
     *
     * Сумма платежа
     *
     * @param float $paymentAmount
     * @return static
     */
    public function setPaymentAmount($paymentAmount)
    {
        $this->paymentAmount = $paymentAmount;
        return $this;
    }

    /**
     * Gets as currCode
     *
     * Цифровой код валюты операции
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
     * Цифровой код валюты операции
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
     * Gets as currNameOper
     *
     * ISO-код валюты валюты (3-х буквенный код валюты)
     *
     * @return string
     */
    public function getCurrNameOper()
    {
        return $this->currNameOper;
    }

    /**
     * Sets a new currNameOper
     *
     * ISO-код валюты валюты (3-х буквенный код валюты)
     *
     * @param string $currNameOper
     * @return static
     */
    public function setCurrNameOper($currNameOper)
    {
        $this->currNameOper = $currNameOper;
        return $this;
    }

    /**
     * Gets as payDirection
     *
     * Направление платежа
     *  Признак платежа
     *  1 - зачисление резиденту, в т.ч. оформившему ПС
     *  2 - списание резидента, в т.ч. оформившего ПС
     *
     * @return string
     */
    public function getPayDirection()
    {
        return $this->payDirection;
    }

    /**
     * Sets a new payDirection
     *
     * Направление платежа
     *  Признак платежа
     *  1 - зачисление резиденту, в т.ч. оформившему ПС
     *  2 - списание резидента, в т.ч. оформившего ПС
     *
     * @param string $payDirection
     * @return static
     */
    public function setPayDirection($payDirection)
    {
        $this->payDirection = $payDirection;
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
     * Gets as currDocType
     *
     * Тип валютного документа:
     *  MandatorySale - Распоряжение об осуществлении обязательной продажи;
     *  PayDocCur - Поручение на перевод валюты;
     *  PayDocRu - Рублевое платежное поручение.
     *  CurrencyNotices - Уведомлений о зачислении (поступлении) иностранной валюты на транзитный
     *  валютный счет
     *
     * @return string
     */
    public function getCurrDocType()
    {
        return $this->currDocType;
    }

    /**
     * Sets a new currDocType
     *
     * Тип валютного документа:
     *  MandatorySale - Распоряжение об осуществлении обязательной продажи;
     *  PayDocCur - Поручение на перевод валюты;
     *  PayDocRu - Рублевое платежное поручение.
     *  CurrencyNotices - Уведомлений о зачислении (поступлении) иностранной валюты на транзитный
     *  валютный счет
     *
     * @param string $currDocType
     * @return static
     */
    public function setCurrDocType($currDocType)
    {
        $this->currDocType = $currDocType;
        return $this;
    }


}


<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing CurrDealCertificateDoc138IType
 *
 * Информация о контракте
 * XSD Type: CurrDealCertificateDoc138I
 */
class CurrDealCertificateDoc138IType
{

    /**
     * Порядковый номер строки в справке
     *
     * @property integer $lineNumber
     */
    private $lineNumber = null;

    /**
     * Номер документа по валютной операции
     *
     * @property string $documentNumber
     */
    private $documentNumber = null;

    /**
     * Признак: 0- документ имеет номер; 1 - документ без номера
     *
     * @property boolean $numCheck
     */
    private $numCheck = null;

    /**
     * Дата документа по валютной операции
     *
     * @property \DateTime $documentDate
     */
    private $documentDate = null;

    /**
     * Признак платежа
     *  1 - зачисление резиденту, в т.ч. оформившему ПС
     *  2 - списание резидента, в т.ч. оформившего ПС
     *  7 - зачисление фактору
     *  8 - зачисление др. лицу/резиденту, который не оформляет ПС
     *  9 - списание 3-го/др. лица
     *  0 - исполнение аккредитива
     *
     * @property string $paymentDirection
     */
    private $paymentDirection = null;

    /**
     * Тип валютного документа:
     *  MandatorySale - Распоряжение об осуществлении обязательной продажи;
     *  PayDocCur - Поручение на перевод валюты;
     *  PayDocRu - Рублевое платежное поручение.
     *
     * @property string $currDocType
     */
    private $currDocType = null;

    /**
     * Код вида валютной операции
     *
     * @property string $operCode
     */
    private $operCode = null;

    /**
     * Наименование вида валютной операции
     *
     * @property string $operName
     */
    private $operName = null;

    /**
     * Сумма платежа в единицах валюты платежа
     *
     * @property \common\models\sbbolxml\request\CurrAmountType $paymentSum
     */
    private $paymentSum = null;

    /**
     * Дата операции
     *
     * @property \DateTime $transactionDate
     */
    private $transactionDate = null;

    /**
     * @property \common\models\sbbolxml\request\ContractDateOptionalType $contract
     */
    private $contract = null;

    /**
     * Номер ПС (строка со слешами)
     *
     * @property string $dealPassNum
     */
    private $dealPassNum = null;

    /**
     * Сумма платежа в валюте цены контракта (кредитного договора)
     *  Если валюта суммы платежа и валюта суммы цены контракта (кредитного договора) совпадают, то код
     *  валюты цены контракта и сумма в валюте цены контракта не должны заполняться
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
     * Срок возврата аванса
     *
     * @property \DateTime $prepaymentDate
     */
    private $prepaymentDate = null;

    /**
     * Примечания по данной строке
     *
     * @property string $addInfo
     */
    private $addInfo = null;

    /**
     * Содержит информацию о связном документе (валютный перевод, распоряжение об обязательной продаже,
     *  уведомление и т.п., доставленные по системе СББОЛ) по списанию или зачислению
     *
     * @property \common\models\sbbolxml\request\LinkedDocsType\LDocAType[] $linkedDocs
     */
    private $linkedDocs = null;

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
     * Gets as documentNumber
     *
     * Номер документа по валютной операции
     *
     * @return string
     */
    public function getDocumentNumber()
    {
        return $this->documentNumber;
    }

    /**
     * Sets a new documentNumber
     *
     * Номер документа по валютной операции
     *
     * @param string $documentNumber
     * @return static
     */
    public function setDocumentNumber($documentNumber)
    {
        $this->documentNumber = $documentNumber;
        return $this;
    }

    /**
     * Gets as numCheck
     *
     * Признак: 0- документ имеет номер; 1 - документ без номера
     *
     * @return boolean
     */
    public function getNumCheck()
    {
        return $this->numCheck;
    }

    /**
     * Sets a new numCheck
     *
     * Признак: 0- документ имеет номер; 1 - документ без номера
     *
     * @param boolean $numCheck
     * @return static
     */
    public function setNumCheck($numCheck)
    {
        $this->numCheck = $numCheck;
        return $this;
    }

    /**
     * Gets as documentDate
     *
     * Дата документа по валютной операции
     *
     * @return \DateTime
     */
    public function getDocumentDate()
    {
        return $this->documentDate;
    }

    /**
     * Sets a new documentDate
     *
     * Дата документа по валютной операции
     *
     * @param \DateTime $documentDate
     * @return static
     */
    public function setDocumentDate(\DateTime $documentDate)
    {
        $this->documentDate = $documentDate;
        return $this;
    }

    /**
     * Gets as paymentDirection
     *
     * Признак платежа
     *  1 - зачисление резиденту, в т.ч. оформившему ПС
     *  2 - списание резидента, в т.ч. оформившего ПС
     *  7 - зачисление фактору
     *  8 - зачисление др. лицу/резиденту, который не оформляет ПС
     *  9 - списание 3-го/др. лица
     *  0 - исполнение аккредитива
     *
     * @return string
     */
    public function getPaymentDirection()
    {
        return $this->paymentDirection;
    }

    /**
     * Sets a new paymentDirection
     *
     * Признак платежа
     *  1 - зачисление резиденту, в т.ч. оформившему ПС
     *  2 - списание резидента, в т.ч. оформившего ПС
     *  7 - зачисление фактору
     *  8 - зачисление др. лицу/резиденту, который не оформляет ПС
     *  9 - списание 3-го/др. лица
     *  0 - исполнение аккредитива
     *
     * @param string $paymentDirection
     * @return static
     */
    public function setPaymentDirection($paymentDirection)
    {
        $this->paymentDirection = $paymentDirection;
        return $this;
    }

    /**
     * Gets as currDocType
     *
     * Тип валютного документа:
     *  MandatorySale - Распоряжение об осуществлении обязательной продажи;
     *  PayDocCur - Поручение на перевод валюты;
     *  PayDocRu - Рублевое платежное поручение.
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
     *
     * @param string $currDocType
     * @return static
     */
    public function setCurrDocType($currDocType)
    {
        $this->currDocType = $currDocType;
        return $this;
    }

    /**
     * Gets as operCode
     *
     * Код вида валютной операции
     *
     * @return string
     */
    public function getOperCode()
    {
        return $this->operCode;
    }

    /**
     * Sets a new operCode
     *
     * Код вида валютной операции
     *
     * @param string $operCode
     * @return static
     */
    public function setOperCode($operCode)
    {
        $this->operCode = $operCode;
        return $this;
    }

    /**
     * Gets as operName
     *
     * Наименование вида валютной операции
     *
     * @return string
     */
    public function getOperName()
    {
        return $this->operName;
    }

    /**
     * Sets a new operName
     *
     * Наименование вида валютной операции
     *
     * @param string $operName
     * @return static
     */
    public function setOperName($operName)
    {
        $this->operName = $operName;
        return $this;
    }

    /**
     * Gets as paymentSum
     *
     * Сумма платежа в единицах валюты платежа
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
     * Сумма платежа в единицах валюты платежа
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
     * Gets as transactionDate
     *
     * Дата операции
     *
     * @return \DateTime
     */
    public function getTransactionDate()
    {
        return $this->transactionDate;
    }

    /**
     * Sets a new transactionDate
     *
     * Дата операции
     *
     * @param \DateTime $transactionDate
     * @return static
     */
    public function setTransactionDate(\DateTime $transactionDate)
    {
        $this->transactionDate = $transactionDate;
        return $this;
    }

    /**
     * Gets as contract
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
     * Номер ПС (строка со слешами)
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
     * Номер ПС (строка со слешами)
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
     *  Если валюта суммы платежа и валюта суммы цены контракта (кредитного договора) совпадают, то код
     *  валюты цены контракта и сумма в валюте цены контракта не должны заполняться
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
     *  Если валюта суммы платежа и валюта суммы цены контракта (кредитного договора) совпадают, то код
     *  валюты цены контракта и сумма в валюте цены контракта не должны заполняться
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
     * Gets as prepaymentDate
     *
     * Срок возврата аванса
     *
     * @return \DateTime
     */
    public function getPrepaymentDate()
    {
        return $this->prepaymentDate;
    }

    /**
     * Sets a new prepaymentDate
     *
     * Срок возврата аванса
     *
     * @param \DateTime $prepaymentDate
     * @return static
     */
    public function setPrepaymentDate(\DateTime $prepaymentDate)
    {
        $this->prepaymentDate = $prepaymentDate;
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
     * Adds as lDoc
     *
     * Содержит информацию о связном документе (валютный перевод, распоряжение об обязательной продаже,
     *  уведомление и т.п., доставленные по системе СББОЛ) по списанию или зачислению
     *
     * @return static
     * @param \common\models\sbbolxml\request\LinkedDocsType\LDocAType $lDoc
     */
    public function addToLinkedDocs(\common\models\sbbolxml\request\LinkedDocsType\LDocAType $lDoc)
    {
        $this->linkedDocs[] = $lDoc;
        return $this;
    }

    /**
     * isset linkedDocs
     *
     * Содержит информацию о связном документе (валютный перевод, распоряжение об обязательной продаже,
     *  уведомление и т.п., доставленные по системе СББОЛ) по списанию или зачислению
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetLinkedDocs($index)
    {
        return isset($this->linkedDocs[$index]);
    }

    /**
     * unset linkedDocs
     *
     * Содержит информацию о связном документе (валютный перевод, распоряжение об обязательной продаже,
     *  уведомление и т.п., доставленные по системе СББОЛ) по списанию или зачислению
     *
     * @param scalar $index
     * @return void
     */
    public function unsetLinkedDocs($index)
    {
        unset($this->linkedDocs[$index]);
    }

    /**
     * Gets as linkedDocs
     *
     * Содержит информацию о связном документе (валютный перевод, распоряжение об обязательной продаже,
     *  уведомление и т.п., доставленные по системе СББОЛ) по списанию или зачислению
     *
     * @return \common\models\sbbolxml\request\LinkedDocsType\LDocAType[]
     */
    public function getLinkedDocs()
    {
        return $this->linkedDocs;
    }

    /**
     * Sets a new linkedDocs
     *
     * Содержит информацию о связном документе (валютный перевод, распоряжение об обязательной продаже,
     *  уведомление и т.п., доставленные по системе СББОЛ) по списанию или зачислению
     *
     * @param \common\models\sbbolxml\request\LinkedDocsType\LDocAType[] $linkedDocs
     * @return static
     */
    public function setLinkedDocs(array $linkedDocs)
    {
        $this->linkedDocs = $linkedDocs;
        return $this;
    }


}


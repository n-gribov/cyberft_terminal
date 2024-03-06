<?php

namespace common\models\raiffeisenxml\request;

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
     * @property string $strNum
     */
    private $strNum = null;

    /**
     * Номер документа по валютной операции
     *
     * @property string $docNum
     */
    private $docNum = null;

    /**
     * 0 - документ с номером, 1 - документ без номера
     *
     * @property bool $numCheck
     */
    private $numCheck = null;

    /**
     * Дата документа по валютной операции
     *
     * @property \DateTime $docDate
     */
    private $docDate = null;

    /**
     * Код признака платежа
     *
     * @property string $paymentDir
     */
    private $paymentDir = null;

    /**
     * Дата операции
     *
     * @property \DateTime $operDate
     */
    private $operDate = null;

    /**
     * Код вида валютной операции
     *
     * @property string $operCode
     */
    private $operCode = null;

    /**
     * Сумма платежа в единицах валюты платежа
     *
     * @property \common\models\raiffeisenxml\request\CurrDealCertificateDoc138IType\DocSumAType $docSum
     */
    private $docSum = null;

    /**
     * @property \common\models\raiffeisenxml\request\ContractType $contract
     */
    private $contract = null;

    /**
     * Номер ПС (строка со слешами)
     *
     * @property string $dealPassNum
     */
    private $dealPassNum = null;

    /**
     * Ожидаемый срок исполнения обязательств по контракту
     *
     * @property \DateTime $term
     */
    private $term = null;

    /**
     * Дата возврата аванса
     *
     * @property \DateTime $dateOfRefund
     */
    private $dateOfRefund = null;

    /**
     * Сумма платежа в валюте цены контракта (кредитного договора)
     *  Если валюта суммы платежа и валюта суммы цены контракта (кредитного договора) совпадают, то код
     *  валюты цены контракта и сумма
     *  в валюте цены контракта не должны заполняться
     *
     * @property \common\models\raiffeisenxml\request\CurrDealCertificateDoc138IType\ContrSumAType $contrSum
     */
    private $contrSum = null;

    /**
     * Связанные документы, например, реестр пополнения корп. карт, зарплатные ведомости
     *  и т.п.
     *
     * @property \common\models\raiffeisenxml\request\LinkedDocsType\LDocAType[] $linkedDocs
     */
    private $linkedDocs = null;

    /**
     * Примечание
     *
     * @property string $addInfo
     */
    private $addInfo = null;

    /**
     * Gets as strNum
     *
     * Порядковый номер строки в справке
     *
     * @return string
     */
    public function getStrNum()
    {
        return $this->strNum;
    }

    /**
     * Sets a new strNum
     *
     * Порядковый номер строки в справке
     *
     * @param string $strNum
     * @return static
     */
    public function setStrNum($strNum)
    {
        $this->strNum = $strNum;
        return $this;
    }

    /**
     * Gets as docNum
     *
     * Номер документа по валютной операции
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
     * Номер документа по валютной операции
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
     * Gets as numCheck
     *
     * 0 - документ с номером, 1 - документ без номера
     *
     * @return bool
     */
    public function getNumCheck()
    {
        return $this->numCheck;
    }

    /**
     * Sets a new numCheck
     *
     * 0 - документ с номером, 1 - документ без номера
     *
     * @param bool $numCheck
     * @return static
     */
    public function setNumCheck($numCheck)
    {
        $this->numCheck = $numCheck;
        return $this;
    }

    /**
     * Gets as docDate
     *
     * Дата документа по валютной операции
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
     * Дата документа по валютной операции
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
     * Gets as paymentDir
     *
     * Код признака платежа
     *
     * @return string
     */
    public function getPaymentDir()
    {
        return $this->paymentDir;
    }

    /**
     * Sets a new paymentDir
     *
     * Код признака платежа
     *
     * @param string $paymentDir
     * @return static
     */
    public function setPaymentDir($paymentDir)
    {
        $this->paymentDir = $paymentDir;
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
     * Gets as docSum
     *
     * Сумма платежа в единицах валюты платежа
     *
     * @return \common\models\raiffeisenxml\request\CurrDealCertificateDoc138IType\DocSumAType
     */
    public function getDocSum()
    {
        return $this->docSum;
    }

    /**
     * Sets a new docSum
     *
     * Сумма платежа в единицах валюты платежа
     *
     * @param \common\models\raiffeisenxml\request\CurrDealCertificateDoc138IType\DocSumAType $docSum
     * @return static
     */
    public function setDocSum(\common\models\raiffeisenxml\request\CurrDealCertificateDoc138IType\DocSumAType $docSum)
    {
        $this->docSum = $docSum;
        return $this;
    }

    /**
     * Gets as contract
     *
     * @return \common\models\raiffeisenxml\request\ContractType
     */
    public function getContract()
    {
        return $this->contract;
    }

    /**
     * Sets a new contract
     *
     * @param \common\models\raiffeisenxml\request\ContractType $contract
     * @return static
     */
    public function setContract(\common\models\raiffeisenxml\request\ContractType $contract)
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
     * Gets as term
     *
     * Ожидаемый срок исполнения обязательств по контракту
     *
     * @return \DateTime
     */
    public function getTerm()
    {
        return $this->term;
    }

    /**
     * Sets a new term
     *
     * Ожидаемый срок исполнения обязательств по контракту
     *
     * @param \DateTime $term
     * @return static
     */
    public function setTerm(\DateTime $term)
    {
        $this->term = $term;
        return $this;
    }

    /**
     * Gets as dateOfRefund
     *
     * Дата возврата аванса
     *
     * @return \DateTime
     */
    public function getDateOfRefund()
    {
        return $this->dateOfRefund;
    }

    /**
     * Sets a new dateOfRefund
     *
     * Дата возврата аванса
     *
     * @param \DateTime $dateOfRefund
     * @return static
     */
    public function setDateOfRefund(\DateTime $dateOfRefund)
    {
        $this->dateOfRefund = $dateOfRefund;
        return $this;
    }

    /**
     * Gets as contrSum
     *
     * Сумма платежа в валюте цены контракта (кредитного договора)
     *  Если валюта суммы платежа и валюта суммы цены контракта (кредитного договора) совпадают, то код
     *  валюты цены контракта и сумма
     *  в валюте цены контракта не должны заполняться
     *
     * @return \common\models\raiffeisenxml\request\CurrDealCertificateDoc138IType\ContrSumAType
     */
    public function getContrSum()
    {
        return $this->contrSum;
    }

    /**
     * Sets a new contrSum
     *
     * Сумма платежа в валюте цены контракта (кредитного договора)
     *  Если валюта суммы платежа и валюта суммы цены контракта (кредитного договора) совпадают, то код
     *  валюты цены контракта и сумма
     *  в валюте цены контракта не должны заполняться
     *
     * @param \common\models\raiffeisenxml\request\CurrDealCertificateDoc138IType\ContrSumAType $contrSum
     * @return static
     */
    public function setContrSum(\common\models\raiffeisenxml\request\CurrDealCertificateDoc138IType\ContrSumAType $contrSum)
    {
        $this->contrSum = $contrSum;
        return $this;
    }

    /**
     * Adds as lDoc
     *
     * Связанные документы, например, реестр пополнения корп. карт, зарплатные ведомости
     *  и т.п.
     *
     * @return static
     * @param \common\models\raiffeisenxml\request\LinkedDocsType\LDocAType $lDoc
     */
    public function addToLinkedDocs(\common\models\raiffeisenxml\request\LinkedDocsType\LDocAType $lDoc)
    {
        $this->linkedDocs[] = $lDoc;
        return $this;
    }

    /**
     * isset linkedDocs
     *
     * Связанные документы, например, реестр пополнения корп. карт, зарплатные ведомости
     *  и т.п.
     *
     * @param int|string $index
     * @return bool
     */
    public function issetLinkedDocs($index)
    {
        return isset($this->linkedDocs[$index]);
    }

    /**
     * unset linkedDocs
     *
     * Связанные документы, например, реестр пополнения корп. карт, зарплатные ведомости
     *  и т.п.
     *
     * @param int|string $index
     * @return void
     */
    public function unsetLinkedDocs($index)
    {
        unset($this->linkedDocs[$index]);
    }

    /**
     * Gets as linkedDocs
     *
     * Связанные документы, например, реестр пополнения корп. карт, зарплатные ведомости
     *  и т.п.
     *
     * @return \common\models\raiffeisenxml\request\LinkedDocsType\LDocAType[]
     */
    public function getLinkedDocs()
    {
        return $this->linkedDocs;
    }

    /**
     * Sets a new linkedDocs
     *
     * Связанные документы, например, реестр пополнения корп. карт, зарплатные ведомости
     *  и т.п.
     *
     * @param \common\models\raiffeisenxml\request\LinkedDocsType\LDocAType[] $linkedDocs
     * @return static
     */
    public function setLinkedDocs(array $linkedDocs)
    {
        $this->linkedDocs = $linkedDocs;
        return $this;
    }

    /**
     * Gets as addInfo
     *
     * Примечание
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
     * Примечание
     *
     * @param string $addInfo
     * @return static
     */
    public function setAddInfo($addInfo)
    {
        $this->addInfo = $addInfo;
        return $this;
    }


}


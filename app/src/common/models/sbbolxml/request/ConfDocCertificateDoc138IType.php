<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing ConfDocCertificateDoc138IType
 *
 *
 * XSD Type: ConfDocCertificateDoc138I
 */
class ConfDocCertificateDoc138IType
{

    /**
     * Порядковый номер строки в справке
     *
     * @property integer $lineNumber
     */
    private $lineNumber = null;

    /**
     * Номер и Дата подтверждающего документа
     *
     * @property \common\models\sbbolxml\request\ConfDocType $confDoc
     */
    private $confDoc = null;

    /**
     * Код вида подтверждающего документа
     *
     * @property string $docCode
     */
    private $docCode = null;

    /**
     * Наименование вида подтверждающего документа
     *
     * @property string $docName
     */
    private $docName = null;

    /**
     * Сумма по подтверждающему документу в единицах валюты документа
     *
     * @property \common\models\sbbolxml\request\CurrAmountType $docSum
     */
    private $docSum = null;

    /**
     * Сумма по подтверждающему документу в единицах валюты контракта (кредитного
     *  договора)
     *
     * @property \common\models\sbbolxml\request\CurrAmountType $contractSum
     */
    private $contractSum = null;

    /**
     * Признак поставки
     *
     * @property string $delDir
     */
    private $delDir = null;

    /**
     * Ожидаемый срок
     *
     * @property \DateTime $expectedTerm
     */
    private $expectedTerm = null;

    /**
     * Cтрана грузоотправителя (грузополучателя)
     *
     * @property \common\models\sbbolxml\request\CountryNameType $country
     */
    private $country = null;

    /**
     * Примечание
     *
     * @property string $addInfo
     */
    private $addInfo = null;

    /**
     * Сумма, соответствующая признаку поставки 2 или 3, в валюте документа
     *
     * @property float $docSumDel
     */
    private $docSumDel = null;

    /**
     * Сумма, соответствующая признаку поставки 2 или 3, в валюте цены контракта
     *  (кредитного договора)
     *
     * @property float $contractSumDel
     */
    private $contractSumDel = null;

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
     * Gets as confDoc
     *
     * Номер и Дата подтверждающего документа
     *
     * @return \common\models\sbbolxml\request\ConfDocType
     */
    public function getConfDoc()
    {
        return $this->confDoc;
    }

    /**
     * Sets a new confDoc
     *
     * Номер и Дата подтверждающего документа
     *
     * @param \common\models\sbbolxml\request\ConfDocType $confDoc
     * @return static
     */
    public function setConfDoc(\common\models\sbbolxml\request\ConfDocType $confDoc)
    {
        $this->confDoc = $confDoc;
        return $this;
    }

    /**
     * Gets as docCode
     *
     * Код вида подтверждающего документа
     *
     * @return string
     */
    public function getDocCode()
    {
        return $this->docCode;
    }

    /**
     * Sets a new docCode
     *
     * Код вида подтверждающего документа
     *
     * @param string $docCode
     * @return static
     */
    public function setDocCode($docCode)
    {
        $this->docCode = $docCode;
        return $this;
    }

    /**
     * Gets as docName
     *
     * Наименование вида подтверждающего документа
     *
     * @return string
     */
    public function getDocName()
    {
        return $this->docName;
    }

    /**
     * Sets a new docName
     *
     * Наименование вида подтверждающего документа
     *
     * @param string $docName
     * @return static
     */
    public function setDocName($docName)
    {
        $this->docName = $docName;
        return $this;
    }

    /**
     * Gets as docSum
     *
     * Сумма по подтверждающему документу в единицах валюты документа
     *
     * @return \common\models\sbbolxml\request\CurrAmountType
     */
    public function getDocSum()
    {
        return $this->docSum;
    }

    /**
     * Sets a new docSum
     *
     * Сумма по подтверждающему документу в единицах валюты документа
     *
     * @param \common\models\sbbolxml\request\CurrAmountType $docSum
     * @return static
     */
    public function setDocSum(\common\models\sbbolxml\request\CurrAmountType $docSum)
    {
        $this->docSum = $docSum;
        return $this;
    }

    /**
     * Gets as contractSum
     *
     * Сумма по подтверждающему документу в единицах валюты контракта (кредитного
     *  договора)
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
     * Сумма по подтверждающему документу в единицах валюты контракта (кредитного
     *  договора)
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
     * Gets as delDir
     *
     * Признак поставки
     *
     * @return string
     */
    public function getDelDir()
    {
        return $this->delDir;
    }

    /**
     * Sets a new delDir
     *
     * Признак поставки
     *
     * @param string $delDir
     * @return static
     */
    public function setDelDir($delDir)
    {
        $this->delDir = $delDir;
        return $this;
    }

    /**
     * Gets as expectedTerm
     *
     * Ожидаемый срок
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
     * Ожидаемый срок
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
     * Gets as country
     *
     * Cтрана грузоотправителя (грузополучателя)
     *
     * @return \common\models\sbbolxml\request\CountryNameType
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Sets a new country
     *
     * Cтрана грузоотправителя (грузополучателя)
     *
     * @param \common\models\sbbolxml\request\CountryNameType $country
     * @return static
     */
    public function setCountry(\common\models\sbbolxml\request\CountryNameType $country)
    {
        $this->country = $country;
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

    /**
     * Gets as docSumDel
     *
     * Сумма, соответствующая признаку поставки 2 или 3, в валюте документа
     *
     * @return float
     */
    public function getDocSumDel()
    {
        return $this->docSumDel;
    }

    /**
     * Sets a new docSumDel
     *
     * Сумма, соответствующая признаку поставки 2 или 3, в валюте документа
     *
     * @param float $docSumDel
     * @return static
     */
    public function setDocSumDel($docSumDel)
    {
        $this->docSumDel = $docSumDel;
        return $this;
    }

    /**
     * Gets as contractSumDel
     *
     * Сумма, соответствующая признаку поставки 2 или 3, в валюте цены контракта
     *  (кредитного договора)
     *
     * @return float
     */
    public function getContractSumDel()
    {
        return $this->contractSumDel;
    }

    /**
     * Sets a new contractSumDel
     *
     * Сумма, соответствующая признаку поставки 2 или 3, в валюте цены контракта
     *  (кредитного договора)
     *
     * @param float $contractSumDel
     * @return static
     */
    public function setContractSumDel($contractSumDel)
    {
        $this->contractSumDel = $contractSumDel;
        return $this;
    }


}


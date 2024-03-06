<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing PayDocInfoType
 *
 * Реквизиты платежного документа/реестра на зачисление
 * XSD Type: PayDocInfo
 */
class PayDocInfoType
{

    /**
     * Номер платежного документа
     *
     * @property string $docNum
     */
    private $docNum = null;

    /**
     * Дата платежного документа
     *
     * @property \DateTime $docDate
     */
    private $docDate = null;

    /**
     * Счет платежного документа
     *
     * @property string $account
     */
    private $account = null;

    /**
     * Сумма платежного документа
     *
     * @property float $docSum
     */
    private $docSum = null;

    /**
     * Номер реестра
     *
     * @property string $regNum
     */
    private $regNum = null;

    /**
     * Формат файла: DBF - 0, XML - 1
     *
     * @property string $fileFormat
     */
    private $fileFormat = null;

    /**
     * Gets as docNum
     *
     * Номер платежного документа
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
     * Номер платежного документа
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
     * Дата платежного документа
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
     * Дата платежного документа
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
     * Gets as account
     *
     * Счет платежного документа
     *
     * @return string
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * Sets a new account
     *
     * Счет платежного документа
     *
     * @param string $account
     * @return static
     */
    public function setAccount($account)
    {
        $this->account = $account;
        return $this;
    }

    /**
     * Gets as docSum
     *
     * Сумма платежного документа
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
     * Сумма платежного документа
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
     * Gets as regNum
     *
     * Номер реестра
     *
     * @return string
     */
    public function getRegNum()
    {
        return $this->regNum;
    }

    /**
     * Sets a new regNum
     *
     * Номер реестра
     *
     * @param string $regNum
     * @return static
     */
    public function setRegNum($regNum)
    {
        $this->regNum = $regNum;
        return $this;
    }

    /**
     * Gets as fileFormat
     *
     * Формат файла: DBF - 0, XML - 1
     *
     * @return string
     */
    public function getFileFormat()
    {
        return $this->fileFormat;
    }

    /**
     * Sets a new fileFormat
     *
     * Формат файла: DBF - 0, XML - 1
     *
     * @param string $fileFormat
     * @return static
     */
    public function setFileFormat($fileFormat)
    {
        $this->fileFormat = $fileFormat;
        return $this;
    }


}


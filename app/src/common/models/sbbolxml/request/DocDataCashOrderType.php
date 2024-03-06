<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing DocDataCashOrderType
 *
 * Реквизиты заявки на получение наличных средств
 * XSD Type: DocDataCashOrder
 */
class DocDataCashOrderType
{

    /**
     * Номер документа
     *
     * @property string $docNumber
     */
    private $docNumber = null;

    /**
     * Дата создания документа
     *
     * @property \DateTime $docDate
     */
    private $docDate = null;

    /**
     * Сумма заказа
     *
     * @property float $docSum
     */
    private $docSum = null;

    /**
     * Буквенный код валюты суммы заказа
     *
     * @property string $docSumCurrCode
     */
    private $docSumCurrCode = null;

    /**
     * Планируемая дата выдачи документа
     *
     * @property \DateTime $withdrawalPlannedDate
     */
    private $withdrawalPlannedDate = null;

    /**
     * Источник происхождения денежных средств
     *
     * @property string $moneySource
     */
    private $moneySource = null;

    /**
     * Признак поручения на блокировку средств
     *
     * @property boolean $blockAmountFlag
     */
    private $blockAmountFlag = null;

    /**
     * Gets as docNumber
     *
     * Номер документа
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
     * Номер документа
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
     * Сумма заказа
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
     * Сумма заказа
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
     * Gets as docSumCurrCode
     *
     * Буквенный код валюты суммы заказа
     *
     * @return string
     */
    public function getDocSumCurrCode()
    {
        return $this->docSumCurrCode;
    }

    /**
     * Sets a new docSumCurrCode
     *
     * Буквенный код валюты суммы заказа
     *
     * @param string $docSumCurrCode
     * @return static
     */
    public function setDocSumCurrCode($docSumCurrCode)
    {
        $this->docSumCurrCode = $docSumCurrCode;
        return $this;
    }

    /**
     * Gets as withdrawalPlannedDate
     *
     * Планируемая дата выдачи документа
     *
     * @return \DateTime
     */
    public function getWithdrawalPlannedDate()
    {
        return $this->withdrawalPlannedDate;
    }

    /**
     * Sets a new withdrawalPlannedDate
     *
     * Планируемая дата выдачи документа
     *
     * @param \DateTime $withdrawalPlannedDate
     * @return static
     */
    public function setWithdrawalPlannedDate(\DateTime $withdrawalPlannedDate)
    {
        $this->withdrawalPlannedDate = $withdrawalPlannedDate;
        return $this;
    }

    /**
     * Gets as moneySource
     *
     * Источник происхождения денежных средств
     *
     * @return string
     */
    public function getMoneySource()
    {
        return $this->moneySource;
    }

    /**
     * Sets a new moneySource
     *
     * Источник происхождения денежных средств
     *
     * @param string $moneySource
     * @return static
     */
    public function setMoneySource($moneySource)
    {
        $this->moneySource = $moneySource;
        return $this;
    }

    /**
     * Gets as blockAmountFlag
     *
     * Признак поручения на блокировку средств
     *
     * @return boolean
     */
    public function getBlockAmountFlag()
    {
        return $this->blockAmountFlag;
    }

    /**
     * Sets a new blockAmountFlag
     *
     * Признак поручения на блокировку средств
     *
     * @param boolean $blockAmountFlag
     * @return static
     */
    public function setBlockAmountFlag($blockAmountFlag)
    {
        $this->blockAmountFlag = $blockAmountFlag;
        return $this;
    }


}


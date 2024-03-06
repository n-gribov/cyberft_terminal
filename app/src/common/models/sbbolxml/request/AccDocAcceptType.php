<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing AccDocAcceptType
 *
 *
 * XSD Type: AccDocAccept
 */
class AccDocAcceptType
{

    /**
     * Наименование подразделения получателя платежа/код участника расчетов
     *
     * @property string $bankName
     */
    private $bankName = null;

    /**
     * Номер документа
     *
     * @property string $docNum
     */
    private $docNum = null;

    /**
     * Дата составления документа
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
     * Тип заявления - указывается значение "Акцепт" или "Отказ от акцепта"
     *
     * @property string $type
     */
    private $type = null;

    /**
     * Gets as bankName
     *
     * Наименование подразделения получателя платежа/код участника расчетов
     *
     * @return string
     */
    public function getBankName()
    {
        return $this->bankName;
    }

    /**
     * Sets a new bankName
     *
     * Наименование подразделения получателя платежа/код участника расчетов
     *
     * @param string $bankName
     * @return static
     */
    public function setBankName($bankName)
    {
        $this->bankName = $bankName;
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
     * Gets as type
     *
     * Тип заявления - указывается значение "Акцепт" или "Отказ от акцепта"
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Sets a new type
     *
     * Тип заявления - указывается значение "Акцепт" или "Отказ от акцепта"
     *
     * @param string $type
     * @return static
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }


}


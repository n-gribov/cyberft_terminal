<?php

namespace common\models\raiffeisenxml\request;

/**
 * Class representing DealPassDataType
 *
 *
 * XSD Type: DealPassData
 */
class DealPassDataType
{

    /**
     * Номер паспорта сделки
     *
     * @property string $num
     */
    private $num = null;

    /**
     * Дата паспорта сделки
     *
     * @property \DateTime $date
     */
    private $date = null;

    /**
     * Сумма в валюте цены контракта
     *
     * @property \common\models\raiffeisenxml\request\DealPassDataType\ContractCurSumAType $contractCurSum
     */
    private $contractCurSum = null;

    /**
     * Ожидаемый срок
     *
     * @property \DateTime $expTerm
     */
    private $expTerm = null;

    /**
     * Gets as num
     *
     * Номер паспорта сделки
     *
     * @return string
     */
    public function getNum()
    {
        return $this->num;
    }

    /**
     * Sets a new num
     *
     * Номер паспорта сделки
     *
     * @param string $num
     * @return static
     */
    public function setNum($num)
    {
        $this->num = $num;
        return $this;
    }

    /**
     * Gets as date
     *
     * Дата паспорта сделки
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Sets a new date
     *
     * Дата паспорта сделки
     *
     * @param \DateTime $date
     * @return static
     */
    public function setDate(\DateTime $date)
    {
        $this->date = $date;
        return $this;
    }

    /**
     * Gets as contractCurSum
     *
     * Сумма в валюте цены контракта
     *
     * @return \common\models\raiffeisenxml\request\DealPassDataType\ContractCurSumAType
     */
    public function getContractCurSum()
    {
        return $this->contractCurSum;
    }

    /**
     * Sets a new contractCurSum
     *
     * Сумма в валюте цены контракта
     *
     * @param \common\models\raiffeisenxml\request\DealPassDataType\ContractCurSumAType $contractCurSum
     * @return static
     */
    public function setContractCurSum(\common\models\raiffeisenxml\request\DealPassDataType\ContractCurSumAType $contractCurSum)
    {
        $this->contractCurSum = $contractCurSum;
        return $this;
    }

    /**
     * Gets as expTerm
     *
     * Ожидаемый срок
     *
     * @return \DateTime
     */
    public function getExpTerm()
    {
        return $this->expTerm;
    }

    /**
     * Sets a new expTerm
     *
     * Ожидаемый срок
     *
     * @param \DateTime $expTerm
     * @return static
     */
    public function setExpTerm(\DateTime $expTerm)
    {
        $this->expTerm = $expTerm;
        return $this;
    }


}


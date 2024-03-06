<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing VoInfoType
 *
 *
 * XSD Type: VoInfo
 */
class VoInfoType
{

    /**
     * Код вида валютной операции
     *
     * @property string $vo
     */
    private $vo = null;

    /**
     * Сумма операции
     *
     * @property \common\models\sbbolxml\request\CurrAmountType $sum
     */
    private $sum = null;

    /**
     * Номер паспорта сделки
     *
     * @property string $psNum
     */
    private $psNum = null;

    /**
     * Контракт
     *
     * @property \common\models\sbbolxml\request\ContractType $contract
     */
    private $contract = null;

    /**
     * Валюта и сумма
     *
     * @property \common\models\sbbolxml\request\CurrAmountType $contractSum
     */
    private $contractSum = null;

    /**
     * Ожидаемый срок
     *
     * @property \DateTime $expectedDate
     */
    private $expectedDate = null;

    /**
     * Примечание
     *
     * @property string $addInfo
     */
    private $addInfo = null;

    /**
     * Gets as vo
     *
     * Код вида валютной операции
     *
     * @return string
     */
    public function getVo()
    {
        return $this->vo;
    }

    /**
     * Sets a new vo
     *
     * Код вида валютной операции
     *
     * @param string $vo
     * @return static
     */
    public function setVo($vo)
    {
        $this->vo = $vo;
        return $this;
    }

    /**
     * Gets as sum
     *
     * Сумма операции
     *
     * @return \common\models\sbbolxml\request\CurrAmountType
     */
    public function getSum()
    {
        return $this->sum;
    }

    /**
     * Sets a new sum
     *
     * Сумма операции
     *
     * @param \common\models\sbbolxml\request\CurrAmountType $sum
     * @return static
     */
    public function setSum(\common\models\sbbolxml\request\CurrAmountType $sum)
    {
        $this->sum = $sum;
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
     * Gets as contract
     *
     * Контракт
     *
     * @return \common\models\sbbolxml\request\ContractType
     */
    public function getContract()
    {
        return $this->contract;
    }

    /**
     * Sets a new contract
     *
     * Контракт
     *
     * @param \common\models\sbbolxml\request\ContractType $contract
     * @return static
     */
    public function setContract(\common\models\sbbolxml\request\ContractType $contract)
    {
        $this->contract = $contract;
        return $this;
    }

    /**
     * Gets as contractSum
     *
     * Валюта и сумма
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
     * Валюта и сумма
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
     * Gets as expectedDate
     *
     * Ожидаемый срок
     *
     * @return \DateTime
     */
    public function getExpectedDate()
    {
        return $this->expectedDate;
    }

    /**
     * Sets a new expectedDate
     *
     * Ожидаемый срок
     *
     * @param \DateTime $expectedDate
     * @return static
     */
    public function setExpectedDate(\DateTime $expectedDate)
    {
        $this->expectedDate = $expectedDate;
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


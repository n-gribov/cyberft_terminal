<?php

namespace common\models\sbbolxml\request\MandatorySaleType\AdviceAType;

/**
 * Class representing DocAType
 */
class DocAType
{

    /**
     * Основные данные уведомления
     *
     * @property \common\models\sbbolxml\request\DocDataType $docData
     */
    private $docData = null;

    /**
     * Сумма поступления денежных средств
     *
     * @property \common\models\sbbolxml\request\CurrAmountType $docSum
     */
    private $docSum = null;

    /**
     * Код вида валютной операции
     *
     * @property string $operCode
     */
    private $operCode = null;

    /**
     * Примечание
     *
     * @property string $addInfo
     */
    private $addInfo = null;

    /**
     * Gets as docData
     *
     * Основные данные уведомления
     *
     * @return \common\models\sbbolxml\request\DocDataType
     */
    public function getDocData()
    {
        return $this->docData;
    }

    /**
     * Sets a new docData
     *
     * Основные данные уведомления
     *
     * @param \common\models\sbbolxml\request\DocDataType $docData
     * @return static
     */
    public function setDocData(\common\models\sbbolxml\request\DocDataType $docData)
    {
        $this->docData = $docData;
        return $this;
    }

    /**
     * Gets as docSum
     *
     * Сумма поступления денежных средств
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
     * Сумма поступления денежных средств
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


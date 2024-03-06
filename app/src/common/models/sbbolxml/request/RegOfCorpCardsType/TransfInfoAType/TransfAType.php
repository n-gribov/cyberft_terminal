<?php

namespace common\models\sbbolxml\request\RegOfCorpCardsType\TransfInfoAType;

/**
 * Class representing TransfAType
 */
class TransfAType
{

    /**
     * Номер п/п (может быть использован при подписи )
     *
     * @property integer $numSt
     */
    private $numSt = null;

    /**
     * Фамилия физического лица
     *
     * @property string $sName
     */
    private $sName = null;

    /**
     * Имя физического лица
     *
     * @property string $name
     */
    private $name = null;

    /**
     * Отчество физического лица
     *  (у некоторых иностранцев нет отчества)
     *
     * @property string $middleName
     */
    private $middleName = null;

    /**
     * Номер карты
     *
     * @property string $cardNum
     */
    private $cardNum = null;

    /**
     * Сумма, которую необходимо зачислить на данную карту
     *
     * @property \common\models\sbbolxml\request\CurrAmountType $sum
     */
    private $sum = null;

    /**
     * Gets as numSt
     *
     * Номер п/п (может быть использован при подписи )
     *
     * @return integer
     */
    public function getNumSt()
    {
        return $this->numSt;
    }

    /**
     * Sets a new numSt
     *
     * Номер п/п (может быть использован при подписи )
     *
     * @param integer $numSt
     * @return static
     */
    public function setNumSt($numSt)
    {
        $this->numSt = $numSt;
        return $this;
    }

    /**
     * Gets as sName
     *
     * Фамилия физического лица
     *
     * @return string
     */
    public function getSName()
    {
        return $this->sName;
    }

    /**
     * Sets a new sName
     *
     * Фамилия физического лица
     *
     * @param string $sName
     * @return static
     */
    public function setSName($sName)
    {
        $this->sName = $sName;
        return $this;
    }

    /**
     * Gets as name
     *
     * Имя физического лица
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets a new name
     *
     * Имя физического лица
     *
     * @param string $name
     * @return static
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Gets as middleName
     *
     * Отчество физического лица
     *  (у некоторых иностранцев нет отчества)
     *
     * @return string
     */
    public function getMiddleName()
    {
        return $this->middleName;
    }

    /**
     * Sets a new middleName
     *
     * Отчество физического лица
     *  (у некоторых иностранцев нет отчества)
     *
     * @param string $middleName
     * @return static
     */
    public function setMiddleName($middleName)
    {
        $this->middleName = $middleName;
        return $this;
    }

    /**
     * Gets as cardNum
     *
     * Номер карты
     *
     * @return string
     */
    public function getCardNum()
    {
        return $this->cardNum;
    }

    /**
     * Sets a new cardNum
     *
     * Номер карты
     *
     * @param string $cardNum
     * @return static
     */
    public function setCardNum($cardNum)
    {
        $this->cardNum = $cardNum;
        return $this;
    }

    /**
     * Gets as sum
     *
     * Сумма, которую необходимо зачислить на данную карту
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
     * Сумма, которую необходимо зачислить на данную карту
     *
     * @param \common\models\sbbolxml\request\CurrAmountType $sum
     * @return static
     */
    public function setSum(\common\models\sbbolxml\request\CurrAmountType $sum)
    {
        $this->sum = $sum;
        return $this;
    }


}


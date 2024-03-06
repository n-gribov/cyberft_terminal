<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing IssCardInfoType
 *
 *
 * XSD Type: IssCardInfo
 */
class IssCardInfoType
{

    /**
     * Значение поля numSt в ответе для записи по конкретной карте (CardNum)должно
     *  совпадать с значением поля numSt в запросе
     *
     * @property integer $numSt
     */
    private $numSt = null;

    /**
     * Результат открытия счетов / выпуска карт (передается код, предопределенное
     *  значение)
     *
     * @property string $result
     */
    private $result = null;

    /**
     * Результат открытия счетов / выпуска карт (передается код, предопределенное значение)
     *
     * @property string $result1C
     */
    private $result1C = null;

    /**
     * Сообщение из Банка
     *
     * @property string $annotation
     */
    private $annotation = null;

    /**
     * @property string $accNum
     */
    private $accNum = null;

    /**
     * @property string $noRef
     */
    private $noRef = null;

    /**
     * Карта выпущена
     *
     * @property boolean $issCard
     */
    private $issCard = null;

    /**
     * Gets as numSt
     *
     * Значение поля numSt в ответе для записи по конкретной карте (CardNum)должно
     *  совпадать с значением поля numSt в запросе
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
     * Значение поля numSt в ответе для записи по конкретной карте (CardNum)должно
     *  совпадать с значением поля numSt в запросе
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
     * Gets as result
     *
     * Результат открытия счетов / выпуска карт (передается код, предопределенное
     *  значение)
     *
     * @return string
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * Sets a new result
     *
     * Результат открытия счетов / выпуска карт (передается код, предопределенное
     *  значение)
     *
     * @param string $result
     * @return static
     */
    public function setResult($result)
    {
        $this->result = $result;
        return $this;
    }

    /**
     * Gets as result1C
     *
     * Результат открытия счетов / выпуска карт (передается код, предопределенное значение)
     *
     * @return string
     */
    public function getResult1C()
    {
        return $this->result1C;
    }

    /**
     * Sets a new result1C
     *
     * Результат открытия счетов / выпуска карт (передается код, предопределенное значение)
     *
     * @param string $result1C
     * @return static
     */
    public function setResult1C($result1C)
    {
        $this->result1C = $result1C;
        return $this;
    }

    /**
     * Gets as annotation
     *
     * Сообщение из Банка
     *
     * @return string
     */
    public function getAnnotation()
    {
        return $this->annotation;
    }

    /**
     * Sets a new annotation
     *
     * Сообщение из Банка
     *
     * @param string $annotation
     * @return static
     */
    public function setAnnotation($annotation)
    {
        $this->annotation = $annotation;
        return $this;
    }

    /**
     * Gets as accNum
     *
     * @return string
     */
    public function getAccNum()
    {
        return $this->accNum;
    }

    /**
     * Sets a new accNum
     *
     * @param string $accNum
     * @return static
     */
    public function setAccNum($accNum)
    {
        $this->accNum = $accNum;
        return $this;
    }

    /**
     * Gets as noRef
     *
     * @return string
     */
    public function getNoRef()
    {
        return $this->noRef;
    }

    /**
     * Sets a new noRef
     *
     * @param string $noRef
     * @return static
     */
    public function setNoRef($noRef)
    {
        $this->noRef = $noRef;
        return $this;
    }

    /**
     * Gets as issCard
     *
     * Карта выпущена
     *
     * @return boolean
     */
    public function getIssCard()
    {
        return $this->issCard;
    }

    /**
     * Sets a new issCard
     *
     * Карта выпущена
     *
     * @param boolean $issCard
     * @return static
     */
    public function setIssCard($issCard)
    {
        $this->issCard = $issCard;
        return $this;
    }


}


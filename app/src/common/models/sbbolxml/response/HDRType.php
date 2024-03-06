<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing HDRType
 *
 * Заголовок сообщения о подтверждении сделки
 * XSD Type: HDRType
 */
class HDRType
{

    /**
     * Уникальный идентификатор сообщения, присваевается DSA
     *
     * @property string $mesUID
     */
    private $mesUID = null;

    /**
     * Тип сообщения: 1 - подтверждение по сделке без ПИ, 2 - подтверждение по сделке с ПИ
     *
     * @property integer $confType
     */
    private $confType = null;

    /**
     * ПИ. 0 - указывается, если подтверждение по сделке без ПИ, N - количество ПИ, приложенных к подтверждению
     *
     * @property integer $sICnt
     */
    private $sICnt = null;

    /**
     * Сумма документа
     *
     * @property float $docSum
     */
    private $docSum = null;

    /**
     * Дата документа
     *
     * @property \DateTime $docDate
     */
    private $docDate = null;

    /**
     * Валюта документа
     *
     * @property string $docSumCurrency
     */
    private $docSumCurrency = null;

    /**
     * Счет плательщика
     *
     * @property string $docAccount
     */
    private $docAccount = null;

    /**
     * Уникальный идентификатор сообщения в СББОЛ
     *
     * @property string $extID
     */
    private $extID = null;

    /**
     * Gets as mesUID
     *
     * Уникальный идентификатор сообщения, присваевается DSA
     *
     * @return string
     */
    public function getMesUID()
    {
        return $this->mesUID;
    }

    /**
     * Sets a new mesUID
     *
     * Уникальный идентификатор сообщения, присваевается DSA
     *
     * @param string $mesUID
     * @return static
     */
    public function setMesUID($mesUID)
    {
        $this->mesUID = $mesUID;
        return $this;
    }

    /**
     * Gets as confType
     *
     * Тип сообщения: 1 - подтверждение по сделке без ПИ, 2 - подтверждение по сделке с ПИ
     *
     * @return integer
     */
    public function getConfType()
    {
        return $this->confType;
    }

    /**
     * Sets a new confType
     *
     * Тип сообщения: 1 - подтверждение по сделке без ПИ, 2 - подтверждение по сделке с ПИ
     *
     * @param integer $confType
     * @return static
     */
    public function setConfType($confType)
    {
        $this->confType = $confType;
        return $this;
    }

    /**
     * Gets as sICnt
     *
     * ПИ. 0 - указывается, если подтверждение по сделке без ПИ, N - количество ПИ, приложенных к подтверждению
     *
     * @return integer
     */
    public function getSICnt()
    {
        return $this->sICnt;
    }

    /**
     * Sets a new sICnt
     *
     * ПИ. 0 - указывается, если подтверждение по сделке без ПИ, N - количество ПИ, приложенных к подтверждению
     *
     * @param integer $sICnt
     * @return static
     */
    public function setSICnt($sICnt)
    {
        $this->sICnt = $sICnt;
        return $this;
    }

    /**
     * Gets as docSum
     *
     * Сумма документа
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
     * Сумма документа
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
     * Gets as docDate
     *
     * Дата документа
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
     * Дата документа
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
     * Gets as docSumCurrency
     *
     * Валюта документа
     *
     * @return string
     */
    public function getDocSumCurrency()
    {
        return $this->docSumCurrency;
    }

    /**
     * Sets a new docSumCurrency
     *
     * Валюта документа
     *
     * @param string $docSumCurrency
     * @return static
     */
    public function setDocSumCurrency($docSumCurrency)
    {
        $this->docSumCurrency = $docSumCurrency;
        return $this;
    }

    /**
     * Gets as docAccount
     *
     * Счет плательщика
     *
     * @return string
     */
    public function getDocAccount()
    {
        return $this->docAccount;
    }

    /**
     * Sets a new docAccount
     *
     * Счет плательщика
     *
     * @param string $docAccount
     * @return static
     */
    public function setDocAccount($docAccount)
    {
        $this->docAccount = $docAccount;
        return $this;
    }

    /**
     * Gets as extID
     *
     * Уникальный идентификатор сообщения в СББОЛ
     *
     * @return string
     */
    public function getExtID()
    {
        return $this->extID;
    }

    /**
     * Sets a new extID
     *
     * Уникальный идентификатор сообщения в СББОЛ
     *
     * @param string $extID
     * @return static
     */
    public function setExtID($extID)
    {
        $this->extID = $extID;
        return $this;
    }


}


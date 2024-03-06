<?php

namespace common\models\sbbolxml\response\FeesRegistriesType;

/**
 * Class representing FeesRegistryAType
 */
class FeesRegistryAType
{

    /**
     * Дата составления документа
     *
     * @property \DateTime $docDate
     */
    private $docDate = null;

    /**
     * Номер документа
     *
     * @property string $docNum
     */
    private $docNum = null;

    /**
     * Идентификатор документа
     *
     * @property string $docId
     */
    private $docId = null;

    /**
     * Статус документа
     *
     * @property string $docState
     */
    private $docState = null;

    /**
     * Имя файла
     *
     * @property string $fileName
     */
    private $fileName = null;

    /**
     * Наименование реестра
     *
     * @property string $registryName
     */
    private $registryName = null;

    /**
     * Дата выгрузки реестра
     *
     * @property \DateTime $dateLoad
     */
    private $dateLoad = null;

    /**
     * Дата начала периода
     *
     * @property \DateTime $dateBegin
     */
    private $dateBegin = null;

    /**
     * Дата окончания периода
     *
     * @property \DateTime $dateEnd
     */
    private $dateEnd = null;

    /**
     * Количество платежей
     *
     * @property integer $uploadedRecords
     */
    private $uploadedRecords = null;

    /**
     * Сумма
     *
     * @property float $sum
     */
    private $sum = null;

    /**
     * Сумма с комиссией
     *
     * @property float $sumBring
     */
    private $sumBring = null;

    /**
     * Расчетный счет
     *
     * @property string $curAccount
     */
    private $curAccount = null;

    /**
     * Расчетный счет
     *
     * @property \DateTime $valueDate
     */
    private $valueDate = null;

    /**
     * Комментарий к документу
     *
     * @property string $docComment
     */
    private $docComment = null;

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
     * Gets as docId
     *
     * Идентификатор документа
     *
     * @return string
     */
    public function getDocId()
    {
        return $this->docId;
    }

    /**
     * Sets a new docId
     *
     * Идентификатор документа
     *
     * @param string $docId
     * @return static
     */
    public function setDocId($docId)
    {
        $this->docId = $docId;
        return $this;
    }

    /**
     * Gets as docState
     *
     * Статус документа
     *
     * @return string
     */
    public function getDocState()
    {
        return $this->docState;
    }

    /**
     * Sets a new docState
     *
     * Статус документа
     *
     * @param string $docState
     * @return static
     */
    public function setDocState($docState)
    {
        $this->docState = $docState;
        return $this;
    }

    /**
     * Gets as fileName
     *
     * Имя файла
     *
     * @return string
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * Sets a new fileName
     *
     * Имя файла
     *
     * @param string $fileName
     * @return static
     */
    public function setFileName($fileName)
    {
        $this->fileName = $fileName;
        return $this;
    }

    /**
     * Gets as registryName
     *
     * Наименование реестра
     *
     * @return string
     */
    public function getRegistryName()
    {
        return $this->registryName;
    }

    /**
     * Sets a new registryName
     *
     * Наименование реестра
     *
     * @param string $registryName
     * @return static
     */
    public function setRegistryName($registryName)
    {
        $this->registryName = $registryName;
        return $this;
    }

    /**
     * Gets as dateLoad
     *
     * Дата выгрузки реестра
     *
     * @return \DateTime
     */
    public function getDateLoad()
    {
        return $this->dateLoad;
    }

    /**
     * Sets a new dateLoad
     *
     * Дата выгрузки реестра
     *
     * @param \DateTime $dateLoad
     * @return static
     */
    public function setDateLoad(\DateTime $dateLoad)
    {
        $this->dateLoad = $dateLoad;
        return $this;
    }

    /**
     * Gets as dateBegin
     *
     * Дата начала периода
     *
     * @return \DateTime
     */
    public function getDateBegin()
    {
        return $this->dateBegin;
    }

    /**
     * Sets a new dateBegin
     *
     * Дата начала периода
     *
     * @param \DateTime $dateBegin
     * @return static
     */
    public function setDateBegin(\DateTime $dateBegin)
    {
        $this->dateBegin = $dateBegin;
        return $this;
    }

    /**
     * Gets as dateEnd
     *
     * Дата окончания периода
     *
     * @return \DateTime
     */
    public function getDateEnd()
    {
        return $this->dateEnd;
    }

    /**
     * Sets a new dateEnd
     *
     * Дата окончания периода
     *
     * @param \DateTime $dateEnd
     * @return static
     */
    public function setDateEnd(\DateTime $dateEnd)
    {
        $this->dateEnd = $dateEnd;
        return $this;
    }

    /**
     * Gets as uploadedRecords
     *
     * Количество платежей
     *
     * @return integer
     */
    public function getUploadedRecords()
    {
        return $this->uploadedRecords;
    }

    /**
     * Sets a new uploadedRecords
     *
     * Количество платежей
     *
     * @param integer $uploadedRecords
     * @return static
     */
    public function setUploadedRecords($uploadedRecords)
    {
        $this->uploadedRecords = $uploadedRecords;
        return $this;
    }

    /**
     * Gets as sum
     *
     * Сумма
     *
     * @return float
     */
    public function getSum()
    {
        return $this->sum;
    }

    /**
     * Sets a new sum
     *
     * Сумма
     *
     * @param float $sum
     * @return static
     */
    public function setSum($sum)
    {
        $this->sum = $sum;
        return $this;
    }

    /**
     * Gets as sumBring
     *
     * Сумма с комиссией
     *
     * @return float
     */
    public function getSumBring()
    {
        return $this->sumBring;
    }

    /**
     * Sets a new sumBring
     *
     * Сумма с комиссией
     *
     * @param float $sumBring
     * @return static
     */
    public function setSumBring($sumBring)
    {
        $this->sumBring = $sumBring;
        return $this;
    }

    /**
     * Gets as curAccount
     *
     * Расчетный счет
     *
     * @return string
     */
    public function getCurAccount()
    {
        return $this->curAccount;
    }

    /**
     * Sets a new curAccount
     *
     * Расчетный счет
     *
     * @param string $curAccount
     * @return static
     */
    public function setCurAccount($curAccount)
    {
        $this->curAccount = $curAccount;
        return $this;
    }

    /**
     * Gets as valueDate
     *
     * Расчетный счет
     *
     * @return \DateTime
     */
    public function getValueDate()
    {
        return $this->valueDate;
    }

    /**
     * Sets a new valueDate
     *
     * Расчетный счет
     *
     * @param \DateTime $valueDate
     * @return static
     */
    public function setValueDate(\DateTime $valueDate)
    {
        $this->valueDate = $valueDate;
        return $this;
    }

    /**
     * Gets as docComment
     *
     * Комментарий к документу
     *
     * @return string
     */
    public function getDocComment()
    {
        return $this->docComment;
    }

    /**
     * Sets a new docComment
     *
     * Комментарий к документу
     *
     * @param string $docComment
     * @return static
     */
    public function setDocComment($docComment)
    {
        $this->docComment = $docComment;
        return $this;
    }


}


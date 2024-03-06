<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing CurrCourseEntryType
 *
 * Запрос обновления справочника курсов валют
 * XSD Type: CurrCourseEntry
 */
class CurrCourseEntryType
{

    /**
     * Дата и время последнего запроса обновления справочника(с час. поясами)
     *
     * @property \DateTime $lastRequestTime
     */
    private $lastRequestTime = null;

    /**
     * Дата начала периода
     *
     * @property \DateTime $beginDate
     */
    private $beginDate = null;

    /**
     * Дата конца периода
     *
     * @property \DateTime $endDate
     */
    private $endDate = null;

    /**
     * Системное наименование подразделения банка
     *
     * @property string $branchSystemName
     */
    private $branchSystemName = null;

    /**
     * Gets as lastRequestTime
     *
     * Дата и время последнего запроса обновления справочника(с час. поясами)
     *
     * @return \DateTime
     */
    public function getLastRequestTime()
    {
        return $this->lastRequestTime;
    }

    /**
     * Sets a new lastRequestTime
     *
     * Дата и время последнего запроса обновления справочника(с час. поясами)
     *
     * @param \DateTime $lastRequestTime
     * @return static
     */
    public function setLastRequestTime(\DateTime $lastRequestTime)
    {
        $this->lastRequestTime = $lastRequestTime;
        return $this;
    }

    /**
     * Gets as beginDate
     *
     * Дата начала периода
     *
     * @return \DateTime
     */
    public function getBeginDate()
    {
        return $this->beginDate;
    }

    /**
     * Sets a new beginDate
     *
     * Дата начала периода
     *
     * @param \DateTime $beginDate
     * @return static
     */
    public function setBeginDate(\DateTime $beginDate)
    {
        $this->beginDate = $beginDate;
        return $this;
    }

    /**
     * Gets as endDate
     *
     * Дата конца периода
     *
     * @return \DateTime
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * Sets a new endDate
     *
     * Дата конца периода
     *
     * @param \DateTime $endDate
     * @return static
     */
    public function setEndDate(\DateTime $endDate)
    {
        $this->endDate = $endDate;
        return $this;
    }

    /**
     * Gets as branchSystemName
     *
     * Системное наименование подразделения банка
     *
     * @return string
     */
    public function getBranchSystemName()
    {
        return $this->branchSystemName;
    }

    /**
     * Sets a new branchSystemName
     *
     * Системное наименование подразделения банка
     *
     * @param string $branchSystemName
     * @return static
     */
    public function setBranchSystemName($branchSystemName)
    {
        $this->branchSystemName = $branchSystemName;
        return $this;
    }


}


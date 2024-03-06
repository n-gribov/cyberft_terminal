<?php

namespace common\models\sbbolxml\response\AdmCashierType;

/**
 * Class representing EmpowermentAType
 */
class EmpowermentAType
{

    /**
     * Дата начала наделения полномочий
     *
     * @property \DateTime $empowermentStart
     */
    private $empowermentStart = null;

    /**
     * Дата окончания наделения полномочий
     *
     * @property \DateTime $empowermentEnd
     */
    private $empowermentEnd = null;

    /**
     * Суточный лимит вносителя (оставшийся)
     *
     * @property float $currentLimit
     */
    private $currentLimit = null;

    /**
     * Суточный лимит вносителя
     *
     * @property float $limit
     */
    private $limit = null;

    /**
     * Наличие доверенности
     *  1 – Да
     *  0 – Нет
     *
     * @property boolean $byAttorney
     */
    private $byAttorney = null;

    /**
     * Номер доверености
     *
     * @property string $number
     */
    private $number = null;

    /**
     * Дата начала действия
     *
     * @property \DateTime $beginDate
     */
    private $beginDate = null;

    /**
     * Дата окончания действия
     *
     * @property \DateTime $endDate
     */
    private $endDate = null;

    /**
     * Gets as empowermentStart
     *
     * Дата начала наделения полномочий
     *
     * @return \DateTime
     */
    public function getEmpowermentStart()
    {
        return $this->empowermentStart;
    }

    /**
     * Sets a new empowermentStart
     *
     * Дата начала наделения полномочий
     *
     * @param \DateTime $empowermentStart
     * @return static
     */
    public function setEmpowermentStart(\DateTime $empowermentStart)
    {
        $this->empowermentStart = $empowermentStart;
        return $this;
    }

    /**
     * Gets as empowermentEnd
     *
     * Дата окончания наделения полномочий
     *
     * @return \DateTime
     */
    public function getEmpowermentEnd()
    {
        return $this->empowermentEnd;
    }

    /**
     * Sets a new empowermentEnd
     *
     * Дата окончания наделения полномочий
     *
     * @param \DateTime $empowermentEnd
     * @return static
     */
    public function setEmpowermentEnd(\DateTime $empowermentEnd)
    {
        $this->empowermentEnd = $empowermentEnd;
        return $this;
    }

    /**
     * Gets as currentLimit
     *
     * Суточный лимит вносителя (оставшийся)
     *
     * @return float
     */
    public function getCurrentLimit()
    {
        return $this->currentLimit;
    }

    /**
     * Sets a new currentLimit
     *
     * Суточный лимит вносителя (оставшийся)
     *
     * @param float $currentLimit
     * @return static
     */
    public function setCurrentLimit($currentLimit)
    {
        $this->currentLimit = $currentLimit;
        return $this;
    }

    /**
     * Gets as limit
     *
     * Суточный лимит вносителя
     *
     * @return float
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * Sets a new limit
     *
     * Суточный лимит вносителя
     *
     * @param float $limit
     * @return static
     */
    public function setLimit($limit)
    {
        $this->limit = $limit;
        return $this;
    }

    /**
     * Gets as byAttorney
     *
     * Наличие доверенности
     *  1 – Да
     *  0 – Нет
     *
     * @return boolean
     */
    public function getByAttorney()
    {
        return $this->byAttorney;
    }

    /**
     * Sets a new byAttorney
     *
     * Наличие доверенности
     *  1 – Да
     *  0 – Нет
     *
     * @param boolean $byAttorney
     * @return static
     */
    public function setByAttorney($byAttorney)
    {
        $this->byAttorney = $byAttorney;
        return $this;
    }

    /**
     * Gets as number
     *
     * Номер доверености
     *
     * @return string
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Sets a new number
     *
     * Номер доверености
     *
     * @param string $number
     * @return static
     */
    public function setNumber($number)
    {
        $this->number = $number;
        return $this;
    }

    /**
     * Gets as beginDate
     *
     * Дата начала действия
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
     * Дата начала действия
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
     * Дата окончания действия
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
     * Дата окончания действия
     *
     * @param \DateTime $endDate
     * @return static
     */
    public function setEndDate(\DateTime $endDate)
    {
        $this->endDate = $endDate;
        return $this;
    }


}


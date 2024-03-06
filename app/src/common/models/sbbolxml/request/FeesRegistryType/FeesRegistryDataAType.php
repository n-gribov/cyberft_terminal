<?php

namespace common\models\sbbolxml\request\FeesRegistryType;

/**
 * Class representing FeesRegistryDataAType
 */
class FeesRegistryDataAType
{

    /**
     * Дата и время последнего запроса реестров платежей
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
     * Дата окончания периода
     *
     * @property \DateTime $endDate
     */
    private $endDate = null;

    /**
     * Признак запроса документов в статусе "Принят"
     *
     * @property boolean $acceptState
     */
    private $acceptState = null;

    /**
     * Признак запроса документов в статусе "Обработан"
     *
     * @property boolean $implementState
     */
    private $implementState = null;

    /**
     * Gets as lastRequestTime
     *
     * Дата и время последнего запроса реестров платежей
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
     * Дата и время последнего запроса реестров платежей
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
     * Дата окончания периода
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
     * Дата окончания периода
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
     * Gets as acceptState
     *
     * Признак запроса документов в статусе "Принят"
     *
     * @return boolean
     */
    public function getAcceptState()
    {
        return $this->acceptState;
    }

    /**
     * Sets a new acceptState
     *
     * Признак запроса документов в статусе "Принят"
     *
     * @param boolean $acceptState
     * @return static
     */
    public function setAcceptState($acceptState)
    {
        $this->acceptState = $acceptState;
        return $this;
    }

    /**
     * Gets as implementState
     *
     * Признак запроса документов в статусе "Обработан"
     *
     * @return boolean
     */
    public function getImplementState()
    {
        return $this->implementState;
    }

    /**
     * Sets a new implementState
     *
     * Признак запроса документов в статусе "Обработан"
     *
     * @param boolean $implementState
     * @return static
     */
    public function setImplementState($implementState)
    {
        $this->implementState = $implementState;
        return $this;
    }


}

